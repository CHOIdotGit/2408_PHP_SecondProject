import { createWebHistory, createRouter } from 'vue-router';
import NotFoundComponent from '../views/components/NotFoundComponent.vue';
import ChildListManagerComponent from '../views/components/board/ChildListManagerComponent.vue';
import ParentsCreateComponent from '../views/components/board/ParentsCreateComponent.vue';


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
        path: '/parentsboards',
        component: ParentsCreateComponent,
    },
    {
        path: '/:catchAll(.*)',
        component: NotFoundComponent,
    }

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;