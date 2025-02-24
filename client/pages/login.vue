<template>
  <div class="max-w-md mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
    <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-6">Login to Your Account</h2>
    <form @submit.prevent="handleLogin" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Email</label>
        <input
          type="email"
          v-model="email"
          required
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Password</label>
        <input
          type="password"
          v-model="password"
          required
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        />
      </div>
      <button
        type="submit"
        class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors"
      >
        Login
      </button>
    </form>
    <p class="mt-4 text-center text-gray-600 dark:text-gray-300">
      Don't have an account?
      <NuxtLink to="/signup" class="text-blue-500 hover:text-blue-600">Sign up</NuxtLink>
    </p>
  </div>
</template>

<script setup >
const email = ref('')
const password = ref('')

const handleLogin = () => {
  // TODO: Implement login logic
  console.log('Login attempt:', { email: email.value, password: password.value })
}
</script> 