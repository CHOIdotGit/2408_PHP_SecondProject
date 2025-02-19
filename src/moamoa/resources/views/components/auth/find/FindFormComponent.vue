<template>
  <!-- 배경 컨테이너 DIV -->
  <div class="find-container">
    <!-- 메인 박스 DIV -->
    <div class="find-main-box">

      <!-- 찾기 선택 헤더 DIV -->
      <div class="find-header-select">
        <!-- 아이디 찾기 DIV -->
        <div :class="{'find-left-selected': findType === 'id', 'find-left-inactive': findType === 'pwd' }">
          <router-link :class="{ 'find-link-inactive': findType === 'pwd' }" to="/find/id" class="find-link-id">
            아이디 찾기
          </router-link>
        </div>

        <!-- 비밀번호 찾기 DIV -->
        <div :class="{'find-right-selected': findType === 'pwd', 'find-right-inactive': findType === 'id' }">
          <router-link :class="{ 'find-link-inactive': findType === 'id' }" to="/find/pwd" class="find-link-pwd">
            비밀번호 재발급
          </router-link>
        </div>
      </div>

      <!-- 메인 폼 DIV -->
      <div class="find-main-form">
        <div>
          <h2 v-if="findType === 'id'">
            > 아이디 찾기
          </h2>
          <h2 v-else>
            > 비밀번호 재발급
          </h2>
          
          <p v-if="errMsg.common" class="err-msg">
            {{ errMsg.common }}
          </p>
        </div>

        <!-- 인풋 그룹 DIV -->
        <div class="find-input-group">
          <div v-if="findType === 'pwd'">
            <input v-model="findInfo.account" type="text" name="account" id="account" placeholder="아이디를 입력하세요" autocomplete="off" required>
          </div>

          <div>
            <input v-model="findInfo.name" type="text" name="name" id="name" placeholder="이름을 입력하세요" autocomplete="off" required>
          </div>

          <div>
            <input v-model="findInfo.email" @input="onlyEmail" type="email" name="email" id="email" placeholder="이메일을 입력하세요" autocomplete="off" required>
          </div>
        </div>
      </div>

      <div class="find-footer-btn">
        <p v-if="findType === 'id'">
          * 일치하는 계정 정보로 이메일이 발송됩니다.
        </p>
        
        <div>
          <button @click="findBtn" type="button">확인</button>
          <router-link to="/login" class="find-btn-cancel">
            <button type="button">취소</button>
          </router-link>
        </div>
      </div>
    </div>
  </div>


  <!-- 이메일 인증 모달 -->
  <EmailAuthModalComponent 
    :email="findInfo.email"
  />

</template>

<script setup>
import EmailAuthModalComponent from '../../modal/EmailAuthModalComponent.vue';
import { computed, onBeforeMount, reactive, watch } from 'vue';
import { useStore } from 'vuex';
import { useRoute, useRouter } from 'vue-router';

  const store = useStore();
  const route = useRoute();
  const router = useRouter();
  
  // 에러 정보 ---------------------------------------------------------------------------------------------
  const errMsg = computed(() => store.state.auth.errMsg);

  // 유형 정보 ---------------------------------------------------------------------------------------------
  const findType = computed(() => route.path.split('/').pop());

  // 입력 정보 ---------------------------------------------------------------------------------------------
  const findInfo = reactive({
    account: null,
    name: '',
    email: '',
    findType: findType,
  });
  
  // 이메일은 @와 .하나씩 그리고 영문숫자만 입력가능 ---------------------------------------------------------------------------------------------
  const onlyEmail = () => {
    findInfo.email = findInfo.email
      .replace(/@+/g, '@')   // `@`가 두 번 이상 입력되면 하나로 변경
      .replace(/\.+/g, '.'); // `.`이 두 번 이상 입력되면 하나로 변경

    // 영문 숫자 이외에는 입력되면 안됨
    findInfo.email = findInfo.email.replace(/[^a-zA-Z0-9@.]/g, '');

    // 입력한 이메일 내에 `@`와 `.`이 하나씩만 있는지 확인
    if((findInfo.email.match(/@/g) || []).length > 1) {
      // `@`가 두 번 이상이면 첫 번째만 남기고 나머지 제거
      findInfo.email = findInfo.email.replace('@', '');  
    }

    // `.`이 두 번 이상이면 첫 번째만 남기고 나머지 제거
    else if((findInfo.email.match(/\./g) || []).length > 1) {
      findInfo.email = findInfo.email.replace('.', '');  
    };
  };

  
  // 입력값 처리 --------------------------------------------------------------------------------------------------------------------------------

  // 확인 버튼
  const findBtn = () => {
    // 에러 정보 리셋
    if(Object.values(errMsg).some(value => value !== '' && value !== null && value !== undefined)) {
      store.commit('auth/resetErrMsg');
    }

    store.dispatch('auth/findUser', findInfo);
  };

  
  // 이벤트 처리 ----------------------------------------------------------------------------------------------------------------------------------
 
  // 인증 통과 여부
  const isEmailPass = computed(() => store.state.auth.isEmailPass);

  // 통과됫으면 페이지 이동
  watch(() => isEmailPass.value, (pass) => {
    if(pass) {
      router.push(findType.value === 'id' ? '/find/complete/id' : '/find/reset/pwd');
    }
  });

  onBeforeMount(() => {
    // 에러 정보 리셋
    if(Object.values(errMsg).some(value => value !== '' && value !== null && value !== undefined)) {
      store.commit('auth/resetErrMsg');
    }
  });


</script>

<style scoped>

  /* 버튼 설정 */
  button {
    cursor: pointer;
  }

  /* 배경 컨테이너 */
  .find-container {
    background-image: url('/img/background.png');
    background-size: cover;
    background-repeat: no-repeat;
    width: 100vw;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  /* 메인 컨텐츠 박스 */
  .find-main-box {
    width: 700px;
    height: 600px;
    background-color: #fff;
    border-radius: 10px;
    padding: 35px 20px;
  }

  /* -------------------------------------------------------------------- */
  
  /* 헤더 박스 */
  .find-header-select {
    width: 100%;
    display: grid;
    grid-template-columns: 1fr 1fr;
    justify-content: center;
    align-items: center;
  }
  /* 헤더 중앙정렬 */
  .find-header-select > div {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100px;

  }
  
  /* 헤더 아이디, 비밀번호 */
  .find-link-id, .find-link-pwd {
    width: 100%;
    height: 100%;
    border: none;
    font-size: 1.6rem;
    font-weight: 600;
    background-color: transparent;
    text-decoration: none;
    color: #000;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .find-link-id {
    border-right: 1px solid #000;
  }
  .find-link-inactive {
    color: #aaa;
  }

  /* 헤더 왼쪽 버튼 활성, 비활성 */
  .find-left-selected {
    border-left: 1px solid #000;
    border-top: 3px solid #3B82F6;
  }
  .find-left-inactive {
    border-left: 1px solid #ccc;
    border-top: 1px solid #ccc;
    border-bottom: 1px solid #000;
    background-color: #ECECEC;
  }

  /* 헤더 오른쪽 버튼 활성, 비활성 */
  .find-right-selected {
    border-right: 1px solid #000;
    border-top: 3px solid #3B82F6;
  }
  .find-right-inactive {
    border-right: 1px solid #ccc;
    border-top: 1px solid #ccc;
    border-bottom: 1px solid #000;
    background-color: #ECECEC;
  }

  /* -------------------------------------------------------------------- */

  /* 메인 폼 */
  .find-main-form {
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .find-main-form > div:nth-child(1) {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  /* 메인 타이틀 */
  .find-main-form h2 {
    font-size: 1.4rem;
    margin: 40px 0 20px 10px;
  }

  /* 입력 컨텐츠 박스 */
  .find-input-group {
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    border-top: 2.5px solid #CCC;
    padding-top: 2px;
  }
  /* 입력 간격 */
  .find-input-group > div {
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    border-bottom: 1.2px solid #ECECEC;
    padding: 0 20px;
  }
  /* 입력 내용 */
  .find-input-group input {
    width: 100%;
    padding: 10px;
    font-size: 1.3rem;
    border: 1px solid #aaa;
    margin: 20px;
  }
  
  /* -------------------------------------------------------------------- */

  /* 하단 부분 */
  .find-footer-btn {
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  /* id 추가 텍스트 */
  .find-footer-btn > p {
    font-size: 1.2rem;
    margin: 45px 0 20px 0;
    color: #acacac;
  }

  /* 하단 버튼 그룹 */
  .find-footer-btn > div {
    display: flex;
    justify-content: center;
    align-items: center; 
    column-gap: 30px;
  }

  /* 하단 버튼 설정 */
  .find-footer-btn > div > button, .find-btn-cancel > button {
    margin-top: 28px;
    padding: 10px;
    width: 120px;
    border: none;
    font-size: 1.2rem;
  }
  /* 확인 버튼 설정 */
  .find-footer-btn > div > button {
    background-color: #3B82F6;
    color: #fff;
  }
  /* 확인 버튼 호버 */
  .find-footer-btn > div > button:hover {
    background-color: #2563EB;
  }

  /* 취소 버튼 설정 */
  .find-btn-cancel > button {
    background-color: #F3F3F3;
  }

  /* 에러 메세지 */
  .err-msg {
    font-size: 1.2rem;
    padding-right: 13px;
    margin-top: 28px;
    color: #ff0000;
  }


</style>