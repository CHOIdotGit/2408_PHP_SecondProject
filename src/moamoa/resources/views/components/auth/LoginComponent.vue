<template>
  <div class="login-container">
    <img src="/img/logo4.png" class="logo" width="250px"  height="100px">

    <div class="login-box">
      <!-- 아이디 비밀 번호 입력 -->
      <div class="data-input">
        <!-- 공용 에러 메세지 -->
        <div v-if="errMsg.common" class="err-msg">
            <span> {{ errMsg.common }} </span>
        </div>

        <div>
          <input 
            v-model="userInfo.account" 
            :class="{ 'err-border' : errMsg.account }" 
            @keyup.enter="loginBtn"
            type="text" 
            name="account" 
            placeholder="아이디를 입력하세요"
          >

          <label for="account">
            <span v-if="errMsg.account" class="err-msg">
              {{ errMsg.account }}
            </span>
          </label>
        </div>
        <div>
            <!-- <br> -->
            <input 
              v-model="userInfo.password" 
              :class="{ 'err-border' : errMsg.password }" 
              @keyup.enter="loginBtn"
              type="password" 
              name="password" 
              placeholder="비밀번호를 입력하세요"
            >
            <label for="password">
              <span v-if="errMsg.password" class="err-msg">
                {{ errMsg.password }}
              </span>
            </label> 
        </div>
      </div>

      <!-- 로그인 버튼 -->
      <button class="login-btn" type="button" @click="loginBtn">
        로그인
      </button>

      <!-- 아이디/비밀번호 찾기, 회원가입 -->
      <div class="data-btn">
        <router-link to="/find/id">
          <button class="regist-btn" type="button">아이디 찾기</button>
        </router-link>

        <router-link to="/find/pwd">
          <button class="regist-btn" type="button">비밀번호 찾기</button>
        </router-link>
        
        <router-link to="/regist/agree">
          <button class="regist-btn" type="button">회원가입</button>
        </router-link>
      </div>
    </div>
  </div>

  <div v-if="!isMobile" class="first-guide-modal">
    <FirstGuideComponent />
  </div>

</template>

<script setup>
import { computed, onBeforeMount, reactive } from 'vue';
import { useStore } from 'vuex';
import FirstGuideComponent from '../manual/FirstGuideComponent.vue';

  const store = useStore();
  
  const isMobile = store.state.mobile.isMobile;

  // 에러 정보 ---------------------------------------------------------------------------------------------
  const errMsg = computed(() => store.state.auth.errMsg);

  // 회원 정보 ---------------------------------------------------------------------------------------------
  const userInfo = reactive({
      account: '',
      password: '',
  });

  // 로그인 버튼
  const loginBtn = () => {
      // 에러정보 리셋
      if(Object.values(errMsg).some(value => value !== '' && value !== null && value !== undefined)) {
          store.commit('auth/resetErrMsg');
      }

      store.dispatch('auth/login', userInfo);
  };

  onBeforeMount(() => {
      // 에러정보 리셋
      if(Object.values(errMsg).some(value => value !== '' && value !== null && value !== undefined)) {
          store.commit('auth/resetErrMsg');
      }
  });

  // 이동 반응 이벤트 ---------------------------------------------------------------------------------------------

  // 예외 정보 리셋
  const clearState = () => {
      // 들어간 값이 하나라도 있으면 리셋
      if(Object.values(errMsg).some(value => value !== '' && value !== null && value !== undefined)) {
          store.commit('auth/resetErrMsg');
      }
  };

</script>

<style scoped>
  /* 폼 기본 설정 */
  input {
      outline: none;
      border: 1px solid #dddddd;
      border-radius: 5px;
      background-color: #ffffff;
      width: 300px;
      /* height: 50px; */
      font-size: 1.4rem;
      padding: 10px;
  }

  input:focus {
      border: 3px solid #A4D8E1;
      outline: none;

  }

  /* 커서 손모양으로 변경 */
  button {
      cursor: pointer;
  }

  @media (max-width:800px) {
      .login-box {
          width: 400px;
      }
      
  }

  .login-container {
      background-image: url('/img/background.png');
      background-size: cover;
      background-repeat: no-repeat;
      width: 100vw;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      row-gap: 20px;
      position: relative;
  }

  .login-box {
      background-color: #ffffff;
      width: 500px;
      height: 350px;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-top: 20px;
      justify-content: center;
      margin-bottom: 50px;
      border-radius: 10px;
  }

  .data-input {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
  }

  .login-btn {
      width: 300px;
      height: 50px;
      font-size: 1.4rem;
      margin: 20px 0 25px 0;
      border: none;
      border-radius: 5px;
  }

  .login-btn:hover {
      background-color: #A4D8E1;
  }

  .data-btn {
    display: flex;
    justify-content: center;
    align-items: center;
    column-gap: 50px;
  }

  /* 아이디/비밀번호 찾기, 회원가입 버튼 */
  .data-btn button {
      width: 100%;
      border: none;
      background-color: #ffffff;
      /* margin: 10px 20px 0 20px; */
      font-size: 1.4rem;
  }

  .regist-btn {
      border: none;
      background-color: #ffffff;
      /* margin: 10px; */
  }

  .regist-btn:hover {
      text-decoration: underline;  
  }

  .data-input label {
      display: flex;
      justify-content: flex-start;
      align-items: center;
  }

  .err-msg {
      font-size: 1rem;
      color: #ff0000;
      margin-top: 2px;
      /* margin-bottom: 5px; */
  }

  .err-border {
      border: 2px solid rgba(255, 0, 0, 0.6);
      box-shadow: 0 0 2px #ff0000;
  }

  input.err-border:focus {
      border: none;
  }

  span {
    font-size: 1rem;
  }

  
  @media(max-width: 390px) {
    input {
      font-size: 1.3rem;
    }

    .login-box {
      max-width: 97vw;
      margin-bottom: 135px;
    }

    .data-btn {
      column-gap: 30px;
    }
    .data-btn button {
      font-size: 1.2rem;
    }
  }

  /* 로그인 가이드 모달창 */
  .first-guide-modal {
    position: absolute;
    top: 12vh;
    left: 30vw;
    z-index: 1000;

  }

</style>