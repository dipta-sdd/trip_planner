<template>
    <div>
        <label for="name" class="block text-sm font-medium leading-6 text-gray-900 dark:text-white"> {{ label }}
            <span v-if="isRequired" class="text-red-500">*</span> </label>
        <div class="mt-2">
            <input v-model="value" :type="type"
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
        </div>
        <small class="text-red-500" v-if="error">{{ error }}</small>
    </div>
</template>

<script setup>
defineProps({
    value: String,
    type: String,
    error: String,
    label: String,
    isRequired: Boolean
})
</script>