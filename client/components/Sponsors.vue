<template>
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Sponsors</h2>

            <button v-if="isAdmin" @click="isModalOpen = true"
                class="bg-green-500 text-white p-2 rounded-lg hover:bg-green-600">
                Add Sponsor
            </button>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg">
            <div class="space-y-4">

                <div v-for="sponsor in sponsors" :key="sponsor.id" class="bg-white p-4 rounded-lg shadow-sm relative">
                    <div class="flex justify-between items-center mb-2">
                        <a v-if="sponsor?.website" :href="sponsor?.website" target="_blank"
                            class="text-md font-semibold text-gray-800 cursor-pointer">
                            {{ sponsor.name }}

                        </a>
                        <h4 v-else class="text-md font-semibold text-gray-800">{{ sponsor.name }}</h4>
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 text-sm font-semibold rounded-full"> $ {{
                            sponsor.amount }}</span>
                    </div>
                    <p class="text-sm text-gray-600">Description: Provided hiking gear.</p>
                    <button v-if="isAdmin" @click="updateModal(sponsor)"
                        class="absolute bottom-2 right-2 p-1 text-gray-500 hover:text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </button>
                </div>
                <div v-if="!sponsors?.length">
                    <p class="text-sm  text-red-400">No sponsors yet.</p>
                </div>

            </div>
        </div>
    </div>
    <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm w-full max-w-2xl">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ sponsor.id ? 'Edit' : 'Add' }} sponsor
                </h3>
                <button @click="clearSponsor" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-4 space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Name
                        <span class="text-red-500">*</span> </label>
                    <div class="mt-2">
                        <input v-model="sponsor.name" type="text"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
                    </div>
                    <small class="text-red-500" v-if="errors?.name">{{ errors.name[0] }}</small>
                </div>
                <div>
                    <label for="website"
                        class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Website </label>
                    <div class="mt-2">
                        <input v-model="sponsor.website" type="text"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
                    </div>
                    <small class="text-red-500" v-if="errors?.website">{{ errors.website[0] }}</small>
                </div>

                <div>
                    <label for="description"
                        class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Description <span
                            class="text-red-500">*</span> </label>
                    <div class="mt-2">
                        <textarea v-model="sponsor.description"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"></textarea>
                    </div>
                    <small class="text-red-500" v-if="errors?.description">{{ errors.description[0] }}</small>
                </div>

                <div>
                    <label for="amount" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Amount
                        <span class="text-red-500">*</span> </label>
                    <div class="mt-2">
                        <input v-model="sponsor.amount" type="number"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
                    </div>
                    <small class="text-red-500" v-if="errors?.amount">{{ errors.amount[0] }}</small>
                </div>
            </div>

            <p class="text-red-500 mx-3" v-if="sponsorError">{{ sponsorError }}</p>

            <!-- Modal Footer -->
            <div class="flex items-center p-4 border-t border-gray-200 dark:border-gray-600">
                <button v-if="!sponsor.id" @click.prevent="submitSponsor"
                    class="text-white bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg">
                    Add
                </button>
                <button v-if="sponsor.id" @click.prevent="updateSponsor"
                    class="text-white bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg">
                    Update
                </button>

                <button v-if="sponsor.id" @click.prevent="deleteSponsor(sponsor)"
                    class="text-white bg-red-700 hover:bg-red-800 px-4 py-2 rounded-lg mx-2">
                    Delete
                </button>

                <button @click.prevent="clearSponsor"
                    class="ml-2 px-4 py-2 text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 rounded-lg dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</template>
<script setup>
const props = defineProps({
    isAdmin: Boolean,
    sponsors: Array,
    tripId: Number
})
const emit = defineEmits(['addSponsor', 'editSponsor', 'deleteSponsor']);
const isModalOpen = ref(false);
const sponsorError = ref(null);
const sponsor = ref({
    name: '',
    website: '',
    description: '',
    amount: 0,
})
const errors = ref(null);


const clearSponsor = () => {
    errors.value = null;
    isModalOpen.value = false;
    sponsorError.value = null;
    sponsor.value = {
        name: '',
        website: '',
        description: '',
        amount: 0,
    }
}

const submitSponsor = async () => {
    try {
        const data = await $fetch(`http://localhost:8000/api/trips/${props.tripId}/sponsor`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            },
            body: sponsor.value
        });
        // console.log(data);

        clearSponsor();
        emit('addSponsor', { ...data.sponsor });
    } catch (error) {
        if (error?.response?._data.errors) {
            errors.value = error.response._data.errors;
        } else {
            console.error(error);
            sponsorError.value = 'Something went wrong. Try again.';
        }
        
    }
};
const updateModal = (data) => {
    sponsor.value = data;
    isModalOpen.value = true;
}
const updateSponsor = async () => {
    try {
        const data = await $fetch(`http://localhost:8000/api/trips/${props.tripId}/sponsor/${sponsor.value.id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            },
            body: sponsor.value
        });
        clearSponsor();
        emit('editSponsor', { ...data.sponsor });
    } catch (error) {
        if (error?.response?._data.errors) {
            errors.value = error.response._data.errors;
        } else {
            console.error(error);
            sponsorError.value = 'Something went wrong. Try again.';
        }
    }     
  
};

const deleteSponsor = async (sponsor) => {
    try {
        const data = await $fetch(`http://localhost:8000/api/trips/${props.tripId}/sponsor/${sponsor.id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            }
        });
        clearSponsor();
        emit('deleteSponsor', sponsor.id);
    } catch (error) {
        console.error(error);
        sponsorError.value = 'Something went wrong. Try again.';
    }
};
</script>