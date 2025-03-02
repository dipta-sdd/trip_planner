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
          <NuxtLink to="/"
            class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
            Home
          </NuxtLink>



          <div v-if="userStore.user">
            <div class="relative">
              <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown" @click="toggleDropdown"
                class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium text-center inline-flex items-center "
                type="button"> {{ userStore.user.name }} <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
                  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m1 1 4 4 4-4" />
                </svg>
              </button>

              <!-- Dropdown menu -->
              <div :class="[
                open ? 'block' : 'hidden',
                'z-10',
                'bg-white',
                'divide-y',
                'divide-gray-100',
                'rounded-lg',
                'shadow-sm',
                'w-44',
                'dark:bg-gray-700',
                'absolute',
                'right-0',
                'left-0'
              ]">

                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                  <li>
                    <NuxtLink href="/trips/my-trips"
                      class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">My Trips</NuxtLink>
                  </li>
                  <li>
                    <NuxtLink href="/create-trip"
                      class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">New Trip</NuxtLink>
                  </li>
                  <li>
                    <a href="#"
                      class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Earnings</a>
                  </li>
                  <li>
                    <button @click="logout"
                      class="block w-full text-start  px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                      Logout
                    </button>
                  </li>
                </ul>
              </div>
            </div>


          </div>

          <div v-else>
            <NuxtLink to="/login"
              class="text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
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
    const open = ref(false);
    const userStore = useUserStore();
    const token = useCookie('token');
    const logout = () => {
      userStore.clearUser();
      token.value = null;
      navigateTo('/login');
    }

    const toggleDropdown = () => {
      open.value = !open.value
    }
  </script>
  
  