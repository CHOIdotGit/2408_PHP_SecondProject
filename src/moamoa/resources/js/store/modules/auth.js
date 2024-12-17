import axios from "../../axios"
import router from "../../router";

export default {
  namespaced: true,

  state: ()=> ({
    // userInfo: {} ,
    // userInfo: localStorage.getItem('userInfo') ? JSON.parse(localStorage.getItem('userInfo')) : {} ,
    authFlg: sessionStorage.getItem('authFlg') ? sessionStorage.getItem('authFlg') : false, // 로그인 상태 체크
    parentFlg: sessionStorage.getItem('parentFlg') ? sessionStorage.getItem('parentFlg') : false, // 로그인 계정이 부모인지 체크
    childFlg: sessionStorage.getItem('childFlg') ? sessionStorage.getItem('childFlg') : false, // 로그인 계정이 자녀인지 체크
    errMsg: null,
  }),

  mutations: {
    // setUserInfo(state, userInfo) {
    //   state.userInfo = userInfo;
    // },
    setAuthFlg(state, authFlg) {
      state.authFlg = authFlg;
    },
    setParentFlg(state, parentFlg) {
      state.parentFlg = parentFlg;
    },
    setChildFlg(state, childFlg) {
      state.childFlg = childFlg;
    },
    setErrMsg(state, errMsg) {
      state.errMsg = errMsg;
    },
  },

  actions: {

    /**
     * 로그인 처리
     * 
     * @param {*} context
     * @param {*} userInfo
     */
    login(context, userInfo) {
      const url = '/api/login';
      const data = JSON.stringify(userInfo);

      axios.post(url, data)
      .then(res => {

        // localStorage.setItem('userInfo', JSON.stringify(res.data.user));
        sessionStorage.setItem('authFlg', true);
        context.commit('setAuthFlg', true);

        // 로그인한 계정에 가족코드가 있으면 부모이고, 없으면 자녀
        sessionStorage.setItem('parentFlg', res.data.user.family_code ? true : false);
        context.commit('setParentFlg', res.data.user.family_code ? true : false);
        
        sessionStorage.setItem('childFlg', res.data.user.family_code ? false : true);
        context.commit('setChildFlg', res.data.user.family_code ? false : true);

        // context.commit('setUserInfo', res.data.user);
        // console.log(res.data.user);

        router.replace(res.data.redirect_to);
      })
      .catch(err => {
        console.error(err);
        // 400: 유효성 검사 실패, 401: 일치 아이디 or 비밀번호 없음 
        let errMsg = [];
        const errorData = err.response.data;
        
        if(err.response.status === 422) {
          // 유효성 검사 에러
          if(errorData.data.account) { // 계정 틀림
            errMsg.push(errorData.data.account[0]);
          }

          if(errorData.data.password) { // 비밀번호 틀림
            errMsg.push(errorData.data.password[0]);
          }
        }else if(err.response.status === 401) {
          // 비밀번호 에러
          errMsg.push(errorData.msg);
        }else {
          errMsg.push('예기치 못한 오류 발생.');
        }

        context.commit('setErrMsg', errMsg);
        // console.log(errorData);
        // alert(errMsg);
      });
    },

    /**
     * 로그아웃 처리
     * 
     * @param context
     */
    logout(context) {
      const url = '/api/logout';

      axios.post(url)
      .then(res => {
        // console.log(res.data);
        // alert('로그아웃 완료');

        // 백에서 만든 새 CSRF 토큰을 담기
        const csrfToken = res.data.csrf_token;

        // 쿠키에 갱신된 데이터를 세팅
        // document.cookie = `XSRF-TOKEN=${csrfToken}; path=/`;

        // 새로운 CSRF 토큰을 meta 태그에 세팅
        document.querySelector('meta[name="csrf-token"]').setAttribute('content', csrfToken);

        // axios 헤더에 CSRF 토큰을 갱신
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
      })
      .catch(err => {
        // console.error(err);
        // alert('로그아웃 실패');
      })
      .finally(() => {
        // 세션 스토리지 초기화
        sessionStorage.clear();
  
        // state 초기화
        context.commit('setAuthFlg', false);
        context.commit('setParentFlg', false);
        context.commit('setChildFlg', false);
        // context.commit('setUserInfo', {});

        router.replace('/login');
      });
    }
  },

  getters: {

  },
}