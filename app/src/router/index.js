import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import RegisterView from '@/views/RegisterView.vue'
import LoginView from '@/views/LoginView.vue'
import HomeUserView from '@/views/user/HomeUserView.vue'
import HomeEnterpriseView from '@/views/enterprise/HomeEnterpriseView.vue'

import { isUserAuthenticated, isEnterpriseAuthenticated } from '@/assets/scripts/AuthMiddleware'

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
    meta: {
      title: 'Avalia Ai! - Meu Perfil',
      requiresAuth: true,
      authRole: 'user',
    }
  },
  {
    path: '/empresa/home',
    name: 'Empresa Home',
    component: HomeEnterpriseView,
    meta: {
      title: 'Avalia Ai! - Minha Empresa',
      requiresAuth: true,
      authRole: 'enterprise',
    }
  },
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

router.beforeEach((to, from, next) => {
  const defaultTitle = 'Avalia Ai!'
  document.title = to.meta.title || defaultTitle

  const requiresAuth = to.meta.requiresAuth
  const authRole = to.meta.authRole

  if (requiresAuth) {
    if (authRole === 'user' && isUserAuthenticated()) {
      next()
    } else if (authRole === 'enterprise' && isEnterpriseAuthenticated()) {
      next()
    } else {
      next({ path: '/login?', query: {expired: true, type: authRole} })
    }
  } else {
    next()
  }
})

export default router
