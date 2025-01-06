<template>
  <!-- 중앙 박스 컨테이너 DIV -->
  <div class="container">

      <!-- 로고 정렬용 DIV -->
      <div class="logo-img">
        <router-link to="/">
          <img src="/user-img/logo.png">
        </router-link>
      </div>

      <!-- 중앙 세로선 DIV -->
      <div class="center-line">
          <hr>
      </div>

      <!-- 입력 폼 DIV -->
      <div class="data-form">
          <!-- 버튼 DIV -->
          <div class="data-button">
            <router-link to="/regist/parent">
              <button type="button">보호자</button>
            </router-link>
          </div>
          
          <div class="data-button">
            <router-link to="/regist/child">
              <button type="button">자녀</button>
            </router-link>
          </div>
      </div>
  </div>
</template>

<script setup>
import { onBeforeMount } from 'vue';
import { useStore } from 'vuex';

  // 혹시모르니 초기화해둠
  onBeforeMount(() => {
    const store = useStore();
    if(store.state.auth.registInfo || store.state.auth.errMsg) {
      store.commit('auth/resetRegist');
      store.commit('auth/resetErrMsg');
    }
  });
</script>

<style scoped>
    /* 폼 기본 설정 */
    input, button {
      outline: none; /* 아웃라인 제거 */
      border: none;
      background-color: #E8E8E8;
    }

    /* 커서 손모양으로 변경 */
    a, button {
      cursor: pointer;
    }

    /* 앱솔르트 이동을 위해 포지션 설정 */
    .position-relative {
      position: relative;
    }
    
    /* 기본 버튼 호버 */
    button:hover {
      background-color: #737373;
      color: #fff;
    }

    /* 컨테이너 */
    .container {
      background-color: #fff;
      width: 100%;
      width: 1610px;
      max-width: 90vw;
      height: 700px;
      max-height: 100vh;
      display: grid;
      grid-template-columns: 1fr 10px 1fr;
      justify-content: space-between;
      align-items: center;
    }

    /* 컨테이너 자식 DIV들 전부 중앙 정렬 */
    .container > div {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .logo-img {
      margin-bottom: 30px;
    }

    /* 로고 이미지 크기 조절 */
    .logo-img img {   
      width: 800px;
      max-width: 40vw; 
      height: auto;
      padding: 20px;
      padding-right: 0;
    }

    /* 중앙 세로선 */
    .center-line hr {
      height: 500px;
      width:.1vw;
      border: none;
      background-color:#000;
    }

    /* 입력 폼 */
    .data-form {
      flex-direction: column; /* 세로 정렬 */
      padding: 10px;
      margin: 0 auto;
    }

    /* 입력 폼 */
    .data-button {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 200px;
    }

    /* 분기 배치 버튼 */
    .data-button button {
      padding: 10px;
      width: 680px;
      max-width: 40vw;
      height: 150px;
      font-size: 2.8rem;
    }

    /* 상하 간격 띄움 */
    .data-button:nth-child(2) {
      margin-top: 50px;
    } 

    /* 모바일 설정: 화면 너비가 일정 크기 이하일 때 */
    @media(max-width: 600px) {
      .container {
        grid-template-columns: 1fr; /* 좌우 한칸으로 변경 */
        grid-template-rows: 100px 1fr; /* 상하 둘 */
        overflow-y: auto;
        overflow-x: hidden;
      }

      /* 중앙선 DIV 숨기기 */
      .container > div:nth-child(2) {
        display: none;
      }

      /* 로고 DIV */
      .logo-img {
        margin-top: 110px;
      }
      
      /* 로고 이미지 */
      .logo-img img {
        width: 300px;
        max-width: 60vw;
      }
      
      .data-button button {
        width: 470px;
        max-width: 80vw;
      }
      
      /* 메인 페이지용 버튼 배치 */
      .data-button:nth-child(1) {
        margin-top: 100px;
      } 
    }
</style>