import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import DashUiView from '../views/DashUiView.vue'
import PlatosAllView from '../views/Op_Plato/PlatosAllView.vue'
import CategoriaAllView from '../views/Op_Categoria/CategoriaAllView.vue'
import UsuariosAllView from '../views/Op_Users/UsuariosAllView.vue'



const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView
  },
  {
    path: '/login',
    name: 'login',
    component: LoginView
  }
  ,
  {
    path: '/admin',
    name: 'admin',
    component: DashUiView
  },
  {
    path: '/platos',
    name: 'platos',
    component: PlatosAllView
  },
  {
    path: '/categorias',
    name: 'categorias',
    component: CategoriaAllView
  },
  {
    path: '/usuarios',
    name: 'usuarios',
    component: UsuariosAllView
  },


  
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
