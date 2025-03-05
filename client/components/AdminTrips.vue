<template>
    <div class="mt-2 p-4 bg-white rounded-lg shadow-lg">
        <h3 class="text-lg font-semibold mb-4">Filter Trips</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Title Filter -->

            <div>
                <label for="title" class="block text-sm font-medium leading-6 text-gray-900 ">
                    Seacrh </label>
                <div class="mt-2">
                    <input v-model="tripsFilter.search" type="text"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900  ring-1 ring-inset ring-gray-300  placeholder:text-gray-400   focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
                </div>
            </div>
            <div>
                <label for="title" class="block text-sm font-medium leading-6 text-gray-900 ">
                    Status </label>
                <div class="mt-2">
                    <select v-model="tripsFilter.status" type="text" @change="fetchTrips()"
                        class="block w-full rounded-md border-0 py-2 text-gray-900  ring-1 ring-inset ring-gray-300  placeholder:text-gray-400   focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3">
                        <option value="">All</option>
                        <option value="planning">Planning</option>
                        <option value="ongoing">Ongoing</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
            <div>
                <label for="start_date" class="block text-sm font-medium leading-6 text-gray-900 ">
                    Start Date</label>
                <div class="mt-2">
                    <input v-model="tripsFilter.start_date" type="date"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900  ring-1 ring-inset ring-gray-300  placeholder:text-gray-400   focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
                </div>
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium leading-6 text-gray-900 ">
                    End Date</label>
                <div class="mt-2">
                    <input v-model="tripsFilter.end_date" type="date"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900  ring-1 ring-inset ring-gray-300  placeholder:text-gray-400   focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
                </div>
            </div>
            <div>
                <label for="min_budget" class="block text-sm font-medium leading-6 text-gray-900 ">
                    Min Budget</label>
                <div class="mt-2">
                    <input v-model="tripsFilter.min_budget" type="number"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900  ring-1 ring-inset ring-gray-300  placeholder:text-gray-400   focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
                </div>
            </div>
            <div>
                <label for="max_budget" class="block text-sm font-medium leading-6 text-gray-900 ">
                    Max Budget</label>
                <div class="mt-2">
                    <input v-model="tripsFilter.max_budget" type="number"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900  ring-1 ring-inset ring-gray-300  placeholder:text-gray-400   focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
                </div>
            </div>
        </div>

        <!-- Filter Buttons -->
        <div class="py-2 flex justify-end space-x-2">
            <button @click="fetchTrips"
                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Apply Filters
            </button>
            <button @click="clearFilters"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                Clear Filters
            </button>
        </div>

        <TripsTable :trips="trips?.data" :sort="tripsPagination.sort" :sortDirection="tripsPagination.sortDirection"
            :current_page="Number(tripsPagination.current_page)" :total_pages="Number(tripsPagination.total_pages)"
            :per_page="Number(tripsPagination.per_page)" :isLoading="tripsIsLoading"
            @handleSort="(column) => handleSort(column)"
            @handlePageChange="(page) => { tripsPagination.current_page = page }"
            @handlePerPageChange="(per_page) => { tripsPagination.per_page = per_page }" />
    </div>
</template>

<script setup>
const props = defineProps({
    limit: {
        type: Number,
        default: 5
    },
    userId: {
        type: Number,
        default: null
    }
})
const tripsIsLoading = ref(true);
const tripsPagination = ref({
    current_page: 1,
    total_pages: 1,
    sortDirection: 'asc',
    sort: 'title',
    per_page: props.limit,
    isLoading: true
});
const tripsFilter = ref({
    search: '',
    status: '',
    start_date: '',
    end_date: '',
    min_budget: 0,
    max_budget: null
})
const clearFilters = () => {
    tripsFilter.value = {
        search: '',
        status: '',
        start_date: '',
        end_date: '',
        min_budget: 0,
        max_budget: null
    }
}

const trips = ref([]);

const handleSort = (column) => {
    if (tripsPagination.value.sort === column) {
        tripsPagination.value.sortDirection =
            tripsPagination.value.sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        tripsPagination.value.sort = column;
        tripsPagination.value.sortDirection = 'asc';
    }
}

onMounted(() => {
    fetchTrips();
})
const fetchTrips = async () => {
    console.log(props.userId);
    try {
        tripsIsLoading.value = true;
        const data = await $fetch(`https://trip-planer-api.sankarsan.xyz/api/admin${ props.userId ? `/users/${props.userId}` : '' }/trips`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            },
            params: {
                sort: tripsPagination.value.sort,
                sortDirection: tripsPagination.value.sortDirection,
                page: tripsPagination.value.current_page,
                per_page: tripsPagination.value.per_page,
                ...tripsFilter.value
            }
        });
        trips.value = data;
        tripsPagination.value.current_page = data.current_page;
        tripsPagination.value.total_pages = data.last_page;
        tripsIsLoading.value = false;
    } catch (error) {
        console.error('Error fetching trips:', error);
        tripsIsLoading.value = false;
    }
};

watch([tripsPagination.value], () => {
    // console.log(tripsPagination.value);
    fetchTrips();
});
</script>