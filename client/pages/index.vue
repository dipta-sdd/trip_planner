<template>
  <div class="max-w-4xl mx-auto text-center">
    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-6">
      Welcome to AuthLab
    </h1>
    <p class="text-lg text-gray-600 dark:text-gray-300 mb-8">
      A secure and modern authentication system built with Nuxt 3
    </p>
    <div class="flex justify-center gap-4">
      <NuxtLink
        to="/login"
        class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors"
      >
        Get Started
      </NuxtLink>
    </div>
  </div>
</template>