<template>
  <!-- 배경 컨테이너 DIV -->
  <div class="find-container">
    <!-- 메인 박스 DIV -->
    <div class="find-main-box">

      <p v-if="errMsg.common" class="err-msg">
        {{ errMsg.common }}
      </p>
      <!-- 메인 폼 DIV -->
      <div class="find-main-form">
        <h2>
          > 비밀번호 재설정
        </h2>

        <!-- 인풋 그룹 DIV -->
        <div class="find-input-group">
          <div>
            <input v-model="pwdInfo.newPassword" type="password" name="newPassword" id="newPassword" placeholder="새 비밀번호" autocomplete="off" required>
            <p v-if="errMsg.newPassword" class="err-msg">
              {{ errMsg.newPassword }}
            </p>
          </div>

          <div>
            <input v-model="pwdInfo.newPasswordChk" type="password" name="newPasswordChk" id="newPasswordChk" placeholder="새 비밀번호 확인" autocomplete="off" required>
            <p v-if="errMsg.newPasswordChk" class="err-msg">
              {{ errMsg.newPasswordChk }}
            </p>
          </div>
        </div>
      </div>

      <div class="find-footer-btn">
        <p>
          * 비밀번호는 영문 대소문자와 숫자, 특수문자 2종류 이상 조합 6~18자  로 사용 가능 합니다.  
        </p>
        
        <div>
          <button @click="resetBtn" type="button">확인</button>

          <router-link to="/login" class="find-btn-cancel">
            <button type="button">취소</button>
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onBeforeMount, reactive } from 'vue';
import { useStore } from 'vuex';

  const store = useStore();

  // 에러 정보 ---------------------------------------------------------------------------------------------
  const errMsg = computed(() => store.state.auth.errMsg);

  // 입력 정보 ---------------------------------------------------------------------------------------------
  const pwdInfo = reactive({
    newPassword: '',
    newPasswordChk: '',
    userType: sessionStorage.getItem('userType') ? sessionStorage.getItem('userType') : '',
    userId: sessionStorage.getItem('userId') ? sessionStorage.getItem('userId') : ''
  });

  // 비밀번호 재발급 ---------------------------------------------------------------------------------------------
  
  // 확인 버튼
  const resetBtn = () => {
    // 에러 정보 리셋
    if(Object.values(errMsg).some(value => value !== '' && value !== null && value !== undefined)) {
      store.commit('auth/resetErrMsg');
    }

    // 비밀번호 재설정 실행
    store.dispatch('auth/resetPassword', pwdInfo);
  };

  // 이벤트 처리 ---------------------------------------------------------------------------------------------
  onBeforeMount(() => {
    // 에러 정보 리셋
    if(Object.values(errMsg).some(value => value !== '' && value !== null && value !== undefined)) {
      store.commit('auth/resetErrMsg');
    }
  });

</script>

<style scoped>

  /* 버튼 설정 */
  button {
    cursor: pointer;
  }

  /* 배경 컨테이너 */
  .find-container {
    background-image: url('/img/background.png');
    background-size: cover;
    background-repeat: no-repeat;
    width: 100vw;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  /* 메인 컨텐츠 박스 */
  .find-main-box {
    width: 700px;
    height: 600px;
    background-color: #fff;
    border-radius: 10px;
    padding: 35px 20px;
    display: flex;
    justify-content: center;
    flex-direction: column;
  }

  .find-main-box > p {
    padding-left: 13px;
  }

  /* -------------------------------------------------------------------- */

  /* 메인 폼 */
  .find-main-form {
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }
  /* 메인 타이틀 */
  .find-main-form > h2 {
    font-size: 1.6rem;
    margin: 40px 0 20px 10px;
  }

  /* 입력 컨텐츠 박스 */
  .find-input-group {
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    border-top: 2.5px solid #CCC;
    padding-top: 2px;
  }
  /* 입력 간격 */
  .find-input-group > div {
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    /* align-items: center; */
    border-bottom: 1.2px solid #ECECEC;
    padding: 20px;
  }
  /* 입력 내용 */
  .find-input-group input {
    width: 100%;
    padding: 10px;
    font-size: 1.4rem;
    border: 1px solid #aaa;
  }
  
  /* -------------------------------------------------------------------- */

  /* 하단 부분 */
  .find-footer-btn {
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  /* id 추가 텍스트 */
  .find-footer-btn > p {
    font-size: 1.2rem;
    margin: 45px 0 20px 0;
    padding: 0 50px;
    color: #acacac;
  }

  /* 하단 버튼 그룹 */
  .find-footer-btn > div {
    display: flex;
    justify-content: center;
    align-items: center; 
    column-gap: 30px;
  }

  /* 하단 버튼 설정 */
  .find-footer-btn > div > button, .find-btn-cancel > button {
    margin-top: 28px;
    padding: 10px;
    width: 120px;
    border: none;
    font-size: 1.4rem;
  }
  /* 확인 버튼 설정 */
  .find-footer-btn > div > button {
    background-color: #3B82F6;
    color: #fff;
  }
  /* 확인 버튼 호버 */
  .find-footer-btn > div > button:hover {
    background-color: #2563EB;
  }

  /* 취소 버튼 설정 */
  .find-btn-cancel > button {
    background-color: #F3F3F3;
  }

  /* 에러 메세지 */
  .err-msg {
    font-size: 1.1rem;
    padding-left: 5px;
    margin-top: 5px;
    color: #ff0000;
  }

  /* 테두리 있는건 포커스 비활성화 */
  input.err-border:focus {
    border: none;
  }

  /* 빨간 테두리 */
  .err-border {
    border: 2px solid rgba(255, 0, 0, 0.6);
    box-shadow: 0 0 2px #ff0000;
  }


</style>