import { createWebHistory, createRouter } from 'vue-router';
import NotFoundComponent from '../views/components/NotFoundComponent.vue';
import ChildListManagerComponent from '../views/components/board/ChildListManagerComponent.vue';

const routes = [
    {
        path: '/',
        redirect: '/boards'
    },
    // {
    //     path: '/login',
    //     component: 
    // },
    {
        path: '/boards',
        component: ChildListManagerComponent,
    },
    {
        path: '/:catchAll(.*)',
        component: NotFoundComponent,
    },

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    next();
});

export default router;