import { createRouter, createWebHistory } from 'vue-router';
import Home from '@/views/Home.vue';
import ClienteList from '@/views/ClienteList.vue';
import ClienteForm from '@/views/ClienteForm.vue';

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home
  },
  {
    path: '/clientes',
    name: 'clientes',
    component: ClienteList
  },
  {
    path: '/clientes/novo',
    name: 'cliente-novo',
    component: ClienteForm
  },
  {
    path: '/clientes/:id/editar',
    name: 'cliente-editar',
    component: ClienteForm,
    props: true
  }
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
});

export default router;