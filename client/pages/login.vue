<template>
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
      <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900 dark:text-gray-100">
          Sign in to your account
        </h2>
      </div>
  
      <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        <form class="space-y-6" @submit.prevent="handleLogin">
          <div>
            <label for="email" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Email address</label>
            <div class="mt-2">
              <input
                v-model="email"
                id="email"
                name="email"
                type="email"
                autocomplete="email"
                required
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"
              />
            </div>
          </div>
  
          <div>
            <label for="password" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Password</label>
            <div class="mt-2">
              <input
                v-model="password"
                id="password"
                name="password"
                type="password"
                autocomplete="current-password"
                required
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"
              />
            </div>
          </div>
  
          <div>
            <button
              type="submit"
              class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            >
              Sign in
            </button>
          </div>
        </form>
  
        <p class="mt-10 text-center text-sm text-gray-500 dark:text-gray-400">
          Not a member?
          <NuxtLink to="/signup" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">
            Create an account
          </NuxtLink>
        </p>
      </div>
    </div>
  </template>
  

<script setup>
const token = useCookie('token');
const userStore = useUserStore();
const email = ref('');
const password = ref('');
const handleLogin = async (event) => {
  
  try {
    const res = await fetch('http://localhost:8000/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        email: email.value,
        password: password.value
      }),
    });
    const data = await res.json();
    userStore.setUser({...data.user, token: data.access_token});
    token.value = data.access_token;
    token.options = {
      maxAge: 60 * 60 * 24 * 30, // Cookie expires in 30 days
      path: '/'
    };
    navigateTo('/');
    console.log('login successful');
  } catch (error) {
    console.log(error);
  }
}
</script>