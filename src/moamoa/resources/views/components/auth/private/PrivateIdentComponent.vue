<template>
  <!-- 배경 컨테이너 DIV -->
  <div class="ident-container">
    <div class="ident-main-box">
      <h2>본인확인</h2>

      <div class="ident-main-content">
        <div class="ident-item-title">
          <div>
            <label>아이디</label>
          </div>
          <div>
            <label for="password">
              비밀번호
            </label>
          </div>
        </div>
  
        <div class="ident-item-content">
          <div>
            <p>user1234</p>
          </div>

          <div>
            <input type="password" name="password" id="password" autocomplete="off" required>
            <!-- <p class="ann-msg">권장 비밀번호</p> -->
          </div>
        </div>
      </div>


      <div class="ident-main-footer">
        <p>* 본인 확인을 위해 비밀번호를 한 번 더 입력해 주세요.</p>
        <p>* 회원님의 개인정보는 본인 동의 없이 절대 공개되지 않습니다.</p>

        <div class="ident-footer-btn">
          <button type="button">확인</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onBeforeMount } from 'vue';
import { useStore } from 'vuex'

  const store = useStore();

  // 에러 정보 ---------------------------------------------------------------------------------------------
  const errMsg = computed(() => store.state.auth.errMsg);
  
  // 이벤트 처리 ---------------------------------------------------------------------------------------------
  
  onBeforeMount(() => {
    // 에러 정보 리셋
    if(Object.values(errMsg).some(value => value !== '' || value !== null || value !== undefined)) {
      store.commit('auth/resetErrMsg');
    }
  });
  
</script>

<style scoped>

  /* 버튼 손모양 */
  button {
    cursor: pointer;
  }

  /* 인풋 기본설정 */
  input {
    border: 1px solid #CCC;
  }

  /* 포커스 시 강조표시 */
  input:focus {
    border: 1.9px solid #101010;
    border-radius: 2px;
    outline: none;
  }
  
  /* 배경 설정 */
  .ident-container {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  /* 메인 박스 */
  .ident-main-box {
    width: 980px;
    height: 860px;
    padding: 15px 55px;
    display: flex;
    align-items: center;
    flex-direction: column;
    justify-content: center;
  }

  /* -------------------------------------------------------------------- */

  /* 본인확인 텍스트 */
  .ident-main-box > h2 {
    font-size: 2rem;
    margin-bottom: 30px;
  }

  /* 입력 문단 셀나눔 */
  .ident-main-content {
    display: grid;
    grid-template-columns: 0.4fr 1fr;
    border-top: 2.5px solid #cccccc;
    border-bottom: 1.2px solid #ECECEC;
    width: 100%;
  }

  /* 공용 넓이 설정 */
  .ident-item-title > div, .ident-item-content > div {
    border-top: 1.2px solid #ECECEC;
    height: 78px;
    padding: 20px;
  }

  /* 입력 타이틀 부분 설정 */
  .ident-item-title > div {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #F6F6F6;
    font-size: 1.2rem;
  }

  /* 입력 내용 부분 설정 */
  .ident-item-content > div {
    display: flex;
    flex-direction: column;
    justify-content: center;
    font-size: 1.4rem;
  }
  .ident-item-content > div input {
    padding: 5px;
    margin-bottom: 5px;
    width: 420px;
    font-size: 1.2rem;
    border: 1px solid #CCC;
  }

  /* -------------------------------------------------------------------- */

  /* 하단 내용 */
  .ident-main-footer {
    margin-top: 30px;
  }
  .ident-main-footer > p {
    font-size: 1.1rem;
    color: #a5a5a5;
  }
  .ident-main-footer > p:nth-child(2) {
    margin: 8px 0;
  }

  /* 확인 버튼 */
  .ident-footer-btn {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 50px 0;
  }
  .ident-footer-btn > button {
    padding: 10px;
    width: 120px;
    border: none;
    background-color: #3B82F6;
    color: #fff;
    font-size: 1.1rem;
  }
  .ident-footer-btn > button:hover {
    background-color: #2563EB;
  }

  /* -------------------------------------------------------------------- */

  /* 안내사항 및 에러 메시지 */
  .ann-msg, .err-msg, .pass-msg {
    font-size: 0.68rem;
    padding-top: 3.5px;
  }

  /* 에러 메세지 */
  .err-msg {
    color: #ff0000;
  }

  /* 빨간 테두리 */
  .err-border {
    border: 2px solid rgba(255, 0, 0, 0.6);
    box-shadow: 0 0 3px #ff0000;
  }

  /* 테두리 있는건 포커스 비활성화 */
  input.err-border:focus,
  input.pass-border:focus {
    border: none;
  }
  


  /* 문단들 간격설정 */
  /* .ident-main-box > div {
    display: flex;
    justify-content: center;
    flex-direction: column;
    width: 100%;
    padding: 20px;
  } */

  /* 외곽선 설정 */
  /* .ident-main-box > div:nth-child(2) {
    border-top: 2.5px solid #cccccc;
  }
  .ident-main-box > div:nth-child(3) {
    border-top: 1.2px solid #ECECEC;
    border-bottom: 1.2px solid #ECECEC;
  } */

</style>