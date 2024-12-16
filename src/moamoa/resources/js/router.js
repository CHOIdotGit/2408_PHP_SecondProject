import { createWebHistory, createRouter } from 'vue-router';
import NotFoundComponent from '../views/components/NotFoundComponent.vue';
import ParentsMissionListComponent from '../views/components/board/parents-board/ParentsMissionListComponent.vue';
import ParentsCalendarComponent from '../views/components/board/parents-board/ParentsCalendarComponent.vue';
import ParentsCreateComponent from '../views/components/board/parents-board/ParentsCreateComponent.vue';
import ParentsUpdateComponent from '../views/components/board/parents-board/ParentsUpdateComponent.vue';
import ParentsDetailComponent from '../views/components/board/parents-board/ParentsDetailComponent.vue';
import ChildCalendarComponent from '../views/components/board/children-board/ChildCalendarComponent.vue';
import ParentsManagementComponent from '../views/components/board/parents-board/ParentsManagementComponent.vue';
import ChildMissionListComponent from '../views/components/board/children-board/ChildMissionListComponent.vue';
import LoginComponent from '../views/components/auth/LoginComponent.vue';
import ChildDetailComponent from '../views/components/board/children-board/ChildDetailComponent.vue';
import ChildUpdateComponent from '../views/components/board/children-board/ChildUpdateComponent.vue';
import ChildCreateComponent from '../views/components/board/children-board/ChildCreateComponent.vue'
import ChildSpendListComponent from '../views/components/board/children-board/ChildSpendListComponent.vue';
import ParentsSpendListComponent from '../views/components/board/parents-board/ParentsSpendListComponent.vue';
import ChildSpendCreateComponent from '../views/components/board/children-board/ChildSpendCreateComponent.vue';
import ChildSpendDetailComponent from '../views/components/board/children-board/ChildSpendDetailComponent.vue';
import ChildSpendUpdateComponent from '../views/components/board/children-board/ChildSpendUpdateComponent.vue';


const routes = [
    {
        // path: '/',
        // redirect: '/parents/boards'
    },
    {
        path: '/login',
        component: LoginComponent
    },
    {
        path: '/parents/home',
        component: ParentsManagementComponent,
    },
    {
        path: '/parents/mission/list',
        component: ParentsMissionListComponent,
    },
    {
        path: '/child/mission/list',
        component: ChildMissionListComponent,
    },
    {
        path: '/parents/calendar',
        component: ParentsCalendarComponent,
    },
    {
        path: '/child/calendar',
        component: ChildCalendarComponent,
    },
    {
        path: '/parents/mission/create',
        component: ParentsCreateComponent,
    },
    {
        path: '/child/mission/create',
        component: ChildCreateComponent,
    },
    {
        path: '/parents/update',
        component: ParentsUpdateComponent,
    },
    {
        path: '/child/update',
        component: ChildUpdateComponent,
    },
    {
        path: '/parents/detail',
        component: ParentsDetailComponent,
    },
    {
        path: '/child/detail',
        component: ChildDetailComponent,
    },
    {
        path: '/child/spend',
        component: ChildSpendListComponent,
    },
    {
        path: '/parents/spend',
        component: ParentsSpendListComponent,
    },
    {
        path: '/child/spend/create',
        component: ChildSpendCreateComponent,
    },
    {
        path: '/child/spend/detail',
        component: ChildSpendDetailComponent,
    },
    {
        path: '/child/spend/update',
        component: ChildSpendUpdateComponent,
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