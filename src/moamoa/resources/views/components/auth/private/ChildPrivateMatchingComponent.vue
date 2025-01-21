<template>
  <!-- 중앙 박스 컨테이너 DIV -->
  <div class="d-flex">
    <div class="container">

      <p v-if="errMsg.common" class="err-msg">
        {{ errMsg.common }}
      </p>
      
      <div v-if="child?.missions?.length > 0 || child?.transactions?.length > 0" class="empty-msg color-red">
        미션 및 지출을 진행한 기록이 감지되었습니다. <br>
        해당 계정의 재매칭은 진행이 불가합니다. <br>
      </div>
      <div v-else>
        <div class="data-form">
          <label for="password">
            가족코드
            <span v-if="errMsg.fam_code" class="err-msg">
              {{ errMsg.fam_code }}
            </span>
          </label>
          <input v-model="matchInfo.fam_code" :class="{ 'err-border' : errMsg.fam_code }" type="text" name="fam_code" id="fam_code" autocomplete="off" required>
        </div>

        <div class="consent-form">
          <span class="color-red">
            부모지정을 잘못하여 가입된 경우를 대비하여 재매칭을 진행할 수 있습니다. <br> 
            &nbsp;미션이나 지출을 한번도 진행하지 않을 시에만 부모 재매칭이 가능합니다. <br> 
            <!-- 부모 교체는 3회로 제한되며, 초과할 시 재매칭을 더이상 진행할 수 없습니다. <br> -->
            <!-- 보다 신중히 선택을 하시기를 바랍니다. <br> -->
          </span>

          <div class="consent-group">
            <!-- <input v-model="isChecked" type="checkbox" name="consent" id="consent" autocomplete="off" required>
            <label for="consent">안내 사항을 모두 확인하였으며, 이에 동의합니다.</label> -->
          </div>

          <button @click="nextMatch" type="button" class="btn-submit">부모 매칭</button>
        </div>
      </div>

      <MatchingModalComponent
        :sendInfo="matchInfo"
        message="님이 맞습니까?"
        action="modifyReMatching"
      />
      
    </div>
  </div>
</template>

<script setup>
import { computed, onBeforeMount, reactive, ref } from 'vue';
import { useStore } from 'vuex';
import MatchingModalComponent from '../../modal/MatchingModalComponent.vue';

  const store = useStore();

  // 에러 정보 ---------------------------------------------------------------------------------------------
  const errMsg = computed(() => store.state.auth.errMsg);

  // 자녀 정보 ---------------------------------------------------------------------------------------------
  const child = computed(() => store.state.auth.childInfo);

  const matchInfo = reactive({
    fam_code: null,
    parent_id: null,
  });
  
  onBeforeMount(() => {
    store.dispatch('auth/childManyInfo'); // 자녀 정보 로드
  });
  
  const nextMatch = () => {
    if(Object.values(errMsg).some(value => value !== '' || value !== null || value !== undefined)) {
      store.commit('auth/resetErrMsg');
    }
  
    // 체크박스 검사
    // if(!isChecked.value) {
    //   alert('안내 사항에 동의해주세요.');
    //   return;
    // }
    
    store.dispatch('auth/childReMatching', matchInfo);
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
    background-color: #0da80d;
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