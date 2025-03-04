<template>
    <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Expense</h2>
            <!-- Add Participant Button (Admin Only) -->
            <button v-if="isAdmin" @click="isModalOpen = true"
                class="bg-green-500 text-white p-2 rounded-lg hover:bg-green-600 flex flex-nowrap gap-1 items-center">
                <PlusIcon /> Add
            </button>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg">
            <ExpenseChart :datedExpenses="datedExpenses" :groupedExpenses="groupedExpenses" />
            <div class="overflow-x-auto rounded-lg border border-gray-200 mt-4 shadow-md">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Added By</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Note</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="expense in expenses" :key="expense.id">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ formatDate(expense.date) }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ expense.name }}
                            </td>
                            <td class="px-4 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                    :class="categoryBadgeClass(expense.category)">
                                    {{ expense.category }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-500 max-w-xs">{{ expense.note }}</td>
                            <td class="px-4 py-4 text-right whitespace-nowrap text-sm font-medium">
                                {{ formatCurrency(expense.amount) }}
                            </td>
                            <td class="px-4 py-4 text-right whitespace-nowrap">
                                <button @click="{isModalOpen = true; form={...expense}}"
                                    class="text-blue-600 hover:text-blue-900 mr-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>
                                <button @click="deleteExpense(expense.id)" class="text-red-600 hover:text-red-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="expenses.length === 0">
                            <td colspan="5" class="px-4 py-4 text-center text-gray-500">No expenses recorded yet</td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-right text-sm font-medium">Total</td>
                            <td class="px-4 py-3 text-right text-sm font-medium">
                                {{ formatCurrency(totalExpenses) }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>
    <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-xs">
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm w-full max-w-2xl">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Add New Expense
                </h3>
                <button @click="clearForm" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-4">
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
                    <label for="category"
                        class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Category
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
                    <label for="note"
                        class="block text-sm font-medium leading-6 text-gray-900 dark:text-white">Note</label>
                    <div class="mt-2">
                        <textarea v-model="form.note" id="note"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"></textarea>
                    </div>
                </div>
                <p v-if="error" class="text-red-500 text-sm px-3"> {{ error }}</p>
                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" @click="clearForm"
                        class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button @click="handleSubmit" type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        {{ form.id ? 'Update' : 'Add' }} Expense
                    </button>

                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    expenses: {
        type: Object,
        required: true
    },
    tripId: {
        type: Number,
        required: true
    },
    isOwner: {
        type: Boolean,
        required: true
    },
    isAdmin: {
        type: Boolean,
        required: true
    }
})
const emit = defineEmits(['update']);
const isModalOpen = ref(false);
const totalExpenses = computed(() => {
    return props?.expenses?.reduce((sum, expense) => sum + parseFloat(expense.amount), 0);
});
const form = ref({
    amount: '',
    date: new Date().toISOString().split('T')[0],
    category: 'Miscellaneous',
    note: ''
});
watch(form.value, () => {
    console.log('form');
    console.log(form.value);
    console.log(props.expenses);
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
const clearForm = () => {
    form.value = {
        amount: '',
        date: new Date().toISOString().split('T')[0],
        category: 'Miscellaneous',
        note: ''
    };
    errors.value = {};
    error.value = null;
    isModalOpen.value = false;
}
const errors = ref({});
const error = ref(null);

const deleteExpense = async (id) => {
    try {
        await $fetch(`http://localhost:8000/api/trips/${props.tripId}/expenses/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${useCookie('token').value}`
            }
        })
        expenses.value = expenses.value.filter((expense) => expense.id !== id);
    } catch (error) {
        console.error(error);
    }
}

const categoryBadgeClass = (category) => {
    const classes = {
        'Accommodation': 'bg-purple-100 text-purple-800',
        'Food': 'bg-green-100 text-green-800',
        'Transportation': 'bg-blue-100 text-blue-800',
        'Activities': 'bg-yellow-100 text-yellow-800',
        'Entertainment': 'bg-pink-100 text-pink-800',
        'Emergency': 'bg-red-100 text-red-800',
        'Miscellaneous': 'bg-gray-100 text-gray-800'
    };
    return classes[category] || classes.Miscellaneous;
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

const handleSubmit = async () => {
    console.log('form');
    if(form.value.id){
        try {
            const response = await $fetch(`http://localhost:8000/api/trips/${props.tripId}/expenses/${form.value.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${useCookie('token').value}`
                },
                body: form.value
            })
            emit('update', response.expenses);
            clearForm();
        } catch (error) {
            if (error?.response?._data.errors) {
                errors.value = error.response._data.errors;
            } else {
                console.error(error);
                error.value = 'Something went wrong. Try again.';
            }
        }
    }
    else{
        try {
            const response = await $fetch(`http://localhost:8000/api/trips/${props.tripId}/expenses`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${useCookie('token').value}`
                },
                body: form.value
            })
            emit('update', response.expenses);
            clearForm();
        } catch (error) {
            if (error?.response?._data.errors) {
                errors.value = error.response._data.errors;
            } else {
                console.error(error);
                error.value = 'Something went wrong. Try again.';
            }
        }
    }
}

const groupedExpenses = computed(() => {
    console.log(form.value);
    console.log(props.expenses);
    const categorySums = {};
    props.expenses.forEach(expense => {
        if (!categorySums[expense.category]) {
            categorySums[expense.category] = 0;
        }
        categorySums[expense.category] += parseFloat(expense.amount);
    });
    return categorySums;
});

const datedExpenses = computed(() => {
    const dateSums = {};
    props.expenses.forEach(expense => {
        const date = new Date(expense.date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
        if (!dateSums[date]) {
            dateSums[date] = 0;
        }
        dateSums[date] += parseFloat(expense.amount);
    });
    return dateSums;
});

</script>
