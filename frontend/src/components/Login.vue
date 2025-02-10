<template>
  <div class="auth-container">
    <div class="auth-form">
      <h2>Login</h2>
      <form @submit.prevent="login">
        <div class="form-group">
          <label for="email">Email</label>
          <input v-model="email" type="email" id="email" placeholder="Enter your email" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input v-model="password" type="password" id="password" placeholder="Enter your password" required>
        </div>
        <button type="submit" :disabled="isLoading">
          {{ isLoading ? 'Logging in...' : 'Login' }}
        </button>
      </form>
      <p v-if="error" class="error-message">{{ error }}</p>
      <p class="auth-link">
        Don't have an account? <router-link to="/register">Register here</router-link>
      </p>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import { useAuth } from '../utils/auth'

export default {
  setup() {
    const router = useRouter()
    const { setUser } = useAuth()
    const email = ref('')
    const password = ref('')
    const error = ref('')
    const isLoading = ref(false)

    const login = async () => {
      isLoading.value = true
      error.value = ''
      try {
        const response = await axios.post('http://localhost/vue-php-blog/api/login.php', {
          email: email.value,
          password: password.value
        })
        if (response.data.success) {
          setUser(response.data.user)
          router.push('/blog')
        } else {
          error.value = response.data.error
        }
      } catch (err) {
        console.error(err)
        error.value = 'An error occurred. Please try again.'
      } finally {
        isLoading.value = false
      }
    }

    return {
      email,
      password,
      error,
      isLoading,
      login
    }
  }
}
</script>

<style scoped>
.auth-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: calc(100vh - 200px);
}

.auth-form {
  width: 100%;
  max-width: 400px;
  padding: 2rem;
  background-color: var(--color-background-light);
  border-radius: 8px;
  box-shadow: var(--shadow-lg);
}

h2 {
  margin-bottom: 1.5rem;
  text-align: center;
  color: var(--color-primary);
}

.form-group {
  margin-bottom: 1rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  font-weight: 500;
}

input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid var(--color-border);
  border-radius: 6px;
  font-size: 1rem;
}

button {
  width: 100%;
  padding: 0.75rem;
  margin-top: 1rem;
  font-size: 1rem;
  font-weight: 600;
}

button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.error-message {
  color: var(--color-danger);
  margin-top: 1rem;
  text-align: center;
}

.auth-link {
  margin-top: 1rem;
  text-align: center;
  font-size: 0.9rem;
}
</style>