<template>
    <header class="bg-white shadow">
        <div class="px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-semibold">{{ title }}</h1>
            <div class="flex items-center">
                <input type="text" placeholder="Search..." class="px-4 py-2 border rounded-lg focus:outline-none">
                <button class="ml-4 p-2 text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                        </path>
                    </svg>
                </button>
            </div>
        </div>
    </header>
</template>
<script setup>
 const props =   defineProps({
        title: {
            type: String,
            default: 'Dashboard'
        }
    })
    
</script>