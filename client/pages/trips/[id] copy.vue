<template>
  <TripSkeleton v-if="loading" />
  <div v-else class="bg-gray-100 p-8 capitalize">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">
      <!-- Trip Title -->
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-3xl font-bold text-gray-800">{{ trip?.title }}</h1>
        <!-- Edit Button (Admin Only) -->
        <button v-if="isAdmin" class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600">
          Edit Trip
        </button>
        <span v-else-if="trip.participant_status" class="text-sm px-3 py-1.5 rounded-md"
          :class="trip.participant_status === 'pending' ? 'bg-blue-200 text-blue-950' : trip.participant_status === 'accepted' ? 'bg-green-200 text-green-950' : 'bg-red-200 text-red-950'">
          {{ trip.participant_status === 'accepted' ? 'Joined' : trip.participant_status }}
        </span>
        <button v-else class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600" @click="joinTrip">
          Join
        </button>
      </div>

      <!-- Trip Description -->
      <div class="flex justify-between items-center mb-6">
        <p class="text-gray-600">
          {{ trip?.description }}
        </p>

      </div>

      <!-- Trip Details Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-8">
        <!-- Start Date -->
        <div>
          <h3 class="text-sm font-semibold text-gray-500">Start Date</h3>
          <p class="text-gray-800">{{ new Date(trip?.start_date).toLocaleDateString('en-US', {
            year: 'numeric', month:
              'long', day: 'numeric'
          }) }}</p>
        </div>

        <!-- End Date -->
        <div>
          <h3 class="text-sm font-semibold text-gray-500">End Date</h3>
          <p class="text-gray-800">{{ new Date(trip?.end_date).toLocaleDateString('en-US', {
            year: 'numeric', month:
              'long', day: 'numeric'
          }) }}</p>
        </div>

        <!-- Status -->
        <div>
          <h3 class="text-sm font-semibold text-gray-500">Status</h3>
          <p class="text-gray-800">{{ trip?.status }}</p>
        </div>

        <!-- Budget -->
        <div>
          <h3 class="text-sm font-semibold text-gray-500">Budget</h3>
          <p class="text-gray-800">$ {{ trip?.budget }}</p>
        </div>

        <!-- Owner -->
        <!-- <div>
          <h3 class="text-sm font-semibold text-gray-500">Owner</h3>
          <p class="text-gray-800">John Doe</p>
        </div> -->
      </div>

      <Participants :isAdmin="isAdmin ? true : false" :participants="trip?.participants" :tripId="trip?.id" />
      <Sponsors :isAdmin="isAdmin ? true : false" :sponsors="trip?.sponsors" :tripId="trip?.id"
        @addSponsor="(data) => trip.sponsors.push(data)"
        @editSponsor="(data) => trip.sponsors = trip.sponsors.map(sponsor => sponsor.id === data.id ? data : sponsor)"
        @deleteSponsor="(id) => trip.sponsors = trip.sponsors.filter(sponsor => sponsor.id !== id)" />



      <!--  Itinerary and Activities Section -->
      <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-2xl font-bold text-gray-800">Itinerary & Activities</h2>

          <button v-if="isAdmin" @click="isModalOpen = true"
            class="bg-green-500 text-white p-2 rounded-lg hover:bg-green-600">
            Add Activity
          </button>
        </div>
        <div class="space-y-6">
          <div class="bg-gray-50 p-3 rounded-lg">
            <div v-if="dataComputed" v-for="date in dataComputed || []" :key="date">
              <h3 class="text-xl font-semibold text-gray-800 my-2">{{ new
                Date(date.date).toLocaleDateString('en-US', {
                  year: 'numeric', month: 'short', day:
                    'numeric'
                }) }}</h3>
              <div class="space-y-4">
                <div v-for="activity in date.activities || []" :key="activity.id"
                  class="bg-white p-4 rounded-lg shadow-sm capitalize relative">
                  <button v-if="isAdmin" @click="editActivity(activity)"
                    class="absolute bottom-2 right-2 p-1 text-gray-500 hover:text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                  </button>
                  <div class="flex justify-between items-center mb-2">
                    <h4 class="text-md font-semibold text-gray-800">{{ activity.title }}</h4>
                    <span :class="{
                      'bg-red-100 text-red-800': activity.type === 'accommodation',
                      'bg-blue-100 text-blue-800': activity.type === 'food',
                      'bg-yellow-100 text-yellow-800': activity.type === 'transportation',
                      'bg-green-100 text-green-800': activity.type === 'activity',
                    }" class="px-3 py-1 text-sm font-semibold rounded-full">{{ activity.type }}</span>
                  </div>
                  <div class="text-sm text-gray-600">
                    <p>{{ activity.start_time }} - {{ activity.end_time }}</p>
                    <p>{{ activity.description }}</p>
                  </div>
                </div>
              </div>
            </div>
            <div v-else>
              <p class="text-sm px-4 py-4 text-center text-gray-500">No activities yet.</p>
            </div>
          </div>
        </div>
      </div>



      <!-- Expense Summary Section -->
      <div class="mb-8">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-2xl font-bold text-gray-800">Expense Summary</h2>
          <!-- Add Expense Button (Admin Only) -->
          <button v-if="isAdmin" class="bg-green-500 text-white p-2 rounded-lg hover:bg-green-600">
            Add Expense
          </button>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <h3 class="text-sm font-semibold text-gray-500">Total Budget</h3>
              <p class="text-gray-800">$1,500</p>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-500">Total Spent</h3>
              <p class="text-gray-800">$920</p>
            </div>
            <div>
              <h3 class="text-sm font-semibold text-gray-500">Remaining Budget</h3>
              <p class="text-gray-800">$580</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Detailed Expenses Section (Admin Only) -->
      <div v-if="isAdmin" id="detailed-expenses">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-2xl font-bold text-gray-800">Detailed Expenses</h2>
          <button @click="expenseModal = true" class="mb-4 bg-blue-600 text-white px-4 py-2 rounded-md">
            Add Expense
          </button>
          <AddExpenseModal :trip-id="trip.id" v-if="expenseModal" @submit="handleExpenseSubmit"
            @close="expenseModal = false" />
        </div>

        <ExpenseTable :expenses="trip?.expenses" @edit="handleEdit" @delete="handleDelete" />
      </div>

    </div>
    <!-- add activity  modal -->
    <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-xs">
      <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm w-full max-w-2xl">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-600">
          <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
            {{ activity.id ? 'Edit' : 'Add' }} Activity
          </h3>
          <button @click="cancelActivity" class="text-gray-400 hover:text-gray-900 dark:hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Modal Body -->
        <div class="p-4 space-y-4">
          <!-- Title -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 invalid">Title
              <span class="text-red-500">*</span> </label>
            <input v-model="activity.title" type="text"
              class="mt-1 block w-full p-2 border border-gray-300 rounded-lg dark:bg-gray-600 dark:border-gray-500" />

          </div>

          <!-- Description -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description <span
                class="text-red-500">*</span> </label>
            <textarea v-model="activity.description"
              class="mt-1 block w-full p-2 border border-gray-300 rounded-lg dark:bg-gray-600 dark:border-gray-500"></textarea>
          </div>

          <!-- Location -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location <span
                class="text-red-500">*</span> </label>
            <input v-model="activity.location" type="text"
              class="mt-1 block w-full p-2 border border-gray-300 rounded-lg dark:bg-gray-600 dark:border-gray-500" />
          </div>

          <!-- Date -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date <span
                class="text-red-500">*</span> </label>
            <input v-model="activity.date" type="date"
              class="mt-1 block w-full p-2 border border-gray-300 rounded-lg dark:bg-gray-600 dark:border-gray-500" />
          </div>

          <!-- Time -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Start Time -->

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Time <span
                  class="text-red-500">*</span> </label>
              <input v-model="activity.start_time" type="time"
                class="mt-1 block w-full p-2 border border-gray-300 rounded-lg dark:bg-gray-600 dark:border-gray-500" />
            </div>

            <!-- End Time -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Time <span
                  class="text-red-500">*</span> </label>
              <input v-model="activity.end_time" type="time"
                class="mt-1 block w-full p-2 border border-gray-300 rounded-lg dark:bg-gray-600 dark:border-gray-500" />
            </div>
          </div>


          <!-- Type -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type <span
                class="text-red-500">*</span> </label>
            <select v-model="activity.type"
              class="mt-1 block w-full p-2 border border-gray-300 rounded-lg dark:bg-gray-600 dark:border-gray-500">
              <option value="accommodation">Accommodation</option>
              <option value="food">Food</option>
              <option value="transport">Transport</option>
              <option value="other">Other</option>
            </select>
          </div>

          <!-- Cost -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cost </label>
            <input v-model="activity.cost" type="number"
              class="mt-1 block w-full p-2 border border-gray-300 rounded-lg dark:bg-gray-600 dark:border-gray-500" />
          </div>

        </div>
        <p class="text-red-500" v-if="activityError">{{ activityError }}</p>

        <!-- Modal Footer -->
        <div class="flex items-center p-4 border-t border-gray-200 dark:border-gray-600">
          <button @click.prevent="submitActivity" class="text-white bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg">
            {{ activity.id ? 'Update' : 'Add' }}
          </button>

          <button v-if="activity.id" @click.prevent="deleteActivity(activity)"
            class="text-white bg-red-700 hover:bg-red-800 px-4 py-2 rounded-lg mx-2">
            Delete
          </button>

          <button @click.prevent="cancelActivity"
            class="ml-2 px-4 py-2 text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 rounded-lg dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
            Cancel
          </button>
        </div>
      </div>
    </div>
    <!-- add activity  modal -->



  </div>
</template>

<script setup>

// const isAdmin = ref(false);

const route = useRoute()
const trip = ref({})
const activities = ref([])
const isModalOpen = ref(false);
const expenseModal = ref(false);
const activityError = ref(null);
const loading = ref(true);
const activity = ref({
  title: '',
  description: '',
  location: '',
  date: '',
  start_time: '',
  end_time: '',
  type: '',
  cost: 0,
});
const editActivity = (act) => {
  activity.value = act;
  isModalOpen.value = true;
}

const deleteActivity = async (act) => {
  try {
    const data = await $fetch(`https://trip-planer-api.sankarsan.xyz/api/trips/${route.params.id}/activity/${activity.value.id}`, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${useCookie('token').value}`
      }
    });
    console.log(data);
    trip.value.activities = trip.value.activities.filter(act => act.id !== activity.value.id);
    isModalOpen.value = false;
    clearActivity();
  } catch (error) {
    console.log(error);
    activityError.value = 'Something went wrong. Try Again';
  }
}
const submitActivity = async () => {
  if (!activity.value.title || !activity.value.description || !activity.value.location || !activity.value.date || !activity.value.start_time || !activity.value.end_time || !activity.value.type) {
    activityError.value = 'Please fill in all the fields';
    return;
  }
  try {
    if (activity.value.id) {
      const data = await $fetch(`https://trip-planer-api.sankarsan.xyz/api/trips/${route.params.id}/activity/${activity.value.id}`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${useCookie('token').value}`
        },
        body: JSON.stringify(activity.value)
      });
      console.log(data);
      if (data.activity) {
        console.log(data.activity);
        trip.value.activities = trip.value.activities.map(act => {
          if (act.id === activity.value.id) {
            return data.activity;
          }
          return act;
        });
        isModalOpen.value = false;
        clearActivity();
      } else {
        activityError.value = 'Something went wrong. Try Again';
      }

    } else {
      const data = await $fetch(`https://trip-planer-api.sankarsan.xyz/api/trips/${route.params.id}/activity`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${useCookie('token').value}`
        },
        body: JSON.stringify(activity.value)
      });
      // const data = await res.json();
      if (data.activity) {
        trip.value.activities.push(data.activity);
        isModalOpen.value = false;
        clearActivity();
        activityError.value = null;
      } else {
        activityError.value = 'Something went wrong. Try Again';
      }
    }
  } catch (error) {
    console.log(error);
    activityError.value = 'Something went wrong. Try Again';
  }
}

const cancelActivity = () => {
  clearActivity();
  isModalOpen.value = false;
}
const clearActivity = () => {
  activity.value = {
    id: '',
    title: '',
    description: '',
    location: '',
    date: '',
    start_time: '',
    end_time: '',
    type: '',
    cost: 0,
  };
  activityError.value = null;
}
onMounted(async () => {
  try {
    const data = await $fetch(`https://trip-planer-api.sankarsan.xyz/api/trips/${route.params.id}`,
      {
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${useCookie('token').value}`
        }
      }
    )
    trip.value = data;
    activities.value = data?.activities;
    loading.value = false;

  } catch (error) {
    return navigateTo('/trips');


  }
})

const dataComputed = computed(() => {
  const groupedActivities = trip.value?.activities?.reduce((acc, activity) => {
    const date = activity.date;
    if (!acc[date]) {
      acc[date] = {
        date,
        activities: []
      };
    }
    acc[date].activities.push(activity);
    return acc;
  }, {});
  return groupedActivities;
});
const isAdmin = computed(() => {
  return trip.value?.can_edit || false;
})

const addSponsor = (data) => {
  console.log(data);
  trip.value.sponsors.push(data);
}

const handleExpenseSubmit = (data) => {
  trip.value.expenses = data;
  expenseModal.value = false;
}

const joinTrip = async () => {
  if (!useUserStore().user) {
    return navigateTo('/login');
  }

  try {
    const data = await $fetch(`https://trip-planer-api.sankarsan.xyz/api/trips/${trip.value.id}/join`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${useCookie('token').value}`
      }
    });
    trip.value.participant_status = 'pending';

  } catch (error) {
    console.error('Join request failed:', error);
  } finally {
    trip.isJoining = false;
  }
};
</script>
