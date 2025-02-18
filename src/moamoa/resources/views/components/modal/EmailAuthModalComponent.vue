<template>
  <!-- 모달 뒷배경 DIV -->
  <div v-show="emailModalFlg" class="email-modal-overlay">
    <!-- 모달 박스 DIV -->
    <div class="email-modal-box">
      <!-- 닫기 X 버튼 DIV -->
      <div class="modal-btn-cancel">
        <img @click="closeEmailModal" src="/img/icon-close-black.webp">
      </div>
      <!-- 모달 내용 DIV -->
      <div class="email-modal-main">
        <!-- 메일 아이콘 DIV -->
        <div class="email-modal-icon">
          <img src="/img/icon-mail.webp">
        </div>

        <!-- 이메일 텍스트 DIV -->
        <div class="email-modal-content">
          <h3>이메일 인증</h3>
          <p>이메일 인증을 위한 코드가 {{ props.email }}으로 전송되었습니다.</p>
          <p>메일을 확인해 전송된 인증코드를 입력해주세요.</p>
        </div>

        <!-- 인증코드 입력 DIV -->
        <div class="email-modal-code">
          <label for="mailCode">인증코드</label>
          <input 
            v-model="inputCode" 
            @input="inputCode = inputCode.toUpperCase()"
            type="text" 
            name="mailCode" 
            maxlength="6" 
            id="mailCode" 
            autocomplete="off" 
            required
          >
          <span>{{ Math.floor(expiryTimer / 60) }}:{{ (expiryTimer % 60).toString().padStart(2, '0') }}</span>
        </div>

        <!-- 버튼 그룹 DIV -->
        <div class="email-modal-btn">
          <button @click="completeBtn" type="button">인증완료</button>
          <button @click="reSendBtn" type="button">
            재전송
            <span v-if="reSendCnt > 0">({{ reSendCnt }}초)</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, watch } from 'vue';
import { useStore } from 'vuex';

  // 스토어 ---------------------------------------------------------------------------------------------
  const store = useStore();

  // 프롭스 전송값 ---------------------------------------------------------------------------------------------
  const props = defineProps({
    email: String,
  });

  // 모달 관련 ---------------------------------------------------------------------------------------------
  
  // 모달 스위치
  const emailModalFlg = computed(() => store.state.auth.emailModalFlg);

  // 모달 닫기
  const closeEmailModal = () => {
    store.commit('auth/setEmailModalFlg', false);
  }

  // 변수 초기화 ---------------------------------------------------------------------------------------------  

  // 인증 시간 (5분)
  const expiryTimer = ref(300);

  // 타이머용 변수
  let timer;

  // 인증완료 처리 ---------------------------------------------------------------------------------------------

  // 사용자가 입력한 인증코드
  const inputCode = ref('');

  // 발급된 인증코드
  const isCode = computed(() => store.state.auth.mailCode);

  // 인증완료 버튼
  const completeBtn = () => {
    // 발급된 코드와 입력한 코드를 비교
    if(inputCode.value === isCode.value) {
      // 맞으면 모달닫고 통과 처리
      store.commit('auth/setIsEmailPass', true);
      store.commit('auth/setEmailModalFlg', false);
    }else {
      // 아니면 알림 메세지
      alert('인증 코드가 일치하지 않습니다.');
    }
  };

  // 재전송 처리 ---------------------------------------------------------------------------------------------

  // 재전송 쿨타임용 변수 (30초)
  const reSendCnt = ref(0);

  // 재전송 버튼
  const reSendBtn = () => {
    if(reSendCnt.value === 0) {
      // 쿨타임 재설정
      expiryTimer.value = 300;
      reSendCnt.value = 30;

      // 메일 재전송
      store.dispatch('auth/sendMail', props.email);

      // 쿨타임 타이머
      const coolTime = setInterval(() => {
        if(reSendCnt.value > 0) {
          reSendCnt.value--;
        }else {
          clearInterval(coolTime);
          reSendCnt.value = 0; // 0이되면 고정
        }
      }, 1000);
    }
  };

  // 타이머 관리 ---------------------------------------------------------------------------------------------

  // 모달 상태 관리
  watch(emailModalFlg, (val) => {
    // 모달 활성화하면 타이머 시작
    if(val) {
      expiryTimer.value = 300;
      startTimer();

    // 닫거나 비활성 상태라면 타이머 멈춤
    }else {
      clearInterval(timer);
    }
  });

  // 시간 타이머
  const startTimer = () => { 
    timer = setInterval(() => {
      // 타이머 감소 시작
      if(expiryTimer.value > 0) {
        expiryTimer.value--;

      // 모달창 띄워져 있을때 만료되면 작동
      }else if(emailModalFlg.value){
        // 타이머 멈춤
        clearInterval(timer);
        // 만료 메세지 출력
        alert('인증 시간이 만료되었습니다. 다시 인증을 진행해주세요.');
        closeEmailModal();
      }
    }, 1000);
  };

</script>

<style scoped>
  /* 버튼 커서 손모양 */
  button, label {
    cursor: pointer;
  }

  /* 뒷배경 */
  .email-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
  }

  /* 모달 박스 */
  .email-modal-box {
    background-color: #fff;
    padding: 25px;
    border-radius: 10px;
    width: 500px;
    height: 390px;
  }

  /* X버튼 */
  .modal-btn-cancel {
    position: relative;
  }
  /* X버튼 이미지 */
  .modal-btn-cancel > img {
    position: absolute;
    left: 98.2%;
    bottom: -8px;
    width: 15px;
    height: 15px;
    cursor: pointer;
  }

  /* 모달 내용 */
  .email-modal-main {
    display: flex;
    /* justify-content: center; */
    align-items: center;
    flex-direction: column;
    width: 100%;
    height: 100%;
    padding: 0 20px;
    /* border: 1px solid #000; */
  }

  /* 메일 아이콘 간격 */
  .email-modal-icon {
    margin-top: 5px;
  }
  /* 메일 아이콘 크기 */
  .email-modal-icon > img {
    width: 50px;
    height: 50px;
  }

  /* 텍스트 정렬 */
  .email-modal-content {
    text-align: center;
  }
  /* 제목 크기 및 간격 */
  .email-modal-content > h3 {
    font-size: 1.2rem;
    margin: 12px 0 22px 0;
  }
  /* 안내 내용 조정 */
  .email-modal-content > p {
    font-size: 0.8rem;
    margin: 9px 0;
    color: #A5A5A5;
  }

  /* 인증코드 입력박스 */
  .email-modal-code {
    position: relative;
    margin: 25px 0;
    width: 100%;
  }
  /* 인증코드 라벨 */
  .email-modal-code > label {
    position: absolute;
    left: 33px;
    top: -10px;
    font-size: 0.95rem;
    /* font-weight: 600; */
    background-color: #fff;
    padding: 3px;
  }
  /* 인증코드 입력창 */
  .email-modal-code > input {
    width: 100%;
    height: 70px;
    border: 3px solid #000;
    border-radius: 15px;
    padding: 2px 0 0 50px;
    font-size: 1.8rem;
    letter-spacing: 20px;
  }
  /* 인증코드 시간 */
  .email-modal-code > span {
    position: absolute;
    right: 25px;
    top: 20px;
    font-size: 1.15rem;
    letter-spacing: 1px;
    color: red;
  }

  /* 아래 버튼 영역 */
  .email-modal-btn {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 90%;
  }
  /* 버튼 크기 설정 */
  .email-modal-btn > button {
    width: 50%;
    font-size: 0.95rem;
    padding: 17px 30px;
    border-radius: 10px;
    border: none;
  }
  /* 완료 버튼 세팅 */
  .email-modal-btn > button:nth-child(1) {
    background-color: #3B82F6;
    color: #fff;
  }
  /* 완료 버튼 호버 */
  .email-modal-btn > button:nth-child(1):hover {
    background-color: #2563EB;
  }
  /* 재전송 버튼 세팅 */
  .email-modal-btn > button:nth-child(2) {
    margin-left: 15px;
  }
  /* 쿨타임 여백 삭제 */
  .email-modal-btn > button:nth-child(2) span {
    padding: 0;
    margin: 0;
  }
  
</style>