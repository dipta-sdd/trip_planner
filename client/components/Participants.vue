<template>
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Participants</h2>
            <!-- Add Participant Button (Admin Only) -->
            <button v-if="isAdmin" class="bg-green-500 text-white p-2 rounded-lg hover:bg-green-600">
                Add Participant
            </button>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg">
            <div class="space-y-4">
                <!-- Participant 1 -->
                <div v-for="participant in participants" :key="participant.id"
                    class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-md font-semibold text-gray-800 capitalize">{{ participant.name }}</h3>
                            <p class="text-sm text-gray-600">Status: <span :class="statusClass(participant.status)">{{
                                    participant.status }}</span></p>
                        </div>

                        <div v-if="isAdmin && participant.status === 'pending'" class="space-x-2">
                            <button class="bg-green-500 text-white p-2 rounded-lg hover:bg-green-600">
                                Approve
                            </button>
                            <button class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600">
                                Reject
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

defineProps({
    participants:{
        type: Array
    },
    isAdmin: Boolean,
    tripId: Number
});

const statusClass = (status) => {
    return {
        'capitalize': true,
        'text-green-600': status === 'accepted',
        'text-red-600': status === 'rejected',
        'text-yellow-600': status === 'pending',
    };
};
</script>
