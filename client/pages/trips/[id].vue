<template>
  <TripSkeleton v-if="loading" />
  <div v-else class="bg-gray-100 p-4 capitalize">
    <div class="max-w-7xl mx-auto bg-white rounded-lg shadow-lg p-4">
      <!-- Trip Title -->
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-3xl font-bold text-gray-800">{{ trip?.title }}</h1>
        <button v-if="trip?.is_participant && trip?.is_creator"
          class="bg-green-500 text-white p-2 rounded-lg flex flex-nowrap gap-1 items-center cursor-pointer"
          @click="isModalOpen = true">
          <PenIcon /> Edit
        </button>
        <button v-else-if="trip?.status === 'planning' && trip?.is_participant"
          class="bg-green-500 text-white p-2 rounded-lg cursor-pointer" @click="leaveTrip">
          Leave
        </button>
        <span v-else-if="trip?.is_participant" class="bg-green-500 text-white p-2 rounded-lg">
          Participant
        </span>
        <button v-else-if="trip?.is_invited && trip?.status === 'planning'"
          class="bg-green-500 text-white p-2 rounded-lg cursor-pointer" @click="acceptInvitation">
          Accept Invitation
        </button>
        <button v-else-if="trip?.status === 'planning' && trip?.participant_status === 'pending'"
          class="bg-green-500 text-white p-2 rounded-lg cursor-pointer" @click="cancelRequest">
          Cancel Request
        </button>
        <span v-else-if="trip?.participant_status === 'declined'" class="bg-red-500 text-white p-2 rounded-lg">
          Declined
        </span>
        <button
          v-else-if="useUserStore()?.user && !trip?.is_participant && !trip?.is_invited && trip?.status === 'planning'"
          class="bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 cursor-pointer" @click="joinTrip">
          Join Trip
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
          <p class="text-gray-800 inline-flex items-center gap-3">$ {{ trip?.budget }}
            <span v-if="totalExpenses > 0"
              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-900">
              ALready Used
              {{ totalExpenses }}$
            </span>
          </p>
        </div>
      </div>


      <!-- Destinations -->
      <div class="mb-2">
        <div class="flex justify-between items-center mb-2">
          <h2 class="text-xl font-semibold text-gray-700">Destinations</h2>
          <button v-if="isAdmin" class=" cursor-pointer bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-lg"
            @click="desModal = true">Add
            Destination</button>
          <DestinationManager v-if="desModal" :tripId="trip?.id" @close="clearDes"
            @add="(data) => { trip.destinations.push(data); clearDes(); }"
            @edit="(data) => { trip.destinations = trip.destinations.map(d => d.id === data.id ? data : d); clearDes(); }"
            @delete="(id) => { trip.destinations = trip.destinations.filter(d => d.id !== id); clearDes(); }"
            :des="des" />
          <ActivityManager v-if="actModal" :destinations="trip?.destinations || []" :tripId="trip?.id" @close="clearAct"
            :act="act" @add="(des) => { trip.destinations = [...des]; clearAct(); }" />
          <AccommodationManager v-if="accModal" :destinations="trip?.destinations || []" :tripId="trip?.id"
            @close="clearAcc" :acc="acc" @add="(des) => { trip.destinations = [...des]; clearAcc(); }" />
          <TransportationManager v-if="transModal" :destinations="trip?.destinations || []" :tripId="trip?.id"
            @close="clearTrans" :trans="trans" @add="(des) => { trip.destinations = [...des]; clearTrans(); }" />
        </div>
        <div class="space-y-6">
          <!-- Destination -->
          <div v-if="trip?.destinations?.length" v-for="destination in trip?.destinations || []"
            class=" p-4 rounded-lg relative border border-gray-300  mb-4">
            <h3 class="text-lg font-medium text-gray-700 mb-2">{{ destination?.name }}</h3>
            <p class="text-gray-600"><span class="font-medium">Arrival:</span> {{ formatDate(destination?.arrival_date)
              }} </p>
            <p class="text-gray-600"><span class="font-medium">Departure:</span> {{
              formatDate(destination?.arrival_date) }}</p>
            <p v-if="destination?.notes" class="text-gray-600"><span class="font-medium">Notes:</span> {{
              destination?.notes }}
            </p>
            <button v-if="isAdmin" @click="() => { des = destination; desModal = true }"
              class="cursor-pointer absolute top-2 right-2 p-1 text-gray-500 hover:text-blue-600">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
              </svg>
            </button>

            <!-- Activities -->
            <div class="mt-4 ">
              <h4 class="text-md font-semibold text-gray-700 mb-3 relative inline-block">Activities
                <button v-if="isAdmin" @click="() => { clearAct(); actModal = true }"
                  class="cursor-pointer absolute top-0 left-full ms-3  text-gray-500 hover:text-blue-600 border border-gray-500 hover:border-blue-600 rounded-full">
                  <PlusIcon />
                </button>

              </h4>
              <div class="space-y-2 my-grid-600 gap-4">
                <div v-if="destination?.activities?.length" v-for="activity in destination.activities || []"
                  :key="activity.id" class="bg-blue-50 p-3 rounded-lg shadow-sm relative">
                  <!-- edit button for admin -->
                  <button v-if="isAdmin" @click="() => { act = activity; actModal = true }"
                    class="cursor-pointer absolute top-2 right-2 p-1 text-gray-500 hover:text-blue-600">
                    <PenIcon />
                  </button>


                  <p class="text-gray-600 flex justify-between items-center">
                    <span class="font-medium text-lg">{{ activity.name }}</span>
                  </p>
                  <p class="text-gray-600 flex justify-between items-center my-1">
                    <span class="flex flex-nowrap gap-1 items-center">
                      <CalenderIcon />
                      {{ formatDate(activity.start_time) }} &nbsp;
                      <ClockIcon />
                      {{ formetTime(activity.start_time) }} - {{ formetTime(activity.end_time) }}
                    </span>
                    <span class="flex flex-nowrap gap-1 items-center  bg-blue-200 rounded-4xl px-2">
                      <!-- Sightseeing Icon -->

                      <svg v-if="activity.type === 'Sightseeing'" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" class="size-5">
                        <circle cx="12" cy="12" r="3.5" stroke="currentColor" stroke-width="1.5" />
                        <path d="M12 4V2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M12 22V20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M20 12H22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M2 12H4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M17.6569 6.34315L19.0711 4.92893" stroke="currentColor" stroke-width="1.5"
                          stroke-linecap="round" />
                        <path d="M4.92896 19.0711L6.34317 17.6569" stroke="currentColor" stroke-width="1.5"
                          stroke-linecap="round" />
                        <path d="M17.6569 17.6569L19.0711 19.0711" stroke="currentColor" stroke-width="1.5"
                          stroke-linecap="round" />
                        <path d="M4.92896 4.92893L6.34317 6.34315" stroke="currentColor" stroke-width="1.5"
                          stroke-linecap="round" />
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.5" />
                      </svg>

                      <!-- Dining Icon -->

                      <svg v-else-if="activity.type === 'Dining'" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" class="size-5">
                        <path d="M8 3V21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M18 3V21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M8 7H6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M8 11H6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M8 15H6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M18 7H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M18 11H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M18 15H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M12 3L12 21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M12 3C12 3 12 7 15 7C18 7 18 3 18 3" stroke="currentColor" stroke-width="1.5"
                          stroke-linecap="round" stroke-linejoin="round" />
                      </svg>

                      <!-- Shopping Icon -->

                      <svg v-else-if="activity.type === 'Shopping'" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" class="size-5">
                        <path d="M3 6H21L19 16H5L3 6Z" stroke="currentColor" stroke-width="1.5"
                          stroke-linejoin="round" />
                        <path
                          d="M8 21C8.55228 21 9 20.5523 9 20C9 19.4477 8.55228 19 8 19C7.44772 19 7 19.4477 7 20C7 20.5523 7.44772 21 8 21Z"
                          stroke="currentColor" stroke-width="1.5" />
                        <path
                          d="M16 21C16.5523 21 17 20.5523 17 20C17 19.4477 16.5523 19 16 19C15.4477 19 15 19.4477 15 20C15 20.5523 15.4477 21 16 21Z"
                          stroke="currentColor" stroke-width="1.5" />
                        <path d="M9 3L7 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M15 3L17 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                      </svg>

                      <!-- Entertainment Icon -->

                      <svg v-else-if="activity.type === 'Entertainment'" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" class="size-5">
                        <path
                          d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z"
                          stroke="currentColor" stroke-width="1.5" />
                        <path d="M10.5 8.5L15.5 12L10.5 15.5V8.5Z" stroke="currentColor" stroke-width="1.5"
                          stroke-linejoin="round" />
                      </svg>

                      <!-- Sports Icon -->

                      <svg v-else-if="activity.type === 'Sports'" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" class="size-5">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5" />
                        <path d="M12 2C14.3206 4.50886 15.7249 7.77972 16 11.2C16.2751 14.6203 15.4267 18.0593 13.6 21"
                          stroke="currentColor" stroke-width="1.5" />
                        <path d="M12 2C9.6794 4.50886 8.27508 7.77972 8 11.2C7.72492 14.6203 8.57326 18.0593 10.4 21"
                          stroke="currentColor" stroke-width="1.5" />
                        <path d="M2.5 9H21.5" stroke="currentColor" stroke-width="1.5" />
                        <path d="M2.5 15H21.5" stroke="currentColor" stroke-width="1.5" />
                      </svg>

                      <!-- Relaxation Icon -->

                      <svg v-else-if="activity.type === 'Relaxation'" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" class="size-5">
                        <path d="M3 18H21" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M5 21L5 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M19 21L19 18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path
                          d="M3 11C3 9.11438 3 8.17157 3.58579 7.58579C4.17157 7 5.11438 7 7 7H17C18.8856 7 19.8284 7 20.4142 7.58579C21 8.17157 21 9.11438 21 11V12C21 13.8856 21 14.8284 20.4142 15.4142C19.8284 16 18.8856 16 17 16H7C5.11438 16 4.17157 16 3.58579 15.4142C3 14.8284 3 13.8856 3 12V11Z"
                          stroke="currentColor" stroke-width="1.5" />
                        <path d="M10.5 7L9.5 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M13.5 7L14.5 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                      </svg>

                      <!-- Cultural Icon -->

                      <svg v-else-if="activity.type === 'Cultural'" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" class="size-5">
                        <path d="M2 22H22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M4 22V9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M20 22V9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M12 22V9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M16 22V9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M8 22V9" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path d="M22 9H2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                        <path
                          d="M12 7L20.4 3.6C20.7418 3.42895 21.1158 3.38281 21.4824 3.46914C21.849 3.55547 22.1755 3.76756 22.4 4.07C22.6245 4.37244 22.7329 4.74627 22.7026 5.12338C22.6722 5.50049 22.5052 5.85127 22.23 6.11L12 7Z"
                          stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                          d="M12 7L3.6 3.6C3.25817 3.42895 2.88421 3.38281 2.51759 3.46914C2.15096 3.55547 1.82453 3.76756 1.6 4.07C1.37548 4.37244 1.26711 4.74627 1.29743 5.12338C1.32775 5.50049 1.49481 5.85127 1.77 6.11L12 7Z"
                          stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                      </svg>

                      <!-- Other Icon -->

                      <svg v-else viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="size-5">
                        <path d="M12 2L14.4 7.2L20 8.5L16.8 13L17.5 19L12 16.5L6.5 19L7.2 13L4 8.5L9.6 7.2L12 2Z"
                          stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                      </svg>
                      {{ activity.type }}
                    </span>

                  </p>

                  <p v-if="activity.description" class="text-gray-600 flex justify-between items-center my-1">
                    <span class="flex flex-nowrap gap-1 items-center">
                      <NoteIcon />
                      {{ activity.description }}
                    </span>
                  </p>

                </div>
                <div v-else class="bg-red-50 rounded-lg shadow-sm text-sm px-4 py-4 text-center text-gray-500">No
                  activities yet.</div>

              </div>
            </div>

            <!-- Accommodation -->
            <div class="mt-4">
              <h4 class="text-md font-semibold text-gray-700 mb-3 relative inline-block"> Accommodation
                <button v-if="isAdmin" @click="() => { clearAcc(); accModal = true }"
                  class="cursor-pointer absolute top-0 left-full ms-3  text-gray-500 hover:text-blue-600 border border-gray-500 hover:border-blue-600 rounded-full">
                  <PlusIcon />
                </button>

              </h4>
              <div class="space-y-2 my-grid-600 gap-4">
                <div v-if="destination?.accommodations?.length"
                  v-for="accommodation in destination?.accommodations || []" :key="accommodation.id"
                  class="bg-green-100 text-gray-600 p-3 rounded-lg shadow-lg space-y-1 relative">
                  <!-- edit for admin -->
                  <button v-if="isAdmin" @click="() => { acc = accommodation; accModal = true }"
                    class="cursor-pointer absolute top-2 right-2 p-1 text-gray-500 hover:text-blue-600">
                    <PenIcon />
                  </button>


                  <p class="text-gray-600 flex justify-between items-center">
                    <span class="font-medium text-lg">{{ accommodation.name }}</span>
                  </p>
                  <p v-if="accommodation.check_in"
                    class="text-gray-600 flex justify-between items-center flex-wrap gap-x-6">

                    <span class="flex flex-nowrap gap-1 items-center">
                      <span class="font-medium">Check-In: </span>
                      <CalenderIcon />
                      {{ formatDate(accommodation.check_in) }} &nbsp;
                      <ClockIcon />
                      {{ formetTime(accommodation.check_in) }}
                    </span>
                    <span class="flex flex-nowrap gap-1 items-center">
                      <span class="font-medium">Check-Out: </span>
                      <CalenderIcon />
                      {{ formatDate(accommodation.check_out) }} &nbsp;
                      <ClockIcon />
                      {{ formetTime(accommodation.check_out) }}
                    </span>
                  </p>
                  <p v-if="accommodation.address" class="flex flex-nowrap gap-1 items-center">
                    <LocationIcon />
                    {{ accommodation.address }}
                  </p>
                  <p v-if="accommodation.contact_info" class="flex flex-nowrap gap-1 items-center">
                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 122.9 98.9" fill="none"
                      stroke="currentColor" stroke-width="8">
                      <g>
                        <path
                          d="M109 98.9H13.7v-14c.5-16.3 14.9-28.7 23.6-42.8V29.7h14.5v8.9h19.5v-8.9h14.5v12.4C95.2 57.2 109 67 109 84.7V98.9L109 98.9z M122.5 42.1c0-2.2.4-4.4.1-6.8c-10.7 3.5-21.1 2.5-31.3-3.1c-.4 3.8.2 7.2 1.6 10.4C96.5 50.6 122.2 53.3 122.5 42.1L122.5 42.1z M0 42.1c0-2.2-.4-4.4-.1-6.8c10.7 3.5 21.1 2.5 31.3-3.1c.4 3.8-.2 7.2-1.6 10.4C26.3 50.6.5 53.3 0 42.1L0 42.1z M0 31.9C8.6-8.2 115.4-13 122.9 32c-10.4 2.9-21 1.2-31.6-3.6c.3-2.1-.2-3.8-1.3-5.2c-6.3-7.9-51.4-8.2-57.2.3c-.9 1.3-1.3 2.9-1.2 4.7C21.1 34.6 10.5 36.1 0 31.9L0 31.9z M47.2 47.7c2.4 0 4.3 1.9 4.3 4.3c0 2.4-1.9 4.3-4.3 4.3c-2.4 0-4.3-1.9-4.3-4.3C43 49.7 44.9 47.7 47.2 47.7L47.2 47.7z M74.8 71.7c2.4 0 4.3 1.9 4.3 4.3c0 2.4-1.9 4.3-4.3 4.3c-2.4 0-4.3-1.9-4.3-4.3C70.5 73.7 72.4 71.7 74.8 71.7L74.8 71.7z M61 71.7c2.4 0 4.3 1.9 4.3 4.3c0 2.4-1.9 4.3-4.3 4.3c-2.4 0-4.3-1.9-4.3-4.3C56.7 73.7 58.6 71.7 61 71.7L61 71.7z M47.2 71.7c2.4 0 4.3 1.9 4.3 4.3c0 2.4-1.9 4.3-4.3 4.3c-2.4 0-4.3-1.9-4.3-4.3C43 73.7 44.9 71.7 47.2 71.7L47.2 71.7z M74.8 59.7c2.4 0 4.3 1.9 4.3 4.3c0 2.4-1.9 4.3-4.3 4.3c-2.4 0-4.3-1.9-4.3-4.3C70.5 61.7 72.4 59.7 74.8 59.7L74.8 59.7z M61 59.7c2.4 0 4.3 1.9 4.3 4.3c0 2.4-1.9 4.3-4.3 4.3c-2.4 0-4.3-1.9-4.3-4.3C56.7 61.7 58.6 59.7 61 59.7L61 59.7z M47.2 59.7c2.4 0 4.3 1.9 4.3 4.3c0 2.4-1.9 4.3-4.3 4.3c-2.4 0-4.3-1.9-4.3-4.3C43 61.7 44.9 59.7 47.2 59.7L47.2 59.7z M74.8 47.7c2.4 0 4.3 1.9 4.3 4.3c0 2.4-1.9 4.3-4.3 4.3c-2.4 0-4.3-1.9-4.3-4.3C70.5 49.7 72.4 47.7 74.8 47.7L74.8 47.7z M61 47.7c2.4 0 4.3 1.9 4.3 4.3c0 2.4-1.9 4.3-4.3 4.3c-2.4 0-4.3-1.9-4.3-4.3C56.7 49.7 58.6 47.7 61 47.7L61 47.7z" />
                      </g>
                    </svg>
                    {{ accommodation.contact_info }}
                  </p>
                  <p v-if="accommodation.notes" class="flex flex-nowrap gap-1 items-center">
                    <NoteIcon />
                    {{ accommodation.notes }}
                  </p>

                </div>
                <div v-else class="bg-red-50 rounded-lg shadow-sm text-sm px-4 py-4 text-center text-gray-500">No
                  accommodations yet.</div>
              </div>
            </div>

            <!-- Transportation -->
            <div class="mt-4">
              <h4 class="text-md font-semibold text-gray-700 mb-3 relative inline-block"> Transportation
                <button v-if="isAdmin" @click="() => { clearTrans(); transModal = true }"
                  class="cursor-pointer absolute top-0 left-full ms-3  text-gray-500 hover:text-blue-600 border border-gray-500 hover:border-blue-600 rounded-full">
                  <PlusIcon />
                </button>

              </h4>
              <div class="space-y-2 my-grid-600 gap-4">
                <div v-if="destination?.transportations?.length"
                  v-for="transportation in destination?.transportations || []" :key="transportation.id"
                  class="bg-green-100 text-gray-600 p-3 rounded-lg shadow-lg space-y-1 relative">
                  <!-- edit for admin -->
                  <button v-if="isAdmin" @click="() => { trans = transportation; transModal = true }"
                    class="cursor-pointer absolute top-2 right-2 p-1 text-gray-500 hover:text-blue-600">
                    <PenIcon />
                  </button>

                  <!-- type -->
                  <p class="flex flex-nowrap gap-1 items-center">
                    <span
                      class="bg-green-50 text-green-600 rounded-xl flex flex-nowrap gap-1 items-center px-3 border border-green-300">

                      <TransportIcon :type="transportation.type" />
                      {{ transportation.type }}
                    </span>

                    {{ transportation.company }}
                  </p>
                  <div class="text-gray-600 flex justify-between items-center flex-wrap gap-x-6">
                    <p v-if="transportation?.arrival_location || transportation?.arrival_time"
                      class="flex flex-nowrap gap-1 items-center">
                      <span class="font-medium">Arrival:</span>
                      <span v-if="transportation.arrival_location" class="flex flex-nowrap gap-1 items-center">
                        <LocationIcon />
                        {{ transportation.arrival_location }}
                      </span>
                      <span v-if="transportation.arrival_time" class="flex flex-nowrap gap-1 items-center">
                        <CalenderIcon />
                        {{ formetTime(transportation.arrival_time) }}
                        <ClockIcon />
                        {{ formatDate(transportation.arrival_time) }}
                      </span>
                    </p>
                    <p v-if="transportation?.departure_location || transportation?.departure_time"
                      class="flex flex-nowrap gap-1 items-center">
                      <span class="font-medium">Departure:</span>
                      <span v-if="transportation.departure_location" class="flex flex-nowrap gap-1 items-center">
                        <LocationIcon />
                        {{ transportation.departure_location }}
                      </span>
                      <span v-if="transportation.departure_time" class="flex flex-nowrap gap-1 items-center">
                        <CalenderIcon />
                        {{ formatDate(transportation.departure_time) }}
                        <ClockIcon />
                        {{ formetTime(transportation.departure_time) }}
                      </span>
                    </p>
                  </div>

                  <p v-if="transportation?.booking_reference" class="flex flex-nowrap gap-1 items-center">
                    <NoteIcon />
                    <span class="font-medium">Booking Reference: </span>
                    {{ transportation.booking_reference }}
                  </p>
                  <p v-if="transportation?.notes" class="flex flex-nowrap gap-1 items-center">
                    <NoteIcon />
                    {{ transportation.notes }}
                  </p>

                </div>
                <div v-else class="bg-red-50 rounded-lg shadow-sm text-sm px-4 py-4 text-center text-gray-500">No
                  Transportations yet.</div>
              </div>
            </div>
          </div>
          <div v-else class="bg-red-50 rounded-lg shadow-sm text-sm px-4 py-4 text-center text-gray-500"> No
            destinations yet. </div>

        </div>
      </div>


      <!-- participants -->
      <Participants v-if="trip?.is_participant || trip?.is_public" :isOwner="isOwner ? true : false"
        :participants="trip.participants" :tripId="trip.id" :isAdmin="isAdmin ? true : false"
        :suggestions="trip.suggested_participants" @update="(data) => { trip.participants = data }" />

      <Comments @update="(data) => { trip.comments = [...trip.comments, ...data]; }" :tripId="trip.id" :comments="trip.comments" />

      <!-- expenses -->
      <Expense :expenses="trip.expenses" v-if="trip?.is_participant" :tripId="trip.id" :isAdmin="isAdmin ? true : false"
        :isOwner="isOwner ? true : false" @update="(data) => { trip.expenses = data }" />

    </div>
    <!-- edit trip -->
    <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center backdrop-blur-xs">
      <div class="bg-white  rounded-lg shadow-sm w-full max-w-2xl">
        <div class="flex items-center justify-between p-4 border-b border-gray-200 ">
          <h3 class="text-xl font-semibold text-gray-900 ">
            Edit Trip
          </h3>
          <button @click="clearForm" class="text-gray-400 hover:text-gray-900 ">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div class="p-4 space-y-4">

          <div>
            <label for="title" class="block text-sm font-medium leading-6 text-gray-900 ">Title
              <span class="text-red-500">*</span> </label>
            <div class="mt-2">
              <input v-model="form.title" type="text"
                class="block w-full rounded-md border-0 py-1.5 text-gray-900  ring-1 ring-inset ring-gray-300  placeholder:text-gray-400   focus:ring-inset focus:ring-indigo-300 sm:text-sm sm:leading-6 px-3" />
            </div>
            <small class="text-red-500" v-if="errors?.title">{{ errors?.title[0] }}</small>
          </div>

          <div>
            <label for="description" class="block text-sm font-medium leading-6 text-gray-900 ">Description
              <span class="text-red-500">*</span> </label>
            <div class="mt-2">
              <textarea v-model="form.description"
                class="block w-full rounded-md border-0 py-1.5 text-gray-900  ring-1 ring-inset ring-gray-300  placeholder:text-gray-400   focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3"></textarea>
            </div>
            <small class="text-red-500" v-if="errors?.description">{{ errors?.description[0] }}</small>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
              <label for="start_date" class="block text-sm font-medium leading-6 text-gray-900 ">Start
                Date
                <span class="text-red-500">*</span> </label>
              <div class="mt-2">
                <input v-model="form.start_date" type="date"
                  class="block w-full rounded-md border-0 py-1.5 text-gray-900  ring-1 ring-inset ring-gray-300  placeholder:text-gray-400   focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
              </div>
              <small class="text-red-500" v-if="errors?.start_date">{{ errors?.start_date[0] }}</small>
            </div>

            <div>
              <label for="end_date" class="block text-sm font-medium leading-6 text-gray-900 ">End Date
                <span class="text-red-500">*</span> </label>
              <div class="mt-2">
                <input v-model="form.end_date" type="date"
                  class="block w-full rounded-md border-0 py-1.5 text-gray-900  ring-1 ring-inset ring-gray-300  placeholder:text-gray-400   focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
              </div>
              <small class="text-red-500" v-if="errors?.end_date">{{ errors?.end_date[0] }}</small>
            </div>

            <div>
              <label for="budget" class="block text-sm font-medium leading-6 text-gray-900 ">Budget
                <span class="text-red-500">*</span> </label>
              <div class="mt-2">
                <input v-model="form.budget" type="number" step="0.01"
                  class="block w-full rounded-md border-0 py-1.5 text-gray-900  ring-1 ring-inset ring-gray-300  placeholder:text-gray-400   focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3" />
              </div>
              <small class="text-red-500" v-if="errors?.budget">{{ errors?.budget[0] }}</small>
            </div>

            <div>
              <label for="status" class="block text-sm font-medium leading-6 text-gray-900 ">Status
                <span class="text-red-500">*</span> </label>
              <div class="mt-2">
                <select v-model="form.status"
                  class="block w-full rounded-md border-0 py-1.5 text-gray-900  ring-1 ring-inset ring-gray-300  placeholder:text-gray-400   focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 px-3">
                  <option value="planning">Planning</option>
                  <option value="completed">Completed</option>
                  <option value="canceled">Canceled</option>
                </select>
              </div>
              <small class="text-red-500" v-if="errors?.status">{{ errors?.status[0] }}</small>
            </div>





          </div>

          <p class="text-red-500 mx-3" v-if="error">{{ error }}</p>
          <div class="flex justify-end items-center p-2 border-t border-gray-200 ">
            <button @click.prevent="updateTrip" class="text-white bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg">
              Update
            </button>

            <button @click.prevent="destroyTrip"
              class="text-white bg-red-700 hover:bg-red-800 px-4 py-2 rounded-lg mx-2">
              Delete
            </button>

            <button @click.prevent="clearForm"
              class="ml-2 px-4 py-2 text-gray-900 bg-white border border-gray-200 hover:bg-gray-100 rounded-lg   ">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script setup>
// const isAdmin = ref(false);

const route = useRoute();
const trip = ref({});
const isAdmin = ref(true);
const isOwner = ref(false);
const loading = ref(true);
const form = ref({
  title: '',
  description: '',
  location: '',
  start_date: '',
  end_date: '',
  budget: 0,
  status: ''
})
watch(trip, () => {
  form.value = {
    title: trip.value.title,
    description: trip.value.description,
    location: trip.value.location,
    start_date: trip.value.start_date,
    end_date: trip.value.end_date,
    budget: trip.value.budget,
    status: trip.value.status
  }
})
const isModalOpen = ref(false);
const errors = ref({});
const error = ref(null);
const clearForm = () => {
  form.value = {
    title: '',
    description: '',
    location: '',
    start_date: '',
    end_date: '',
    budget: 0,
    status: ''
  };
  errors.value = {};
  error.value = null;
  isModalOpen.value = false;
}
// description
const desModal = ref(false);
const des = ref({
  name: '',
  arrival_date: '',
  departure_date: '',
  notes: ''
});
const clearDes = () => {
  des.value = {
    name: '',
    arrival_date: '',
    departure_date: '',
    notes: ''
  }
  desModal.value = false
}
// activity
const actModal = ref(false);
const act = ref({
  title: '',
  description: '',
  location: '',
  date: '',
  start_time: '',
  end_time: '',
  type: '',
  cost: 0,
});
const clearAct = () => {
  act.value = {
    title: '',
    description: '',
    location: '',
    date: '',
    start_time: '',
    end_time: '',
    type: '',
    cost: 0,
  };
  actModal.value = false;
}
// accommodation
const accModal = ref(false);
const acc = ref({
  destination_id: '',
  name: '',
  check_in: '',
  check_out: '',
  address: '',
  contact_info: '',
  notes: '',
});
const clearAcc = () => {
  acc.value = {
    destination_id: '',
    name: '',
    check_in: '',
    check_out: '',
    address: '',
    contact_info: '',
    notes: '',
  };
  accModal.value = false;
}
//  transportation
const transModal = ref(false);
const trans = ref({
  destination_id: '',
  type: '',
  departure_location: '',
  arrival_location: '',
  departure_time: '',
  arrival_time: '',
  company: '',
  booking_reference: '',
  notes: '',
});
const clearTrans = () => {
  trans.value = {
    destination_id: '',
    type: '',
    departure_location: '',
    arrival_location: '',
    departure_time: '',
    arrival_time: '',
    company: '',
    booking_reference: '',
    notes: '',
  };
  transModal.value = false;
}

onMounted(async () => {
  fetchTrip();
})
const fetchTrip = async () => {
  try {
    loading.value = true;
    const data = await $fetch(`http://localhost:8000/api/trips/${route.params.id}`,
      {
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${useCookie('token').value}`
        }
      }
    )
    trip.value = data;
    isAdmin.value = data?.can_edit || false;
    isOwner.value = data?.is_creator || false;
    loading.value = false;

  } catch (error) {
    return navigateTo('/trips');
  }
}
const formatDate = (date) => {
  // console.log(date);
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    timeZone: 'UTC'
  });
}
const formetTime = (time) => {
  return new Date(time).toLocaleTimeString('en-US', {
    hour: 'numeric',
    minute: 'numeric',
    hour12: true,
    timeZone: 'UTC'
  });
}
const activityIcon = (type) => {
  return `M6 12a.75.75 0 0 1-.75-.75v-7.5a.75.75 0 1 1 1.5 0v7.5A.75.75 0 0 1 6 12ZM18 12a.75.75 0 0 1-.75-.75v-7.5a.75.75 0 0 1 1.5 0v7.5A.75.75 0 0 1 18 12ZM6.75 20.25v-1.5a.75.75 0 0 0-1.5 0v1.5a.75.75 0 0 0 1.5 0ZM18.75 18.75v1.5a.75.75 0 0 1-1.5 0v-1.5a.75.75 0 0 1 1.5 0ZM12.75 5.25v-1.5a.75.75 0 0 0-1.5 0v1.5a.75.75 0 0 0 1.5 0ZM12 21a.75.75 0 0 1-.75-.75v-7.5a.75.75 0 0 1 1.5 0v7.5A.75.75 0 0 1 12 21ZM3.75 15a2.25 2.25 0 1 0 4.5 0 2.25 2.25 0 0 0-4.5 0ZM12 11.25a2.25 2.25 0 1 1 0-4.5 2.25 2.25 0 0 1 0 4.5ZM15.75 15a2.25 2.25 0 1 0 4.5 0 2.25 2.25 0 0 0-4.5 0Z`;
}

const joinTrip = async () => {
  if (!useUserStore().user) {
    return navigateTo('/login');
  }
  try {
    const data = await $fetch(`http://localhost:8000/api/trips/${trip.value.id}/join`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${useCookie('token').value}`
      }
    });
    trip.value.participant_status = 'pending';
  } catch (error) {
    console.error('Join request failed:', error);
  }
};

const updateTrip = async () => {
  try {
    const data = await $fetch(`http://localhost:8000/api/trips/${trip.value.id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${useCookie('token').value}`
      },
      body: form.value
    });
    trip.value = { ...trip.value, ...data };
    clearForm();
  } catch (error) {
    if (error?.response?._data.errors) {
      errors.value = error.response._data.errors;
    } else {
      console.error(error);
      error.value = 'Something went wrong. Try again.';
    }
  }
};

const destroyTrip = async () => {
  try {
    const data = await $fetch(`http://localhost:8000/api/trips/${trip.value.id}`, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${useCookie('token').value}`
      }
    });
    navigateTo('/trips/my-trips');

  } catch (error) {
    console.error('Join request failed:', error);
  }
};
const leaveTrip = async () => {
  try {
    const data = await $fetch(`http://localhost:8000/api/trips/${trip.value.id}/leave`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${useCookie('token').value}`
      }
    });
    fetchTrip();
  } catch (error) {
    console.error('Join request failed:', error);
  }
}
const acceptInvitation = async () => {
  try {
    const data = await $fetch(`http://localhost:8000/api/trips/${trip.value.id}/accept`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${useCookie('token').value}`
      }
    });
    trip.value.participant_status = 'accepted';
    fetchTrip();
  } catch (error) {
    console.error('Join request failed:', error);
  }
}
const cancelRequest = async () => {
  try {
    const data = await $fetch(`http://localhost:8000/api/trips/${trip.value.id}/cancel`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${useCookie('token').value}`
      }
    });
    fetchTrip();
  } catch (error) {
    console.error('Join request failed:', error);
  }
}
const totalExpenses = computed(() => {
  return trip.value?.expenses?.reduce((sum, expense) => sum + parseFloat(expense.amount), 0);
});

</script>
