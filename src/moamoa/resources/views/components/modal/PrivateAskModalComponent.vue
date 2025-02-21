<template>
  <div v-show="privateModalFlg" class="base-modal-overlay">
    <div class="base-modal-box">
      <div class="base-modal-content">
        <p>{{ modalMsg }}</p>
      </div>

      <div v-if="!isConfirm" class="base-modal-btn">
        <button @click="confirm" type="button" class="base-modal-submit">확인</button>
        <button @click="close" type="button" class="base-modal-cancel">취소</button>
      </div>

      <div v-else class="base-modal-btn">
        <button @click="close" type="button" class="base-modal-cancel">닫기</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useStore } from 'vuex';

  // 스토어 ---------------------------------------------------------------------------------------------
  const store = useStore();
  
  // 모달 스위칭 ---------------------------------------------------------------------------------------------
  const privateModalFlg = computed(() => store.state.auth.privateModalFlg);

  // 넘어온 프롭스 ---------------------------------------------------------------------------------------------
  const props = defineProps({
    msg: {
      type: String,
      default: '해당 기능을 실행하시겠습니까?'
    },
    successMsg: {
      type: String,
      default: '해당 기능이 완료되었습니다.'
    },
  });

  // 메세지 세팅---------------------------------------------------------------------------------------------
  
  // 메세지 넣을 변수 초기화
  const modalMsg = ref('');
  
  // 초기 메세지 설정
  modalMsg.value = props.msg;


  // 이벤트 처리 ---------------------------------------------------------------------------------------------

  // 상태 이벤트 생성
  const emit = defineEmits(['confirm', 'cancel']);

  // 확인 상태체크
  const isConfirm = ref(false);

  // 확인 이벤트
  const confirm = () => {
    // 확인 상태 변경
    isConfirm.value = true;
    modalMsg.value = props.successMsg;
    
    // 부모 컴포넌트의 확인 이벤트 실행
    emit('confirm');
  };
  
  // 취소 이벤트
  const close = () => {
    // 부모 컴포넌트의 취소 이벤트 실행
    emit('cancel');
  };

</script>

<style scoped>
  /* 버튼 손모양 */
  button {
    cursor: pointer;
  }

  /* 뒷배경 */
  .base-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.3);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
  }

  /* 모달 박스 */
  .base-modal-box {
    background-color: #fff;
    padding: 25px;
    border-radius: 10px;
    width: 430px;
    height: 330px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    border: 3px solid #A2CAAC;
  }

  /* 각 넓이 설정 */
  .base-modal-content, .base-modal-btn {
    width: 100%;
  }

  /* 모달 내용 */
  .base-modal-content {
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    font-size: 1.4rem;
    height: 100%;
  }

  /* 버튼 중앙 정렬 */
  .base-modal-btn {
    display: flex;
    justify-content: center;
    align-items: center;
    column-gap: 75px;
  }

  /* 각 버튼 설정 */
  .base-modal-btn > button {
    padding: 12px 40px;
    border: none;
    border-radius: 5px;
    font-size: 1.1rem;
  }

  /* 확인 버튼 */
  .base-modal-submit {
    background-color: #A2CAAC;
    color: #fff;
  }

  /* 취소 버튼 */
  .base-modal-cancel {
    background-color: #F3F3F3;
  }
  
  /* ------------------------------------------------------------------------ */

  @media(max-width: 390px) {
    .base-modal-overlay {
      white-space: wrap;
    }

    .base-modal-content {
      font-size: 1.25rem;
    }
  }
</style>