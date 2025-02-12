/**
 * 어스 스토어 모듈
 * 개인 보안 관련 처리하는 모듈
 * 주로 로그인, 회원가입, 개인정보 관리등을 여기에 작성
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
    parentFlg: sessionStorage.getItem('parentFlg') ? (sessionStorage.getItem('parentFlg') === 'true' ? true : false) : false, // 로그인 계정이 부모인지 체크
    childFlg: sessionStorage.getItem('childFlg') ? (sessionStorage.getItem('childFlg') === 'true' ? true : false) : false, // 로그인 계정이 자녀인지 체크
    
    // 에러 메세지 관련 ---------------------------------------------------------------------------------------
    errMsg: {
      common: null,
      account: null,
      password: null,
      passwordChk: null,
      name: null,
      // nick_name: null,
      email: null,
      tel: null,
      famCode: null,
      newPassword: null,
      newPasswordChk:null,
    },
    
    // 모달 관련 ---------------------------------------------------------------------------------------------
    modalText: '',
    modalColor: false,
    modalFlg: false,

    emailModalFlg: false,
    matchingModalFlg: false,
    
    // 로그인 관련 -------------------------------------------------------------------------------------------
    // loginInfo: {},
    
    // 회원가입 관련 -----------------------------------------------------------------------------------------
    matchingInfo: {}, // 매칭 정보
    preview: { // 프로필 사진 미리보기용 세팅
      imgFlg: false, // 파일 여부
      url: null, // src url
    },
    
    registFlg: true, // 자녀 페이지 전환용 플래그
    registInfo: sessionStorage.getItem('registInfo') ? JSON.parse(sessionStorage.getItem('registInfo')) : {}, // 가입 폼 정보
    parentInfo: sessionStorage.getItem('parentInfo') ? JSON.parse(sessionStorage.getItem('parentInfo')) : {}, // 가입 부모 정보
    childInfo: {},
    matchInfo: {},

    // 인증 관련 -----------------------------------------------------------------------------------------
    isAccountPass: false, // 아이디 중복 검사용
    isEmailPass: false, // 이메일 인증 검사용
    mailCode: null, // 메일 전송용 코드
    isMatchingPass: false, // 가족코드 인증 검사용
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
    setModalFlg(state, modalFlg) {
      state.modalFlg = modalFlg;
    },

    setEmailModalFlg(state, emailModalFlg) {
      state.emailModalFlg = emailModalFlg;
    },
    setMatchingModalFlg(state, matchingModalFlg) {
      state.matchingModalFlg = matchingModalFlg;
    },

    // 회원가입 관련 ---------------------------------------------------------------------------------------------
    setPreviewFlg(state, flg) {
      state.preview.imgFlg = flg;
    },
    setPreviewUrl(state, preview) {
      state.preview.url = preview;
    },
    setMatchingInfo(state, matchingInfo) {
      state.matchingInfo = matchingInfo;
    },
    
    setRegistInfo(state, registInfo) {
      state.registInfo = registInfo;
    },
    setRegistFlg(state, registFlg) {
      state.registFlg = registFlg;
    },
    setParentInfo(state, parentInfo) {
      state.parentInfo = parentInfo;
    },
    setMatchInfo(state, matchInfo) {
      state.matchInfo = matchInfo;
    },
    setChildInfo(state, childInfo) {
      state.childInfo = childInfo;
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
      state.errMsg.passwordChk = errMsg;
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
    setErrMsgFamCode(state, errMsg) {
      state.errMsg.famCode = errMsg;
    },
    setErrMsgNewPassword(state, errMsg) {
      state.errMsg.newPassword = errMsg;
    },
    setErrMsgNewPasswordChk(state, errMsg) {
      state.errMsg.newPasswordChk = errMsg;
    },

    // 인증 관련 ---------------------------------------------------------------------------------------------
    setIsAccountPass(state, isAccountPass) {
      state.isAccountPass = isAccountPass;
    },
    setIsEmailPass(state, isEmailPass) {
      state.isEmailPass = isEmailPass;
    },
    setMailCode(state, mailCode) {
      state.mailCode = mailCode;
    },
    setIsMatchingPass(state, isMatchingPass) {
      state.isMatchingPass = isMatchingPass;
    },
    
    // 초기화 관련 ------------------------------------------------------------------------------------------

    // 가입정보 초기화
    resetRegist(state) {
      state.registInfo = {};
      state.registFlg = true;
      state.parentInfo = {};
      state.childInfo = {};
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
        // nick_name: null,
        email: null,
        tel: null,
        famCode: null,
        newPassword: null,
        newPasswordChk:null,
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
        // context.commit('setModalText', '아이디를 입력해주세요.');
        // context.commit('setModalColor', false);

        context.commit('setErrMsgAccount', '아이디를 입력해주세요.');

      }else {
        const url = '/api/auth/chkAccount/'+ account;

        axios.get(url)
        .then(res => {
          //   context.commit('setModalText', res.data.msg);
          //   context.commit('setModalColor', res.data.color ? true : false);
          
          // if(!res.data.isPass) {
          //   context.commit('setErrMsgAccount', res.data.msg);
          // }else {
          //   context.commit('setIsAccountPass', true);
          // }
          
          // 통과 여부 검사
          !res.data.isPass
            ? context.commit('setErrMsgAccount', res.data.msg)
            : context.commit('setIsAccountPass', true);

        });
      }
    },

    /**
     * 이메일 인증 검사 처리
     * 
     * @param {*} context
     * @param {*} email
     */
    chkEmail(context, email) {
      if(email === undefined || email === '' || email === null) {
        context.commit('setErrMsgEmail', '이메일을 입력해주세요.');
      }else {
        const url = '/api/auth/chkEmail/'+ email;

        axios.get(url)
        .then(res => {
          // 통과 여부 검사
          if(!res.data.isPass) {
            context.commit('setErrMsgEmail', res.data.msg);
          }else {
            // 기존에 에러 메세지가 있다면 초기화
            if(context.state.errMsg.email !== null) {
              context.commit('setErrMsgEmail', null);
            }

            // 모달 활성화
            context.commit('setEmailModalFlg', true);

            // 해당 메일로 전송
            context.dispatch('sendMail', email);

          }
        });
      }
    },

    /**
     * 메일 전송 처리
     * 
     * @param {*} context
     * @param {*} email
    */
    sendMail(context, email) {
      const url = '/api/auth/sendEmail';

      axios.post(url, {email: email})
      .then(res => {
        // console.log(res.data);

        // 코드 세팅
        if(res.data.code) {
          context.commit('setMailCode', res.data.code);
        }
      })
      .catch(err => {
        // console.error(err);
        alert('메일 전송에 실패하였습니다. 다시 시도해 주세요');
        context.commit('setEmailModalFlg', false);
      });
    },

    /**
     * 자녀 가족코드 처리
     * 
     * @param {*} context
     * @param {*} famCode
     */
    chkFamCode(context, famCode) {
      if(famCode === undefined || famCode === '' || famCode === null) {
        context.commit('setErrMsgFamCode', '가족코드를 입력해주세요.');
      }else {
        const url = '/api/auth/chkFamCode/'+ famCode;

        axios.get(url)
        .then(res => {
          // 통과 여부 검사
          if(!res.data.isPass) {
            context.commit('setErrMsgFamCode', res.data.msg);
          }else {
            // 기존에 에러 메세지가 있다면 초기화
            if(context.state.errMsg.famCode !== null) {
              context.commit('setErrMsgFamCode', null);
            }

            // 부모 매칭 수행
            context.dispatch('matchingParent', famCode);

            // 모달 활성화
            setTimeout(() => {
              context.commit('setMatchingModalFlg', true);
            }, 500);
          }
        });
      }
    },

    /**
     * 부모정보 매칭
     * 
     * @param {*} context
     * @param {*} famCode
     */
    matchingParent(context, famCode) {
      const url = '/api/auth/matchingParent';

      axios.post(url, {famCode: famCode})
      .then(res => {
        if(res.data.parent) {
          // 매칭 정보 세팅
          context.commit('setMatchingInfo', res.data.parent);
        }
      }).catch(err => {
        alert('부모 매칭에 실패하였습니다. 다시 시도해 주세요');
        context.commit('setMatchingModalFlg', false);
      });
    },

    /**
     * 회원가입 처리
     * 부모, 자녀와 공용 처리
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
        
        // if(res.data.parent) {
        //   sessionStorage.setItem('parentInfo', JSON.stringify(res.data.parent));
        //   context.commit('setParentInfo', res.data.parent);
        // }

        if(res.data.famCode) {
          sessionStorage.setItem('famCode', res.data.famCode);
        }

        // 회원가입 완료 페이지로 이동
        router.replace('/regist/complete/'+ registInfo.userType);
      })
      .catch(err => {
        // 다시 시도할 경우를 대비해 메세지 초기화
        context.commit('resetErrMsg');

        // 출력 메세지 변수 연결
        const errData = err.response.data.error;

        // 유효성 검사 실패
        if(err.response.status === 422) { 
          // 에러 정보값 세팅
          const errInfo = {
            account: 'setErrMsgAccount',
            password: 'setErrMsgPassword',
            passwordChk: 'setErrMsgPasswordChk',
            name: 'setErrMsgName',
            email: 'setErrMsgEmail',
            tel: 'setErrMsgTel',
            // nick_name: 'setErrMsgNickName',
          };

          // 돌려서 있는것만 데이터 담음
          Object.entries(errData).forEach(([key, val]) => {
            if(val[0] && errInfo[key]) {
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
     * 부모 정보 호출
     * 
     * @param {*} context
     */
    parentInfo(context) {
      const url = '/api/auth/parentInfo';
      axios.post(url)
      .then(res => {
        context.commit('setParentInfo', res.data.parent);
      });
    },

    /**
     * 자녀 정보 호출
     * 
     * @param {*} context
     */
    childInfo(context) {
      const url = '/api/auth/childInfo';
      axios.post(url)
      .then(res => {
        context.commit('setChildInfo', res.data.child);
      });
    },

    /**
     * 로그인 자녀에 연결된 미션과 지출 정보 로드
     * 
     * @param {*} context
     */
    childManyInfo(context) {
      const url = '/api/auth/childManyInfo';

      axios.post(url)
      .then(res => {
        context.commit('setChildInfo', res.data.child);
      });
    },

    /**
     * 본인 확인
     * 
     * @param {*} context
     * @param {*} password
     */
    identUser(context, identInfo) {
      const url = '/api/auth/identUser';

      axios.post(url, {password: identInfo.password})
      .then(res => {
        router.push('/' + identInfo.userType + '/private/' + identInfo.identType);
      })
      .catch(err => {
        // 출력 메세지 변수 연결
        const errData = err.response.data.error;
        console.log(errData);

        // 유효성 검사 실패
        if(err.response.status === 422 && errData.password[0]) {
          context.commit('setErrMsgPassword', errData.password[0]);
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
     * 회원정보 수정
     */
    modifyUser(context, editInfo) {
      const url = '/api/auth/modifyUser';
      const config = {
        headers: {
          // 파일 생성을 할수도 있기에 멀티파트폼 데이터 세팅
          'Content-Type': 'multipart/form-data',
        }
      };

      // 전송용 폼데이터 생성
      const data = new FormData();

      // 각 데이터에 값을 넣음
      Object.entries(editInfo).forEach(([key, val]) => {
        if(val) {
          data.append(key, val);
        }
      });

      axios.post(url, data, config)
      .then(res => {
        alert('회원 정보의 수정이 완료되었습니다.');

        router.replace('/');
      }).catch(err => {
        // 다시 시도할 경우를 대비해 메세지 초기화
        context.commit('resetErrMsg');

        // 출력 메세지 변수 연결
        const errData = err.response.data.error;
        console.log(errData);

        // 유효성 검사 실패
        if(err.response.status === 422) { 
          // 에러 정보값 세팅
          const errInfo = {
            password: 'setErrMsgPassword',
            newPassword: 'setErrMsgNewPassword',
            newPasswordChk: 'setErrMsgNewPasswordChk',
            name: 'setErrMsgName',
            email: 'setErrMsgEmail',
            tel: 'setErrMsgTel',
          };

          // 돌려서 있는것만 데이터 담음
          Object.entries(errData).forEach(([key, val]) => {
            if(val[0] && errInfo[key]) {
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
     * 회원 탈퇴
     * 
     * @param {*} context 
     * @param {*} removeInfo 
     */
    removeUser(context, removeInfo) {
      const url = '/api/auth/removeUser';
      const data = { password: removeInfo };

      axios.post(url, data)
      .then(res => {
        // console.log(res.data);
        alert('회원 탈퇴 신청이 완료되었습니다. 로그인 페이지로 이동합니다.');
        context.dispatch('logout'); // 로그아웃 실행
      }).catch(err => {
        // 출력 메세지 변수 연결
        const errData = err.response.data.error;

        // 유효성 검사 실패
        if(err.response.status === 422 && errData.password[0]) { 
          context.commit('setErrMsgPassword', errData.password[0]);
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
     * 회원 비밀번호 변경
     * 
     * @param {*} context 
     * @param {*} changeInfo 
     */
    changePassword(context, changeInfo) {
      const url = '/api/auth/changePassword';
      const data = JSON.stringify(changeInfo);

      axios.post(url, data)
      .then(res => {
        // console.log(res.data);
        alert('비밀번호 변경이 완료되었습니다.');
        router.replace('/');
      }).catch(err => {
        // 출력 메세지 변수 연결
        const errData = err.response.data.error;

        // 유효성 검사 실패
        if(err.response.status === 422) { 
          // 에러 정보값 세팅
          const errInfo = {
            password: 'setErrMsgPassword',
            newPassword: 'setErrMsgNewPassword',
            newPasswordChk: 'setErrMsgNewPasswordChk',
          };

          // 돌려서 있는것만 데이터 담음
          Object.entries(errData).forEach(([key, val]) => {
            if(val[0] && errInfo[key]) {
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
     * 자녀의 부모 재매칭
     * 
     * @param {*} context 
     * @param {*} sendInfo 
     */
    childReMatching(context, sendInfo) {
      const url = '/api/auth/childReMatching';
      const data = JSON.stringify(sendInfo);

      axios.post(url, data)
      .then(res => {
        // 담겨온 부모 정보를 state에 세팅
        context.commit('setMatchInfo', res.data.parent);

        // 모달 활성화
        context.commit('setModalFlg', true);

      }).catch(err => {
        // 출력 메세지 변수 연결
        const errData = err.response.data.error;

        // 유효성 검사 실패
        if(err.response.status === 422 && errData.famCode[0]) {
          context.commit('setErrMsgFamCode', errData.famCode[0]);
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
     * 자녀의 부모 재매칭 처리
     * 
     * @param {*} context 
     * @param {*} sendInfo 
     */
    modifyReMatching(context, sendInfo) {
      const url = '/api/auth/modifyReMatching';
      const data = JSON.stringify(sendInfo);

      axios.post(url, data)
      .then(res => {
        alert('부모의 변경이 정상적으로 완료되었습니다.');
        context.commit('setModalFlg', false);
        router.replace('/');
      }).catch(err => {
        const errData = err.response.data.error;

        // 공용 실패
        if(err.response.status === 401) {
          context.commit('setErrMsgCommon', errData);
        }
        // 그 이외 오류
        else {
          context.commit('setErrMsgCommon', '예기치 못한 오류 발생.');
        }
      });
    }

  },
  getters: {

  },
}