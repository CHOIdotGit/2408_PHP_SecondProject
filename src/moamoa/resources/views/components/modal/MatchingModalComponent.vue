<template>
  <!-- 모달 뒷배경 DIV -->
  <div v-show="matchingModalFlg" class="matching-modal-overlay">
    <!-- 모달 박스 DIV -->
    <div class="matching-modal-box">
      <!-- 닫기 X 버튼 DIV -->
      <div class="modal-btn-cancel">
        <img @click="closeMatchingModal" src="/img/icon-close-black.webp">
      </div>
      
      <!-- 모달 내용 DIV -->
      <div class="matching-modal-main">
        <!-- 부모 아이콘 DIV -->
        <div class="matching-modal-icon">
            <!-- <img src="/user-img/default9.webp"> -->
            <img :src="parentInfo?.profile">
        </div>
        
        <!-- 텍스트 표시 DIV -->
        <div class="matching-modal-content">
          <h3>
            <span>{{ parentInfo?.name }}</span>
            님의 자녀입니까?
          </h3>
        </div>

        <!-- 버튼 그룹 DIV -->
        <div class="matching-modal-btn">
          <button @click="closeMatchingModal" type="button">
            <img src="/img/icon-close-black.webp">
            아닙니다
          </button>
          
          <button @click="matchingBtn" type="button">
            <img src="/img/icon-check-white.webp">
            맞습니다
          </button>
        </div>
        
      </div>
    </div>
  </div>
</template>



<script setup>
import { computed, defineEmits } from 'vue';
import { useStore } from 'vuex';

  // 스토어 ---------------------------------------------------------------------------------------------
  const store = useStore();

  // 이벤트 정의
  const emit = defineEmits(['matchingParentId']);
  
  // 부모 정보 ---------------------------------------------------------------------------------------------
  const parentInfo = computed(() => store.state.auth.matchingInfo);
  
  // 모달 관련 ---------------------------------------------------------------------------------------------
  
  // 모달 스위치
  const matchingModalFlg = computed(() => store.state.auth.matchingModalFlg);

  // 모달 닫기
  const closeMatchingModal = () => {
    store.commit('auth/setMatchingModalFlg', false);
  }

  // 매칭 완료 ---------------------------------------------------------------------------------------------
  
  // 맞습니다 버튼
  const matchingBtn = () => {
    emit('matchingParentId', parentInfo.value.parent_id);
    store.commit('auth/setIsMatchingPass', true);
    closeMatchingModal();
  }


</script>

<style scoped>
  /* 버튼 커서 손모양 */
  button {
    cursor: pointer;
  }

  /* 뒷배경 */
  .matching-modal-overlay {
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
  .matching-modal-box {
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
  .matching-modal-main {
    display: flex;
    align-items: center;
    flex-direction: column;
    width: 100%;
    height: 100%;
    padding: 0 20px;
  }

  /* 부모 이미지 영역 */
  .matching-modal-icon {
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;
    width: 110px;
    height: 110px;
    margin: 35px 0 40px 0;
    background-color: #EFF6FF;
  }
  /* 부모 이미지 설정 */
  .matching-modal-icon > img {
    width: 80%;
    height: 80%;
  }

  /* 텍스트 표시 영역 */
  .matching-modal-content {
    text-align: center;
    font-size: 1.5rem;
    margin-bottom: 45px;
  }
  /* 이름 색변경 */
  .matching-modal-content span {
    color: #00a500;
    padding: 0;
    margin: 0;
  }

  /* 아래 버튼 영역 */
  .matching-modal-btn {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 90%;
  }
  /* 버튼 크기 설정 */
  .matching-modal-btn > button {
    width: 50%;
    font-size: 0.95rem;
    padding: 17px 30px;
    border-radius: 10px;
    border: none;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  /* 완료 버튼 세팅 */
  .matching-modal-btn > button:nth-child(2) {
    background-color: #3B82F6;
    color: #fff;
  }
  /* 완료 버튼 호버 */
  .matching-modal-btn > button:nth-child(2):hover {
    background-color: #2563EB;
  }
  /* 재전송 버튼 세팅 */
  .matching-modal-btn > button:nth-child(2) {
    margin-left: 15px;
  }
  /* X 이미지 설정 */
  .matching-modal-btn > button:nth-child(1) > img {
    width: 13px;
    height: 13px;
    padding: 0 2px 2px 0;
    margin-right: 5px;
  }
  /* 체크 이미지 설정 */
  .matching-modal-btn > button:nth-child(2) > img {
    width: 17px;
    height: 17px;
    padding: 0 3px 3px 0;
    margin-right: 4px;
  }


  
</style>