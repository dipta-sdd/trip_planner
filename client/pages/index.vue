<template>
  <div class="flex-1">
    <!-- Hero Section -->
    <section class="relative bg-gray-50">
      <div class="absolute inset-0 bg-gradient-to-r from-blue-100 to-blue-50 z-0"></div>
      <div class="container mx-auto relative z-10 py-20 md:py-32 px-4">
        <div class="max-w-2xl space-y-6">
          <h1 class="text-4xl md:text-6xl font-bold tracking-tight">
            Plan your perfect trip, <span class="text-blue-600">together</span>
          </h1>
          <p class="text-xl text-gray-600">
            Seamlessly organize your travel itineraries, collaborate with friends, and keep track of all your trip
            details in one place.
          </p>
          <div class="flex flex-col sm:flex-row gap-4">
            <NuxtLink v-if="useUserStore()?.user" to="/create-trip"
              class="px-6 py-3 text-white bg-blue-600 rounded-md hover:bg-blue-700 inline-flex items-center justify-center">
              Get Started
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="h-4 w-4 ml-2">
                <path d="M5 12h14"></path>
                <path d="m12 5 7 7-7 7"></path>
              </svg>
            </NuxtLink>
            <NuxtLink v-else to="/signup"
              class="px-6 py-3 text-white bg-blue-600 rounded-md hover:bg-blue-700 inline-flex items-center justify-center">
              Get Started
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="h-4 w-4 ml-2">
                <path d="M5 12h14"></path>
                <path d="m12 5 7 7-7 7"></path>
              </svg>
            </NuxtLink>
            
          </div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="container mx-auto py-20 px-4">
      <div class="text-center mb-16">
        <h2 class="text-3xl font-bold mb-4">Everything you need for perfect trip planning</h2>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
          Our comprehensive tools make planning your next adventure a breeze, whether you're traveling solo or with a
          group.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
          <div class="p-6">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="h-10 w-10 text-blue-600 mb-2">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="16" x2="16" y1="2" y2="6"></line>
              <line x1="8" x2="8" y1="2" y2="6"></line>
              <line x1="3" x2="21" y1="10" y2="10"></line>
            </svg>
            <h3 class="text-2xl font-semibold mb-2">Itinerary Management</h3>
            <p class="text-gray-600 mb-4">
              Create detailed day-by-day schedules with activities, reservations, and notes.
            </p>
            <ul class="space-y-2 text-sm">
              <li class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="h-4 w-4 text-blue-600 mt-1">
                  <path d="M5 12h14"></path>
                  <path d="m12 5 7 7-7 7"></path>
                </svg>
                <span>Organize destinations by day and time</span>
              </li>
              

            </ul>
          </div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
          <div class="p-6">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="h-10 w-10 text-blue-600 mb-2">
              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
              <circle cx="9" cy="7" r="4"></circle>
              <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
              <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
            </svg>
            <h3 class="text-2xl font-semibold mb-2">Collaboration</h3>
            <p class="text-gray-600 mb-4">
              Invite friends and family to plan together.
            </p>
            <ul class="space-y-2 text-sm">
              <li class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="h-4 w-4 text-blue-600 mt-1">
                  <path d="M5 12h14"></path>
                  <path d="m12 5 7 7-7 7"></path>
                </svg>
                <span>Invite friends and family</span>
              </li>
              
            </ul>
          </div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
          <div class="p-6">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="h-10 w-10 text-blue-600 mb-2">
              <rect width="20" height="14" x="2" y="5" rx="2"></rect>
              <line x1="2" x2="22" y1="10" y2="10"></line>
            </svg>
            <h3 class="text-2xl font-semibold mb-2">Budget Tracking</h3>
            <p class="text-gray-600 mb-4">
              Keep track of all your expenses and stay within your budget.
            </p>
            <ul class="space-y-2 text-sm">
              <li class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="h-4 w-4 text-blue-600 mt-1">
                  <path d="M5 12h14"></path>
                  <path d="m12 5 7 7-7 7"></path>
                </svg>
                <span>Set overall spending by category</span>
              </li>
              
             
            </ul>
          </div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
          <div class="p-6">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="h-10 w-10 text-blue-600 mb-2">
              <path
                d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z">
              </path>
            </svg>
            <h3 class="text-2xl font-semibold mb-2">Transportation Management</h3>
            <p class="text-gray-600 mb-4">
              Store all your transportation details in one convenient place.
            </p>
            <ul class="space-y-2 text-sm">
              <li class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="h-4 w-4 text-blue-600 mt-1">
                  <path d="M5 12h14"></path>
                  <path d="m12 5 7 7-7 7"></path>
                </svg>
                <span>Track flights, trains, car rentals, and more</span>
              </li>
              <li class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="h-4 w-4 text-blue-600 mt-1">
                  <path d="M5 12h14"></path>
                  <path d="m12 5 7 7-7 7"></path>
                </svg>
                <span>Store confirmation numbers and tickets</span>
              </li>
             
            </ul>
          </div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
          <div class="p-6">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="h-10 w-10 text-blue-600 mb-2">
              <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"></path>
              <circle cx="12" cy="10" r="3"></circle>
            </svg>
            <h3 class="text-2xl font-semibold mb-2">Accommodation Tracking</h3>
            <p class="text-gray-600 mb-4">
              Manage all your lodging information with ease.
            </p>
            <ul class="space-y-2 text-sm">
              <li class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="h-4 w-4 text-blue-600 mt-1">
                  <path d="M5 12h14"></path>
                  <path d="m12 5 7 7-7 7"></path>
                </svg>
                <span>Store hotel, Airbnb, and hostel details</span>
              </li>
              <li class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="h-4 w-4 text-blue-600 mt-1">
                  <path d="M5 12h14"></path>
                  <path d="m12 5 7 7-7 7"></path>
                </svg>
                <span>Save check-in/check-out times and addresses</span>
              </li>
              <li class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="h-4 w-4 text-blue-600 mt-1">
                  <path d="M5 12h14"></path>
                  <path d="m12 5 7 7-7 7"></path>
                </svg>
                <span>Keep reservation confirmations accessible</span>
              </li>
            </ul>
          </div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
          <div class="p-6">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="h-10 w-10 text-blue-600 mb-2">
              <circle cx="12" cy="12" r="10"></circle>
              <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path>
              <path d="M2 12h20"></path>
            </svg>
            <h3 class="text-2xl font-semibold mb-2">Multiple User Roles</h3>
            <p class="text-gray-600 mb-4">
              Different permission levels for trip owners and participants.
            </p>
            <ul class="space-y-2 text-sm">
              <li class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="h-4 w-4 text-blue-600 mt-1">
                  <path d="M5 12h14"></path>
                  <path d="m12 5 7 7-7 7"></path>
                </svg>
                <span>Trip owners have full control over planning</span>
              </li>
              <li class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="h-4 w-4 text-blue-600 mt-1">
                  <path d="M5 12h14"></path>
                  <path d="m12 5 7 7-7 7"></path>
                </svg>
                <span>Participants can view and edit as permitted</span>
              </li>
              <li class="flex items-start gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                  stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                  class="h-4 w-4 text-blue-600 mt-1">
                  <path d="M5 12h14"></path>
                  <path d="m12 5 7 7-7 7"></path>
                </svg>
                <span>System admins can manage all users and trips</span>
              </li>
            </ul>
          </div>
        </div>
       
      </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 bg-gray-100">
      <div class="container mx-auto px-4">
        <div class="text-center mb-16">
          <h2 class="text-3xl font-bold mb-4">How TripPlanner Works</h2>
          <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Get started in just a few simple steps and make your travel planning stress-free.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
          <div class="flex flex-col items-center text-center">
            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mb-4">
              <span class="text-2xl font-bold text-blue-600">1</span>
            </div>
            <h3 class="text-xl font-semibold mb-2">Create Your Trip</h3>
            <p class="text-gray-600">
              Sign up and create a new trip with basic details like destination, dates, and purpose.
            </p>
          </div>

          <div class="flex flex-col items-center text-center">
            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mb-4">
              <span class="text-2xl font-bold text-blue-600">2</span>
            </div>
            <h3 class="text-xl font-semibold mb-2">Add Details & Invite</h3>
            <p class="text-gray-600">
              Fill in your itinerary, add accommodations, and invite friends or family to collaborate.
            </p>
          </div>

          <div class="flex flex-col items-center text-center">
            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center mb-4">
              <span class="text-2xl font-bold text-blue-600">3</span>
            </div>
            <h3 class="text-xl font-semibold mb-2">Enjoy Your Trip</h3>
            <p class="text-gray-600">
              Access your plans on the go, track expenses, and make real-time adjustments as needed.
            </p>
          </div>
        </div>

        <div class="mt-16 text-center">
          <button @click="navigateTo('/create-trip')"
            class="cursor-pointer px-6 py-3 text-white bg-blue-600 rounded-md hover:bg-blue-700 inline-flex items-center justify-center">
            Start Planning Now
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="h-4 w-4 ml-2">
              <path d="M5 12h14"></path>
              <path d="m12 5 7 7-7 7"></path>
            </svg>
          </button>
        </div>
      </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="container mx-auto py-20 px-4">
      <div class="text-center mb-16">
        <h2 class="text-3xl font-bold mb-4">What Our Users Say</h2>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
          Join thousands of satisfied travelers who have simplified their trip planning.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
          <div class="flex flex-col gap-4">
            <div class="flex gap-0.5 text-yellow-400">
              <StarIcon v-for="n in 5" :key="n" />
              <!-- Repeat the star SVG 4 more times for a total of 5 stars -->
            </div>
            <p class="text-gray-600">
              "TripPlanner made organizing our family vacation to Europe so much easier. Everyone could see the
              itinerary and add their own suggestions. The budget tracking feature saved us from overspending!"
            </p>
            <div class="mt-4">
              <p class="font-semibold">Sarah Johnson</p>
              <p class="text-sm text-gray-500">Family Traveler</p>
            </div>
          </div>
        </div>
        <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
          <div class="flex flex-col gap-4">
            <div class="flex gap-0.5 text-yellow-400">
              <StarIcon v-for="n in 5" :key="n" />
              <!-- Repeat the star SVG 4 more times for a total of 5 stars -->
            </div>
            <p class="text-gray-600">
              "TripPlanner is a game-changer for business travelers like me. It's easy to organize my trips and keep
              track of expenses. I've already recommended it to my colleagues!"
            </p>
            <div class="mt-4">
              <p class="font-semibold">John Doe</p>
              <p class="text-sm text-gray-500">Business Traveler</p>
            </div>
          </div>
        </div>
        <div class="rounded-lg border border-gray-200 bg-gray-50 p-6">
          <div class="flex flex-col gap-4">
            <div class="flex gap-0.5 text-yellow-400">
              <StarIcon v-for="n in 5" :key="n" />
              <!-- Repeat the star SVG 4 more times for a total of 5 stars -->
            </div>
            <p class="text-gray-600">
              "I used TripPlanner for my honeymoon and it was a lifesaver. We could both access the itinerary and
              make changes as needed. The app is so user-friendly and the customer support is excellent!"
            </p>
            <div class="mt-4">
              <p class="font-semibold">Emily Chen</p>
              <p class="text-sm text-gray-500">Honeymooner</p>
            </div>
          </div>
        </div>

        <!-- Repeat similar structure for other testimonials -->

      </div>
    </section>


    <!-- CTA Section -->
    <section v-if="!useUserStore()?.user" id="cta" class="container mx-auto py-20 px-4">
      <div class="rounded-lg bg-blue-600 p-8 md:p-12 shadow-lg">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
          <div class="max-w-md">
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-2">
              Ready to start planning your next adventure?
            </h2>
            <p class="text-blue-100">
              Join thousands of travelers who have simplified their trip planning process with TripPlanner.
            </p>
          </div>
          <NuxtLink to="/signup" class="px-6 py-3 text-blue-600 bg-white rounded-md hover:bg-blue-50 font-medium">
            Sign Up for Free
          </NuxtLink>
        </div>
      </div>
    </section>
  </div>


</template>
  