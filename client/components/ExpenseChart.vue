<template>
    <div class="flex flex-col md:flex-row gap-8">
        <div class="flex-1 p-4 bg-white rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Expenses by Category</h2>
            <div class="relative h-[300px]">
                <canvas ref="categoryChartRef"></canvas>
            </div>
        </div>

        <div class="flex-1 p-4 bg-white rounded-lg shadow-md">
            <h2 class="text-xl font-bold mb-4 text-gray-800">Expenses by Date</h2>
            <div class="relative h-[300px]">
                <canvas ref="dateChartRef"></canvas>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import Chart from 'chart.js/auto'

const props = defineProps({
    datedExpenses: {
        type: Object,
        default: () => ({})
    },
    groupedExpenses: {
        type: Object,
        default: () => ({})
    }
})

const categoryChartRef = ref(null)
const dateChartRef = ref(null)

let categoryChart = null
let dateChart = null

const generateCharts = () => {
    if (categoryChart) categoryChart.destroy()
    if (dateChart) dateChart.destroy()

    categoryChart = new Chart(categoryChartRef.value, {
        type: 'pie',
        data: {
            labels: Object.keys(props.groupedExpenses),
            datasets: [{
                data: Object.values(props.groupedExpenses),
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#8BC34A', '#9C27B0',
                    '#2196F3', '#E91E63', '#4CAF50', '#66D9EF', '#03A9F4'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)',
                    'rgba(139, 195, 74, 1)', 'rgba(156, 39, 176, 1)', 'rgba(33, 150, 243, 1)',
                    'rgba(233, 30, 99, 1)', 'rgba(76, 175, 80, 1)', 'rgba(102, 217, 239, 1)',
                    'rgba(3, 169, 244, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: (context) => {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: $${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    })

    dateChart = new Chart(dateChartRef.value, {
        type: 'bar',
        data: {
            labels: Object.keys(props.datedExpenses),
            datasets: [{
                label: 'Expenses',
                data: Object.values(props.datedExpenses),
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (value) => `$${value}`
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: (context) => {
                            return `$${context.raw}`;
                        }
                    }
                }
            }
        }
    })
}

watch(() => [props.datedExpenses, props.groupedExpenses], () => {
    try {
        generateCharts();
    } catch (error) {
        console.error('Error generating charts:', error);
    }
}, { deep: true })

onMounted(() => {
    generateCharts();
})
</script>