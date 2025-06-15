import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import RegisterView from '@/views/RegisterView.vue'
import LoginView from '@/views/LoginView.vue'
import HomeUserView from '@/views/user/HomeUserView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
    meta: {title: 'Avalia Ai! - Avalie tudo o que quiser!'}
  },
  {
    path: '/cadastro',
    name: 'cadastro',
    component: RegisterView,
    meta: {title: 'Avalia Ai! - Cadastro'}
  },
  {
    path: '/login',
    name: 'login',
    component: LoginView,
    meta: {title: 'Avalia Ai! - Entrar'}
  },

  {
    path: '/usuario/home',
    name: 'Usuario Home',
    component: HomeUserView,
    meta: {title: 'Avalia Ai! - Dashboard'}
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

router.beforeEach((to, from, next) => {
  const defaultTitle = 'Avalia Ai!'
  document.title = to.meta.title || defaultTitle
  next()
})

export default router
