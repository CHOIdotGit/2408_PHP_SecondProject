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
import CompleteRegistComponent from '../views/components/auth/regist/CompleteRegistComponent.vue';
import ParentsSpendDetailComponent from '../views/components/board/parents-board/ParentsSpendDetailComponent.vue';
import ParentCodeComponent from '../views/components/auth/regist/ParentCodeComponent.vue';
import ParentPrivateFamCodeComponent from '../views/components/auth/private/ParentPrivateFamCodeComponent.vue';
import FamilyPrivateEditComponent from '../views/components/auth/private/FamilyPrivateEditComponent.vue';
import ParentPrivateWithdrawalComponent from '../views/components/auth/private/ParentPrivateWithdrawalComponent.vue';
import ChildPrivateWithdrawalComponent from '../views/components/auth/private/ChildPrivateWithdrawalComponent.vue';

const chkAuth = (to, from, next) => {
    const store = useStore();

    // 로그인 상태 변수
    const authFlg = store.state.auth.authFlg;
    const parentFlg = store.state.auth.parentFlg;
    const childFlg = store.state.auth.childFlg;

    // 비인증용 경로 변수
    const notAuthPath = (to.path === '/' || to.path === '/login' || to.path.startsWith('/regist/'));

    // 부모 URL 목록
    const parentPath = to.path.startsWith('/parent/');

    // 자녀 URL 목록
    const childPath = to.path.startsWith('/child/');

    // 인증 유저가 비인증 페이지에 접근했거나 
    // 부모가 자녀페이지, 자녀가 부모페이지에 접근했는가?
    if((authFlg && notAuthPath) 
    || (authFlg && ((parentFlg && childPath) || (childFlg && parentPath)))
    ) { 
        // 부모면 부모 메인, 자녀면 자녀 메인 페이지로 이동
        parentFlg ? next('/parent/home') : next('/child/home');
    }else if(!authFlg && !notAuthPath) { // 비인증 유저가 인증 페이지에 접근했는가?
        // 로그인 페이지로 이동
        next('/login');
    }else { 
        // 그 이외는 통과
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
    // 부모 코드뷰 페이지
    {
        path: '/regist/parent/code',
        component: ParentCodeComponent,
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
    // 부모 가족 정보 페이지
    {
        path: '/parent/family/info',
        component: ParentPrivateFamCodeComponent,
        beforeEnter: chkAuth,
    },
    // 부모 회원 정보 수정 페이지
    {
        path: '/parent/private/edit',
        component: FamilyPrivateEditComponent,
        beforeEnter: chkAuth,
    },
    // 부모 회원 탈퇴 페이지
    {
        path: '/parent/private/withdrawal',
        component: ParentPrivateWithdrawalComponent,
        beforeEnter: chkAuth,
    },

    // +==================================+
    // +          부모 미션 모음           +
    // +==================================+

    // 부모 미션 리스트 페이지
    {
        path: '/parent/mission/list/:id',
        component: ParentsMissionListComponent,
        beforeEnter: chkAuth,
    },
    // 부모 미션 작성 페이지
    {
        path: '/parent/mission/create/:child_id',
        component: ParentsCreateComponent,
        beforeEnter: chkAuth,
    },
    // 부모 미션 수정 페이지
    {
        path: '/parent/mission/update/:mission_id',
        component: ParentsUpdateComponent,
        beforeEnter: chkAuth,
    },
    // 부모 미션 상세 페이지
    {
        path: '/parent/mission/detail/:id',
        component: ParentsDetailComponent,
        beforeEnter: chkAuth,
    },

    // +==================================+
    // +          부모 지출 모음           +
    // +==================================+

    // 부모 지출 리스트 페이지
    {
        path: '/parent/spend/list/:id',
        component: ParentsSpendListComponent,
        beforeEnter: chkAuth,
    },
    // 부모 지출 상세 페이지
    {
        path: '/parent/spend/detail/:id',
        component: ParentsSpendDetailComponent,
        beforeEnter: chkAuth,
    },

    // +==================================+
    // +          부모 달력/통계           +
    // +==================================+

    // 부모 달력 페이지
    {
        path: '/parent/calendar/:child_id',
        component: ParentsCalendarComponent,
        beforeEnter: chkAuth,
    },
    // 부모 통계 페이지
    {
        path: '/parent/stats/:child_id',
        component: ParentsStatsComponent,
        beforeEnter: chkAuth,
    },
    //**************************************************** */
    // 자녀 페이지 모음
    // 자녀 홈페이지
    {
        path: '/child/home',
        component: ChildCalendarComponent,
        beforeEnter: chkAuth,
    },
    // 자녀 회원 수정 페이지
    {
        path: '/child/private/edit',
        component: FamilyPrivateEditComponent,
        beforeEnter: chkAuth,
    },
    // 자녀 회원 탈퇴 페이지
    {
        path: '/child/private/withdrawal',
        component: ChildPrivateWithdrawalComponent,
        beforeEnter: chkAuth,
    },
    // +==================================+
    // +          자녀 미션 모음           +
    // +==================================+

    // 자녀 미션 리스트 페이지
    {
        path: '/child/mission/list',
        component: ChildMissionListComponent,
        beforeEnter: chkAuth,
    },
    // 자녀 미션 작성 페이지
    {
        path: '/child/mission/create',
        component: ChildCreateComponent,
        beforeEnter: chkAuth,
    },
    // 자녀 미션 상세 페이지
    {
        path: '/child/mission/detail/:id',
        component: ChildDetailComponent,
        beforeEnter: chkAuth,
    },
    // 자녀 미션 수정 페이지
    {
        path: '/child/mission/update/:mission_id',
        component: ChildUpdateComponent,
        beforeEnter: chkAuth,
    },

    // +==================================+
    // +          자녀 지출 모음           +
    // +==================================+

    // 자녀 지출 리스트 페이지
    {
        path: '/child/spend/list',
        component: ChildSpendListComponent,
        beforeEnter: chkAuth,
    },
    // 자녀 지출 작성 페이지
    {
        path: '/child/spend/create',
        component: ChildSpendCreateComponent,
        beforeEnter: chkAuth,
    },
    // 자녀 지출 상세 페이지
    {
        path: '/child/spend/detail/:id',
        component: ChildSpendDetailComponent,
        beforeEnter: chkAuth,
    },
    // 자녀 지출 수정 페이지
    {
        path: '/child/spend/update/:transaction_id',
        component: ChildSpendUpdateComponent,
        beforeEnter: chkAuth,
    },
    
    // +==================================+
    // +          자녀 지출 모음           +
    // +==================================+

    // 자녀 달력 페이지
    {
        path: '/child/calendar',
        component: ChildCalendarComponent,
        beforeEnter: chkAuth,
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