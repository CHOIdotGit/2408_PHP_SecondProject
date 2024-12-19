<template>
  <!-- 중앙 박스 컨테이너 DIV -->
  <div class="container">
    <!-- 프로필 사진 영역 DIV -->
    <div class="data-form">
        <!-- 로고 조절용 DIV -->
      <div class="logo-img">
          <img src="/user-img/logo.png">
      </div>

      <div class="profile-field">
        <p>프로필 사진</p>

        
        <label for="file">
          <div class="profile-preview">
            <img v-if="imgFlg" :src="preview" width="200" height="280">
            <span v-else>클릭하여 <br>사진 선택</span>
          </div>
        </label>
        <input @change="fileSetting" type="file" name="file" id="file" accept="image/*">
      </div>
    </div>
    <!-- 회원가입 입력 폼 1 -->
    <div class="data-form">
      <label for="name">이름</label>
      <input v-model="registInfo.name" type="text" name="name" id="name" autocomplete="off" required>

      <label for="account">아이디</label>
      <div class="account-group">
        <input v-model="registInfo.account" type="text" name="account" id="account" autocomplete="off" required>
        <button @click="openModal" type="button" class="dup-btn">중복확인</button>
      </div>
      
      <label for="password">비밀번호</label>
      <input v-model="registInfo.password" type="password" name="password" id="password" autocomplete="off" required>
      
      <label for="password_chk">비밀번호 확인</label>
      <input v-model="registInfo.password_chk" type="password" name="password_chk" id="password_chk" autocomplete="off" required>
      
      <label for="email">이메일</label>
      <input v-model="registInfo.email" @input="onlyEmail" type="email" name="email" id="email" autocomplete="off" required>
    </div>
    
    <!-- 회원가입 입력 폼 2 -->
    <div class="data-form">
      <label for="nick_name">닉네임</label>
      <input v-model="registInfo.nick_name" type="text" name="nick_name" id="nick_name" autocomplete="off">

      <label for="tel">전화번호</label>
      <input v-model="registInfo.tel" @input="onlyNumber" type="text" name="tel" id="tel" autocomplete="off" required>

      <div class="next-btn position-relative">
        <button @click="$store.dispatch('auth/saveRegistInfo', registInfo)" type="button">다음 단계로 >&nbsp;</button>
      </div>
    </div>

    <div></div>
  </div>

  <!-- 아이디 중복 검사 미니 모달 -->
  <div v-show="modalFlg" class="mini-modal-bg">
    <div class="mini-modal-box">
      <div class="mini-modal-content">
        <p :class="$store.state.auth.modalColor">
            {{ $store.state.auth.modalText }}
        </p>
      </div>

      <!-- <hr> -->

      <div class="mini-modal-btn">
        <button @click="closeModel" type="button">
          확인
        </button>
      </div>
    </div>
  </div>

</template>

<script setup>
import { reactive, ref } from 'vue';
import { useStore } from 'vuex';

  const store = useStore(); 

  // 회원 정보 ---------------------------------------------------------------------------------------------
  const registInfo = reactive({
    name: ''
    ,account: ''
    ,password: ''
    ,password_chk: ''
    ,email: ''
    ,nick_name: null
    ,tel: ''
    ,profile: null
    ,auth: 'parent'
  });

  // 파일 세팅 및 프리뷰 ---------------------------------------------------------------------------------------------
  const imgFlg = ref(false);
  const preview = ref('');
  const fileSetting = (e) => {
    registInfo.profile = e.target.files[0];
    preview.value = URL.createObjectURL(registInfo.profile);
    imgFlg.value = true;
  }

  // 중복 검사 모달 ---------------------------------------------------------------------------------------------
  const modalFlg = ref(false);
  const openModal = () => {
    store.dispatch('auth/chkAccount', registInfo.account);
    setTimeout(() => { modalFlg.value = true; }, 500);
  }
  const closeModel = () => {
    modalFlg.value = false;
  }

  // 전화번호는 숫자만 입력가능 ---------------------------------------------------------------------------------------------
  const onlyNumber = () => {
    registInfo.tel = registInfo.tel.replace(/\D/g, ''); // 숫자이외는 모두 제거
  }

  // 이메일은 @와 .하나씩 그리고 영문만 입력가능 ---------------------------------------------------------------------------------------------
  const onlyEmail = () => {
    registInfo.email = registInfo.email
        .replace(/@+/g, '@')   // `@`가 두 번 이상 입력되면 하나로 변경
        .replace(/\.+/g, '.') // `.`이 두 번 이상 입력되면 하나로 변경

    // 영문 숫자 이외에는 입력되면 안됨
    registInfo.email = registInfo.email.replace(/[^a-zA-Z0-9@.]/g, '');

    // 입력한 이메일 내에 `@`와 `.`이 하나씩만 있는지 확인

    if ((registInfo.email.match(/@/g) || []).length > 1) {
      // `@`가 두 번 이상이면 첫 번째만 남기고 나머지 제거
      registInfo.email = registInfo.email.replace('@', '');  
    }

    // `.`이 두 번 이상이면 첫 번째만 남기고 나머지 제거
    if ((registInfo.email.match(/\./g) || []).length > 1) {
      registInfo.email = registInfo.email.replace('.', '');  
    };
  }

  
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

  /* 이미지 미리보기용 */
  .profile-preview > img {
    border: 2px solid #E8E8E8;
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
    padding: 20px 10px 10px 10px;
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