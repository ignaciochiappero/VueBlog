<template>
  <div class="app-container">
    <nav class="navbar">
      <div class="container navbar-content">
        <router-link to="/" class="logo">VueBlog</router-link>
        <div class="nav-links">
          <router-link to="/" class="nav-link">Home</router-link>
          <router-link to="/blog" v-if="isLoggedIn" class="nav-link">Blog</router-link>
          <template v-if="isLoggedIn">
            <div class="dropdown">
              <button class="dropbtn">{{ user.name }} <span class="arrow">▼</span></button>
              <div class="dropdown-content">
                <router-link to="/profile">Perfil</router-link>
                <a href="#" @click="logout">Cerrar Sesión</a>
              </div>
            </div>
          </template>
          <template v-else>
            <router-link to="/register" class="nav-link">Register</router-link>
            <router-link to="/login" class="nav-link">Login</router-link>
          </template>
        </div>
      </div>
    </nav>
    <main class="main-content">
      <div class="container">
        <router-view></router-view>
      </div>
    </main>
    <footer class="footer">
      <div class="container">
        <p>&copy; 2025 VueBlog | NachoDev</p>
      </div>
    </footer>
  </div>
</template>

<script>
import { useAuth } from './utils/auth'
import { useRouter } from 'vue-router'
import { watch } from 'vue'

export default {
  setup() {
    const router = useRouter()
    const { user, isLoggedIn, clearUser } = useAuth()

    const logout = () => {
      clearUser()
      router.push('/login')
    }

    watch(isLoggedIn, (newValue) => {
      if (!newValue) {
        router.push('/login')
      }
    })

    return {
      user,
      isLoggedIn,
      logout
    }
  }
}
</script>

<style scoped>
.app-container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.navbar {
  background-color: var(--color-background-light);
  box-shadow: var(--shadow-sm);
  padding: 1rem 0;
  position: sticky;
  top: 0;
  z-index: 1000;
}

.navbar-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo {
  font-size: 1.5rem;
  font-weight: bold;
  color: var(--color-primary);
}

.nav-links {
  display: flex;
  gap: 1rem;
  align-items: center;
}

.nav-link {
  color: var(--color-text);
  text-decoration: none;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  transition: background-color 0.2s;
}

.nav-link:hover {
  background-color: var(--color-background);
}

.dropdown {
  position: relative;
}

.dropbtn {
  background-color: var(--color-primary);
  color: white;
  padding: 10px;
  font-size: 16px;
  border: none;
  cursor: pointer;
  border-radius: 6px;
}

.dropdown-content {
  display: none;
  position: absolute;
  right: 0;
  background-color: var(--color-background-light);
  min-width: 160px;
  box-shadow: var(--shadow-md);
  z-index: 1;
  border-radius: 6px;
  overflow: hidden;
}

.dropdown-content a {
  color: var(--color-text);
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown-content a:hover {
  background-color: var(--color-background);
}

.dropdown:hover .dropdown-content {
  display: block;
}

.dropdown:hover .dropbtn {
  background-color: var(--color-primary-dark);
}

.arrow {
  font-size: 12px;
  margin-left: 5px;
}

.main-content {
  flex: 1;
  padding: 2rem 0;
}

.footer {
  background-color: var(--color-background-light);
  padding: 1rem 0;
  text-align: center;
  color: var(--color-text-muted);
  box-shadow: var(--shadow-sm);
}

@media (max-width: 768px) {
  .navbar-content {
    flex-direction: column;
    gap: 1rem;
  }

  .nav-links {
    flex-direction: column;
    width: 100%;
  }

  .nav-link {
    width: 100%;
    text-align: center;
  }

  .dropdown {
    width: 100%;
  }

  .dropbtn {
    width: 100%;
  }

  .dropdown-content {
    width: 100%;
  }
}
</style>