<template>
  <div class="max-w-md mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
    <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white mb-6">Create an Account</h2>
    <form @submit.prevent="handleSignup" class="space-y-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Name</label>
        <input
          type="text"
          v-model="name"
          required
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        />
      </div>
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
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Confirm Password</label>
        <input
          type="password"
          v-model="confirmPassword"
          required
          class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
        />
      </div>
      <button
        type="submit"
        class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors"
      >
        Sign Up
      </button>
    </form>
    <p class="mt-4 text-center text-gray-600 dark:text-gray-300">
      Already have an account?
      <NuxtLink to="/login" class="text-blue-500 hover:text-blue-600">Login</NuxtLink>
    </p>
  </div>
</template>

<script setup >
const name = ref('')
const email = ref('')
const password = ref('')
const confirmPassword = ref('')

const handleSignup = () => {
  // TODO: Implement signup logic
  console.log('Signup attempt:', {
    name: name.value,
    email: email.value,
    password: password.value,
    confirmPassword: confirmPassword.value
  })
}
</script> 