<template>
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation -->
        <AdminHeader />

        <main class="flex-1 bg-gray-100 overflow-y-auto p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">Total Users</h3>
                    <p class="text-2xl font-bold mt-2">1,234</p>
                </div>
                <!-- Card 2 -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">Active Trips</h3>
                    <p class="text-2xl font-bold mt-2">56</p>
                </div>
                <!-- Card 3 -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold">Total Revenue</h3>
                    <p class="text-2xl font-bold mt-2">$12,345</p>
                </div>
            </div>

            <!-- Table Section -->
            <AdminTrips :limit="5" />
            <AdminUsers :limit="5" />
        </main>
    </div>
</template>
<script setup>
definePageMeta({
    layout: 'admin'
})

</script>
