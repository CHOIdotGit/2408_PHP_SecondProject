/**
 * 어스 스토어 모듈
 * 개인 보안 관련 처리하는 모듈
 * 주로 로그인, 회원가입, 개인정보 관리등을 여기서 작성
 * 
 * 241212 V001 김현석 초기구축
 */
import axios from "../../axios"
import router from "../../router";
// import forge from 'node-forge';

export default {
  namespaced: true,

  state: ()=> ({
    // 로그인 상태 관련 ---------------------------------------------------------------------------------------
    authFlg: sessionStorage.getItem('authFlg') ? true : false, // 로그인 상태 체크
    parentFlg: sessionStorage.getItem('parentFlg') ? (sessionStorage.getItem('parentFlg') === 'true' ? true : false) : false, // 로그인 계정이 부모인지 체크
    childFlg: sessionStorage.getItem('childFlg') ? (sessionStorage.getItem('childFlg') === 'true' ? true : false) : false, // 로그인 계정이 자녀인지 체크
    // 에러 메세지 관련 ---------------------------------------------------------------------------------------
    errMsg: {
      common: null,
      account: null,
      password: null,
      password_chk: null,
      name: null,
      nick_name: null,
      email: null,
      tel: null,
    },
    // 모달 관련 ---------------------------------------------------------------------------------------------
    modalText: '',
    modalColor: false,
    // 회원가입 관련 -----------------------------------------------------------------------------------------
    // familyCode: null, // 부모에서 발급한 가족코드
    userInfo: {}, // 로그인 유저 정보
    registInfo: sessionStorage.getItem('registInfo') ? JSON.parse(sessionStorage.getItem('registInfo')) : {}, // 가입폼 정보
    registFlg: true, // 자녀 페이지 전환용 플래그
    parentInfo: {}, // 부모 정보
    preview: { // 프로필 사진 미리보기용 세팅
      imgFlg: false,
      url: null,
    },
  }),

  mutations: {
    // 로그인 상태 관련 ------------------------------------------------------------------------------------------
    setAuthFlg(state, authFlg) {
      state.authFlg = authFlg;
    },
    setParentFlg(state, parentFlg) {
      state.parentFlg = parentFlg;
    },
    setChildFlg(state, childFlg) {
      state.childFlg = childFlg;
    },
    // 모달 관련 ------------------------------------------------------------------------------------------------
    setModalText(state, modalText) {
      state.modalText = modalText;
    },
    setModalColor(state, modalColor) {
      state.modalColor = modalColor;
    },
    // 회원가입 관련 ---------------------------------------------------------------------------------------------
    setRegistInfo(state, registInfo) {
      state.registInfo = registInfo;
    },
    // setFamilyCode(state, familyCode) {
    //   state.familyCode = familyCode;
    // },
    setRegistFlg(state, registFlg) {
      state.registFlg = registFlg;
    },
    setParentInfo(state, parentInfo) {
      state.parentInfo = parentInfo;
    },
    setPreviewFlg(state, flg) {
      state.preview.imgFlg = flg;
    },
    setPreviewUrl(state, preview) {
      state.preview.url = preview;
    },
    setUserInfo(state, userInfo) {
      state.userInfo = userInfo;
    },
    // 예외 메세지 관련 ------------------------------------------------------------------------------------------
    setErrMsgCommon(state, errMsg) {
      state.errMsg.common = errMsg;
    },
    setErrMsgAccount(state, errMsg) {
      state.errMsg.account = errMsg;
    },
    setErrMsgPassword(state, errMsg) {
      state.errMsg.password = errMsg;
    },
    setErrMsgPasswordChk(state, errMsg) {
      state.errMsg.password_chk = errMsg;
    },
    setErrMsgName(state, errMsg) {
      state.errMsg.name = errMsg;
    },
    setErrMsgNickName(state, errMsg) {
      state.errMsg.nick_name = errMsg;
    },
    setErrMsgEmail(state, errMsg) {
      state.errMsg.email = errMsg;
    },
    setErrMsgTel(state, errMsg) {
      state.errMsg.tel = errMsg;
    },
    // 초기화 관련 ------------------------------------------------------------------------------------------

    // 가입정보 초기화
    resetRegist(state) {
      // state.familyCode = null;
      state.registInfo = {};
      state.registFlg = true;
      state.parentInfo = {};
      state.preview = {
        imgFlg: false,
        url: null,
      };
    },

    // 예외 메세지 초기화
    resetErrMsg(state) {
      state.errMsg = {
        common: null,
        account: null,
        password: null,
        passwordChk: null,
        name: null,
        nick_name: null,
        email: null,
        tel: null,
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
        sessionStorage.setItem('authFlg', true);
        context.commit('setAuthFlg', true);

        // 로그인한 계정에 가족코드가 있으면 부모이고, 없으면 자녀
        sessionStorage.setItem('parentFlg', res.data.user.family_code ? true : false);
        context.commit('setParentFlg', res.data.user.family_code ? true : false);
        
        sessionStorage.setItem('childFlg', res.data.user.family_code ? false : true);
        context.commit('setChildFlg', res.data.user.family_code ? false : true);

        router.replace(res.data.redirect_to);
      })
      .catch(err => {
        // 다시 시도할 경우를 대비해 메세지 초기화
        context.commit('resetErrMsg');

        // 출력 메세지 변수 연결
        const errData = err.response.data.error;
        // console.log(err.response.data.error);

        // 유효성 검사 실패
        if(err.response.status === 422) { 
          if(errData.account) {
            context.commit('setErrMsgAccount', errData.account[0]);
          }

          if(errData.password) {
            context.commit('setErrMsgPassword', errData.password[0]);
          }
        }
        // 로그인 실패
        else if(err.response.status === 401) {
          context.commit('setErrMsgCommon', errData);
        }
        // 그 이외 오류
        else {
          context.commit('setErrMsgCommon', '예기치 못한 오류 발생.');
        }
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
      if(account === undefined || account === '' || account === null) {
        context.commit('setModalText', '아이디를 입력해주세요.');
        context.commit('setModalColor', false);
      }else {
        const url = '/api/auth/chkAccount/'+ account;
        axios.get(url)
        .then(res => {
          // console.log(res.data);
          context.commit('setModalText', res.data.msg);
          context.commit('setModalColor', res.data.color ? true : false);
        });
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

      // 각 데이터에 값을 넣음
      Object.entries(registInfo).forEach(([key, val]) => {
        if(val) {
          data.append(key, val);
        }
      });

      // 레코드 생성
      axios.post(url, data, config)
      .then(res => {
        console.log(res.data.data);

        // // 부모가입일경우 값을 반환함
        // if(res.data.parent) {
        //   context.commit('setParentInfo', res.data.parent);
        // }
        // // 회원가입 다음 페이지로 이동
        // router.replace(res.data.redirect_to);
      })
      .catch(err => {
        // 다시 시도할 경우를 대비해 메세지 초기화
        context.commit('resetErrMsg');

        // 출력 메세지 변수 연결
        const errData = err.response.data.error;
        // console.log(err.response.data.error);

        // 유효성 검사 실패
        if(err.response.status === 422) { 
          // 에러 정보값 세팅
          const errInfo = {
            account: 'setErrMsgAccount',
            password: 'setErrMsgPassword',
            password_chk: 'setErrMsgPasswordChk',
            name: 'setErrMsgName',
            email: 'setErrMsgEmail',
            nick_name: 'setErrMsgNickName',
            tel: 'setErrMsgTel',
          };

          // 돌려서 있는것만 데이터 담음
          Object.entries(errData).forEach(([key, val]) => {
            if(val && errInfo[key]) {
              context.commit(errInfo[key], val[0]);
            }
          });
        }
        // 공용 실패
        else if(err.response.status === 401) {
          context.commit('setErrMsgCommon', errData);
        }
        // 그 이외 오류
        else {
          context.commit('setErrMsgCommon', '예기치 못한 오류 발생.');
        }
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

        // 담겨온 부모 정보를 state에 세팅
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

    loginUser(context) {
      const url = '/api/auth/loginUser';

      axios.post(url)
      .then(res => {
        
        // 세팅된 플래그값이 없으면
        if(!(sessionStorage.getItem('authFlg') && sessionStorage.getItem('parentFlg') && sessionStorage.getItem('childFlg'))) {
          sessionStorage.setItem('authFlg', true);
          context.commit('setAuthFlg', true);
  
          // 로그인한 계정에 가족코드가 있으면 부모이고, 없으면 자녀
          sessionStorage.setItem('parentFlg', res.data.user.family_code ? true : false);
          context.commit('setParentFlg', res.data.user.family_code ? true : false);
          
          sessionStorage.setItem('childFlg', res.data.user.family_code ? false : true);
          context.commit('setChildFlg', res.data.user.family_code ? false : true);
        }
        
        context.commit('setUserInfo', res.data.user);
      });
    }

  },
  getters: {

  },
}