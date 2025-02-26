<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6 flex flex-col justify-center items-center sm:py-12">
    <div class="relative py-3 w-full max-w-xl">
      <div class="hidden md:block absolute inset-0 bg-gradient-to-r from-indigo-500 to-purple-600 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl"></div>
      <div class="relative px-4 py-10 bg-white dark:bg-gray-800 shadow-lg sm:rounded-3xl">
        <div class="mx-auto">
          <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Create New Trip</h1>
          </div>
          <div class="divide-y divide-gray-200 dark:divide-gray-700">
            <form class="space-y-6 pt-6">
              <div>
                <label for="title" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Title</label>
                <div class="mt-2">
                  <input
                    id="title"
                    name="title"
                    type="text"
                    required
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100  ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"
                  />
                </div>
              </div>

              <div>
                <label for="description" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Description</label>
                <div class="mt-2">
                  <textarea
                    id="description"
                    name="description"
                    required
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"
                  ></textarea>
                </div>
              </div>

              <div>
                <label for="start_date" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Start Date</label>
                <div class="mt-2">
                  <input
                    id="start_date"
                    name="start_date"
                    type="date"
                    required
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"
                  />
                </div>
              </div>

              <div>
                <label for="end_date" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">End Date</label>
                <div class="mt-2">
                  <input
                    id="end_date"
                    name="end_date"
                    type="date"
                    required
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"
                  />
                </div>
              </div>

              <div>
                <label for="budget" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Budget</label>
                <div class="mt-2">
                  <input
                    id="budget"
                    name="budget"
                    type="number"
                    required
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"
                  />
                </div>
              </div>

              <div>
                <label for="status" class="block text-sm font-medium leading-6 text-gray-900 dark:text-gray-100">Status</label>
                <div class="mt-2">
                  <select
                    id="status"
                    name="status"
                    required
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-gray-100 ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"
                  >
                    <option value="planned">Planned</option>
                    <option value="in-progress">In Progress</option>
                    <option value="completed">Completed</option>
                  </select>
                </div>
              </div>

              <div class="flex items-center">
                <input
                  id="is_public"
                  name="is_public"
                  type="checkbox"
                  class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                />
                <label for="is_public" class="ml-2 block text-sm text-gray-900 dark:text-gray-100">Public</label>
              </div>

              <div>
                <button
                  type="submit"
                  class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
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