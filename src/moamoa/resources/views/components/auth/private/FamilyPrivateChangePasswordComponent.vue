<template>
  <!-- 중앙 박스 컨테이너 DIV -->
  <div class="d-flex">
    <div class="container">

      <p v-if="errMsg.common" class="err-msg">
        {{ errMsg.common }}
      </p>
      
      <div class="data-form">
        <label for="password">
          현재 비밀번호
          <span v-if="errMsg.password" class="err-msg">
            {{ errMsg.password }}
          </span>
        </label>

        <input 
          v-model="changeInfo.password" 
          :class="{ 'err-border' : errMsg.password }" 
          type="password" 
          name="password" 
          id="password" 
          autocomplete="off" 
        required>
      </div>
      
      <div class="data-form">
        <label for="password">
          변경 비밀번호
          <span v-if="errMsg.newPassword" class="err-msg">
            {{ errMsg.newPassword }}
          </span>
        </label>

        <input 
          v-model="changeInfo.newPassword" 
          :class="{ 'err-border' : errMsg.newPassword }" 
          type="password" 
          name="newPassword" 
          id="newPassword" 
          autocomplete="off" 
        required>
      </div>
      
      <div class="data-form">
        <label for="password">
          변경 비밀번호 확인
          <span v-if="errMsg.newPasswordChk" class="err-msg">
            {{ errMsg.newPasswordChk }}
          </span>
        </label>

        <input 
          v-model="changeInfo.newPasswordChk" 
          :class="{ 'err-border' : errMsg.newPasswordChk }" 
          type="password" 
          name="newPasswordChk" 
          id="newPasswordChk" 
          autocomplete="off" 
        required>
      </div>

      <div>
        <button @click="nextChange" type="button" class="btn-submit">
          비밀번호 변경
        </button>
      </div>
      
    </div>
  </div>
</template>

<script setup>
import { computed, reactive } from 'vue';
import { useStore } from 'vuex';

  const store = useStore();

  // 에러 정보 ---------------------------------------------------------------------------------------------
  const errMsg = computed(() => store.state.auth.errMsg);

  // 인풋 정보 ---------------------------------------------------------------------------------------------
  const changeInfo = reactive({
    password: '',
    newPassword: '',
    newPasswordChk: '',
  });

  const nextChange = () => {
    // 에러메세지 리셋
    if(Object.values(errMsg).some(value => value !== '' || value !== null || value !== undefined)) {
      store.commit('auth/resetErrMsg');
    }
    
    store.dispatch('auth/changePassword', changeInfo);
  }

</script>

<style scoped>
  /* 커서 손모양으로 변경 */
  a, button, label, input {
    cursor: pointer;
  }
  
  /* 폼 기본 설정 */
  input, button {
    outline: none;
    border: none;
    background-color: #E8E8E8;
  }

  .color-red {
    color: #ff0000;
  }

  /* 메인 화면 */
  .container {
    margin-top: 20px;
    width: 1500px;
    height: 720px;
    background-color: white;
    display: flex;
    gap: 40px;
    justify-content: center;
    align-items: center; 
    flex-direction: column; 
  }

  /* 플랙스 적용 */
  .d-flex {
    margin-left: 50px;
    display: flex;
    justify-content: center;
    align-items: center; 
  }

  /* 안내문구 그룹 */
  .consent-group {
    display: flex;
    gap: 10px;
    margin: 10px 0;
  }

  .data-form {
    font-size: 1.8rem;
  }

  .data-form > input {
    width: 500px;
    height: 45px;
    padding: 10px;
    font-size: 1.3rem;
  }

  .btn-submit {
    width: 100%;
    margin: 10px 0;
    padding: 20px;
    background-color: #5c5cfa;
    font-size: 1.8rem;
    color: #fff;
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
</style>