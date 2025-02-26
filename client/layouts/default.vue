<template>
    <div :class="theme" class="min-h-screen bg-gray-50 dark:bg-gray-900">
      <!-- Navbar -->
      <Navbar />
  
      <!-- Page Content -->
      <main class="max-w-7xl mx-auto py-3 sm:px-6 lg:px-8 bg-transparent">
        <slot />
      </main>
    </div>
  </template>
  
  <script setup>
  
  </script>
  