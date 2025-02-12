<template>
  <!-- 배경 컨테이너 DIV -->
  <div class="regist-container">
    <!-- 메인 박스 DIV -->
    <div class="regist-main-box">
      <!-- 메인 타이틀 DIV -->
      <div class="regist-main-title">
        >
        <span v-if="userType === 'parent'">
          부모
        </span>
        <span v-else>자녀</span>
        정보입력&nbsp;및&nbsp;본인확인
      </div>

      <!-- 프로필 영역 DIV -->
      <div class="regist-main-profile">
        <label for="file">
          <div class="regist-profile-preview">
            <img v-if="preview.imgFlg" :src="preview.url">
            <img v-else src="/img/default_profile.webp">
          </div>
          
          <div class="regist-camera">
            <img class="fa-camera" src="/img/icon-camera.webp">
          </div>
        </label>

        <input @change="fileSetting" type="file" name="file" id="file" accept="image/*">
      </div>

      <p v-if="errMsg.common" class="err-msg">
        {{ errMsg.common }}
      </p>
      <p v-else>
        <!-- <span>*</span>가 있는 항목은 필수 입력사항입니다. -->
      </p>
      <!-- 회원가입 작성 폼 DIV -->
      <div class="regist-main-content">
        
        <!-- 제목 타이틀 DIV -->
        <div class="regist-item-title">
          <label for="account">
            아이디
            <!-- <span>*</span> -->
          </label>
          <label for="password">
            비밀번호
            <!-- <span>*</span> -->
          </label>
          <label for="passwordChk">
            비밀번호&nbsp;확인
            <!-- <span>*</span> -->
          </label>
          <label for="name">
            이름
            <!-- <span>*</span> -->
          </label>
          <label for="email">
            이메일
            <!-- <span>*</span> -->
          </label>
          <label for="tel">
            휴대폰
            <!-- <span>*</span> -->
          </label>
          <label v-if="userType === 'child'" for="famCode">
            가족코드
            <!-- <span>*</span> -->
          </label>
        </div>

        <!-- 내용 컨텐츠 DIV -->
        <div class="regist-item-content">
          <!-- 아이디 입력 DIV -->
          <div>
            <div class="btn-group">
              <input 
                v-model="registInfo.account" 
                :class="{ 'err-border' : errMsg.account, 'pass-border' : isAccountPass }" 
                @blur="chgAccount"
                type="text" 
                name="account" 
                id="account" 
                autocomplete="off" 
                required
              >
              <button @click="chkAccount" type="button">중복확인</button>
            </div>
            <p v-if="isAccountPass" class="pass-msg">
              사용가능한 아이디 입니다.
            </p>
            <p v-else-if="errMsg.account" class="err-msg">
              {{ errMsg.account }}
            </p>
            <p v-else class="ann-msg">
              영문 대소문자와 숫자 6~18자 사용 가능.
            </p>
          </div>

          <!-- 비밀번호 입력 DIV -->
          <div>
            <input v-model="registInfo.password" :class="{ 'err-border' : errMsg.password }" type="password" name="password" id="password" autocomplete="off" required>
            <p v-if="errMsg.password" class="err-msg">
              {{ errMsg.password }}
            </p>
            <p v-else class="ann-msg">
              영문 대소문자와 숫자, 특수문자 2종류 이상 조합 6~18자 사용 가능.
            </p>
          </div>

          <!-- 비밀번호 확인 입력 DIV -->
          <div>
            <input v-model="registInfo.passwordChk" :class="{ 'err-border' : errMsg.passwordChk }" type="password" name="passwordChk" id="passwordChk" autocomplete="off" required>
            <p v-if="errMsg.passwordChk" class="err-msg">
              {{ errMsg.passwordChk }}
            </p>
            <p v-else class="ann-msg">
              비밀번호를 확인 해주세요.
            </p>
          </div>

          <!-- 이름 입력 DIV -->
          <div>
            <input v-model="registInfo.name" :class="{ 'err-border' : errMsg.name }" type="text" name="name" id="name" autocomplete="off" required>
            <p v-if="errMsg.name" class="err-msg">
              {{ errMsg.name }}
            </p>
          </div>

          <!-- 이메일 입력 DIV -->
          <div>
            <div class="btn-group">
              <input 
                v-model="registInfo.email" 
                :class="{ 'err-border' : errMsg.email, 'pass-border' : isEmailPass }"
                @input="onlyEmail"
                @blur="chgEmail"
                type="email" 
                name="email" 
                id="email" 
                autocomplete="off" 
                required
              >
              <button @click="openEmailModal" type="button">인증확인</button>
            </div>
            <p v-if="isEmailPass" class="pass-msg">
              이메일 인증이 완료되었습니다.
            </p>
            <p v-else-if="errMsg.email" class="err-msg">
              {{ errMsg.email }}
            </p>
            <p v-else class="ann-msg">
              아이디나 비밀번호를 찾기위해 사용되니 정확히 입력하시길 바랍니다.
            </p>
          </div>

          <!-- 전화번호 입력 DIV -->
          <div>
            <input 
              v-model="registInfo.tel" 
              :class="{ 'err-border' : errMsg.tel }" 
              @input="onlyNumber"
              type="text" 
              name="tel" 
              id="tel" 
              autocomplete="off" 
              required
            >
            <p v-if="errMsg.tel" class="err-msg">
              {{ errMsg.tel }}
            </p>
          </div>
          
          <!-- 가족코드 DIV -->
          <div v-if="userType === 'child'">
            <div class="btn-group">
              <input
                v-model="registInfo.famCode"
                :class="{ 'err-border' : errMsg.famCode, 'pass-border' : isMatchingPass }" 
                @blur="chgFamCode"
                type="text" 
                name="famCode" 
                id="famCode" 
                autocomplete="off" 
                maxlength="8"
                required
              >
              <button @click="openMatchingModal" type="button">
                부모확인
              </button>
            </div>
            <p v-if="isMatchingPass" class="pass-msg">
              가족코드 매칭이 완료되었습니다.
            </p>
            <p v-else-if="errMsg.famCode" class="err-msg">
              {{ errMsg.famCode }}
            </p>
            <p v-else class="ann-msg">
              가족코드는 보호자 계정내의 가족정보 에서 확인하실 수 있습니다.
            </p>
          </div>
        </div>
      </div>

      <div class="regist-footer">
        <button @click="registBtn" type="button" class="btn-submit">
          가입확인
        </button>
      </div>

    </div>
  </div>

  <!-- 이메일 인증 모달 -->
  <EmailAuthModalComponent 
    :email="registInfo.email"
  />

  <!-- 자녀 부모매칭 -->
  <MatchingModalComponent
    @matchingParentId="receiveParentId"
  />
  <!-- v-if="userType === 'child'" -->
</template>

<script setup>
import EmailAuthModalComponent from '../../modal/EmailAuthModalComponent.vue';
import MatchingModalComponent from '../../modal/MatchingModalComponent.vue';
import { computed, onBeforeMount, onBeforeUnmount, onMounted, reactive, ref } from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';

  const store = useStore();
  const route = useRoute();

  // 부모 or 자녀 ---------------------------------------------------------------------------------------------
  const userType = ref(route.path.split('/').pop());

  // 에러 정보 ---------------------------------------------------------------------------------------------
  const errMsg = computed(() => store.state.auth.errMsg);

  // 회원 정보 ---------------------------------------------------------------------------------------------
  const registInfo = reactive({
    account: '',
    password: '',
    passwordChk: '',
    name: '',
    email: '',
    tel: '',
    profile: null,
    famCode: userType === 'child' ? '' : null,
    parentId: userType === 'child' ? '' : null,
    userType: userType, // parent or child
  });

  // 파일 세팅 및 프리뷰 ---------------------------------------------------------------------------------------------
  
  // state 프로필 프리뷰 세팅값
  const preview = computed(() => store.state.auth.preview);

  const fileSetting = (e) => {
    const file = e.target.files[0];

    // 이미지 파일 여부 검사
    if(file && !file.type.startsWith('image/')) {
      alert('이미지 파일만 업로드할 수 있습니다.');
      e.target.value = '';
      return;
    }else {
      registInfo.profile = e.target.files[0];
      store.commit('auth/setPreviewUrl', URL.createObjectURL(registInfo.profile));
      store.commit('auth/setPreviewFlg', true);
    }
  };

  // 아이디 중복 검사 ---------------------------------------------------------------------------------------------
  
  // 검사 통과 여부
  const isAccountPass = computed(() => store.state.auth.isAccountPass);

  // 이전 입력 아이디
  const prevAccount = ref('');

  // 중복확인 버튼
  const chkAccount = () => {
    // 중복검사 액션 실행
    store.dispatch('auth/chkAccount', registInfo.account);

    // 입력값 저장
    prevAccount.value = registInfo.account;
  };

  // 아이디 추가 입력 검사
  const chgAccount = () => {
    // 중복검사를 통과후 추가 입력을 했을때 입력값이 기존것과 다를경우
    if(isAccountPass.value && (registInfo.account !== prevAccount.value)) {
      // 통과 취소 시키고
      store.commit('auth/setIsAccountPass', false);

      // 메시지 출력
      store.commit('auth/setErrMsgAccount', '아이디 중복확인을 진행해 주세요.');
    }
  };

  // 이메일 인증 ---------------------------------------------------------------------------------------------
  
  // 인증 통과 여부
  const isEmailPass = computed(() => store.state.auth.isEmailPass);

  // 이전 입력 이메일
  const prevEmail = ref('');

  // 인증 확인 버튼
  const openEmailModal = () => {
    // 중복검사 및 메일전송 액션 실행
    store.dispatch('auth/chkEmail', registInfo.email);

    // 입력값 저장
    prevEmail.value = registInfo.email;
  };

  // 이메일 추가 입력 검사
  const chgEmail = () => {
    // 인증 통과후 추가 입력을 했을때 입력값이 기존것과 다를경우
    if(isEmailPass.value && (registInfo.email !== prevEmail.value)) {
      // 통과 취소 시키고
      store.commit('auth/setIsEmailPass', false);

      // 메시지 출력
      store.commit('auth/setErrMsgEmail', '이메일 인증을 진행해 주세요.');
    }
  };

  // 이메일은 @와 .하나씩 그리고 영문숫자만 입력가능 ---------------------------------------------------------------------------------------------
  const onlyEmail = () => {
    registInfo.email = registInfo.email
      .replace(/@+/g, '@')   // `@`가 두 번 이상 입력되면 하나로 변경
      .replace(/\.+/g, '.'); // `.`이 두 번 이상 입력되면 하나로 변경

    // 영문 숫자 이외에는 입력되면 안됨
    registInfo.email = registInfo.email.replace(/[^a-zA-Z0-9@.]/g, '');

    // 입력한 이메일 내에 `@`와 `.`이 하나씩만 있는지 확인
    if((registInfo.email.match(/@/g) || []).length > 1) {
      // `@`가 두 번 이상이면 첫 번째만 남기고 나머지 제거
      registInfo.email = registInfo.email.replace('@', '');  
    }

    // `.`이 두 번 이상이면 첫 번째만 남기고 나머지 제거
    else if((registInfo.email.match(/\./g) || []).length > 1) {
      registInfo.email = registInfo.email.replace('.', '');  
    };
  };

  // 전화번호는 숫자만 입력가능 ---------------------------------------------------------------------------------------------
  const onlyNumber = () => {
    // 숫자이외는 모두 제거
    registInfo.tel = registInfo.tel.replace(/\D/g, '');
  }

  // 부모 확인 (자녀용) ---------------------------------------------------------------------------------------------
  
  // 매칭 통과 여부
  const isMatchingPass = computed(() => store.state.auth.isMatchingPass);

  // 이전 입력 가족코드
  const prevFamCode = ref('');

  // 부모 확인 버튼
  const openMatchingModal = () => {
    // 유효성 검사 및 부모 매칭 실행
    store.dispatch('auth/chkFamCode', registInfo.famCode);

    // 입력값 저장
    prevFamCode.value = registInfo.famCode;
  };

  // 가족코드 추가 입력 검사
  const chgFamCode = () => {
    // 매칭 통과후 추가 입력을 했을때 입력값이 기존것과 다를경우
    if(isMatchingPass.value && (registInfo.famCode !== prevFamCode.value)) {
      // 통과 취소 시키고
      store.commit('auth/setIsMatchingPass', false);

      // 메시지 출력
      store.commit('auth/setErrMsgFamCode', '가족코드 매칭을 진행해 주세요.');
    }
  };

  // 모달에서 반환된 부모ID 저장
  const receiveParentId = (parentId) => {
    if(parentId){
      registInfo.parentId = parentId;
    }
  }

  // 가입 처리 ---------------------------------------------------------------------------------------------

  // 가입확인 버튼
  const registBtn = () => {

    // 체크할 인증패스들
    const chkVal = [
      { 
        condition: !isAccountPass.value, 
        message: '아이디 중복확인을 진행해 주세요.', 
        action: 'auth/setErrMsgAccount' 
      },
      { 
        condition: !isEmailPass.value, 
        message: '이메일 인증을 진행해 주세요.', 
        action: 'auth/setErrMsgEmail' },
      { 
        condition: userType === 'child' && !isMatchingPass.value,
        message: '가족코드 매칭을 진행해 주세요.', 
        action: 'auth/setErrMsgFamCode' 
      }
    ];

    // 에러 메세지 세팅
    for(const check of chkVal) {
      if(check.condition) {
        store.commit(check.action, check.message);
      }
    }

    // 하나라도 걸리면 중지
    if(chkVal.some(check => check.condition)) {
      return;
    }else {
      // 전부 통과됫다면 회원가입 실행
      store.dispatch('auth/storeUser', registInfo);
    }
  }

  // 이벤트 세팅 ---------------------------------------------------------------------------------------------
  onBeforeMount(() => {
    // 에러 정보 리셋
    if(Object.values(errMsg).some(value => value !== '' || value !== null || value !== undefined)) {
      store.commit('auth/resetErrMsg');
    }

    // 인증 리셋
    if(isAccountPass.value) {
      store.commit('auth/setIsAccountPass', false);
    }
    if(isEmailPass.value) {
      store.commit('auth/setIsEmailPass', false);
    }
    if(isMatchingPass.value) {
      store.commit('auth/setIsMatchingPass', false);
    }
  });

  // 새로고침 시 물어보는 이벤트
  // onMounted(() => {
  //   if(route.path === '/regist/parent' || route.path === '/regist/child') {
  //     window.addEventListener('beforeunload', (e) => {
  //       e.preventDefault();
  //     });
  //   }
  // });

  // onBeforeUnmount(() => {
  //   if(route.path === '/regist/parent' || route.path === '/regist/child') {
  //     window.removeEventListener('beforeunload', (e) => {
  //       e.preventDefault();
  //     });
  //   }
  // });

</script>

<style scoped>
  /* 버튼 커서 손모양 */
  button, img {
    cursor: pointer;
  }

  /* 인풋 기본설정 */
  input {
    border: 1px solid #CCC;
  }

  /* 파일 인풋 숨김 */
  input[type="file"] {
    display: none;
  }

  /* 포커스 시 강조표시 */
  input:focus {
    border: 1.9px solid #101010;
    border-radius: 2px;
    outline: none;
  }
  
  /* 증감 UI 제거 */
  input[type="number"] {
    -moz-appearance: textfield;
    -webkit-appearance: none;
    appearance: none;
  }
  input[type="number"]::-webkit-outer-spin-button,
  input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
  }
  input[type="number"]::-moz-spin-button {
    display: none;
  }

  /* 배경 컨테이너 */
  .regist-container {
    background-image: url('/img/background.png');
    background-size: cover;
    background-repeat: no-repeat;
    width: 100vw;
    height: 100vh;
    display: flex;
    justify-content: center;
  }

  /* 메인 컨텐츠 박스 */
  .regist-main-box {
    width: 980px;
    /* max-width: 80vw; */
    height: 860px;
    margin-top: 50px;
    background-color: #fff;
    border-radius: 10px;
    padding: 15px 20px;
  }

  /* 메인 제목 타이틀 */
  .regist-main-title {
    font-size: 1.4rem;
    font-weight: 600;
  }

  /* 부모 or 자녀 텍스트 여백삭제 */
  .regist-main-title > span {
    padding: 0;
  }

  /* 프로필 사진 이미지란 */
  .regist-main-profile {
    margin: 15px 0;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  /* 프로필 이미지 박스 */
  .regist-profile-preview {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 10px;
    background-color: #EFF6FF;
  }

  /* 이미지 채움 */
  .regist-profile-preview > img {
    width: 100%;
    height: 100%;
  }

  /* 카메라 아이콘 영역 */
  .regist-camera {
    position: relative;
    width: 100%;
    height: 100%;
  }

  /* 카메라 아이콘 */
  .fa-camera {
    width: 45px;
    height: 45px;
    position: absolute;
    left: 76%;
    bottom: 50%;
  }

  /* 필수입력 안내사항 */
  .regist-main-profile + p {
    font-size: 0.8rem;
    font-weight: 500;
    text-align: left;
    width: 100%;
  }

  /* 안내사항 별 */
  .regist-main-profile + p > span {
    padding: 0;
    color: #ff0000;
  }

  /* 회원가입 작성 폼 박스 */
  .regist-main-content {
    display: grid;
    grid-template-columns: 0.4fr 1fr; /* 270px */
    border-top: 2.5px solid #cccccc;
    border-bottom: 2.5px solid #CCC;
    width: 100%;
    margin-top: 10px;
  }

  /* 제목 내용 위치 조정 */
  .regist-item-title, .regist-item-content {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    /* display: grid;
    grid-template-rows: repeat('auto-fit', minmax(68px, 1fr)); */
  }
  
  /* 제목 내용 크기 조정 */
  .regist-item-title > label, .regist-item-content > div {
    width: 100%;
    height: 68px;
    border-top: 1.2px solid #ECECEC;
  }

  /* 제목 섹션 위치 조정 */
  .regist-item-title > label {
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 1.2rem;
    background-color: #F6F6F6;
  }

  /* 필수항목 별 */
  .regist-item-title > label > span {
    padding: 0;
    color: #ff0000;
  }

  /* 내용 섹션 위치 조정 */
  .regist-item-content > div {
    padding: 17px 0 0 10px;
  }

  /* 내용 안의 인풋들 */
  .regist-item-content > div input {
    padding: 5px;
    font-size: 0.9rem;
    width: 280px;
  }

  /* 버튼 없는 인풋 반응형 */
  /* .regist-item-content > div > input {
    max-width: 32.6vw;
  } */

  /* 내용 안의 우측 버튼들 */
  .regist-item-content > div button {
    margin-left: 5px;
    padding: 4px;
    background-color: transparent;
    border-radius: 5px;
    border: 2px solid #779CCD;
    color: #4D7BBE;
  }

  /* 버튼 있는 인풋 그룹 */
  .btn-group {
    display: flex;
    align-items: center;
  }

  /* 버튼 있는 인풋 반응형 */
  /* .btn-group > input {
    max-width: 30vw;
  } */

  /* 해당 버튼 호버시 효과 */
  .regist-item-content > div button:hover {
    color: #fff;
    background-color: #3D7CC3;
    border: 2px solid #3D7CC3;
  }

  /* 안내사항 및 에러 메시지 */
  .ann-msg, .err-msg, .pass-msg {
    font-size: 0.68rem;
    padding-top: 3.5px;
  }

  /* 안내 메세지 */
  .ann-msg {
    color: #7537AA;
  }

  /* 에러 메세지 */
  .err-msg {
    color: #ff0000;
  }

  /* 통과 메세지 */
  .pass-msg {
    color: #00a500;
  }

  /* 테두리 있는건 포커스 비활성화 */
  input.err-border:focus,
  input.pass-border:focus {
    border: none;
  }

  /* 빨간 테두리 */
  .err-border {
    border: 2px solid rgba(255, 0, 0, 0.6);
    box-shadow: 0 0 2px #ff0000;
  }

  /* 초록 테두리 */
  .pass-border {
    border: 2px solid rgba(0, 165, 0, 0.6);
    box-shadow: 0 0 2px #00a500;
  }

  /* 아래 버튼 영역 */
  .regist-footer {
    display: flex;
    justify-content: center;
    align-items: center; 
  }
  
  /* 가입확인 버튼 */
  .btn-submit {
    margin-top: 27.5px;
    padding: 10px;
    width: 100px;
    border: none;
    background-color: #3B82F6;
    color: #fff;
    font-size: 1.2rem;
  }
  /* 버튼 호버 */
  .btn-submit:hover {
    background-color: #2563EB;
  }

</style>