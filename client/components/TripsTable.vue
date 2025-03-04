<template>
    <div v-if="!isLoading" class="overflow-x-auto  border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <THead @click="sortTo('title')" label="Title" :isSorted="sort === 'title'"
                        :sortDirection="sortDirection" />
                    <THead @click="sortTo('owner')" label="Owner" :isSorted="sort === 'owner'"
                        :sortDirection="sortDirection" />
                    <THead @click="sortTo('participant_count')" label="Participants"
                        :isSorted="sort === 'participant_count'" :sortDirection="sortDirection" />
                    <THead @click="sortTo('status')" label="Status" :isSorted="sort === 'status'"
                        :sortDirection="sortDirection" />
                    <THead @click="sortTo('budget')" label="Budget" :isSorted="sort === 'budget'"
                        :sortDirection="sortDirection" />
                    <THead @click="sortTo('total_expense')" label="Total Expense" :isSorted="sort === 'total_expense'"
                        :sortDirection="sortDirection" />
                    <THead @click="sortTo('start_date')" label="Start Date" :isSorted="sort === 'start_date'"
                        :sortDirection="sortDirection" />
                    <THead @click="sortTo('end_date')" label="End Date" :isSorted="sort === 'end_date'"
                        :sortDirection="sortDirection" />
                    <th class="px-4 py-4  text-left cursor-pointer hover:bg-gray-50">
                        <div class="flex items-center">
                            <span>Actions</span>
                            
                        </div>
                    </th>

                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="trip in trips" :key="trip.id">
                    <td class="px-4 py-4 text-sm text-gray-500 max-w-xs">
                        {{ trip.title.length > 20 ? trip.title.substring(0, 20) + '...' : trip.title }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500 max-w-xs">
                        {{ trip.owner }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500 max-w-xs">
                        {{ trip.participant_count}}
                    </td>
                    <td class="px-4 py-4 text-sm max-w-xs">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                            :class="statusBadgeClass(trip.status)">
                            {{ trip.status }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500 max-w-xs">
                        {{ formatCurrency(trip.budget) }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500 max-w-xs">
                        {{ formatCurrency(trip.total_expense) }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500 max-w-xs">
                        {{ formatDate(trip.start_date) }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500 max-w-xs">
                        {{ formatDate(trip.end_date) }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-500 max-w-xs">
                        <NuxtLink class="inline-block text-blue-500 hover:text-gray-100 border-1 border-blue-500 hover:bg-blue-500 rounded p-1 cursor-pointer" :to="`/admin/trips/${trip.id}`"><PenIcon class="w-5 h-5" /></NuxtLink>
                    </td>
                </tr>
                <tr v-if="trips.length === 0">
                    <td colspan="9" class="px-4 py-4 text-center text-gray-500">No Trips Found.</td>
                </tr>
            </tbody>


        </table>
        <div
            class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0 sm:space-x-4 bg-white px-4 py-3 border-t border-gray-200">
            <div class="flex items-center space-x-2">
                <label for="perPage" class="text-sm font-medium text-gray-700">Show</label>
                <select v-model="perPage" id="perPage" name="perPage"
                    class="block w-auto pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option>5</option>
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                    <option>100</option>
                </select>
                <span class="text-sm font-medium text-gray-700">per page</span>
            </div>

            <!-- Pagination -->
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <!-- Previous page -->
                <button @click="handlePageChange(current_page - 1)" :disabled="current_page == 1"
                    class="cursor-pointer disabled:cursor-text relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <span class="sr-only">Previous</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <!-- Page numbers -->
                <button v-for="page in total_pages" href="#" aria-current="page" @click="handlePageChange(page)"
                    :key="page" :disabled="page == current_page"
                    class="cursor-pointer disabled:cursor-text bg-white disabled:bg-blue-100 border-gray-300 text-gray-500 not-disabled:hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                    {{ page }}
                </button>


                <!-- Next page -->
                <button @click="handlePageChange(current_page + 1)" :disabled="current_page == total_pages"
                    class="cursor-pointer disabled:cursor-text relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                    <span class="sr-only">Next</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </nav>
        </div>
    </div>
    <div v-else class="mt-6 bg-white rounded-lg shadow-md">
        <div class="overflow-x-auto border border-gray-200">
            <!-- Table Header Skeleton -->
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-4">
                            <div class="h-4 bg-gray-200 rounded w-24"></div>
                        </th>
                        <th class="px-4 py-4">
                            <div class="h-4 bg-gray-200 rounded w-24"></div>
                        </th>
                        <th class="px-4 py-4">
                            <div class="h-4 bg-gray-200 rounded w-24"></div>
                        </th>
                        <th class="px-4 py-4">
                            <div class="h-4 bg-gray-200 rounded w-24"></div>
                        </th>
                        <th class="px-4 py-4">
                            <div class="h-4 bg-gray-200 rounded w-24"></div>
                        </th>
                        <th class="px-4 py-4">
                            <div class="h-4 bg-gray-200 rounded w-24"></div>
                        </th>
                        <th class="px-4 py-4">
                            <div class="h-4 bg-gray-200 rounded w-24"></div>
                        </th>
                        <th class="px-4 py-4">
                            <div class="h-4 bg-gray-200 rounded w-24"></div>
                        </th>
                    </tr>
                </thead>
                <!-- Table Body Skeleton -->
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Row 1 -->
                    <tr v-for="i in per_page" :key="i">
                        <td class="px-4 py-4">
                            <div class="h-4 bg-gray-200 rounded w-32"></div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="h-4 bg-gray-200 rounded w-32"></div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="h-4 bg-gray-200 rounded w-32"></div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="h-4 bg-gray-200 rounded w-32"></div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="h-4 bg-gray-200 rounded w-32"></div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="h-4 bg-gray-200 rounded w-32"></div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="h-4 bg-gray-200 rounded w-32"></div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="h-4 bg-gray-200 rounded w-32"></div>
                        </td>
                    </tr>

                </tbody>
            </table>

            <!-- Pagination Skeleton -->
            <div
                class="flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0 sm:space-x-4 bg-white px-4 py-3 border-t border-gray-200">
                <!-- Show per page dropdown skeleton -->
                <div class="flex items-center space-x-2">
                    <div class="h-4 bg-gray-200 rounded w-16"></div>
                    <div class="h-8 bg-gray-200 rounded w-20"></div>
                    <div class="h-4 bg-gray-200 rounded w-16"></div>
                </div>
                <!-- Pagination buttons skeleton -->
                <div class="flex space-x-2">
                    <div class="h-8 bg-gray-200 rounded w-8"></div>
                    <div class="h-8 bg-gray-200 rounded w-8"></div>
                    <div class="h-8 bg-gray-200 rounded w-8"></div>
                    <div class="h-8 bg-gray-200 rounded w-8"></div>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>
const props = defineProps({
    trips: {
        type: Array,
        default: []
    },
    sort: {
        type: String,
        required: true
    },
    sortDirection: {
        type: String,
        required: true
    },
    per_page: {
        type: Number,
        default: 10
    },
    current_page: {
        type: Number,
        default: 1
    },
    total_pages: {
        type: Number ,
        default: 1
    },
    isLoading: {
        type: Boolean,
        default: false
    }
});
const perPage = ref(props.per_page);
const emit = defineEmits(['handleSort', 'handlePageChange', 'handlePerPageChange']);
watch(perPage, () => {
    // console.log(perPage.value);
    emit('handlePerPageChange', perPage.value);
})

const handlePageChange = (page) => {
    // console.log(page);
    emit('handlePageChange', page);

};

const statusBadgeClass = (status) => {
    switch (status) {
        case 'planning':
            return 'bg-yellow-100 text-yellow-800';
        case 'ongoing':
            return 'bg-blue-100 text-blue-800';
        case 'completed':
            return 'bg-green-100 text-green-800';
        case 'canceled':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};
const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};


const sortTo = (column) => {
    emit('handleSort', column);
};

</script>