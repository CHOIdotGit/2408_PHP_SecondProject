<template>
  <div class="base-modal-overlay">
    <div class="base-modal-box">
      <div class="base-modal-content">

      </div>

      <div class="base-modal-btn">
        <button type="button" class="base-modal-submit">확인</button>
        <button type="button" class="base-modal-cancel">취소</button>
      </div>
    </div>
  </div>
</template>

<script setup>
  // 
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
    background-color: rgba(0, 0, 0, 0.5);
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
</style>