<template>
    <header class=" bg-white shadow">
        <div class="px-6 py-4 flex justify-between items-center">
            <h1 class="text-xl font-semibold">{{ title }}</h1>
            <div class="flex items-center space-x-4 mr-2  md:mr-5">




                <NuxtLink href="/" class="py-2 px-4 text-gray-700 hover:text-blue-600 font-medium">Home</NuxtLink>
                <NuxtLink href="/trips" class="py-2 px-4 text-gray-700 hover:text-blue-600 font-medium">Trips</NuxtLink>
                <div v-if="useUserStore()?.user" class="relative group">
                    <button class="py-2 px-4 text-gray-700 hover:text-blue-600 font-medium">
                        {{ userStore.user?.name || 'Profile' }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block ml-1" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div
                        class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <NuxtLink v-if="userStore.user?.role == 'admin'" href="/admin" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                            Dashboard</NuxtLink>
                        <NuxtLink href="/trips/my-trips" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">
                            My Trips</NuxtLink>
                        <NuxtLink href="/create-trip" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">New
                            Trip
                        </NuxtLink>
                        <button @click="logout" href="#"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-50">Logout</button>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>
<script setup>
 const props = defineProps({
    title: {
        type: String,
        default: 'Dashboard'
    }
})
// console.log(props.title);
const userStore = useUserStore();
const token = useCookie('token');
const logout = () => {
    userStore.clearUser();
    token.value = null;
    navigateTo('/login');
}

</script>