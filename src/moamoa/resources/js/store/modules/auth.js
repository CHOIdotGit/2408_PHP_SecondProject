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
    // 로그인 상태 관련 ---------------------------------------------------------------------------------------
    authFlg: sessionStorage.getItem('authFlg') ? true : false, // 로그인 상태 체크
    parentFlg: sessionStorage.getItem('parentFlg') ? true : false, // 로그인 계정이 부모인지 체크
    childFlg: sessionStorage.getItem('childFlg') ? true : false, // 로그인 계정이 자녀인지 체크
    // 에러 메세지 관련 ---------------------------------------------------------------------------------------
    errMsg: null,
    // 모달 관련 ---------------------------------------------------------------------------------------------
    modalText: '',
    modalColor: '',
    // 회원가입 관련 ---------------------------------------------------------------------------------------------
    registFlg: true,
    parentInfo: {},
    familyCode: null,
    preview: {
      imgFlg: false,
      url: null,
    },
  }),

  mutations: {
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
    setRegistFlg(state, registFlg) {
      state.registFlg = registFlg;
    },
    setParentInfo(state, parentInfo) {
      state.parentInfo = parentInfo;
    },
    setFamilyCode(state, familyCode) {
      state.familyCode = familyCode;
    },
    setPreviewFlg(state, flg) {
      state.preview.imgFlg = flg;
    },
    setPreviewUrl(state, preview) {
      state.preview.url = preview;
    },
    
    // 가입정보 초기화
    resetRegist(state) {
      state.registFlg = true;
      state.parentInfo = {};
      state.familyCode = null;
      state.preview = {
        imgFlg: false,
        url: null,
      };
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
        // context.commit('setUserInfo', res.data.user);
        
        sessionStorage.setItem('authFlg', true);
        context.commit('setAuthFlg', true);

        // 로그인한 계정에 가족코드가 있으면 부모이고, 없으면 자녀
        sessionStorage.setItem('parentFlg', res.data.user.family_code ? true : false);
        context.commit('setParentFlg', res.data.user.family_code ? true : false);
        
        sessionStorage.setItem('childFlg', res.data.user.family_code ? false : true);
        context.commit('setChildFlg', res.data.user.family_code ? false : true);

        // console.log(res.data.user);

        router.replace(res.data.redirect_to);
      })
      .catch(err => {
        // TODO: 예외처리 헤야함
        // console.error(err);
        // 422: 유효성 검사 실패, 401: 일치 아이디 or 비밀번호 없음 
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
        // 수동 CSRF 토큰 발행
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
    storeUser(context, registInfo) {
      const url = '/api/auth/storeUser';
      const config = {
        headers: {
          // 파일 생성을 할수도 있기에 멀티파트폼 데이터 세팅
          'Content-Type': 'multipart/form-data',
        }
      };

      // 전송용 폼데이터 생성
      const data = new FormData();

      // 자녀는 parent_id가 있음
      if(registInfo.parent_id) {
        data.append('parent_id', registInfo.parent_id);
      }

      // 부모는 family_code를 가지고 있음
      if(registInfo.family_code) {
        data.append('family_code', registInfo.family_code);
      }

      // 입력된 프로필 사진 정보가 있다면
      if(registInfo.profile) {
        data.append('profile', registInfo.profile);
      }
      
      // 입력된 닉네임 정보가 있다면
      if(registInfo.nick_name) {
        data.append('nick_name', registInfo.nick_name);
      }

      // 나머지 필수 데이터 삽입
      data.append('name', registInfo.name);
      data.append('account', registInfo.account);
      data.append('password', registInfo.password);
      data.append('password_chk', registInfo.password_chk);
      data.append('email', registInfo.email);
      data.append('tel', registInfo.tel);

      // 레코드 생성
      axios.post(url, data, config)
      .then(res => {
        // console.log(res.data);

        // state값 초기화
        context.commit('resetRegist');

        // 회원가입 완료 페이지로 이동
        router.replace('/regist/complete');
      })
      .catch(err => {
        console.error(err);
      });
    },

    /**
     * 부모 가족코드 발급
     * 
     * @param {*} context 
     */
    parentRegistCode(context) {
      const url = '/api/auth/parentRegistCode';

      axios.get(url)
      .then(res => {
        // 발급된 가족코드를 state에 세팅
        context.commit('setFamilyCode', res.data.family_code);

        // 코드 발급 페이지로 전환
        context.commit('setRegistFlg', false);
      })
      .catch(err => {
        console.error(err);
      });
    },

    /**
     * 자녀 회원가입 부모 매칭
     * 
     * @param {*} context 
     * @param {*} registInfo 
     */
    childRegistMatching(context, registInfo) {
      const url= '/api/auth/childRegistMatching';
      const data = JSON.stringify(registInfo);
  
      axios.post(url, data)
      .then(res => {
        // console.log(res.data.parent);

        // 담겨온 부모 정보를 state에 세티
        context.commit('setParentInfo', res.data.parent);

        // 매칭 페이지로 전환
        context.commit('setRegistFlg', false);

        // 데이터에 추가 정보 삽입
        // registInfo.parent_id = res.data.parent.parent_id;
        // registInfo.parent_name = res.data.parent.name;
        // registInfo.parent_profile = res.data.parent.profile;
        
        // console.log(registInfo);

        // 넣은걸 스토리지에 세팅
        // sessionStorage.setItem('registInfo', JSON.stringify(registInfo));
        // context.commit('setRegistInfo', registInfo);

        // 매칭 페이지로 이동
        // router.replace('/regist/child/matching');
        // context.commit('setRegistInfo', registInfo);
      }).catch(err => {
        console.error(err);
      });
    },

  },
  getters: {

  },
}