<template>
  <!-- 배경 컨테이너 DIV -->
  <div class="wdrl-container">

    <div v-if="userType === 'parent' && userInfo?.children?.length > 0" class="empty-msg">
      연결된 자녀들이 모두 회원 탈퇴를 진행해야 가능합니다.
    </div>

    <!-- 메인 박스 DIV -->
    <div v-else class="wdrl-main-box">
      <!-- 탈퇴 안내문 DIV -->
      <div class="wdrl-header-notice">
        <div class="wdrl-notice-item">
          <div>
            <img src="/img/icon-unfollow.webp">
          </div>
          <div>
            <h3>회원 탈퇴가 완료되면 회원님의 개인정보는 즉시 삭제됩니다.</h3>
            <p>
              회원 탈퇴 처리 후 회원님의 개인정보를 복원할 수 없으며, 
              기존에 가지고 있던 포인트나 내역등을 복원할 수 없고 탈퇴한 ID는 재사용이 불가합니다.
            </p>
          </div>
        </div>

        <hr>

        <div class="wdrl-notice-item">
          <div>
            <img src="/img/icon-speech-bubble.webp">
          </div>
          <div>
            <h3>게시판에 등록하신 관련 게시물도 같이 삭제됩니다.</h3>
            <p>
              탈퇴 후에는 회원정보가 삭제되어 본인과 연관된 게시물도 같이 삭제됩니다. 
              탈퇴 하시기 전에 중요 정보가 있는 게시물이 있다면, 반드시 확인하시기 바랍니다. 
              <!-- 게시물 등의 복원을 원하시는 경우에는 회원 탈퇴 처리 후 30일 이내에 반드시 회원계정의 복원을 진행하시기 바랍니다. -->
            </p>
          </div>
        </div>
      </div>
      
      <!-- 설문조사 DIV -->
      <!-- <div class="wdrl-main-survey">
        <h2>회원 탈퇴 사유를 알려주시면, 보다 나은 서비스를 위해 소중한 정보로 활용하겠습니다.</h2>

        <div class="wdrl-survey-box">
          <div>
            <div class="wdrl-survey-item">
              <input type="radio" name="survey" id="survey-1" value="1">
              <label for="survey-1">콘텐츠 품질 불만</label>
            </div>

            <div class="wdrl-survey-item">
              <input type="radio" name="survey" id="survey-2" value="2">
              <label for="survey-2">고객 서비스 불만</label>
            </div>

            <div class="wdrl-survey-item">
              <input type="radio" name="survey" id="survey-3" value="3">
              <label for="survey-3">개인정보 노출 우려</label>
            </div>
            
            <div class="wdrl-survey-item">
              <input type="radio" name="survey" id="survey-4" value="4">
              <label for="survey-4">접속 에러 등 시스템상의 불만</label>
            </div>
            
            <div class="wdrl-survey-item">
              <input type="radio" name="survey" id="survey-5" value="5">
              <label for="survey-5">개인적인 인터넷 환경의 어려움</label>
            </div>
          </div>

          <div class="wdrl-survey-etc">
            <input type="radio" name="survey" id="survey-6" value="6">
            <label for="survey-6">기타</label>
            <input type="text" name="etc" id="survey-6" autocomplete="off">
          </div>
        </div>
      </div> -->
      
      <!-- 체크박스 및 탈퇴 버튼 DIV -->
      <div class="wdrl-footer-group">
        <div class="wdrl-footer-check">
          <input v-model="isChecked" type="checkbox" name="agree" id="agree" autocomplete="off" required>
          <label for="agree">안내 사항을 모두 확인하였으며, 이에 동의합니다.</label>
        </div>

        <div class="wdrl-footer-btn">
          <button @click="removeBtn" type="button">회원탈퇴</button>
        </div>
      </div>
    </div>
  </div>

  <PrivateAskModalComponent
    v-if="privateModalFlg"
    :msg="'해당 계정의 회원 탈퇴가 진행됩니다.'"
    :successMsg="'　회원탈퇴 신청처리가 완료되었습니다. 로그인으로 이동합니다.'"
    @confirm="runningDelete()"
    @cancel="store.commit('auth/setPrivateModalFlg', false)"
  />
</template>

<script setup>
  import PrivateAskModalComponent from '../../modal/PrivateAskModalComponent.vue';
  import { computed, onBeforeMount, ref } from 'vue';
  import { useStore } from 'vuex';
  import { useRoute } from 'vue-router';

  const store = useStore();
  const route = useRoute();

  // 에러 정보 ---------------------------------------------------------------------------------------------
  // const errMsg = computed(() => store.state.auth.errMsg);

  // 모달 스위칭
  const privateModalFlg = computed(() => store.state.auth.privateModalFlg);

  // 유저 정보 ---------------------------------------------------------------------------------------------
  const userInfo = computed(() => store.state.auth.parentFlg ? store.state.auth.parentInfo : store.state.auth.childInfo);
  const userType = ref(route.path.split('/')[1]);
  
  const isChecked = ref(false);
  const removeBtn = () => {
    // 체크박스 검사
    if(!isChecked.value) {
      alert('안내 사항에 동의해주세요.');
      return;
    }
    
    store.commit('auth/setPrivateModalFlg', true);
  };

  // 탈퇴 실행
  const runningDelete = () => {
    store.dispatch('auth/removeUser');
  };
  

  // 이벤트 처리 ---------------------------------------------------------------------------------------------
  
  onBeforeMount(() => {
    // 유저 정보 로드
    store.dispatch(store.state.auth.parentFlg ? 'auth/parentInfo' : 'auth/childInfo' );
  });
    

</script>

<style scoped>

  /* 버튼 손모양 */
  button, input[type="radio"], label {
    cursor: pointer;
  }
  
  /* 배경 설정 */
  .wdrl-container {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  /* 메인 박스 */
  .wdrl-main-box {
    width: 980px;
    height: 860px;
    padding: 15px 55px;
    display: flex;
    align-items: center;
    flex-direction: column;
    justify-content: center;
    /* border: 1px solid #000; */
  }

  .empty-msg {
    width: 980px;
    height: 860px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 1.8rem;
    color: red;
    padding-bottom: 90px;
  }
  
  /* -------------------------------------------------------------------- */

  /* 안내문 박스 */
  .wdrl-header-notice {
    width: 100%;
    border-top: 2.5px solid #000;
    border-bottom: 2.5px solid #000;
  }

  /* 안내문 상하 중앙 */
  .wdrl-header-notice > div {
    display: flex;
    align-items: center;
  }

  /* 중간 선 */
  .wdrl-header-notice > hr {
    width: 100%;
    height: .1vh;
    border: none;
    background-color: #d7d6d6;
  }

  /* 아이콘 영역 중앙 정렬 */
  .wdrl-notice-item > div:nth-child(1) {
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  /* 첫번째 아이콘 크기 설정 */
  .wdrl-notice-item:nth-child(1) > div:nth-child(1) {
    width: 320px;
    height: 200px;
    margin: 20px 5px 20px 20px;
  }

  /* 두번째 아이콘 크기 설정 */
  .wdrl-notice-item:nth-child(3) > div:nth-child(1) {
    width: 280px;
    height: 160px;
    margin: 21px 30px 35px 32px;
  }
  
  /* 아이콘 이미지 크기 설정 */
  .wdrl-notice-item > div:nth-child(1) > img {
    width: 100%;
    height: 100%;
  }
  
  /* 안내문 설명 박스 */
  .wdrl-notice-item > div:nth-child(2) {
    height: 180px;
    padding: 30px 0;
  }
  
  /* 안내문 제목 설정 */
  .wdrl-notice-item > div:nth-child(2) > h3 {
    font-size: 1.6rem;
  }

  /* 안내문 설명 설정 */
  .wdrl-notice-item > div:nth-child(2) > p {
    font-size: 1rem;
    padding: 20px 40px 0 0;
    color: #A5A5A5;
    line-height: 1.5;
  }

  /* -------------------------------------------------------------------- */

  .wdrl-main-survey {
    width: 100%;
    margin: 50px 0;
    display: flex;
    justify-content: center;
    flex-direction: column;
    /* align-items: center; */
  }
  .wdrl-main-survey > h2 {
    font-size: 1.4rem;
  }

  .wdrl-survey-box {
    width: 100%;
    height: 200px;
    background-color: #EAEAEA;
    margin: 15px 0;
    padding: 20px;
  }
  .wdrl-survey-box > div:nth-child(1) {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start;
  }

  .wdrl-survey-item, .wdrl-survey-etc {
    margin-top: 20px;
  }
  .wdrl-survey-item {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  input[type="radio"] {
    border: 0.5px solid #000;
    outline: none;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    appearance: none;
    -webkit-appearance: none;
  }
  input[type="radio"]:checked {
    background-color: transparent;
  }
  input[type="radio"]:checked::before {
    content: '';
    display: block;
    width: 9px;
    height: 9px; 
    border-radius: 50%; 
    background-color: blue; 
    position: relative;
    top: 7px; 
    left: 7px;
  }

  .wdrl-survey-item > label {
    margin-left: 5px;
    font-size : 1.2rem;
    width: 245px;
  }

  .wdrl-survey-etc {
    width: 100%;
    font-size: 1.2rem;
    display: flex;
    align-items: center;
  }

  .wdrl-survey-etc > input[type="text"] {
    width: 90%;
    height: 30px;
    margin-left: 10px;
    padding: 5px;
    font-size: 1rem;
  }

  /* -------------------------------------------------------------------- */

  .wdrl-footer-group {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
  }

  .wdrl-footer-check {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 80px;
    font-size: 1.4rem;
  }
  
  .wdrl-footer-check > input[type="checkbox"] {
    width: 22px;
    height: 22px;
    margin-right: 5px;
  }

  .wdrl-footer-btn {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 40px;
  }
  .wdrl-footer-btn > button {
    padding: 10px;
    width: 120px;
    height: 50px;
    border: none;
    background-color: #ff5555;
    color: #fff;
    font-size: 1.2rem;
  }
  .wdrl-footer-btn > button:hover {
    background-color: #ff0000;
  }
  
</style>