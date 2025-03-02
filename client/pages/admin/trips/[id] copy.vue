<template>
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation -->
        <AdminHeader />

        <main class="flex-1 bg-gray-100 overflow-y-auto p-6">
            <!-- Trip Details -->
            <div v-if="trip" class=" mx-auto">
                <!-- Trip Header -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">{{ trip.title }}</h1>
                            <p class="text-gray-600 mt-1">{{ trip.description }}</p>
                            <div class="flex gap-4 mt-3">
                                <div class="flex items-center text-sm text-gray-600">
                                    <span class="font-medium">Dates:</span>
                                    <span class="ml-2">{{ formatDate(trip.start_date) }} - {{ formatDate(trip.end_date)
                                        }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <span class="font-medium">Budget:</span>
                                    <span class="ml-2">${{ trip.budget }}</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <span class="font-medium">Status:</span>
                                    <span class="ml-2 capitalize">{{ trip.status }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button @click="isModalOpen = true"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Edit
                                Trip</button>
                            <button @click.prevent="destroyTrip"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded hover:bg-gray-50">Delete
                                Trip</button>
                        </div>
                    </div>
                </div>

                <!-- Participants Section -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Participants ({{ trip.participant_count }})</h2>
                        <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Invite
                            Member</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Email</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Role</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="participant in trip.participants" :key="participant.id">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ participant.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ participant.email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap capitalize">{{ participant.role }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            :class="`inline-flex px-2 py-1 text-xs rounded-full ${getStatusClass(participant.status)}`">
                                            {{ participant.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                        <button class="text-red-600 hover:text-red-900">Remove</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Destinations Section -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Destinations</h2>
                        <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Add
                            Destination</button>
                    </div>

                    <div v-for="destination in trip.destinations" :key="destination.id"
                        class="border border-gray-200 rounded-lg p-4 mb-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">{{ destination.name }}</h3>
                                <p class="text-sm text-gray-600">
                                    {{ formatDateTime(destination.arrival_date) }} - {{
                                    formatDateTime(destination.departure_date) }}
                                </p>
                            </div>
                            <div class="flex gap-2">
                                <button class=" cursor-pointer text-blue-600 hover:text-blue-900">Edit</button>
                                <button class=" cursor-pointer text-red-600 hover:text-red-900">Delete</button>
                            </div>
                        </div>

                        <!-- Activities -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-medium text-gray-700">Activities</h4>
                                <button class="text-sm text-blue-600 hover:text-blue-900">Add Activity</button>
                            </div>
                            <div v-if="destination.activities.length" class="pl-4 border-l-2 border-blue-300">
                                <div v-for="activity in destination.activities" :key="activity.id"
                                    class="mb-2 p-2 bg-gray-50 rounded">
                                    <div class="flex justify-between">
                                        <div>
                                            <div class="font-medium">{{ activity.name }}</div>
                                            <div class="text-xs text-gray-600">{{ activity.type }}</div>
                                            <div class="text-xs text-gray-600">
                                                {{ formatDateTime(activity.start_time) }} - {{
                                                formatDateTime(activity.end_time) }}
                                            </div>
                                            <div v-if="activity.description" class="text-sm mt-1">{{
                                                activity.description }}</div>
                                        </div>
                                        <div class="flex gap-2">
                                            <button class="text-xs text-blue-600 hover:text-blue-900">Edit</button>
                                            <button class="text-xs text-red-600 hover:text-red-900">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-sm text-gray-500 italic">No activities added yet.</div>
                        </div>

                        <!-- Accommodations -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-medium text-gray-700">Accommodations</h4>
                                <button class="text-sm text-blue-600 hover:text-blue-900">Add Accommodation</button>
                            </div>
                            <div v-if="destination.accommodations.length" class="pl-4 border-l-2 border-green-300">
                                <div v-for="accommodation in destination.accommodations" :key="accommodation.id"
                                    class="mb-2 p-2 bg-gray-50 rounded">
                                    <div class="flex justify-between">
                                        <div>
                                            <div class="font-medium">{{ accommodation.name }}</div>
                                            <div class="text-xs text-gray-600">
                                                {{ formatDateTime(accommodation.check_in) }} - {{
                                                formatDateTime(accommodation.check_out) }}
                                            </div>
                                            <div class="text-xs text-gray-600">{{ accommodation.address }}</div>
                                            <div class="text-xs text-gray-600">Contact: {{ accommodation.contact_info }}
                                            </div>
                                            <div v-if="accommodation.notes" class="text-sm mt-1">{{ accommodation.notes
                                                }}</div>
                                        </div>
                                        <div class="flex gap-2">
                                            <button class="text-xs text-blue-600 hover:text-blue-900">Edit</button>
                                            <button class="text-xs text-red-600 hover:text-red-900">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-sm text-gray-500 italic">No accommodations added yet.</div>
                        </div>

                        <!-- Transportation -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-medium text-gray-700">Transportation</h4>
                                <button class="text-sm text-blue-600 hover:text-blue-900">Add Transportation</button>
                            </div>
                            <div v-if="destination.transportations.length" class="pl-4 border-l-2 border-purple-300">
                                <div v-for="transport in destination.transportations" :key="transport.id"
                                    class="mb-2 p-2 bg-gray-50 rounded">
                                    <div class="flex justify-between">
                                        <div>
                                            <div class="font-medium capitalize">{{ transport.type }} with {{
                                                transport.company }}</div>
                                            <div class="text-xs text-gray-600">
                                                {{ transport.departure_location }} → {{ transport.arrival_location }}
                                            </div>
                                            <div class="text-xs text-gray-600">
                                                {{ formatDateTime(transport.departure_time) }} - {{
                                                formatDateTime(transport.arrival_time) }}
                                            </div>
                                            <div v-if="transport.booking_reference" class="text-xs text-gray-600">
                                                Ref: {{ transport.booking_reference }}
                                            </div>
                                            <div v-if="transport.notes" class="text-sm mt-1">{{ transport.notes }}</div>
                                        </div>
                                        <div class="flex gap-2">
                                            <button class="text-xs text-blue-600 hover:text-blue-900">Edit</button>
                                            <button class="text-xs text-red-600 hover:text-red-900">Delete</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-sm text-gray-500 italic">No transportation added yet.</div>
                        </div>
                    </div>
                </div>

                <!-- Expenses Section -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-gray-800">Expenses</h2>
                        <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Add
                            Expense</button>
                    </div>

                    <div class="mb-4">
                        <div class="text-sm font-medium text-gray-700 mb-2">
                            Total Expenses: ${{ calculateTotalExpenses() }}
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Category</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Added By</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Notes</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="expense in trip.expenses" :key="expense.id">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ formatDate(expense.date) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ expense.category }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">${{ expense.amount }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ expense.name }}</td>
                                    <td class="px-6 py-4">
                                        <div class="max-w-xs truncate">{{ expense.note || '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                                        <button class="text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-xs">
            <div class="bg-white  rounded-lg shadow-sm w-full max-w-2xl">
                <div class="flex items-center justify-between p-4 border-b border-gray-200 ">
                    <h3 class="text-xl font-semibold text-gray-900 ">
                        Edit Trip
                    </h3>
                    <button @click="clearForm" class="text-gray-400 hover:text-gray-900 ">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-4 space-y-4">

                    <div>
                        <label for="title" class="block text-sm font-medium leading-6 text-gray-900 ">Title
                            <span class="text-red-500">*</span> </label>
                        <div class="mt-2">
                            <input v-model="form.title" type="text"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900  ring-1 ring-inset ring-gray-300  placeholder:text-gray-400   focus:ring-inset focus:ring-indigo-300 sm:text-sm sm:leading-6 px-3" />
                        </div>
                        <small class="text-red-500" v-if="errors?.title">{{ errors?.title[0] }}</small>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium leading-6 text-gray-900 ">Description
                            <span class="text-red-500">*</span> </label>
                        <div class="mt-2">
                            <textarea v-model="form.description"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900  ring-1 ring-inset ring-gray-300  placeholder:text-gray-400   focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"></textarea>
                        </div>
                        <small class="text-red-500" v-if="errors?.description">{{ errors?.description[0] }}</small>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label for="start_date" class="block text-sm font-medium leading-6 text-gray-900 ">Start
                                Date
                                <span class="text-red-500">*</span> </label>
                            <div class="mt-2">
                                <input v-model="form.start_date" type="date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900  ring-1 ring-inset ring-gray-300  placeholder:text-gray-400   focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
                            </div>
                            <small class="text-red-500" v-if="errors?.start_date">{{ errors?.start_date[0] }}</small>
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium leading-6 text-gray-900 ">End Date
                                <span class="text-red-500">*</span> </label>
                            <div class="mt-2">
                                <input v-model="form.end_date" type="date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900  ring-1 ring-inset ring-gray-300  placeholder:text-gray-400   focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
                            </div>
                            <small class="text-red-500" v-if="errors?.end_date">{{ errors?.end_date[0] }}</small>
                        </div>

                        <div>
                            <label for="budget" class="block text-sm font-medium leading-6 text-gray-900 ">Budget
                                <span class="text-red-500">*</span> </label>
                            <div class="mt-2">
                                <input v-model="form.budget" type="number" step="0.01"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900  ring-1 ring-inset ring-gray-300  placeholder:text-gray-400   focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
                            </div>
                            <small class="text-red-500" v-if="errors?.budget">{{ errors?.budget[0] }}</small>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium leading-6 text-gray-900 ">Status
                                <span class="text-red-500">*</span> </label>
                            <div class="mt-2">
                                <select v-model="form.status"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900  ring-1 ring-inset ring-gray-300  placeholder:text-gray-400   focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3">
                                    <option value="planning">Planning</option>
                                    <option value="completed">Completed</option>
                                    <option value="canceled">Canceled</option>
                                </select>
                            </div>
                            <small class="text-red-500" v-if="errors?.status">{{ errors?.status[0] }}</small>
                        </div>





                    </div>

                    <p class="text-red-500 mx-3" v-if="error">{{ error }}</p>
                    <div class="flex justify-end items-center p-2 border-t border-gray-200 ">
                        <button @click.prevent="updateTrip"
                            class="text-white bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg">
                            Update
                        </button>



                        <button @click.prevent="clearForm"
                            class="ml-2 px-4 py-2 text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 rounded-lg   ">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

definePageMeta({
    layout: 'admin'
});
const loading = ref(true);
const trip = ref({});
const form = ref({
    title: '',
    description: '',
    location: '',
    start_date: '',
    end_date: '',
    budget: 0,
    status: ''
})
watch(trip, () => {
    form.value = {
        title: trip.value.title,
        description: trip.value.description,
        location: trip.value.location,
        start_date: trip.value.start_date,
        end_date: trip.value.end_date,
        budget: trip.value.budget,
        status: trip.value.status
    }
})
const isModalOpen = ref(false);
const errors = ref({});
const error = ref(null);
const clearForm = () => {
    form.value = {
        title: '',
        description: '',
        location: '',
        start_date: '',
        end_date: '',
        budget: 0,
        status: ''
    };
    errors.value = {};
    error.value = null;
    isModalOpen.value = false;
}
const route = useRoute();
const id = route.params.id;
const getTrip = async () => {
    try {
        const response = await $fetch(`http://localhost:8000/api/admin/trips/${id}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            }
        });
        trip.value = response;
        loading = false;
    } catch (error) {
        console.error(error);
    }
};

onMounted(() => {
    getTrip();
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
        case 'accepted':
            return 'bg-green-100 text-green-800';
        case 'invited':
            return 'bg-yellow-100 text-yellow-800';
        case 'declined':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

// Calculate total expenses
const calculateTotalExpenses = () => {
    return trip.value?.expenses?.reduce((total, expense) => {
        return total + parseFloat(expense.amount);
    }, 0).toFixed(2);
};

const updateTrip = async () => {
    try {
        const data = await $fetch(`http://localhost:8000/api/admin/trips/${trip.value.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            },
            body: form.value
        });
        trip.value = { ...trip.value, ...data };
        clearForm();
    } catch (error) {
        if (error?.response?._data.errors) {
            errors.value = error.response._data.errors;
        } else {
            console.error(error);
            error.value = 'Something went wrong. Try again.';
        }
    }
};

const destroyTrip = async () => {
    try {
        const data = await $fetch(`http://localhost:8000/api/admin/trips/${trip.value.id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            }
        });
        navigateTo('/trips/my-trips');

    } catch (error) {
        console.error('Join request failed:', error);
    }
};
</script>