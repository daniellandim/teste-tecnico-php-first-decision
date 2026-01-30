import {defineStore} from 'pinia'

export const useAuthStore = defineStore('auth', {

  state: () => {
    const auth = localStorage.getItem('user')
    if (auth) {
      return JSON.parse(auth)
    }
    return {
      user: null,
      logged: false
    }
  },

  actions: {
    setUser(user) {
      this.user = user
      this.logged = true
      localStorage.setItem('user', JSON.stringify({user, logged: true}))
    },

    logout() {
      this.user = null
      this.logged = false
      localStorage.removeItem('user');
    }
  }
})

