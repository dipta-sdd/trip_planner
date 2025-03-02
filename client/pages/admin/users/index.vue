<template>
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Top Navigation -->
        <AdminHeader />

        <main class="flex-1 bg-gray-100 overflow-y-auto p-3">
            <!-- Table Section -->
            <AdminUsers :limit="10" />
        </main>
    </div>
</template>
<script setup>
definePageMeta({
    layout: 'admin'
})

</script>
