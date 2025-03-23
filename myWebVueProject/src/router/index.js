import { createRouter, createWebHistory } from 'vue-router';
import StudentList from '../components/StudentList.vue';
import StudentDetails from '../components/StudentDetails.vue';
import Login from '../components/Login.vue';

const routes = [
  {
    path: '/',
    name: 'Login',
    component: Login
  },
  {
    path: '/students',
    name: 'StudentList',
    component: StudentList
  },
  {
    path: '/student/:id',
    name: 'StudentDetails',
    component: StudentDetails,
    props: true
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router; 