<template>
    <nav class="bg-white dark:bg-gray-950 shadow-md">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
              <NuxtLink to="/" class="text-xl font-bold text-indigo-600">AuthLab</NuxtLink>
            </div>
          </div>
  
          <!-- Navigation Links -->
          <div class="flex items-center space-x-4">
            <NuxtLink to="/" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
              Home
            </NuxtLink>

            

            <div v-if="userStore.user">
              <NuxtLink to="/profile" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                Profile
              </NuxtLink>
            </div>

            <div v-else>
              <NuxtLink to="/login" class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                Login
              </NuxtLink>
            </div>
            <!-- Theme Toggle Button -->
            <ThemeToggle />
          </div>
        </div>
      </div>
    </nav>
  </template>

  <script setup>
    const userStore = useUserStore();
  </script>
  
  