<template>
    <div v-if="isOpen" class=" fixed inset-0 z-50 flex items-center justify-center backdrop-blur-xs">
        <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm w-full max-w-2xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ title }}
                </h3>
                <button @click="closeModal" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <slot/>

            
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';

// Props
const props = defineProps({
    isOpen: {
        type: Boolean,
        required: true,
    },
    title: {
        type: String,
        default: 'Modal Title',
    }
});

// Emits
const emit = defineEmits(['close']);

// Close modal
const closeModal = () => {
    emit('close');
};


</script>
