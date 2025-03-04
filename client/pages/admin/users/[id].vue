<template>
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation -->
        <AdminHeader :title="`User`" />

        <main class="flex-1 bg-gray-100 overflow-y-auto p-6">
            <!-- user Details -->
            <div v-if="user && !isLoading" class=" mx-auto">
                <!-- user Header -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ user.name }}</h1>
                        <p class="text-gray-600 my-1">{{ user.email }}</p>
                        <div class="w-full grid gap-1 grid-cols-1 md:grid-cols-2">
                            <div class="flex items-center text-sm text-gray-600">
                                <span class="font-medium">Role:</span>
                                <span class="ml-2 capitalize">{{ user.role }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <span class="font-medium">Status:</span>
                                <span class="mx-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                    :class="getStatusClass(user.ustatus)">
                                    {{ user.ustatus }}
                                </span>
                                <button v-if="user.ustatus === 'active'" @click="updateUserStatus('banned')" class="cursor-pointer text-xs text-red-600 hover:text-gray-100 hover:bg-red-600 px-2 py-0 border rounded-full"> Ban This User</button>
                                <button v-if="user.ustatus === 'banned'" @click="updateUserStatus('active')" class="cursor-pointer text-xs text-green-600 hover:text-gray-100 hover:bg-green-600 px-2 py-0 border rounded-full"> Activate This User</button>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <span class="font-medium">Trips Joined:</span>
                                <span class="ml-2">{{ user.trips }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <span class="font-medium">Trips Owned:</span>
                                <span class="ml-2">{{ user.trip_owned }}</span>
                            </div>
                        </div>
                    </div>

                </div>

                <AdminTrips :userId="Number(user.id)" />





            </div>
            <div v-else class="mx-auto">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex justify-between items-start">
                        <div class="w-full">
                            <!-- Title Placeholder -->
                            <div class="h-8 bg-gray-200 rounded w-3/4 mb-2"></div>
                            <!-- Description Placeholder -->
                            <div class="h-4 bg-gray-200 rounded w-full mb-4"></div>
                            <!-- Metadata Placeholders -->
                            <div class="flex gap-4 mt-3">
                                <div class="h-4 bg-gray-200 rounded w-1/4"></div>
                                <div class="h-4 bg-gray-200 rounded w-1/4"></div>
                                <div class="h-4 bg-gray-200 rounded w-1/4"></div>
                            </div>
                        </div>
                        <!-- Buttons Placeholder -->
                        <div class="flex gap-2">
                            <div class="h-10 bg-gray-200 rounded w-20"></div>
                            <div class="h-10 bg-gray-200 rounded w-20"></div>
                        </div>
                    </div>
                </div>

                <!-- Participants Section -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800"></h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Destinations Section -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800"></h2>
                    </div>
                    <div class="border border-gray-200 rounded-lg p-4 mb-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800"></h3>
                                <p class="text-sm text-gray-600"></p>
                            </div>
                        </div>
                        <!-- Activities -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-medium text-gray-700"></h4>
                            </div>
                            <div class="pl-4 border-l-2 border-blue-300">
                                <div class="mb-2 p-2 bg-gray-50 rounded">
                                    <div class="flex justify-between">
                                        <div>
                                            <div class="font-medium"></div>
                                            <div class="text-xs text-gray-600"></div>
                                            <div class="text-xs text-gray-600"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Accommodations -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-medium text-gray-700"></h4>
                            </div>
                            <div class="pl-4 border-l-2 border-green-300">
                                <div class="mb-2 p-2 bg-gray-50 rounded">
                                    <div class="flex justify-between">
                                        <div>
                                            <div class="font-medium"></div>
                                            <div class="text-xs text-gray-600"></div>
                                            <div class="text-xs text-gray-600"></div>
                                            <div class="text-xs text-gray-600"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Transportation -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-medium text-gray-700"></h4>
                            </div>
                            <div class="pl-4 border-l-2 border-purple-300">
                                <div class="mb-2 p-2 bg-gray-50 rounded">
                                    <div class="flex justify-between">
                                        <div>
                                            <div class="font-medium"></div>
                                            <div class="text-xs text-gray-600"></div>
                                            <div class="text-xs text-gray-600"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Expenses Section -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800"></h2>
                    </div>
                    <div class="mb-4">
                        <div class="text-sm font-medium text-gray-700 mb-2"></div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4"></td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4"></td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4 whitespace-nowrap"></td>
                                    <td class="px-6 py-4"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

    </div>
</template>

<script setup>

definePageMeta({
    layout: 'admin'
});
const isLoading = ref(true);
const user = ref({});
const route = useRoute();
const id = route.params.id;
const updateUserStatus = async (status) => {
    user.value.ustatus = status;
}




const getUser = async () => {
    try {
        const response = await $fetch(`http://localhost:8000/api/admin/users/${id}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            }
        });
        user.value = response;
        isLoading.value = false;
    } catch (error) {
        // navigateTo('/admin/users');
        console.error(error);
    }
};

onMounted(() => {
    getUser();
});

// Format functions
const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
};

const formatDateTime = (dateTimeString) => {
    if (!dateTimeString) return '';
    const date = new Date(dateTimeString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

// Get status badge class
const getStatusClass = (status) => {
    switch (status) {
        case 'active':
            return 'bg-green-100 text-green-800';
        case 'banned':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};


</script>