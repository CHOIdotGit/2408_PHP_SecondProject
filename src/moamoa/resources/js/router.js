import { createWebHistory, createRouter } from 'vue-router';
import NotFoundComponent from '../views/components/NotFoundComponent.vue';
import ChildListManagerComponent from '../views/components/board/ChildListManagerComponent.vue';
import ParentsCalendarComponent from '../views/components/board/ParentsCalendarComponent.vue';
import ParentsCreateComponent from '../views/components/board/ParentsCreateComponent.vue';
import ParentsUpdateComponent from '../views/components/board/ParentsUpdateComponent.vue';
import ParentsDetailComponent from '../views/components/board/ParentsDetailComponent.vue';


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
        // parentsBoards로 변경 예정
    },
    {
        path: '/parentsMissionList',
        component: ParentsMissionListComponent,
        // parentsBoards로 변경 예정
    },
    {
        path: '/pcalendar',
        component: ParentsCalendarComponent,
    },
    {
        path: '/parentsMissionCreate',
        component: ParentsCreateComponent,
    },
    {
        path: '/parentsupdate',
        component: ParentsUpdateComponent,
    },
    {
        path: '/parentsdetail',
        component: ParentsDetailComponent,
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