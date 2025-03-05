<template>
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Participants</h2>
            <!-- Add Participant Button (Admin Only) -->
            <button v-if="isAdmin" @click="isModalOpen = true"
                class="bg-green-500 text-white p-2 rounded-lg hover:bg-green-600 flex flex-nowrap gap-1 items-center">
                <PlusIcon /> Invite
            </button>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg">
            <div class="grid my-grid-400 gap-4">

                <div v-for="participant in participants?.filter(participant => (participant.status !== 'rejected' && useUser?.user?.id !== participant.user_id)) || []"
                    :key="participant.id" class="bg-white p-4 rounded-lg shadow-sm">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3
                                class="text-md font-semibold text-gray-800 capitalize flex flex-nowrap items-center gap-2">
                                {{ participant.name }}
                                <span v-if="participant.role === 'owner'"
                                    class="text-xs px-3 py-1.5  rounded-full bg-blue-200 text-blue-950">Owner</span>
                                <span v-else-if="participant.can_edit === true"
                                    class="text-xs px-3 py-1.5 rounded-full bg-green-200 text-green-950">Admin</span>
                            </h3>
                            <p class="text-sm text-gray-600">Status: <span :class="statusClass(participant.status)">{{
                                participant.status }}</span></p>
                        </div>

                        <div v-if="isAdmin" class="space-x-2 flex flex-nowrap items-center">
                            <button v-if="participant.status === 'pending'" @click="updates(participant.id, 'accepted')"
                                class="bg-green-500 text-white p-2 rounded-lg hover:bg-green-600">
                                Approve
                            </button>
                            <button v-if="participant.status === 'pending'" @click="updates(participant.id, 'rejected')"
                                class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600">
                                Reject
                            </button>
                            <button v-else-if="participant.status === 'rejected' && isOwner"
                                @click="updates(participant.id, 'accepted')"
                                class="bg-green-500 text-white p-2 rounded-lg hover:bg-green-600">
                                Accept
                            </button>
                            <button v-else-if="participant.status === 'invited'" @click="cancelInvite(participant.id)"
                                class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600">
                                Cancel Invitation
                            </button>
                            <button v-else-if="participant.status === 'accepted' && !participant.can_edit && isOwner"
                                @click="makeAdmin(participant.id)"
                                class="bg-green-500 text-white p-2 rounded-lg hover:bg-green-600">
                                Make Admin
                            </button>
                            <button v-else-if="participant.status === 'accepted' && participant.can_edit && isOwner"
                                @click="removeAdmin(participant.id)"
                                class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600">
                                Remove As Admin
                            </button>

                            <button v-if="participant.status === 'accepted' && !participant.can_edit && isOwner" @click="remove(participant.id)"
                                class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>

                            </button>

                        </div>


                    </div>
                </div>
                <div v-if="!participants?.length">
                    <p class="text-sm px-4 py-2 text-center text-gray-500">No participants yet.</p>
                </div>
            </div>
        </div>
    </div>
    <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-xs">
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm w-full max-w-2xl">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Invite Your Friend & Family
                </h3>
                <button @click="isModalOpen = false" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-4 space-y-4">
                <div>
                    <div v-if="invited?.length > 0">
                        <span>You have invited:</span>
                        <div class="d-flex flex-wrap">

                            <div v-for="user in invited" :key="user.id"
                                class="inline-flex items-center bg-blue-50 rounded-full ps-1 pe-3 m-1 border border-blue-200">
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-2">
                                    {{ user.name.charAt(0) }}
                                </div>
                                <div>
                                    <div class="font-medium">{{ user.name }}</div>
                                    <div class="text-xs text-gray-500">{{ user.email }}</div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="mt-2 relative">
                        <Autocompleteinput v-model="selectedUser" :suggestions="suggestions" display-property="name"
                            @select="onUserSelect" label="Select a user"
                            placeholder="Start typing a user's name / email" />
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>

const props = defineProps({
    participants: {
        type: Array
    },
    isOwner: Boolean || Number,
    isAdmin: Boolean || Number,
    tripId: Number,
    suggestions: Array
});
const emit = defineEmits(['update']);
const useUser = useUserStore();
const isModalOpen = ref(false);
const selectedUser = ref(null);
const invited = ref([]);

const statusClass = (status) => {
    return {
        'capitalize': true,
        'text-green-600': status === 'accepted',
        'text-red-600': status === 'rejected',
        'text-yellow-600': status === 'pending',
        'text-gray-600': status === 'invited',


    };
};
const onUserSelect = async (user) => {
    console.log('User selected:', user);
    // invited.value.push(user);
    try {
        const res = await $fetch(`https://trip-planer-api.sankarsan.xyz/api/trips/${props.tripId}/participants/invite/${user.id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            },
        });
        invited.value.push(user);
        emit('update', [...props.participants, { ...res, name: user.name, email: user.email }]);
    } catch (error) {
        console.error(error);
    }
}

const cancelInvite = async (participantId) => {
    try {
        const res = await $fetch(`https://trip-planer-api.sankarsan.xyz/api/trips/participants/cancelInvite/${participantId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            },
        });

        emit('update', props.participants.filter(participant => participant.id !== participantId));
    } catch (error) {
        console.error(error);
    }
}

const updates = async (participantId, status) => {
    try {
        const res = await $fetch(`https://trip-planer-api.sankarsan.xyz/api/trips/participants/update/${participantId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            },
            body: JSON.stringify({ status })
        });
        emit('update', props.participants.map(participant => {
            if (participant.id === participantId) {
                return { ...participant, status: res.status };
            }
            return participant;
        }));
    } catch (error) {
        console.error(error);
    }
}

const removeAdmin = async (participantId) => {
    try {
        const res = await $fetch(`https://trip-planer-api.sankarsan.xyz/api/trips/participants/removeAdmin/${participantId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            },
        });
        emit('update', props.participants.map(participant => {
            if (participant.id === participantId) {
                return { ...participant, can_edit: res.can_edit };
            }
            return participant;
        }));
    } catch (error) {
        console.error(error);
    }
}

const makeAdmin = async (participantId) => {
    try {
        const res = await $fetch(`https://trip-planer-api.sankarsan.xyz/api/trips/participants/makeAdmin/${participantId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            },
        });
        emit('update', props.participants.map(participant => {
            if (participant.id === participantId) {
                return { ...participant, can_edit: res.can_edit };
            }
            return participant;
        }));
    } catch (error) {
        console.error(error);
    }
}

const remove = async (participantId) => {
    try {
        const res = await $fetch(`https://trip-planer-api.sankarsan.xyz/api/trips/participants/remove/${participantId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            },
        });
        emit('update', props.participants.filter(participant => participant.id !== participantId));
    } catch (error) {
        console.error(error);
    }
}
</script>
