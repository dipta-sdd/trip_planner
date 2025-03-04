<template>
  <header class="bg-white shadow-md">
    <nav class="container mx-auto px-4 py-4">
      <div class="flex justify-between items-center">
        <div class="flex items-center">
          <a href="#" class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
              stroke="currentColor" class="w-8 h-8 text-blue-600">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M6.115 5.19l.319 1.913A6 6 0 008.11 10.36L9.75 12l-.387.775c-.217.433-.132.956.21 1.298l1.348 1.348c.21.21.329.497.329.795v1.089c0 .426.24.815.622 1.006l.153.076c.433.217.956.132 1.298-.21l.723-.723a8.7 8.7 0 002.288-4.042 1.087 1.087 0 00-.358-1.099l-1.33-1.108c-.251-.21-.582-.299-.905-.245l-1.17.195a1.125 1.125 0 01-.98-.314l-.295-.295a1.125 1.125 0 010-1.591l.13-.132a1.125 1.125 0 011.3-.21l.603.302a.809.809 0 001.086-1.086L14.25 7.5l1.256-.837a4.5 4.5 0 001.528-1.732l.146-.292M6.115 5.19A9 9 0 1017.18 4.64M6.115 5.19A8.965 8.965 0 0112 3c1.929 0 3.716.607 5.18 1.64" />
            </svg>
            <span class="ml-2 text-xl font-bold text-gray-800">TripPlanner</span>
          </a>
        </div>

        <div class="flex items-center space-x-4">




          <NuxtLink href="/" class="py-2 px-4 text-gray-700 hover:text-blue-600 font-medium">Home</NuxtLink>
          <NuxtLink href="/trips" class="py-2 px-4 text-gray-700 hover:text-blue-600 font-medium">Trips</NuxtLink>
          <div v-if="useUserStore()?.user" class="relative group">
            <button class="py-2 px-4 text-gray-700 hover:text-blue-600 font-medium">
              {{userStore.user?.name || 'Profile'}}
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block ml-1" viewBox="0 0 20 20"
                fill="currentColor">
                <path fill-rule="evenodd"
                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                  clip-rule="evenodd" />
              </svg>
            </button>
            <div
              class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
              <NuxtLink v-if="userStore.user?.role == 'admin'" href="/admin"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                Dashboard</NuxtLink>
              <NuxtLink href="/trips/my-trips" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">My Trips
              </NuxtLink>
              <NuxtLink href="/create-trip" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">New Trip
              </NuxtLink>
              <button @click="logout" href="#"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">Logout</button>
            </div>
          </div>
          <NuxtLink href="/login" v-if="!useUserStore()?.user"
            class="py-2 px-4 text-gray-700 hover:text-blue-600 font-medium">Login</NuxtLink>
          <NuxtLink href="/signup" v-if="!useUserStore()?.user"
            class="py-2 px-4 text-gray-700 hover:text-blue-600 font-medium">Signup</NuxtLink>
        </div>




      </div>


    </nav>
  </header>

</template>

  <script setup>
    const userStore = useUserStore();
    const token = useCookie('token');
    const logout = () => {
      userStore.clearUser();
      token.value = null;
      navigateTo('/login');
    }

  </script>
  
  