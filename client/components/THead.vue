<template>
    <th class="px-4 py-4  text-left cursor-pointer hover:bg-gray-50">
        <div class="flex items-center">
            <span>{{label}}</span>
            <UpDownIcon v-if="!isSorted" />
            <UpIcon v-else-if="sortDirection === 'asc'" />
            <DownIcon v-else />
        </div>
    </th>
</template>

<script setup>

const props = defineProps({
    label: String,
    isSorted: Boolean,
    sortDirection: String
})

</script>