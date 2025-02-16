<template>
  <!-- 배경 컨테이너 DIV -->
  <div class="find-container">
    <!-- 메인 박스 DIV -->
    <div class="find-main-box">
      <div class="find-header-icon">
        <div>
          <img src="/img/icon-complete-check.webp">
        </div>

        <h1 v-if="findType === 'id'">
          아이디 찾기 완료
        </h1>

        <h1 v-else>
          비밀번호 재발급 완료
        </h1>
      </div>

      <div class="find-main-content">

        <div v-if="findType === 'id'" class="find-content-id">
          <p>회원님의 아이디를 찾았습니다</p>

          <div>
            <p>{{ userInfo.account }}</p>
          </div>
        </div>
        
        <div v-else class="find-content-pwd">
          <p>
            입력하신 정보로 회원(user1234)님의 비밀번호를 재설정 하였습니다. 
            다시 로그인 해주세요.
          </p>
        </div>
        
      </div>

      <div class="find-footer-btn">
        <router-link to="/login" class="find-link-btn">
          <button type="button" class="find-login-btn">
            로그인하기
          </button>
        </router-link>
        
        <router-link v-if="findType === 'id'" to="/find/pwd" class="find-link-btn">
          <button type="button" class="find-pwd-btn">
            비밀번호 찾기
          </button>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onBeforeMount, reactive } from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';

  const store = useStore();
  const route = useRoute();

  // 유형 정보 ---------------------------------------------------------------------------------------------
  const findType = computed(() => route.path.split('/').pop());

  const userInfo = computed(() => store.state.auth.userInfo);

  const settingUser = reactive({
    userType: sessionStorage.getItem('userType') ? sessionStorage.getItem('userType') : '',
    userId: sessionStorage.getItem('userId') ? sessionStorage.getItem('userId') : ''
  });

  onBeforeMount(() => {

    store.dispatch('auth/userInfo', settingUser);
  });

</script>

<style scoped>
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
    display: flex;
    /* justify-content: center; */
    align-items: center;
    flex-direction: column;
  }

  /* -------------------------------------------------------------------- */

  /* 헤더 박스 */
  .find-header-icon {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    /* margin-top: 10px; */
    flex-direction: column;
  }
  /* 헤더 이미지 크기 설정 */
  .find-header-icon > div {
    width: 150px;
    height: 150px;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  .find-header-icon > div > img {
    width: 100%;
    height: 100%;
  }

  /* 헤더 텍스트 설정 */
  .find-header-icon > h1 {
    font-size: 2.1rem;
    margin: 10px 0;
  }

  /* -------------------------------------------------------------------- */

  /* 메인 공통 설정 */
  .find-main-content, 
  .find-main-content > div , 
  .find-main-content > div > div {
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
  }

  /* 메인 텍스트 */
  .find-main-content > div > p {
    color: #A5A5A5;
  }

  /* ID 박스 좌우 여백 */
  .find-content-id {
    padding: 0 25px;
    margin-top: 10px;
  }
  /* 설명 텍스트 크기 */
  .find-content-id > p {
    font-size: 1.4rem;
  }
  /* 음영 박스 설정 */
  .find-content-id > div {
    font-size: 2rem;
    background-color: #f5f5f5;
    border-radius: 10px;
    margin: 20px 0;
    padding: 40px 0;
    letter-spacing: 0.1rem;
  }

  .find-content-pwd {
    height: 190px;
    padding: 0 60px;
  }

  .find-content-pwd > p {
    font-size: 1.6rem;
  }

  /* -------------------------------------------------------------------- */

  /* 하단 부분 */
  .find-footer-btn {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    column-gap: 60px;
    padding: 0 25px;
    margin-top: 30px;
  }

  .find-link-btn {
    width: 100%;
    border: none;
    border-radius: 10px;
  }
  .find-footer-btn button {
    width: 100%;
    border: none;
    border-radius: 10px;
    font-size: 1.4rem;
    padding: 25px 0;
  }

  .find-login-btn {
    background-color: #3B82F6;
    color: #fff;
  }
  .find-login-btn:hover {
    background-color: #2563EB;
  }

  .find-pwd-btn {
    background-color: #F3F3F3;
  }

</style>