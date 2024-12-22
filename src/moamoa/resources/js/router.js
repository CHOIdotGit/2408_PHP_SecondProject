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
import ParentsStatsComponent from '../views/components/board/parents-board/ParentsStatsComponent.vue';
import { useStore } from "vuex";
import SelectRegistComponent from '../views/components/auth/regist/SelectRegistComponent.vue';
import ParentRegistComponent from '../views/components/auth/regist/ParentRegistComponent.vue';
import ChildRegistComponent from '../views/components/auth/regist/ChildRegistComponent.vue';
import ChildHomeComponent from '../views/components/board/children-board/ChildHomeComponent.vue';
import ParentRegistCodeComponent from '../views/components/auth/regist/ParentRegistCodeComponent.vue';
import CompleteRegistComponent from '../views/components/auth/regist/CompleteRegistComponent.vue';

const chkAuth = (to, from, next) => {
    const store = useStore();

    // 로그인 상태 변수
    const authFlg = store.state.auth.authFlg;
    const parentFlg = store.state.auth.parentFlg;
    const childFlg = store.state.auth.childFlg;

    // 비인증용 경로 변수
    const notAuthPath = (to.path === '/' || to.path === '/login' || to.path === '/regist/main' 
        || to.path === '/regist/parent' || to.path === '/regist/child' || to.path === '/regist/parent/code'
        || to.path === '/regist/complete'
    );

    if(authFlg && notAuthPath) { // 인증 유저가 비인증 페이지에 접근했는가?
        if(parentFlg) { // 그 인증 유저가 부모인가?
            next('/parent/home');
        }else if(childFlg) { // 아니면 자녀인가?
            next('/child/home');
        }
    }else if(!authFlg && !notAuthPath) { // 비인증 유저가 인증 페이지에 접근했는가?
        next('/login');
    }else { // 그 이외는 통과
        next();
    }
};

const routes = [
    // 기본 페이지 -> 로그인 페이지
    {
        path: '/',
        redirect: '/login',
        beforeEnter: chkAuth,
    },
    // 로그인 페이지
    {
        path: '/login',
        component: LoginComponent,
        beforeEnter: chkAuth,
    },
    // 회원가입 선택 페이지
    {
        path: '/regist/main',
        component: SelectRegistComponent,
        beforeEnter: chkAuth,
    },
    // 부모 회원가입 정보입력 페이지
    {
        path: '/regist/parent',
        component: ParentRegistComponent,
        beforeEnter: chkAuth,
    },
    // 부모 회원가입 코드뷰 페이지
    {
        path: '/regist/parent/code',
        component: ParentRegistCodeComponent,
        beforeEnter: chkAuth,
    },
    // 자녀 회원가입 정보입력 페이지
    {
        path: '/regist/child',
        component: ChildRegistComponent,
        beforeEnter: chkAuth,
    },
    // 회원가입 완료 페이지
    {
        path: '/regist/complete',
        component: CompleteRegistComponent,
        beforeEnter: chkAuth,
    },
    // 부모 페이지 모음
    // 홈페이지
    {
        path: '/parent/home',
        component: ParentsManagementComponent,
        beforeEnter: chkAuth,
    },
    // 부모 미션 리스트 페이지
    {
        path: '/parent/mission/list/:id',
        component: ParentsMissionListComponent,
    },
    // 부모 미션 작성 페이지
    {
        path: '/parent/mission/create',
        component: ParentsCreateComponent,
    },
    // 부모 미션 수정 페이지
    {
        path: '/parent/mission/update',
        component: ParentsUpdateComponent,
    },
    // 부모 미션 상세 페이지
    {
        path: '/parent/mission/detail',
        component: ParentsDetailComponent,
    },
    // 부모 지출 리스트 페이지
    {
        path: '/parent/spend/list',
        component: ParentsSpendListComponent,
    },
    // 부모 달력 페이지
    {
        path: '/parent/calendar',
        component: ParentsCalendarComponent,
    },
    // 부모 통계 페이지
    {
        path: '/parent/stats',
        component: ParentsStatsComponent,
    },
    // 자녀 페이지 모음
    // 자녀 홈페이지
    {
        path: '/child/home',
        component: ChildHomeComponent,
        beforeEnter: chkAuth,
    },
    // 자녀 미션 리스트 페이지
    {
        path: '/child/mission/list',
        component: ChildMissionListComponent,
    },
    // 자녀 미션 작성 페이지
    {
        path: '/child/mission/create',
        component: ChildCreateComponent,
    },
    // 자녀 미션 상세 페이지
    {
        path: '/child/mission/detail',
        component: ChildDetailComponent,
    },
    // 자녀 미션 수정 페이지
    {
        path: '/child/mission/update',
        component: ChildUpdateComponent,
    },
    // 자녀 지출 리스트 페이지
    {
        path: '/child/spend/list',
        component: ChildSpendListComponent,
    },
    // 자녀 지출 작성 페이지
    {
        path: '/child/spend/create',
        component: ChildSpendCreateComponent,
    },
    // 자녀 지출 상세 페이지
    {
        path: '/child/spend/detail',
        component: ChildSpendDetailComponent,
    },
    // 자녀 지출 수정 페이지
    {
        path: '/child/spend/update',
        component: ChildSpendUpdateComponent,
    },
    // 자녀 달력 페이지
    {
        path: '/child/calendar',
        component: ChildCalendarComponent,
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

router.beforeEach((to, from, next) => {
    next();
});

export default router;