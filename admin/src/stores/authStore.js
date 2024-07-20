import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useAuthStore = defineStore('authStore', {
  state: () => ({
    userData: null,
  }),
  actions: {
    setUserData(data) {
      this.userData = data
    },
  },
})