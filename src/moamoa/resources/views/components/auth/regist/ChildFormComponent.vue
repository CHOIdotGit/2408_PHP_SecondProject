<template>
  <div class="container">
    <!-- 프로필 사진 영역 DIV -->
    <div class="data-form">
        <!-- 로고 조절용 DIV -->
      <div class="logo-img">
        <router-link to="/">
          <img src="/user-img/logo.png">
        </router-link>
      </div>

      <div class="profile-field">
        <p>프로필 사진</p>

        
        <label for="file">
          <div class="profile-preview">
            <div v-if="preview.imgFlg" class="profile-img">
              <img :src="preview.url">
            </div>
            <span v-else>클릭하여 <br>사진 선택</span>
          </div>
        </label>
        <input @change="fileSetting" type="file" name="file" id="file" accept="image/*">
      </div>
    </div>
    <!-- 회원가입 입력 폼 1 -->
    <div class="data-form">
      <!-- 이름 입력 영역 -->
      <label for="name">
        이름
        <span v-if="errMsg.name" class="err-msg">
          {{ errMsg.name }}
        </span>
      </label>
      <input v-model="props.registInfo.name" :class="{ 'err-border' : errMsg.name }" type="text" name="name" id="name" autocomplete="off" required>

      <label for="account">
        아이디
        <span v-if="errMsg.account" class="err-msg">
          {{ errMsg.account }}
        </span>
      </label>
      <div class="account-group">
        <input v-model="props.registInfo.account" :class="{ 'err-border' : errMsg.account }" type="text" name="account" id="account" autocomplete="off" required>
        <button @click="openModal" type="button" class="dup-btn">중복확인</button>
      </div>
      
      <label for="password">
        비밀번호
        <span v-if="errMsg.password" class="err-msg">
          {{ errMsg.password }}
        </span>
      </label>
      <input v-model="props.registInfo.password" :class="{ 'err-border' : errMsg.password }" type="password" name="password" id="password" autocomplete="off" required>
      
      <label for="password_chk">
        비밀번호 확인
        <span v-if="errMsg.password_chk" class="err-msg">
          {{ errMsg.password_chk }}
        </span>
      </label>
      <input v-model="props.registInfo.password_chk" :class="{ 'err-border' : errMsg.password_chk }" type="password" name="password_chk" id="password_chk" autocomplete="off" required>
      
      <label for="email">
        이메일
        <span v-if="errMsg.email" class="err-msg">
          {{ errMsg.email }}
        </span>
      </label>
      <input v-model="props.registInfo.email" @input="onlyEmail" :class="{ 'err-border' : errMsg.email }" type="email" name="email" id="email" autocomplete="off" required>
    </div>
    
    <!-- 회원가입 입력 폼 2 -->
    <div class="data-form">
      <label for="nick_name">
        닉네임
        <span v-if="errMsg.nick_name" class="err-msg">
          {{ errMsg.nick_name }}
        </span>
      </label>
      <input v-model="props.registInfo.nick_name" :class="{ 'err-border' : errMsg.nick_name }" type="text" name="nick_name" id="nick_name" autocomplete="off">

      <label for="tel">
        전화번호
        <span v-if="errMsg.tel" class="err-msg">
          {{ errMsg.tel }}
        </span>
      </label>
      <input v-model="props.registInfo.tel" @input="onlyNumber" :class="{ 'err-border' : errMsg.tel }" type="text" name="tel" id="tel" autocomplete="off" required>

      <label for="fam_code">
        가족코드
        <span v-if="errMsg.fam_code" class="err-msg">
          {{ errMsg.fam_code }}
        </span>
      </label>
      <input v-model="props.registInfo.fam_code" @input="limitCode" :class="{ 'err-border' : errMsg.fam_code }" type="text" name="fam_code" id="fam_code" autocomplete="off" required>

      <br>
      
      <p v-if="errMsg.common" class="err-msg">
        {{ errMsg.common }}
      </p>

      <div class="next-btn position-relative">
        <button @click="nextCodePage" type="button">다음 단계로 >&nbsp;</button>
      </div>
    </div>

    <div></div>
  </div>
  
  <!-- 아이디 중복 검사 미니 모달 -->
  <div v-show="modalFlg" class="mini-modal-bg">
    <div class="mini-modal-box">
      <div class="mini-modal-content">
        <p class="color-red" :class="$store.state.auth.modalColor ? 'color-green' : ''">
          {{ $store.state.auth.modalText }}
        </p>
      </div>

      <div class="mini-modal-btn">
        <button @click="closeModel" type="button">
          확인
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, onMounted, onBeforeUnmount, reactive } from 'vue';
import { onBeforeRouteLeave } from 'vue-router';
import { useStore } from 'vuex';

  const store = useStore(); 
  const props = defineProps({
    registInfo: Object,
  });
  

  // 다음 단계로 버튼 세팅 ---------------------------------------------------------------------------------------------
  const nextCodePage = () => {
    // 아이디 중복 검사를 통과하지 못했다면
    if(!store.state.auth.modalColor) {
      if(errMsg.value.account) { errMsg.value.account = null; }
      errMsg.value.account = '아이디 중복 확인을 진행해주세요.';
    }else {
      // 통과됬으면 부모매칭 수행
      store.dispatch('auth/childRegistMatching', props.registInfo);
    }
  };
  

  // 이동 반응 이벤트 ---------------------------------------------------------------------------------------------

  // 가입 정보 리셋
  const clearRegistInfo = () => {
    // 예외 메세지 체크, 있으면 리셋
    if(Object.values(errMsg).some(value => value !== '' || value !== null || value !== undefined)) {
      store.commit('auth/resetErrMsg');
    }
  };

  // 페이지가 로드되면 DOM에 이벤트 추가
  onMounted(() => {
    // 뒤로, 앞으로 버튼 이동시
    window.addEventListener('popstate', clearRegistInfo());
  });

  // 다음 컴포넌트로 넘어갈 때 이벤트를 제거
  onBeforeUnmount(() => {
    window.removeEventListener('popstate', clearRegistInfo());
  });

  // 다른 페이지로 이동 시 작동함
  onBeforeRouteLeave((to, from, next) => {
    clearRegistInfo();
    next();
  });

  // 에러 정보 ---------------------------------------------------------------------------------------------
  const errMsg = computed(() => store.state.auth.errMsg);

  // 파일 세팅 및 프리뷰 ---------------------------------------------------------------------------------------------
  const preview = computed(() => store.state.auth.preview);
  const fileSetting = (e) => {
    props.registInfo.profile = e.target.files[0];
    store.commit('auth/setPreviewUrl', URL.createObjectURL(props.registInfo.profile));
    store.commit('auth/setPreviewFlg', true);
  };

  // 중복 검사 모달 ---------------------------------------------------------------------------------------------
  const modalFlg = ref(false);
  const openModal = () => {
    store.dispatch('auth/chkAccount', props.registInfo.account);
    setTimeout(() => { modalFlg.value = true; }, 500);

    if(errMsg.value.account) {
      errMsg.value.account = null;
    }
  };
  const closeModel = () => {
    modalFlg.value = false;
  };

  // 전화번호는 숫자만 입력가능 ---------------------------------------------------------------------------------------------
  const onlyNumber = () => {
    props.registInfo.tel = props.registInfo.tel.replace(/\D/g, ''); // 숫자이외는 모두 제거
  }

  // 이메일은 @와 .하나씩 그리고 영문만 입력가능 ---------------------------------------------------------------------------------------------
  const onlyEmail = () => {
    props.registInfo.email = props.registInfo.email
      .replace(/@+/g, '@')   // `@`가 두 번 이상 입력되면 하나로 변경
      .replace(/\.+/g, '.') // `.`이 두 번 이상 입력되면 하나로 변경

    // 영문 숫자 이외에는 입력되면 안됨
    props.registInfo.email = props.registInfo.email.replace(/[^a-zA-Z0-9@.]/g, '');

    // 입력한 이메일 내에 `@`와 `.`이 하나씩만 있는지 확인

    if((props.registInfo.email.match(/@/g) || []).length > 1) {
      // `@`가 두 번 이상이면 첫 번째만 남기고 나머지 제거
      props.registInfo.email = props.registInfo.email.replace('@', '');  
    }

    // `.`이 두 번 이상이면 첫 번째만 남기고 나머지 제거
    if((props.registInfo.email.match(/\./g) || []).length > 1) {
      props.registInfo.email = props.registInfo.email.replace('.', '');  
    };
  };

  // 가족코드는 고정 8자리 ---------------------------------------------------------------------------------------------
  const limitCode = () => {
    if(props.registInfo.fam_code.length > 8) {
      props.registInfo.fam_code = props.registInfo.fam_code.slice(0, 8);
    }
  };

</script>

<style scoped>
  .color-red {
    color:#ff0000;
  }

  .color-green {
    color:#008000;
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

  /* 폼 기본 설정 */
  input, button {
    outline: none; /* 아웃라인 제거 */
    border: none;
    background-color: #E8E8E8;
  }

  /* 커서 손모양으로 변경 */
  a, button, .profile-preview {
    cursor: pointer;
  }

  /* ////// 모달 관련 제어 ////// */

  .mini-modal-bg {
    position: fixed;
    display: flex;
    justify-content: center;
    align-items: center;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
  }

  .mini-modal-box {
    width: 400px;
    max-width: 80vw;
    background-color: #fff;
    border-radius: 10px;
  }

  .mini-modal-box > .mini-modal-content {
    padding: 20px 15px 15px 10px;
    height: 150px;
    max-height: 30vh;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .mini-modal-box > .mini-modal-content > p {
    font-size: 1.5rem;
    text-align: center;
    white-space: pre-line;
  }

  .mini-modal-box > .mini-modal-btn {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .mini-modal-box > .mini-modal-btn > button {
    width: 100%;
    padding: 10px;
    border-radius: 10px;
    border-start-start-radius: 0;
    border-start-end-radius: 0;
    font-size: 1.4rem;
  }

  /* .mini-modal-box > .mini-modal-btn > button:hover {
    background-color: #737373;
    color: #fff;
  } */

  /* ////// ************** ////// */

  /* 앱솔르트 이동을 위해 포지션 설정 */
  .position-relative {
    position: relative;
  }

  /* 컨테이너 */
  .container {
    background-color: #fff;
    width: 100%;
    width: 1610px;
    max-width: 90vw;
    height: 700px;
    max-height: 100vh;
    display: grid;
    grid-template-columns: 300px 1fr 1fr 0.1fr;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    gap: 50px;
  }
  .logo-img {
    display: flex;
    justify-content: center;
  }

  /* 컨테이너 자식 DIV들 */
  .container > div {
    display: flex;
  }

  /* 로고 이미지 크기 조정 */
  .logo-img > img {
    width: 300px;
  }

  /* 데이터 들어갈 폼 */
  .data-form {
    flex-direction: column; /* 세로 정렬 */
    font-size: 2rem;
    padding: 20px;
    padding-top: 40px;
    margin: 0 6px;
    height: 100%;
  }

  /* 첫번째 영역만 여백 변경  */
  .data-form:nth-child(1) {
    padding: 0;
    /* margin-right: 50px; */
  }

  /* 데이터 라벨 박스 */
  .data-form > label {
    margin-top: 15px;
  }

  /* 데이터 입력 박스  */
  .data-form input {
    width: 100%;
    /* max-width: 80vw; */
    max-width: 490px;
    padding: 10px;
    font-size: 1.35rem;
  }

  /* 프로필 영역 */
  .profile-field {
    text-align: center;
    padding: 10px;
    padding-top: 30px;
  }

  /* 프로필 사진 텍스트 */
  .profile-field > label {
    font-size: 1.5rem;
  }

  /* 파일 인풋 숨김 */
  .profile-field > input[type="file"] {
    display: none;
  }

  /* 클릭 사진 영역 넓힘 */
  .profile-field > label {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  /* 클릭 영역 프리뷰 부분 */
  .profile-preview {
    padding: 10px;
    width: 200px;
    height: 280px;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #E8E8E8;
    margin-top: 5px;
  }

  /* 이미지 표시 영역 */
  .profile-img {
    width: 180px;
    height: 180px; 
    border-radius: 50%;
    overflow: hidden;
  }

  /* 이미지 영역 */
  .profile-img > img {
    width: 100%;
    height: 100%;
    /* object-fit: cover; */
  }

  /* 중복확인 영역 */
  .account-group {
    display: flex;
    align-items: center;
    max-width: 80vw;
  }

  .account-group input {
    padding-right: 0;
  }

  /* 중복확인 버튼 */
  .dup-btn {
    width: 100px;
    font-size: 1.2rem;
    height: 100%;
  }

  /* 다음 단계로 영역 */
  .next-btn {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: flex-end;
    align-items: flex-end;
  }

  /* 다음 단계로 버튼 */
  .next-btn button {
    position: absolute;
    top:91.4%;
    /* left: calc(100% - 130px); */
    left: calc(100% - 106px); /* 컨테이너 너비에서 버튼 너비를 뺀 위치 */
    background-color: transparent;
    font-size: 2.3rem;
    width: 230px;
    max-width: 70vw;
    text-align: right;
  }

  /* 에러 메시지용 세팅 */
  /* 라벨 끝쪽에 출력 배치 */
  label { 
    display: flex;
    justify-content: space-between;
  }

  /* 메세지 설정 */
  .err-msg {
    font-size: 0.9rem;
    padding: 10px 0;
    color: #ff0000;
  }

  /* 빨간 테두리, 추가입력하면 사라짐 */
  .err-border:invalid {
    border: 2px solid rgba(255, 0, 0, 0.6);
    box-shadow: 0 0 5px #ff0000;
  }

  /* 모바일 설정 : 화면 너비가 일정 크기 이하일 경우 작동함 */
  @media(max-width: 945px) {
    .container {
      grid-template-columns: 1fr; /* 좌우를 한칸으로 변경 */
      grid-template-rows: repeat(auto-fit, minmax(500px, 1fr));
      overflow-y: auto;
      overflow-x: hidden;
      padding: 10px;
    }

    /* 우측 공백 DIV 제거 */
    .container > div:last-child {
      display: none;
    }

    /* 문단간 간격 제거 */
    .data-form {
      padding: 0;
    }

    .data-form input {
      width: 100%;
      max-width: 80vw;
    }

    /* 로고 중앙정렬 */
    .logo-img {
      align-items: center;
    }

    /* 로고 이미지 크기 수정 */
    .logo-img > img {
      width: 400px;
      max-width: 80vw;
    }

    /* 프로필 영역 */
    .profile-field {
      padding: 10px;
    }

    .next-btn {
      padding-top: 150px;
    }

    /* 다음 단계로 버튼 위치 변경 */
    .next-btn button {
      position: static;
    }

    /* 중복확인 버튼 크기 변경 */
    .dup-btn {
      width: 150px;
    }
  }
</style>