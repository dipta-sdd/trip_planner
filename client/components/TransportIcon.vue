<template>
    <div v-if="type == 'flight'" class="">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-5 text-blue-500">
            <path
                d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"
                stroke-linecap="round" stroke-linejoin="round" />
        </svg>

    </div>

    <!-- Train -->
    <div v-else-if="type == 'train'" class="">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-5 text-red-500">
            <rect x="4" y="3" width="16" height="16" rx="2" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M4 11h16" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M12 3v8" stroke-linecap="round" stroke-linejoin="round" />
            <circle cx="8" cy="16" r="1" fill="currentColor" />
            <circle cx="16" cy="16" r="1" fill="currentColor" />
            <path d="M8 21l-2 2M16 21l2 2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>

    </div>

    <!-- Bus -->
    <div v-else-if="type == 'bus'" class="">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-5 text-green-500">
            <rect x="2" y="3" width="20" height="14" rx="2" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M2 9h20" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M7 17v3M17 17v3" stroke-linecap="round" stroke-linejoin="round" />
            <circle cx="6" cy="14" r="1" fill="currentColor" />
            <circle cx="18" cy="14" r="1" fill="currentColor" />
        </svg>

    </div>

    <!-- Taxi -->
    <div v-else-if="type == 'taxi'" class="">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-5 text-yellow-500">
            <path d="M3 18v-5l2-5h14l2 5v5" stroke-linecap="round" stroke-linejoin="round" />
            <rect x="2" y="13" width="20" height="5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M7 18v2M17 18v2" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M5 11V7c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2v4" stroke-linecap="round" stroke-linejoin="round" />
            <circle cx="6" cy="15.5" r="1" fill="currentColor" />
            <circle cx="18" cy="15.5" r="1" fill="currentColor" />
        </svg>

    </div>

    <!-- Bike -->
    <div v-else-if="type == 'bike'" class="">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-5 text-purple-500">
            <circle cx="5.5" cy="17.5" r="3.5" stroke-linecap="round" stroke-linejoin="round" />
            <circle cx="18.5" cy="17.5" r="3.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M12 17.5l-3-7 4-3h4M5.5 6h4l3 7" stroke-linecap="round" stroke-linejoin="round" />
        </svg>

    </div>

    <!-- Car -->
    <div v-else-if="type == 'car'" class="">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-5 text-indigo-500">
            <path d="M5 11l2-6h10l2 6M3 17h18v2c0 1.1-.9 2-2 2H5c-1.1 0-2-.9-2-2v-2z" stroke-linecap="round"
                stroke-linejoin="round" />
            <rect x="2" y="11" width="20" height="6" stroke-linecap="round" stroke-linejoin="round" />
            <circle cx="7" cy="14" r="1" fill="currentColor" />
            <circle cx="17" cy="14" r="1" fill="currentColor" />
        </svg>

    </div>

    <!-- Motorcycle -->
    <div v-else-if="type == 'motorcycle'" class="">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-5 text-orange-500">
            <circle cx="5" cy="17" r="3" stroke-linecap="round" stroke-linejoin="round" />
            <circle cx="19" cy="17" r="3" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M7.5 14l4-6h5l1 2-6 4M13 6h-3l-3 4.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M15 12l-3 5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>

    </div>

    <!-- Boat -->
    <div v-else-if="type == 'boat'" class="">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-5 text-cyan-500">
            <path d="M3 17h18c-.6 3-3 5-6 5H9c-3 0-5.4-2-6-5z" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M3 17l1.5-9 3-3h9l3 3 1.5 9" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M12 7v4M8 11h8" stroke-linecap="round" stroke-linejoin="round" />
        </svg>

    </div>

    <!-- Subway -->
    <div v-else-if="type == 'subway'" class="">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-5 text-gray-500">
            <rect x="3" y="3" width="18" height="14" rx="2" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M3 10h18" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M7 17v3M17 17v3" stroke-linecap="round" stroke-linejoin="round" />
            <circle cx="7" cy="14" r="1" fill="currentColor" />
            <circle cx="17" cy="14" r="1" fill="currentColor" />
            <path d="M12 3v7" stroke-linecap="round" stroke-linejoin="round" />
        </svg>

    </div>

    <!-- Tram -->
    <div v-else-if="type == 'tram'" class="">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-5 text-teal-500">
            <rect x="4" y="3" width="16" height="16" rx="2" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M4 8h16M4 13h16" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M8 19v2M16 19v2" stroke-linecap="round" stroke-linejoin="round" />
            <circle cx="8" cy="16" r="1" fill="currentColor" />
            <circle cx="16" cy="16" r="1" fill="currentColor" />
            <path d="M12 3v5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>

    </div>

    <!-- Helicopter -->
    <div v-else-if="type == 'helicopter'" class="">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-5 text-pink-500">
            <!-- Main rotor -->
            <path d="M4 7h16" stroke-linecap="round" stroke-linejoin="round" />
            <!-- Helicopter body -->
            <path d="M9 7v4c0 1.1.9 2 2 2h2c1.1 0 2-.9 2-2V7" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M8 11l-1 2v2h10v-2l-1-2" stroke-linecap="round" stroke-linejoin="round" />
            <!-- Tail boom -->
            <path d="M12 13v5" stroke-linecap="round" stroke-linejoin="round" />
            <!-- Tail rotor -->
            <path d="M12 18l-2 1.5M12 18l2 1.5" stroke-linecap="round" stroke-linejoin="round" />
            <!-- Skids/landing gear -->
            <path d="M7 15c0 1 .5 2 1 2M17 15c0 1-.5 2-1 2" stroke-linecap="round" stroke-linejoin="round" />
            <!-- Cockpit window -->
            <path d="M10 9h4" stroke-linecap="round" stroke-linejoin="round" />
            <!-- Spinning rotor animation -->
            <path d="M12 7L8 5M12 7l4-2M12 7l-3-3M12 7l3-3" stroke-linecap="round" stroke-linejoin="round" />
        </svg>

    </div>
    <div v-else class="">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-5 text-blue-500">
            <path
                d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z"
                stroke-linecap="round" stroke-linejoin="round" />
        </svg>

    </div>

</template>

<script setup>
const props = defineProps({
    type: String
});
</script>