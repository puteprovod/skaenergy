import {createRouter, createWebHistory} from 'vue-router'

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'index',
            component: () => import('../views/gbook/Index.vue'),
            meta: { title: "Гостевая книга болельщиков ФК СКА-Хабаровск" }
        },
        {
            path: '/registration',
            name: 'registration',
            component: () => import('../views/Registration.vue'),
            meta: { title: "Регистрация | Гостевая книга болельщиков ФК СКА-Хабаровск" }
        },
        {
            path: '/login',
            name: 'login',
            component: () => import('../views/Login.vue'),
            meta: { title: "Логин | Гостевая книга болельщиков ФК СКА-Хабаровск" }
        },
        {
            path: '/forgot_password',
            name: 'forgot_password',
            meta: { title: "Восстановление пароля | Гостевая книга болельщиков ФК СКА-Хабаровск" },
            component: () => import('../views/ForgotPassword.vue')
        },
        {
            path: '/reset-password/:token',
            name: 'reset_password',
            meta: { title: "Cброс пароля | Гостевая книга болельщиков ФК СКА-Хабаровск" },
            component: () => import('../views/ResetPassword.vue')
        },
        {
            path: '/profile',
            name: 'profile',
            meta: { title: "Профиль пользователя | Гостевая книга болельщиков ФК СКА-Хабаровск" },
            component: () => import('../views/Profile.vue')
        },
        {
            path: '/gbook/post/:id',
            name: 'post',
            meta: { title: "Просмотр поста | Гостевая книга болельщиков ФК СКА-Хабаровск" },
            component: () => import('../views/Gbook/Post.vue')
        }
    ]
})

export default router
