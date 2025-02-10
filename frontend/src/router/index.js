import { createRouter, createWebHistory } from 'vue-router'
import Home from '../components/Home.vue'
import PostList from '../components/PostList.vue'
import Register from '../components/Register.vue'
import Login from '../components/Login.vue'
import UserProfile from '../components/UserProfile.vue'
import { useAuth } from '../utils/auth'

const routes = [
  { path: '/', component: Home },
  { path: '/blog', component: PostList, meta: { requiresAuth: true } },
  { path: '/register', component: Register },
  { path: '/login', component: Login },
  { path: '/profile', component: UserProfile, meta: { requiresAuth: true } }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const { isLoggedIn } = useAuth()
  if (to.matched.some(record => record.meta.requiresAuth) && !isLoggedIn.value) {
    next('/login')
  } else {
    next()
  }
})

export default router