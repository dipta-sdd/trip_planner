<template>
  <div class="flex h-screen">
  <!-- <div class="flex h-screen"> -->
    <!-- Sidebar -->
    <aside class=" w-64 bg-blue-800 text-white h-screen align-top ">
      <div class="p-6">
        <h2 class="text-2xl font-bold">Admin Panel</h2>
      </div>
      <nav class="mt-6">
        <ul>
          <li class="px-6 py-2 hover:bg-blue-700" :class="{ 'bg-blue-700': $route.path === '/admin' }">
            <NuxtLink to="/admin" class="flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                </path>
              </svg>
              Dashboard
            </NuxtLink>
          </li>
          <li class="px-6 py-2 hover:bg-blue-700" :class="{ 'bg-blue-700': $route.path === '/admin/users' }">
            <NuxtLink to="/admin/users"  class="flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                </path>
              </svg>
              Users
            </NuxtLink>
          </li>
          <li class="px-6 py-2 hover:bg-blue-700" :class="{ 'bg-blue-700': $route.path === '/admin/trips' }">
            <NuxtLink to="/admin/trips" class="flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                </path>
              </svg>
              Trips
            </NuxtLink>
          </li>

        </ul>
      </nav>
    </aside>

    <!-- Main Content -->
    <slot />
  </div>
</template>
  
  <script setup>
  const route = useRoute();
  const useUser = useUserStore();
  if( useUser.user?.role !== 'admin'){
    navigateTo('/login');
  }
  </script>
  