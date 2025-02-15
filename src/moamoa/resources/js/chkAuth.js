import { useStore } from "vuex";
import axios from './axios';

const chkAuth = (to, from, next) => {
    
  const store = useStore();

  // 로그인 상태 변수
  const authFlg = store.state.auth.authFlg;
  const parentFlg = store.state.auth.parentFlg;
  const childFlg = store.state.auth.childFlg;

  // 세션 상태 체크
  axios.get('/api/check-session')
  .then(res => {
    // 세션이 만료되었으면 로그아웃 처리
    if(authFlg && !res.data.isSession) {
      store.dispatch('auth/logout');
    }

    // 비로그인 상태인데 세션정보가 있으면 재로그인 처리
    else if(!authFlg && res.data.isSession) {
      store.dispatch('auth/redirectLogin', res.data.isParent ? 'parent' : 'child');
    }
  });

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

export default chkAuth;