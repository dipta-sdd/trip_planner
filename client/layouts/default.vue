<template>
    <div :class="theme" class="min-h-screen bg-gray-50 dark:bg-gray-900">
      <!-- Navbar -->
      <Navbar />
  
      <!-- Page Content -->
      <main class="">
        <slot />
      </main>
      <FooterSection />
    </div>
  </template>
  
  <script setup>
  
  </script>
  