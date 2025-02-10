import { ref, computed } from 'vue'

const user = ref(JSON.parse(localStorage.getItem('user')))

export function useAuth() {
  const setUser = (userData) => {
    user.value = userData
    localStorage.setItem('user', JSON.stringify(userData))
  }

  const clearUser = () => {
    user.value = null
    localStorage.removeItem('user')
  }

  const isLoggedIn = computed(() => !!user.value)

  return {
    user,
    setUser,
    clearUser,
    isLoggedIn
  }
}