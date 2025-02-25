// plugins/auth.js
import { useUserStore } from "~/stores/user";

export default defineNuxtPlugin(async (nuxtApp) => {
  const userStore = useUserStore();

  const token = useCookie("token").value;
  if (token) {
    // console.log(token);
    try {
      const response = await fetch("http://localhost:8000/api/me", {
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "application/json",
        },
      });
      const user = await response.json();
      userStore.setUser({ ...user, token: token }); // Store the user in Pinia state
      
    } catch (error) {
      console.error("Error loading user:", error);
    }
  }
});

