// middleware/auth.js
export default defineNuxtRouteMiddleware((to, from) => {
  const userStore = useUserStore(); // Pinia store for user data
  const user = userStore.user; // Get user data from the store
  

  // If the user is logged in, prevent access to login/signup pages
  if (user && (to.name === "login" || to.name === "signup")) {
    return navigateTo("/"); // Redirect to home page
  }

  // Optionally, if you want to redirect to login if the user is not authenticated
  // and trying to access protected routes, use this:
  // if (!user && to.name !== 'login' && to.name !== 'signup') {
  //   return navigateTo('/login'); // Redirect to login page
  // }
});
