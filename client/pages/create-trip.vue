<script setup>
import { ref } from 'vue'

const title = ref('')
const description = ref('')
const startDate = ref('')
const endDate = ref('')
const budget = ref('')
const isPublic = ref(true)

const formError = ref('')
const userStore = useUserStore();
if(!userStore.user){
  navigateTo('/login')
}

const submitForm = async () => {
  formError.value = ''

  // Basic form validation
  if (!title.value || !description.value || !startDate.value || !endDate.value || !budget.value) {
    formError.value = 'Please fill in all  fields.'
    return
  }

  if (new Date(endDate.value) <= new Date(startDate.value)) {
    formError.value = 'End date must be after start date.'
    return
  }

  // Form submission logic would go here
  console.log('Form submitted:', {
    title: title.value,
    description: description.value,
    startDate: startDate.value,
    endDate: endDate.value,
    budget: parseFloat(budget.value),
    isPublic: isPublic.value
  })
  try {
    const response = await fetch('https://trip-planer-api.sankarsan.xyz/api/trips', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${useCookie('token').value}`
      },
      body: JSON.stringify({
        title: title.value,
        description: description.value,
        start_date: startDate.value,
        end_date: endDate.value,
        budget: parseFloat(budget.value),
        is_public: isPublic.value
      }),
    });
    const data = await response.json();
    // console.log(data);
    if(data?.id){
      navigateTo(`/trips/${data.id}`);
    }
  } catch (error) {
    console.error('Error creating trip:', error);
  }

  // Reset form after submission
  // title.value = ''
  // description.value = ''
  // startDate.value = ''
  // endDate.value = ''
  // budget.value = ''
  // isPublic.value = false
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6 flex flex-col justify-center items-center sm:py-12">
    <div class="relative py-3 w-full max-w-xl">
      <div
        class="hidden md:block absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-600 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl">
      </div>
      <div class="relative px-4 py-10 bg-white dark:bg-gray-800 shadow-lg sm:rounded-3xl">
        <div class="mx-auto">
          <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Create New Trip</h1>
          </div>
          <div class="divide-y divide-gray-200 dark:divide-gray-700">
            <form @submit.prevent="submitForm" class="space-y-6 pt-6">
              <div>
                <label for="title"
                  class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Title <span class="text-red-500">*</span> </label>
                <div class="mt-2">
                  <input id="title" v-model="title" type="text" 
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
                </div>
              </div>

              <div>
                <label for="description"
                  class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Description <span class="text-red-500">*</span> </label>
                <div class="mt-2">
                  <textarea id="description" v-model="description" 
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"></textarea>
                </div>
              </div>

              <div>
                <label for="start_date"
                  class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Start Date <span class="text-red-500">*</span> </label>
                <div class="mt-2">
                  <input id="start_date" v-model="startDate" type="date" :min="new Date().toISOString().split('T')[0]" 
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
                </div>
              </div>

              <div>
                <label for="end_date" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">End
                  Date <span class="text-red-500">*</span> </label>
                <div class="mt-2">
                  <input id="end_date" v-model="endDate" type="date" :min="startDate ? startDate : new Date().toISOString().split('T')[0]" 
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
                </div>
              </div>

              <div>
                <label for="budget"
                  class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Budget <span class="text-red-500">*</span> </label>
                <div class="mt-2">
                  <input id="budget" v-model="budget" type="number" step="0.01" 
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
                </div>
              </div>

              

              <div class="flex items-center">
                <input id="is_public" v-model="isPublic" type="checkbox"
                  class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" />
                <label for="is_public" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">Public <span class="text-red-500">*</span> </label>
              </div>

              <div v-if="formError" class="text-red-600 text-sm">{{ formError }}</div>

              <div>
                <button type="submit"
                  class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white hover:bg-indigo-500 focus-visible:outline  focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                  Create Trip
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>