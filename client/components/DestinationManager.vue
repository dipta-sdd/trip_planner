<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-xs">
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm w-full max-w-2xl">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ data?.id ? 'Edit' : 'Add' }} Destination
                </h3>
                <button @click="cancel" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-4 space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Name
                        <span class="text-red-500">*</span> </label>
                    <div class="mt-2">
                        <input v-model="data.name" type="text"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
                    </div>
                    <small class="text-red-500" v-if="errors?.name">{{ errors?.name[0] }}</small>
                </div>

                <div>
                    <label for="arrival_date"
                        class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Arrival Date <span
                            class="text-red-500">*</span> </label>
                    <div class="mt-2">
                        <input v-model="data.arrival_date" type="date"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
                    </div>
                    <small class="text-red-500" v-if="errors?.arrival_date">{{ errors?.arrival_date[0] }}</small>
                </div>

                <div>
                    <label for="departure_date"
                        class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Departure Date <span
                            class="text-red-500">*</span> </label>
                    <div class="mt-2">
                        <input v-model="data.departure_date" type="date"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
                    </div>
                    <small class="text-red-500" v-if="errors?.departure_date">{{ errors?.departure_date[0] }}</small>
                </div>

                <div>
                    <label for="notes"
                        class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Notes</label>
                    <div class="mt-2">
                        <textarea v-model="data.notes" rows="3"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"></textarea>
                    </div>
                    <small class="text-red-500" v-if="errors?.notes">{{ errors?.notes[0] }}</small>
                </div>
            </div>

            <p class="text-red-500 mx-3" v-if="error">{{ error }}</p>
            <div class="flex items-center p-4 border-t border-gray-200 dark:border-gray-600">
                <button v-if="!data?.id" @click.prevent="submit"
                    class="text-white bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg">
                    Add
                </button>
                <button v-if="data?.id" @click.prevent="update"
                    class="text-white bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg">
                    Update
                </button>

                <button v-if="data?.id" @click.prevent="destroy(data?.id)"
                    class="text-white bg-red-700 hover:bg-red-800 px-4 py-2 rounded-lg mx-2">
                    Delete
                </button>

                <button @click.prevent="cancel"
                    class="ml-2 px-4 py-2 text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 rounded-lg dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>

const props = defineProps({
    des: {
        type: Object,
        default: () => ({
            name: '',
            arrival_date: '',
            departure_date: '',
            notes: '',
        })
    },
    tripId: Number
})
const emit = defineEmits(['edit', 'add', 'delete', 'close']);

onMounted(() => {
    if (props.des) {
        console.log(props.des);
        data.value = { ...props.des, arrival_date: props.des.arrival_date.split(' ')[0], departure_date: props.des.departure_date.split(' ')[0] };
    }
})
const data = ref(props.des);


const submit = async () => {
    try {
        const res = await $fetch(`https://trip-planer-api.sankarsan.xyz/api/trips/${props.tripId}/destinations`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            },
            body: data.value
        })
        emit('add', { ...res });
    } catch (error) {
        if (error?.response?._data.errors) {
            errors.value = error.response._data.errors;
        } else {
            console.error(error);
            error.value = 'Something went wrong. Try again.';
        }
    }
}

const update = async () => {
    try {
        const res = await $fetch(`https://trip-planer-api.sankarsan.xyz/api/trips/${props.tripId}/destinations/${data.value.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            },
            body: data.value
        })
        emit('edit', { ...res });
    } catch (error) {
        if (error?.response?._data.errors) {
            errors.value = error.response._data.errors;
        } else {
            console.error(error);
            error.value = 'Something went wrong. Try again.';
        }
    }
}

const destroy = async (id) => {
    try {
        const res = await $fetch(`https://trip-planer-api.sankarsan.xyz/api/trips/${props.tripId}/destinations/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            }
        })
        emit('delete', id);
    } catch (error) {
        console.error(error);
        error.value = 'Something went wrong. Try again.';
    }
}
const cancel = () => {
    data.value = {
        name: '',
        arrival_date: '',
        departure_date: '',
        notes: '',
    };
    errors.value = {};
    error.value = null;
    emit('close');
}
const errors = ref({});
const error = ref(null);
</script>
