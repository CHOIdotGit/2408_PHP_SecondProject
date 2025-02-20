<template>
  <!-- 배경 컨테이너 DIV -->
  <div class="ident-container">

    <div v-if="identType === 'wdrl' && (userType === 'parent' && userInfo?.children?.length > 0)" class="empty-msg">
      연결된 자녀들이 모두 회원 탈퇴를 진행해야 가능합니다.
    </div>

    <!-- 메인 박스 DIV -->
    <div v-else class="ident-main-box">
      <h2>본인확인</h2>

      <p v-if="errMsg.common" class="err-msg">
        {{ errMsg.common }}
      </p>
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
            <p>{{ userInfo.account }}</p>
          </div>

          <div>
            <input 
              v-model="identInfo.password" 
              :class="{ 'err-border' : errMsg.password }" 
              :placeholder="isMobile ? '비밀번호를 입력하세요' : ''"
              @keyup.enter="identBtn"
              type="password" 
              name="password" 
              id="password" 
              autocomplete="off" 
              required
            >
            <p v-if="errMsg.password" class="err-msg">
              {{ errMsg.password }}
            </p>
          </div>
        </div>
      </div>


      <div class="ident-main-footer">
        <p>* 본인 확인을 위해 비밀번호를 한 번 더 입력해 주세요.</p>
        <p>* 회원님의 개인정보는 본인 동의 없이 절대 공개되지 않습니다.</p>

        <div class="ident-footer-btn">
          <button @click="identBtn" type="button">확인</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onBeforeMount, reactive, ref } from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';

  const store = useStore();
  const route = useRoute();

  // 유저 정보 ---------------------------------------------------------------------------------------------
  const userInfo = computed(() => store.state.auth.parentFlg ? store.state.auth.parentInfo : store.state.auth.childInfo);

  // 에러 정보 ---------------------------------------------------------------------------------------------
  const errMsg = computed(() => store.state.auth.errMsg);
  
  // 유형 정보 ---------------------------------------------------------------------------------------------
  const identType = computed(() => route.path.split('/').pop());
  const userType = ref(route.path.split('/')[1]);

  // 패스워드 정보 ---------------------------------------------------------------------------------------------
  const identInfo = reactive({
    password: '',
    identType: identType,
    userType: userType
  });


  // 본인 확인 처리 ---------------------------------------------------------------------------------------------
  const identBtn = () => {
    // 에러 정보 리셋
    if(Object.values(errMsg).some(value => value !== '' && value !== null && value !== undefined)) {
      store.commit('auth/resetErrMsg');
    }

    // 본인확인 실행
    store.dispatch('auth/identUser', identInfo);
  }

  // 이벤트 처리 ---------------------------------------------------------------------------------------------
  
  const isMobile = ref(window.innerWidth <= 390);

  // 윈도우 크기 변경 시 반응
  window.addEventListener('resize', () => {
    isMobile.value = window.innerWidth <= 390;
  });

  onBeforeMount(() => {
    // 에러 정보 리셋
    if(Object.values(errMsg).some(value => value !== '' && value !== null && value !== undefined)) {
      store.commit('auth/resetErrMsg');
    }

    // 유저 정보 로드
    store.dispatch(store.state.auth.parentFlg ? 'auth/parentInfo' : 'auth/childInfo' );
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
    font-size: 2.2rem;
    margin-bottom: 30px;
  }

  .ident-main-box > p {
    width: 100%;
    text-align: left;
    font-size: 0.8rem;
    padding: 5px;
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
    font-size: 1.5rem;
  }

  /* 입력 내용 부분 설정 */
  .ident-item-content > div {
    display: flex;
    flex-direction: column;
    justify-content: center;
    font-size: 1.5rem;
  }
  .ident-item-content > div input {
    padding: 5px;
    margin-bottom: 5px;
    width: 440px;
    font-size: 1.4rem;
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
    height: 50px;
    border: none;
    background-color: #3B82F6;
    color: #fff;
    font-size: 1.4rem;
  }
  .ident-footer-btn > button:hover {
    background-color: #2563EB;
  }

  /* -------------------------------------------------------------------- */

  /* 안내사항 및 에러 메시지 */
  .ann-msg, .err-msg, .pass-msg {
    font-size: 0.8rem;
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

  @media(max-width: 390px) {
    .ident-container {
      white-space: wrap;
    }

    /* 메인박스 */
    .ident-main-box {
      width: 100%;
      height: 645px;
      padding: 0 10px;
    }

    /* 메인 제목 텍스트 */
    .ident-main-box > h2 {
      font-size: 2rem;
    }

    /* 메인 입력창 제목탭 숨김 */
    .ident-main-content {
      grid-template-columns: 1fr;
    }
    .ident-item-title {
      display: none;
    }

    /* 입력창 여백조정 */
    .ident-item-content > div {
      height: inherit;
      padding: 20px 10px;
      font-size: 1.3rem;
    }
    /* 입력창 조절 */
    .ident-item-content > div input {
      width: 100%;
      font-size: 1.2rem;
    }

    .ident-main-footer > p {
      font-size: 0.9rem;
    }

    /* 버튼색 변경 */
    .ident-footer-btn > button {
      background-color: #2563EB;
    }
  }

</style>