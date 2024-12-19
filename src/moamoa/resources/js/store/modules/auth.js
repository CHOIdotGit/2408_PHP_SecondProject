/**
 * 어스 스토어 모듈
 * 개인 보안 관련 처리하는 모듈
 * 주로 로그인, 회원가입, 개인정보 관리등을 여기서 작성
 * 
 * 241212 V001 김현석 초기구축
 */

import axios from "../../axios"
import router from "../../router";

export default {
  namespaced: true,

  state: ()=> ({
    // userInfo: {} ,
    // userInfo: localStorage.getItem('userInfo') ? JSON.parse(localStorage.getItem('userInfo')) : {} ,

    // 로그인 상태 관련 ---------------------------------------------------------------------------------------
    authFlg: sessionStorage.getItem('authFlg') ? sessionStorage.getItem('authFlg') : false, // 로그인 상태 체크
    parentFlg: sessionStorage.getItem('parentFlg') ? sessionStorage.getItem('parentFlg') : false, // 로그인 계정이 부모인지 체크
    childFlg: sessionStorage.getItem('childFlg') ? sessionStorage.getItem('childFlg') : false, // 로그인 계정이 자녀인지 체크
    // 에러 메세지 관련 ---------------------------------------------------------------------------------------
    errMsg: null,
    // 모달 관련 ---------------------------------------------------------------------------------------------
    modalText: '',
    modalColor: '',
    // 회원가입 관련 ---------------------------------------------------------------------------------------------
    registInfo : sessionStorage.getItem('registInfo') ? JSON.parse(sessionStorage.getItem('registInfo')) : {},
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
    setModalText(state, modalText) {
      state.modalText = modalText;
    },
    setModalColor(state, modalColor) {
      state.modalColor = modalColor;
    },
    setRegistInfo(state, registInfo) {
      state.registInfo = registInfo;
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
        // 세션에 담아둔 유저 정보를 쓸 예정
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
        // console.error(err);
        // 422: 유효성 검사 실패, 401: 일치 아이디 or 비밀번호 없음 
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

        // --------------------------------- V001 del start --------------------------------------------
        // 백에서 만든 새 CSRF 토큰을 담기
        // const csrfToken = res.data.csrf_token;

        // 쿠키에 갱신된 데이터를 세팅
        // document.cookie = `XSRF-TOKEN=${csrfToken}; path=/`;

        // 새로운 CSRF 토큰을 meta 태그에 세팅
        // document.querySelector('meta[name="csrf-token"]').setAttribute('content', csrfToken);

        // axios 헤더에 CSRF 토큰을 갱신
        // axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
        // --------------------------------- V001 del end ----------------------------------------------
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
    },

    /**
     * 아이디 중복 검사 처리
     * 
     * @param {*} context
     * @param {*} account
     */
    chkAccount(context, account) {
      // console.log('값: ' + (account === '' ? 'yes' : account));

      // 입력한 아이디 값이 있을 시
      if(account !== '') {
        const url = '/api/auth/chkAccount/'+ account;
        axios.get(url)
        .then(res => {
          // console.log(res.data);
          context.commit('setModalText', res.data.msg);
          context.commit('setModalColor', res.data.color);
        })
        .catch(err => {
          // console.error(err);
        });
      }else { // 아무것도 입력 안할 시
        context.commit('setModalText', '아이디를 입력해주세요.');
        context.commit('setModalColor', 'color-red');
      }
    },

    /**
     * 회원가입 처리
     * 부모, 자녀와 같이 처리할 예정
     * 
     * @param {*} context
     * @param {*} registInfo
     */
    saveRegistInfo(context, registInfo) {
      const url = '/api/auth/saveRegistInfo';
      const config = {
        headers: {
          // 파일 전송을 할수도 있기에 멀티파트폼 데이터 세팅
          'Content-Type': 'multipart/form-data',
        }
      };

      // 전송용 폼데이터 생성
      const data = new FormData();

      // 입력된 프로필 사진 정보가 있다면
      if(registInfo.profile) {
        data.append('profile', registInfo.profile);
      }
      
      // 입력된 닉네임 정보가 있다면
      if(registInfo.nick_name) {
        data.append('nick_name', registInfo.nick_name);
      }
      data.append('name', registInfo.name);
      data.append('account', registInfo.account);
      data.append('password', registInfo.password);
      data.append('password_chk', registInfo.password_chk);
      data.append('email', registInfo.email);
      data.append('tel', registInfo.tel);
      data.append('auth', registInfo.auth);

      axios.post(url, data, config)
      .then(res => {
        // console.log(res.data);

        // 세션에 데이터를 세팅
        sessionStorage.setItem('registInfo', JSON.stringify(res.data.data));
        context.commit('setRegistInfo', res.data.data);

        // 지정된 다음 페이지로 이동
        router.replace(res.data.redirect_to);
      })
      .catch(err => {
        // console.error(err);
      });
    },
  },

  getters: {

  },
}