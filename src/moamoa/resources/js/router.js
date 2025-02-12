import { createWebHistory, createRouter } from 'vue-router';
import { useStore } from "vuex";
import axios from './axios';
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
import ChildHomeComponent from '../views/components/board/children-board/ChildHomeComponent.vue';
import ParentsSpendDetailComponent from '../views/components/board/parents-board/ParentsSpendDetailComponent.vue';
import ParentPrivateFamCodeComponent from '../views/components/auth/private/ParentPrivateFamCodeComponent.vue';
import FamilyPrivateEditComponent from '../views/components/auth/private/FamilyPrivateEditComponent.vue';
import ParentPrivateWithdrawalComponent from '../views/components/auth/private/ParentPrivateWithdrawalComponent.vue';
import ChildPrivateWithdrawalComponent from '../views/components/auth/private/ChildPrivateWithdrawalComponent.vue';
import FamilyPrivateChangePasswordComponent from '../views/components/auth/private/FamilyPrivateChangePasswordComponent.vue';
import ChildPrivateMatchingComponent from '../views/components/auth/private/ChildPrivateMatchingComponent.vue';
import LoginTestComponent from '../views/components/auth/LoginTestComponent.vue';
import ParentsMoaBankComponent from '../views/components/bank/parent-bank/ParentsMoaBankComponent.vue';
import ChildBankProductComponent from '../views/components/bank/child-bank/ChildBankProductComponent.vue';
import ChildBankProductRegisterComponent from '../views/components/bank/child-bank/ChildBankProductRegisterComponent.vue';
import ChildMoaBankComponent from '../views/components/bank/child-bank/ChildMoaBankComponent.vue';
import ChildBankBookComponent from '../views/components/bank/child-bank/ChildBankBookComponent.vue';
import ParentsBankBookComponent from '../views/components/bank/parent-bank/ParentsBankBookComponent.vue';
import ParentsPointComponentCopy from '../views/components/bank/parent-bank/ParentsPointComponent.vue';
import RegistFormComponent from '../views/components/auth/regist/RegistFormComponent.vue';
import RegistAgreeComponent from '../views/components/auth/regist/RegistAgreeComponent.vue';
import RegistSelectComponent from '../views/components/auth/regist/RegistSelectComponent.vue';
import RegistCompleteComponent from '../views/components/auth/regist/RegistCompleteComponent.vue';
import ChildBankProductDetailComponent from '../views/components/bank/child-bank/ChildBankProductDetailComponent.vue';
import PrivateIdentComponent from '../views/components/auth/private/PrivateIdentComponent.vue';
import PrivateEditComponent from '../views/components/auth/private/PrivateEditComponent.vue';

const chkAuth = (to, from, next) => {
    
    const store = useStore();

    // 로그인 상태 변수
    const authFlg = store.state.auth.authFlg;
    const parentFlg = store.state.auth.parentFlg;
    const childFlg = store.state.auth.childFlg;

    // 로그인 상태일 때만 세션 체크 실행
    if(authFlg) {
        // 세션 상태 체크
        axios.get('/api/check-session')
        .then(res => {
            // 세션이 만료되었으면 로그아웃 처리
            if(!res.data.isExpired) {
                store.dispatch('auth/logout');
                next('/login');
                return;
            }
        });
    }

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
    // 회원가입 그룹
    {
        path: '/regist',
        children: [

            // 사용자 계약 동의
            {
                path: 'agree',
                component: RegistAgreeComponent,
                beforeEnter: (to, from, next) => {
                    // 초기 진입, 로그인 페이지, 회원 선택에서 뒤로올 경우
                    if(from.path === '/' || from.path === '/login' || from.path === '/regist/select') {
                        sessionStorage.setItem('registPage', 'agree');
                        chkAuth(to, from, next);
                    }else {
                        next('/:catchAll(.*)');
                    }
                },
            },

            // 회원 유형 선택
            {
                path: 'select',
                component: RegistSelectComponent,
                beforeEnter: (to, from, next) => {
                    const currentPage = sessionStorage.getItem('registPage');

                    // agree 페이지에서 정상적으로 넘어온 경우
                    if(from.path === '/regist/agree' || from.path === '/regist/parent' || from.path === '/regist/child') {
                        sessionStorage.setItem('registPage', 'select');
                        chkAuth(to, from, next);
                    // 새로고침하거나 뒤로가기로 온 경우
                    }else if(from.path === '/' && (currentPage === 'select' || currentPage === 'form')) {
                        chkAuth(to, from, next);
                    }
                    // 아니면 404
                    else {
                        next('/:catchAll(.*)');
                    }
                },
            },

            // 회원가입 폼
            {
                path: ':type(parent|child)',
                component: RegistFormComponent,
                beforeEnter: (to, from, next) => {
                    const currentPage = sessionStorage.getItem('registPage');

                    // select 페이지에서 정상적으로 넘어온 경우
                    if(from.path === '/regist/select') {
                        sessionStorage.setItem('registPage', 'form');
                        chkAuth(to, from, next);
                    }
                    // 새로고침할 경우
                    else if(from.path === '/' && currentPage === 'form') {
                        chkAuth(to, from, next);
                    }
                    // 아니면 404
                    else {
                        next('/:catchAll(.*)');
                    }
                },
            },

            // 가입완료
            {
                path: 'complete/:type(parent|child)',
                component: RegistCompleteComponent,
                beforeEnter: (to, from, next) => {
                    const currentPage = sessionStorage.getItem('registPage');

                    // 회원가입 폼에서 정상적으로 넘어온 경우
                    if(from.path === '/regist/parent' || from.path === '/regist/child') {
                        sessionStorage.setItem('registPage', 'complete');
                        chkAuth(to, from, next);
                    }
                    // 새로고침의 경우
                    else if(from.path === '/' && currentPage === 'complete') {
                        chkAuth(to, from, next);
                    }
                    // 아니면 404
                    else {
                        next('/:catchAll(.*)');
                    }
                },
            },
        ], 
    },

    // 공용 개인정보 그룹
    {
        path: '/:type(parent|child)/private',
        children: [

            // 본인 확인 페이지
            {
                path: 'ident',
                component: PrivateIdentComponent,
                // beforeEnter: chkAuth,
            },

            // 개인정보 수정 페이지
            {
                path: 'edit',
                component: PrivateEditComponent,
                // beforeEnter: chkAuth,
            },
        ],
    },

    // --------------------------- V001 del start -----------------------------
    // 회원가입 선택 페이지
    // {
    //     path: '/regist/main',
    //     component: SelectRegistComponent,
    //     beforeEnter: chkAuth,
    // },
    // 부모 회원가입 정보입력 페이지
    // {
    //     path: '/regist/parent',
    //     component: ParentRegistComponent,
    //     beforeEnter: chkAuth,
    // },
    // 부모 코드뷰 페이지
    // {
    //     path: '/regist/parent/code',
    //     component: ParentCodeComponent,
    //     beforeEnter: chkAuth,
    // },
    // 자녀 회원가입 정보입력 페이지
    // {
    //     path: '/regist/child',
    //     component: ChildRegistComponent,
    //     beforeEnter: chkAuth,
    // },
    // 회원가입 완료 페이지
    // {
    //     path: '/regist/complete',
    //     component: CompleteRegistComponent,
    //     beforeEnter: chkAuth,
    // },
    // 부모 회원 정보 수정 페이지
    // {
    //     path: '/parent/private/edit',
    //     component: FamilyPrivateEditComponent,
    //     beforeEnter: chkAuth,
    // },
    // 자녀 회원 수정 페이지
    // {
    //     path: '/child/private/edit',
    //     component: FamilyPrivateEditComponent,
    //     beforeEnter: chkAuth,
    // },
    // --------------------------- V001 del end -----------------------------

    // 부모 페이지 모음 *********************************** //
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
    // 부모 회원 탈퇴 페이지
    {
        path: '/parent/private/withdrawal',
        component: ParentPrivateWithdrawalComponent,
        beforeEnter: chkAuth,
    },
    // 부모 회원 비밀번호 변경 페이지
    {
        path: '/parent/private/password',
        component: FamilyPrivateChangePasswordComponent,
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
        path: '/parent/mission/detail/:mission_id',
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

    // +==================================+
    // +          부모 은행 페이지         +
    // +==================================+

    // 부모 통장 페이지
    {
        path: '/parent/moabank/:child_id',
        component: ParentsMoaBankComponent,
        beforeEnter: chkAuth,
    },

    // 부모 자녀 적금 페이지
    {
        path: '/parent/bankbook/:child_id',
        component: ParentsBankBookComponent,
        beforeEnter: chkAuth,
    },

    // 부모 자녀 포인트 페이지
    {
        path: '/parent/point/:child_id',
        component: ParentsPointComponentCopy,
        beforeEnter: chkAuth,
    },


    // 자녀 페이지 모음 ************************************************* */
    // 자녀 홈페이지
    {
        path: '/child/home',
        // component: ChildCalendarComponent,
        component: ChildHomeComponent,
        beforeEnter: chkAuth,
    },
    // 자녀 회원 탈퇴 페이지
    {
        path: '/child/private/withdrawal',
        component: ChildPrivateWithdrawalComponent,
        beforeEnter: chkAuth,
    },
    // 자녀 회원 비밀번호 변경 페이지
    {
        path: '/child/private/password',
        component: FamilyPrivateChangePasswordComponent,
        beforeEnter: chkAuth,
    },
    // 자녀 부모 재매칭 페이지
    {
        path: '/child/private/rematching',
        component: ChildPrivateMatchingComponent,
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

    // +==================================+
    // +          자녀 은행 페이지         +
    // +==================================+
    // 자녀 은행 페이지
    {
        path: '/child/moabank',
        component: ChildMoaBankComponent,
        beforeEnter: chkAuth,
    },

    // 적금 상품 몰 상세 페이지
    {
        path: '/moabank/product',
        component: ChildBankProductComponent,
        beforeEnter: chkAuth,
    },

    // 적금 상품 몰 상세 페이지
    {
        path: '/moabank/product/detail/:id',
        component: ChildBankProductDetailComponent,
        beforeEnter: chkAuth,
    },

    // 적금 상품 가입 페이지
    {
        path: '/moabank/product/regist/:id',
        component: ChildBankProductRegisterComponent,
        beforeEnter: chkAuth,
    },
    
    // 자녀 통장 페이지
    {
        path: '/child/bankbook',
        component: ChildBankBookComponent,
        beforeEnter: chkAuth,
    },

    // +==================================+
    // +          테스트 하는 중           +
    // +==================================+
    {
        path: '/test/login',
        component: LoginTestComponent,
    },


    // +==================================+
    // +          페이지 못찾음            +
    // +==================================+
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
    // 회원가입 스토리지 체크
    if(sessionStorage.getItem('registPage') && (from.path.startsWith('/regist/') && !to.path.startsWith('/regist/'))) {
        // 해당 페이지에서 벗어났을 경우 스토리지 삭제
        sessionStorage.removeItem('registPage');

        // 추가 검사
        if(sessionStorage.getItem('famCode')) {
            sessionStorage.removeItem('famCode');
        }
    }

    next();
});

export default router;