<template>
  <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">
        Create your account
      </h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">

      <form class="max-w-sm mx-auto" @submit.prevent="handleSignup">

        <label for="name" class="block my-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
        <div class="relative">
          <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
              fill="currentColor" viewBox="0 0 24 24">
              <path fillRule="evenodd"
                d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"
                clipRule="evenodd" />
            </svg>


          </div>
          <input type="text" id="name" v-model="name"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Name" required>

        </div>
        <small v-if="errors?.name" class="text-red-500">{{ errors?.name[0] }}</small>

        <label for="email-address-icon" class="block my-2 text-sm font-medium text-gray-900 dark:text-white">Your
          Email</label>
        <div class="relative">
          <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
              fill="currentColor" viewBox="0 0 20 16">
              <path d="m10.036 8.278 9.258-7.79A1.979 1.979 0 0 0 18 0H2A1.987 1.987 0 0 0 .641.541l9.395 7.737Z" />
              <path
                d="M11.241 9.817c-.36.275-.801.425-1.255.427-.428 0-.845-.138-1.187-.395L0 2.6V14a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V2.5l-8.759 7.317Z" />
            </svg>
          </div>
          <input type="text" id="email-address-icon" v-model="email"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="name@flowbite.com">
        </div>
        <small v-if="errors?.email" class="text-red-500">{{ errors?.email[0] }}</small>

        <label for="password" class="block my-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
        <div class="relative">
          <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24" fill="currentColor">
              <path fill-rule="evenodd"
                d="M15.75 1.5a6.75 6.75 0 0 0-6.651 7.906c.067.39-.032.717-.221.906l-6.5 6.499a3 3 0 0 0-.878 2.121v2.818c0 .414.336.75.75.75H6a.75.75 0 0 0 .75-.75v-1.5h1.5A.75.75 0 0 0 9 19.5V18h1.5a.75.75 0 0 0 .53-.22l2.658-2.658c.19-.189.517-.288.906-.22A6.75 6.75 0 1 0 15.75 1.5Zm0 3a.75.75 0 0 0 0 1.5A2.25 2.25 0 0 1 18 8.25a.75.75 0 0 0 1.5 0 3.75 3.75 0 0 0-3.75-3.75Z"
                clip-rule="evenodd" />
            </svg>


          </div>
          <input type="password" id="password" v-model="password"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Password" required>
        </div>
        <small v-if="errors?.password" class="text-red-500">{{ errors?.password[0] }}</small>


        <label for="confirm-password" class="block my-2 text-sm font-medium text-gray-900 dark:text-white">Confirm
          Password</label>
        <div class="relative">
          <div class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24" fill="currentColor">
              <path fill-rule="evenodd"
                d="M15.75 1.5a6.75 6.75 0 0 0-6.651 7.906c.067.39-.032.717-.221.906l-6.5 6.499a3 3 0 0 0-.878 2.121v2.818c0 .414.336.75.75.75H6a.75.75 0 0 0 .75-.75v-1.5h1.5A.75.75 0 0 0 9 19.5V18h1.5a.75.75 0 0 0 .53-.22l2.658-2.658c.19-.189.517-.288.906-.22A6.75 6.75 0 1 0 15.75 1.5Zm0 3a.75.75 0 0 0 0 1.5A2.25 2.25 0 0 1 18 8.25a.75.75 0 0 0 1.5 0 3.75 3.75 0 0 0-3.75-3.75Z"
                clip-rule="evenodd" />
            </svg>
          </div>
          <input type="password" id="confirm-password" v-model="password_confirmation"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            placeholder="Confirm Password" required>
        </div>

        <button type="submit"
          class=" cursor-pointer mt-4 bg-indigo-600 dark:bg-indigo-600 text-white hover:bg-indigo-700 dark:hover:bg-indigo-700 px-4 py-2 rounded-md text-sm font-medium">
          Sign Up
        </button>
      </form>


      <p class="mt-10 text-center text-sm text-gray-500">
        Already have an account?
        <NuxtLink to="/login" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">
          Sign in
        </NuxtLink>
      </p>
    </div>
  </div>
</template>

<script setup>
const token = useCookie('token');
const userStore = useUserStore();
const name = ref('');
const email = ref('');
const password = ref('');
const password_confirmation = ref('');
const errors = ref(null);
const handleSignup = async (event) => {
  try{
    const response = await fetch('https://trip-planer-api.sankarsan.xyz/api/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'Access-Control-Allow-Origin': '*',
      },
      body: JSON.stringify({
        name: name.value,
        email: email.value,
        password: password.value,
        password_confirmation: password_confirmation.value
      }),
    });
    const data = await response.json();
    

    if (data.user) {
      userStore.setUser({ ...data.user, token: data.access_token });
      token.value = data.access_token;
      token.options = {
        maxAge: 60 * 60 * 24 * 30, // Cookie expires in 30 days
        path: '/'
      };
      navigateTo('/');
      console.log('reg successful');
    }
    
    if (data.errors) {
      errors.value = data.errors;  // Ensure the errors are set correctly
    } else {
      errors.value = null;  // Clear errors if no validation errors are returned
    }
    

  } catch(e){
    console.log(e);
  }
  
}
</script>
