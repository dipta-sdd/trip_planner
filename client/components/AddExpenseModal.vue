<template>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg w-full max-w-md">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium">Add Expense</h3>
                <button @click="$emit('close')" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form @submit.prevent="handleSubmit" class="p-4 space-y-4">
                
                <div>
                    <label for="name" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Amount
                        <span class="text-red-500">*</span> </label>
                    <div class="mt-2">
                        <input v-model="form.amount" type="number" 
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
                    </div>
                    <small class="text-red-500" v-if="errors?.amount">{{ errors.amount[0] }}</small>
                </div>
                <div>
                    <label for="date" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Date
                        <span class="text-red-500">*</span> </label>
                    <div class="mt-2">
                        <input v-model="form.date" type="date" required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
                    </div>
                    <small class="text-red-500" v-if="errors?.date">{{ errors.date[0] }}</small>
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Category
                        <span class="text-red-500">*</span> </label>
                    <div class="mt-2">
                        <select v-model="form.category" id="category" required
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3">
                            <option v-for="category in categories" :key="category" :value="category">
                                {{ category }}
                            </option>
                        </select>
                    </div>
                    <small class="text-red-500" v-if="errors?.category">{{ errors.category[0] }}</small>
                </div>
                <div>
                    <label for="note" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Note</label>
                    <div class="mt-2">
                        <textarea v-model="form.note" id="note"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"></textarea>
                    </div>
                </div>
                <p v-if="error" class="text-red-500 text-sm px-3"> {{ error }}</p>
                <div class="flex justify-end space-x-2">
                    <button type="button" @click="$emit('close')"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Save Expense
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
const emit = defineEmits(['submit', 'close']);
const props = defineProps({
    tripId: Number
})
const categories = [
    'Accommodation',
    'Food',
    'Transportation',
    'Activities',
    'Entertainment',
    'Emergency',
    'Miscellaneous'
];

const form = ref({
    amount: '',
    date: new Date().toISOString().split('T')[0],
    category: 'Miscellaneous',
    note: ''
});
const errors = ref({});
const error = ref('');
const handleSubmit = async () => {
    try {
        const data = await $fetch(`https://trip-planer-api.sankarsan.xyz/api/trips/${props.tripId}/expense`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            },
            body: form.value
        });
        emit('submit', data.expenses);
    } catch (e) {
        if(e?.response?._data.errors){
            errors.value = e.response._data.errors;
            console.log(errors.value);
        }else{
            console.error(e);
            error.value = 'Something went wrong. Try again.';
        }
    }
};
</script>