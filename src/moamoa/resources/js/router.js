import { createWebHistory, createRouter } from 'vue-router';
import NotFoundComponent from '../views/components/NotFoundComponent.vue';
import ParentsMissionListComponent from '../views/components/board/parents-board/ParentsMissionListComponent.vue';
import ParentsCalendarComponent from '../views/components/board/parents-board/ParentsCalendarComponent.vue';
import ParentsCreateComponent from '../views/components/board/parents-board/ParentsCreateComponent.vue';
import ParentsUpdateComponent from '../views/components/board/parents-board/ParentsUpdateComponent.vue';
import ParentsDetailComponent from '../views/components/board/parents-board/ParentsDetailComponent.vue';
import ChildCalendarComponent from '../views/components/board/children-board/ChildCalendarComponent.vue';
import ParentsManagementComponent from '../views/components/board/parents-board/ParentsManagementComponent.vue';


const routes = [
    {
        path: '/',
        redirect: '/parents/boards'
    },
    // {
    //     path: '/login',
    //     component: 
    // },
    {
        path: '/parents/boards',
        component: ParentsManagementComponent,
        // parentsBoards로 변경 예정
    },
    {
        path: '/parents/mission/list',
        component: ParentsMissionListComponent,
    },
    {
        path: '/parents/calendar',
        component: ParentsCalendarComponent,
    },
    {
        path: '/parents/mission/create',
        component: ParentsCreateComponent,
    },
    {
        path: '/parents/update',
        component: ParentsUpdateComponent,
    },
    {
        path: '/parents/detail',
        component: ParentsDetailComponent,
    },
    {
        path: '/child/calendar',
        component: ChildCalendarComponent,
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