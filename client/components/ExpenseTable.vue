<template>
    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
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
                        <button @click="$emit('edit', expense)" class="text-blue-600 hover:text-blue-900 mr-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </button>
                        <button @click="$emit('delete', expense.id)" class="text-red-600 hover:text-red-900">
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
                    <td colspan="3" class="px-4 py-3 text-right text-sm font-medium">Total</td>
                    <td class="px-4 py-3 text-right text-sm font-medium">
                        {{ formatCurrency(totalExpenses) }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    expenses: {
        type: Array,
        required: true,
        default: () => []
    }
});

const totalExpenses = computed(() => {
    return props.expenses.reduce((sum, expense) => sum + parseFloat(expense.amount), 0);
});

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
</script>