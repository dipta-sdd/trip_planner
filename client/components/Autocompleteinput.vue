<template>
    <div class="relative w-full">
        <!-- <label :for="id" class="block text-sm font-medium mb-1">{{ label }}</label> -->
        <div class="relative">
            <input :id="id" v-model="inputValue" type="text"
                class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-primary"
                :placeholder="placeholder" @input="onInput" @keydown.down.prevent="onArrowDown"
                @keydown.up.prevent="onArrowUp" @keydown.enter.prevent="onEnter" @keydown.esc="onEscape" @blur="onBlur"
                :aria-expanded="showSuggestions" :aria-activedescendant="activeDescendant" aria-autocomplete="list"
                :aria-controls="`${id}-suggestions`" role="combobox" autocomplete="off" />
            <button v-if="inputValue && clearable" @click="clearInput" type="button"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                aria-label="Clear input">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-x">
                    <path d="M18 6 6 18" />
                    <path d="m6 6 12 12" />
                </svg>
            </button>
        </div>

        <div v-if="showSuggestions && filteredSuggestions.length > 0" :id="`${id}-suggestions`"
            class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-y-auto"
            role="listbox">
            <ul>
                <li v-for="(user, index) in filteredSuggestions" :key="index" :id="`${id}-suggestion-${index}`"
                    class="px-4 py-2 cursor-pointer hover:bg-gray-100" :class="{ 'bg-gray-100': index === activeIndex }"
                    @mousedown.prevent="selectSuggestion(user)" @mouseover="activeIndex = index" role="option"
                    :aria-selected="index === activeIndex">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-2">
                            {{ user.name.charAt(0) }}
                        </div>
                        <div>
                            <div class="font-medium">{{ user.name }}</div>
                            <div class="text-xs text-gray-500">{{ user.email }}</div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <div v-if="showSuggestions && filteredSuggestions.length === 0 && noResultsText"
            class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-md shadow-lg p-4 text-center text-gray-500">
            {{ noResultsText }}
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';

const props = defineProps({
    modelValue: {
        type: [String, Number, Object],
        default: ''
    },
    suggestions: {
        type: Array,
        default: () => []
    },
    label: {
        type: String,
        default: 'Search'
    },
    placeholder: {
        type: String,
        default: 'Type to search...'
    },
    displayProperty: {
        type: String,
        default: 'name'
    },
    minChars: {
        type: Number,
        default: 1
    },
    clearable: {
        type: Boolean,
        default: true
    },
    noResultsText: {
        type: String,
        default: 'No results found'
    },
    id: {
        type: String,
        default: () => `autocomplete-${Math.random().toString(36).substring(2, 9)}`
    }
});

const emit = defineEmits(['update:modelValue', 'select', 'input']);

const inputValue = ref('');
const showSuggestions = ref(false);
const activeIndex = ref(-1);

// Initialize input value from prop
onMounted(() => {
    if (props.modelValue) {
        if (typeof props.modelValue === 'object' && props.modelValue !== null) {
            inputValue.value = props.modelValue[props.displayProperty] || '';
        } else {
            inputValue.value = String(props.modelValue);
        }
    }
});

watch(() => props.modelValue, (newValue) => {
    if (newValue === null || newValue === undefined) {
        inputValue.value = '';
        return;
    }

    if (typeof newValue === 'object') {
        inputValue.value = newValue[props.displayProperty] || '';
    } else {
        inputValue.value = String(newValue);
    }
});

const filteredSuggestions = computed(() => {
    if (!inputValue.value || inputValue.value.length < props.minChars) {
        return [];
    }

    return props.suggestions.filter(suggestion => {
        // const value = typeof suggestion === 'object'
        //     ? suggestion[props.displayProperty]
        //     : String(suggestion);
        const ff = suggestion?.name.toLowerCase().includes(inputValue.value.toLowerCase()) || suggestion?.email.toLowerCase().includes(inputValue.value.toLowerCase());
        // console.log(suggestion);
        return ff;
    });
});

const activeDescendant = computed(() => {
    return activeIndex.value >= 0 ? `${props.id}-suggestion-${activeIndex.value}` : '';
});

function onInput() {
    showSuggestions.value = true;
    activeIndex.value = -1;
    emit('input', inputValue.value);
    emit('update:modelValue', inputValue.value);
}

function onArrowDown() {
    if (!showSuggestions.value) {
        showSuggestions.value = true;
        return;
    }

    if (filteredSuggestions.value.length > 0) {
        activeIndex.value = (activeIndex.value + 1) % filteredSuggestions.value.length;
    }
}

function onArrowUp() {
    if (!showSuggestions.value) {
        showSuggestions.value = true;
        return;
    }

    if (filteredSuggestions.value.length > 0) {
        activeIndex.value = activeIndex.value <= 0
            ? filteredSuggestions.value.length - 1
            : activeIndex.value - 1;
    }
}

function onEnter() {
    if (showSuggestions.value && activeIndex.value >= 0) {
        selectSuggestion(filteredSuggestions.value[activeIndex.value]);
    }
}

function onEscape() {
    showSuggestions.value = false;
    activeIndex.value = -1;
}

function onBlur() {
    // Delay hiding suggestions to allow for mousedown on suggestion
    setTimeout(() => {
        showSuggestions.value = false;
        activeIndex.value = -1;
    }, 150);
}

function selectSuggestion(suggestion) {
    if (typeof suggestion === 'object') {
        inputValue.value = suggestion[props.displayProperty];
        emit('update:modelValue', suggestion);
    } else {
        inputValue.value = String(suggestion);
        emit('update:modelValue', suggestion);
    }

    emit('select', suggestion);
    showSuggestions.value = false;
    activeIndex.value = -1;
    clearInput();
}

function clearInput() {
    inputValue.value = '';
    emit('update:modelValue', '');
    showSuggestions.value = false;
    activeIndex.value = -1;
}
</script>