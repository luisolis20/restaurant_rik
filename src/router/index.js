import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import DashUiView from '../views/DashUiView.vue'
import StockPlatosAllView from '../views/Op_Plato/StockPlatosAllView.vue'
import CategoriaAllView from '../views/Mantenimiento/Op_Categoria/CategoriaAllView.vue'
import UsuariosAllView from '../views/Mantenimiento/Op_Users/UsuariosAllView.vue'
import RolesAllView from '../views/Mantenimiento/Op_Roles/RolesAllView.vue'
import PlatosAllView from '../views/Mantenimiento/Op_Plato/PlatosAllView.vue'
import PageHomeView from '../views/Home/PageHomeView.vue'



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
    path: '/mantenimiento/platos',
    name: 'platos',
    component: PlatosAllView
  },
  {
    path: '/mantenimiento/categorias',
    name: 'categorias',
    component: CategoriaAllView
  },
  {
    path: '/mantenimiento/usuarios',
    name: 'usuarios',
    component: UsuariosAllView
  },
  {
    path: '/mantenimiento/roles',
    name: 'roles',
    component: RolesAllView
  },
  {
    path: '/home',
    name: 'pagehome',
    component: PageHomeView
  },
  {
    path: '/stock/platos',
    name: 'stockplatos',
    component: StockPlatosAllView
  },


  
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router
