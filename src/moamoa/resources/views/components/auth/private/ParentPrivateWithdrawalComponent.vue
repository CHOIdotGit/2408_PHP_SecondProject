<template>
  <!-- 중앙 박스 컨테이너 DIV -->
  <div class="d-flex">
    <div class="container">
      <p v-if="errMsg.common" class="err-msg">
        {{ errMsg.common }}
      </p>

      <div v-if="parent?.children?.length > 0" class="empty-msg color-red">
        연결된 자녀들이 모두 탈퇴를 진행해야 가능합니다.
      </div>
      <div v-else>
        <div class="data-form">
          <label for="password">
            비밀번호
            <span v-if="errMsg.password" class="err-msg">
              {{ errMsg.password }}
            </span>
          </label>
          <input v-model="removeInfo" :class="{ 'err-border' : errMsg.password }" type="password" name="password" id="password" autocomplete="off" required>
        </div>
        
        <div class="consent-form">
          <span class="color-red">
            회원 탈퇴 진행 시 현재 가지고 있는 포인트 포함 모든 데이터가 삭제됩니다. <br> 
            30일 이내로 재로그인 시 계정 및 데이터 복구 요청이 가능하며 <br>
            계정 복구 시 자녀 계정도 복구 요청이 가능합니다.
          </span>

          <div class="consent-group">
            <input v-model="isChecked" type="checkbox" name="consent" id="consent" autocomplete="off" required>
            <label for="consent">안내 사항을 모두 확인하였으며, 이에 동의합니다.</label>
          </div>

          <button @click="nextRemove" type="button" class="btn-submit">회원 탈퇴</button>
        </div>
      </div>
      
    </div>
  </div>
</template>

<script setup>
import { computed, onBeforeMount, ref } from 'vue';
import { useStore } from 'vuex';

  const store = useStore();

  // 에러 정보 ---------------------------------------------------------------------------------------------
  const errMsg = computed(() => store.state.auth.errMsg);

  // 부모 정보 ---------------------------------------------------------------------------------------------
  const parent = computed(() => store.state.auth.parentInfo);

  const isChecked = ref(false);
  const removeInfo = ref();
  
  onBeforeMount(() => {
    store.dispatch('auth/parentInfo'); // 부모 정보 로드
  });
  
  const nextRemove = () => {
    if(Object.values(errMsg).some(value => value !== '' || value !== null || value !== undefined)) {
      store.commit('auth/resetErrMsg');
    }
  
    // 체크박스 검사
    if(!isChecked.value) {
      alert('안내 사항에 동의해주세요.');
      return;
    }
    
    store.dispatch('auth/removeUser', removeInfo.value);
  };

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
    background-color: #f55d5d;
    font-size: 2rem;
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

  .empty-msg {
    font-size: 1.8rem;
  }

  .consent-form {
    margin-top: 20px;
  }
</style>