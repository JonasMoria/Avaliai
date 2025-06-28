import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import RegisterView from '@/views/RegisterView.vue'
import LoginView from '@/views/LoginView.vue'
import HomeUserView from '@/views/user/HomeUserView.vue'
import HomeEnterpriseView from '@/views/enterprise/HomeEnterpriseView.vue'

import { isUserAuthenticated, isEnterpriseAuthenticated } from '@/assets/scripts/AuthMiddleware'
import ServicesView from '@/views/enterprise/ServicesView.vue'
import AccountView from '@/views/enterprise/AccountView.vue'
import ReviewsView from '@/views/enterprise/ReviewsView.vue'
import UserAccountView from '@/views/user/AccountView.vue'
import UserReviewsView from '@/views/user/ReviewsView.vue'
import EnterprisePage from '@/views/EnterprisePage.vue'
import ServicePage from '@/views/ServicePage.vue'
import SearchView from '@/views/SearchView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
    meta: { title: 'Avalia Ai! - Avalie tudo o que quiser!' }
  },
  {
    path: '/search/:term',
    name: 'Search',
    component: SearchView,
    meta: { title: 'Avalia Ai! - Buscar!' }
  },
  {
    path: '/empresa/:name/:id',
    name: 'EnterprisePage',
    component: EnterprisePage,
    meta: { title: 'Avalia Ai! - Empresas!' }
  },
  {
    path: '/empresa/item/:name/:id',
    name: 'EnterpriseServicePage',
    component: ServicePage,
    meta: { title: 'Avalia Ai! - Serviços da Empresa' }
  },
  {
    path: '/cadastro',
    name: 'cadastro',
    component: RegisterView,
    meta: { title: 'Avalia Ai! - Cadastro' }
  },
  {
    path: '/login',
    name: 'login',
    component: LoginView,
    meta: { title: 'Avalia Ai! - Entrar' }
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
    path: '/usuario/conta',
    name: 'Usuario Conta',
    component: UserAccountView,
    meta: {
      title: 'Avalia Ai! - Minha Conta',
      requiresAuth: true,
      authRole: 'user',
    }
  },
  {
    path: '/usuario/avaliacoes',
    name: 'Usuario Avaliacoes',
    component: UserReviewsView,
    meta: {
      title: 'Avalia Ai! - Minhas Avaliações',
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
  {
    path: '/empresa/conta',
    name: 'Empresa Conta',
    component: AccountView,
    meta: {
      title: 'Avalia Ai! - Minha Conta',
      requiresAuth: true,
      authRole: 'enterprise',
    }
  },
  {
    path: '/empresa/servicos',
    name: 'Empresa Servicos',
    component: ServicesView,
    meta: {
      title: 'Avalia Ai! - Meus Serviços',
      requiresAuth: true,
      authRole: 'enterprise',
    }
  },
  {
    path: '/empresa/avaliacoes',
    name: 'Empresa Avaliacoes',
    component: ReviewsView,
    meta: {
      title: 'Avalia Ai! - Avaliações',
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
      next({ path: '/login?', query: { expired: true, type: authRole } })
    }
  } else {
    next()
  }
})

export default router
