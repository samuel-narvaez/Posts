import { createRouter, createWebHistory } from 'vue-router'

import Home from '../components/Home.vue'
import Configuration from '../components/Configuration.vue'
import Post from '../components/Post.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/configuration',
    name: 'Configuration',
    component: Configuration
  },
  {
    path: '/post',
    name: 'Post',
    component: Post
  }
]


const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router