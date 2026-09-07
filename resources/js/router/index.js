import { createRouter, createWebHistory } from 'vue-router'
import Login from '../pages/Login.vue'
import Dashboard from '../pages/Dashboard.vue'
import DatasetList from '../pages/DatasetList.vue'
import DatasetUpload from '../pages/DatasetUpload.vue'
import UserManagement from '../pages/UserManagement.vue'

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: Login,
        meta: { requiresAuth: false }
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
    },
    {
        path: '/datasets',
        name: 'DatasetList',
        component: DatasetList,
        meta: { requiresAuth: true }
    },
    {
        path: '/datasets/upload',
        name: 'DatasetUpload',
        component: DatasetUpload,
        meta: { requiresAuth: true, roles: ['admin_opd', 'admin_portal'] }
    },
    {
        path: '/users',
        name: 'UserManagement',
        component: UserManagement,
        meta: { requiresAuth: true, roles: ['admin_portal'] }
    },
    {
        path: '/',
        redirect: '/dashboard'
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token')
    const user = JSON.parse(localStorage.getItem('user') || '{}')

    if (to.meta.requiresAuth && !token) {
        next('/login')
    } else if (to.meta.roles && !to.meta.roles.includes(user.role)) {
        next('/dashboard')
    } else {
        next()
    }
})

export default router
