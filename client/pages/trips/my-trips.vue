<template>

    <div class="min-h-screen bg-gray-50 p-2">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Invites</h1>
            <div class="bg-white p-0 rounded-lg shadow-md mb-4">
                <ul class="divide-y divide-gray-300">
                    <li v-if="invites?.length" v-for="invite in invites" :key="invite.id" class="py-0">
                        <NuxtLink :to="'/trips/' + invite.trip_id"
                            class="flex justify-between items-center py-4 px-2 hover:bg-gray-100">
                            <p class="text-sm font-medium text-gray-700">
                                {{ invite?.invited_by_name }} has invited you to trip,
                                <span class="font-bold">{{ invite.trip_name }}</span>
                            </p>
                            <p class="text-xs text-gray-500"> {{ invite?.invited_at }}</p>
                        </NuxtLink>
                    </li>
                    <li v-else class="py-4 px-2 text-sm font-medium text-gray-700">Currently you dont have any
                        invitations.</li>
                </ul>
            </div>
        </div>
        <div class="max-w-4xl mx-auto">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">My Trips</h1>
            <div class="bg-white p-4 rounded-lg shadow-md mb-4">

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select v-model="filter.status" id="status"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md">
                            <option value="">All</option>
                            <option value="planning">Planning</option>
                            <option value="completed">Completed</option>
                            <option value="canceled">Canceled</option>
                            <option value="declined">Declined</option>
                            <option value="invited">Invited</option>
                            <option value="owner">Owner</option>
                            <option value="admin">Admin</option>
                            <option value="participant">Participant</option>
                            <option value="requested">Requested</option>
                            <option value="declined">Declined</option>
                            <!-- <option value="can_join">Can Join</option> -->

                        </select>
                    </div>

                    <!-- Date Range Filter -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                        <input type="date" id="start_date" v-model="filter.start_date"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md" />
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" id="end_date" v-model="filter.end_date"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md" />
                    </div>
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700">Sort By</label>
                        <select v-model="filter.sort" id="sort"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 rounded-md">
                            <option value="start_date-desc">Start Date N-O</option>
                            <option value="start_date-asc">Start Date O-N</option>
                            <option value="end_date-desc">End Date N-O</option>
                            <option value="end_date-asc">End Date O-N</option>
                            <option value="budget-desc">Budget High-Small</option>
                            <option value="budget-asc">Budget Small-High</option>
                        </select>
                    </div>
                </div>
            </div>
            <div v-if="loading" v-for="i in 10"
                class="bg-white rounded-lg p-4 shadow-sm border border-gray-200 animate-pulse">
                <!-- Header Section -->
                <div class="flex justify-between items-start mb-3">
                    <div class="w-1/2 h-6 bg-gray-300 rounded"></div>
                    <div class="w-16 h-6 bg-gray-300 rounded"></div>
                </div>

                <div class="w-full h-4 bg-gray-300 rounded mb-3"></div>

                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3 texy-sm text-gray-600">
                    <div class="w-24 h-4 bg-gray-300 rounded"></div>
                    <div class="w-24 h-4 bg-gray-300 rounded"></div>
                    <div class="w-24 h-4 bg-gray-300 rounded"></div>
                    <div class="w-24 h-4 bg-gray-300 rounded"></div>
                </div>
            </div>
            <div v-else class="space-y-2">
                <div v-if="trips?.length" v-for="trip in trips" :key="trip.id"
                    class="bg-white rounded-lg p-4 shadow-sm border border-gray-200 hover:border-blue-100 transition-colors capitalize">
                    <!-- Header Section -->
                    <div class="flex justify-between items-start mb-3">

                        <h2 class="text-lg font-semibold text-blue-500 hover:text-blue-600 mb-2 cursor-pointer"
                            @click="navigateTo(`/trips/${trip.id}`)">
                            {{ trip.title }}
                        </h2>
                        <button v-if="trip?.participant_status == 'can_join' && trip.status === 'planning'"
                            @click="joinTrip(trip)"
                            class="cursor-pointer text-sm px-3 py-1.5 rounded-md bg-blue-500 text-white hover:bg-blue-600">
                            Join
                        </button>
                        <button v-if="trip?.participant_status == 'can_join' && trip.status !== 'planning'"
                            @click="navigateToTrip(trip.id)"
                            class="cursor-text text-sm px-3 py-1.5 rounded-md bg-blue-400 text-white">
                            Join
                        </button>
                        <button v-else-if="trip?.participant_status == 'owner'" @click="navigateToTrip(trip.id)"
                            class="cursor-pointer text-sm px-3 py-1.5 rounded-md bg-green-500 text-white hover:bg-green-600">
                            Manage
                        </button>
                        <button v-else-if="trip?.participant_status == 'admin'" @click="navigateToTrip(trip.id)"
                            class="cursor-pointer text-sm px-3 py-1.5 rounded-md bg-green-500 text-white hover:bg-green-600">
                            Admin
                        </button>
                        <span v-else-if="trip?.participant_status == 'participant'" @click="navigateToTrip(trip.id)"
                            class="text-sm px-3 py-1.5 rounded-md bg-green-500 text-white">
                            Participant
                        </span>
                        <span v-else-if="trip?.participant_status == 'requested'" @click="navigateToTrip(trip.id)"
                            class="text-sm px-3 py-1.5 rounded-md bg-orange-500 text-white">
                            Pending
                        </span>
                        <span v-else-if="trip?.participant_status == 'declined'" @click="navigateToTrip(trip.id)"
                            class="text-sm px-3 py-1.5 rounded-md bg-red-500 text-white">
                            Declined
                        </span>
                        <span v-else-if="trip?.participant_status == 'invited'" @click="navigateToTrip(trip.id)"
                            class="text-sm px-3 py-1.5 rounded-md bg-cyan-500 text-white">
                            Invited
                        </span>

                    </div>

                    <p class=" text-sm text-gray-500 line-clamp-2 ">
                        {{ trip.description }}
                    </p>

                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3 texy-sm text-gray-600">

                        <div>
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ formatDate(trip.start_date) }} - {{ formatDate(trip.end_date) }}
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                {{ trip.participant_count }} participants
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Budget: {{ formatCurrency(trip.budget) }}
                            </div>
                        </div>
                        <div>
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="relative">Status:
                                    <span :class="statusBadgeClass(trip.status)"
                                        class="px-3 mx-1 py-1 rounded-full text-sm ">
                                        {{ trip.status }}

                                    </span>
                                </span>

                            </div>
                        </div>

                    </div>
                </div>
                <div v-else class="flex items-center justify-center">
                    <p class="text-gray-600">No trips found.</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>


const trips = ref([]);
const invites = ref([]);
const loading = ref(true);
const filter = ref({
    status: '',
    start_date: null,
    end_date: null,
    sort: 'start_date-desc'
});
watch(filter.value, () => {
    fetchTrips();
})
const fetchTrips = async () => {
    loading.value = true;
    if(! useUserStore().user){
        return navigateTo('/login');
    }
    try {
        const data = await $fetch('http://localhost:8000/api/mytrips', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            },
            params: filter.value
        });
        trips.value = data.trips.map(trip => ({
            ...trip,
            isJoining: trip.participant_status === 'accepted',
            hasRequested: trip.participant_status === 'pending' || trip.participant_status === 'declined'
        }));
        invites.value = data.invites;
        loading.value = false;
    } catch (error) {
        console.error('Error fetching trips:', error);
    } finally {
        loading.value = false;
    }
};

const joinTrip = async (trip) => {
    if(! useUserStore().user){
        return navigateTo('/login');
    }
    
    try {
        const data = await $fetch(`http://localhost:8000/api/trips/${trip.id}/join`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            }
        });
        trip.hasRequested = true;
        trip.participant_status = 'pending';
        trips.value = trips.value.map(t => {
            if (t.id === trip.id) {
                return trip;
            }
            return t;
        })
    } catch (error) {
        console.error('Join request failed:', error);
    } finally {
        trip.isJoining = false;
    }
};

const navigateToTrip = (tripId) => {
    navigateTo(`/trips/${tripId}`);
};

const statusBadgeClass = (status) => {
    const classes = {
        planning: 'bg-blue-100 text-blue-800',
        active: 'bg-green-100 text-green-800',
        completed: 'bg-gray-100 text-gray-800',
        cancelled: 'bg-red-100 text-red-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};


const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
};

onMounted(fetchTrips);

</script>