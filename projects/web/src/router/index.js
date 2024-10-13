import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      name: 'home',
      path: '/',
      component: () => import('../views/HomeView.vue'),
    },
    {
      name: 'upload',
      path: '/upload',
      component: () => import('../views/UploadView.vue'),
    },
    {
      name: 'uploading',
      path: '/uploading',
      component: () => import('../views/UploadingView.vue'),
    },
    {
      name: 'uploaded',
      path: '/uploaded',
      component: () => import('../views/UploadedView.vue'),
    }
  ]
});

export default router
