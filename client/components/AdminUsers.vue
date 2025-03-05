<template>
    <div class="mt-2 p-4 bg-white rounded-lg shadow-lg">
        <h3 class="text-lg font-semibold mb-4">Filter users</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-2">
            <!-- Title Filter -->

            <div>
                <label for="title" class="block text-sm font-medium leading-6 text-gray-900 ">
                    Seacrh </label>
                <div class="mt-2">
                    <input v-model="usersFilter.search" type="text"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900  ring-1 ring-inset ring-gray-300  placeholder:text-gray-400   focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
                </div>
            </div>
            <div>
                <label for="title" class="block text-sm font-medium leading-6 text-gray-900 ">
                    Status </label>
                <div class="mt-2">
                    <select v-model="usersFilter.ustatus" type="text" @change="fetchUsers()"
                        class="block w-full rounded-md border-0 py-2 text-gray-900  ring-1 ring-inset ring-gray-300  placeholder:text-gray-400   focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3">
                        <option value="">All</option>
                        <option value="active">Active</option>
                        <option value="banned">Banned</option>
                    </select>
                </div>
            </div>
            <div class="flex flex-nowrap gap-1 justify-end" style="align-items: end;">
                <div class="inline-flex h-10 justify-end items-end  space-x-2 mt-auto">
                    <button @click="fetchUsers"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                        Apply Filters
                    </button>
                    <button @click="clearFilters"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                        Clear Filters
                    </button>
                </div>
            </div>

        </div>

        <!-- Filter Buttons -->
        <!-- <div class="py-2 flex justify-end space-x-2">
            <button @click="fetchUsers"
                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                Apply Filters
            </button>
            <button @click="clearFilters"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                Clear Filters
            </button>
        </div> -->

        <UsersTable :users="users?.data" :sort="usersPagination.sort" :sortDirection="usersPagination.sortDirection"
            :current_page="Number(usersPagination.current_page)" :total_pages="Number(usersPagination.total_pages)"
            :per_page="Number(usersPagination.per_page)" :isLoading="usersIsLoading"
            @handleSort="(column) => handleSort(column)"
            @handlePageChange="(page) => { usersPagination.current_page = page }"
            @handlePerPageChange="(per_page) => { usersPagination.per_page = per_page }" />
    </div>
</template>

<script setup>
const props = defineProps({
    limit: {
        type: Number,
        default: 5
    }
})
const usersIsLoading = ref(true);
const usersPagination = ref({
    current_page: 1,
    total_pages: 1,
    sortDirection: 'asc',
    sort: 'name',
    per_page: props.limit,
    isLoading: true
});
const usersFilter = ref({
    search: '',
    ustatus: ''
})
const clearFilters = () => {
    usersFilter.value = {
        search: '',
        ustatus: ''
    }
}

const users = ref([]);

const handleSort = (column) => {
    if (usersPagination.value.sort === column) {
        usersPagination.value.sortDirection =
            usersPagination.value.sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
        usersPagination.value.sort = column;
        usersPagination.value.sortDirection = 'asc';
    }
}

onMounted(() => {
    fetchUsers();
})
const fetchUsers = async () => {
    try {
        usersIsLoading.value = true;
        const data = await $fetch('https://trip-planer-api.sankarsan.xyz/api/admin/users', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            },
            params: {
                sort: usersPagination.value.sort,
                sortDirection: usersPagination.value.sortDirection,
                page: usersPagination.value.current_page,
                per_page: usersPagination.value.per_page,
                ...usersFilter.value
            }
        });
        users.value = data;
        usersPagination.value.current_page = data.current_page;
        usersPagination.value.total_pages = data.last_page;
        usersIsLoading.value = false;
    } catch (error) {
        console.error('Error fetching users:', error);
        usersIsLoading.value = false;
    }
};

watch([usersPagination.value], () => {
    // console.log(usersPagination.value);
    fetchUsers();
});
</script>