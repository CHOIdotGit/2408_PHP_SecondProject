<template>
  <!-- 중앙 박스 컨테이너 DIV -->
  <div class="container">
    <!-- 로고 정렬용 DIV -->
    <div class="logo-img">
      <img src="/img/logo.png">
    </div>

    <!-- 중앙 세로선 DIV -->
    <div class="center-line">
      <hr>
    </div>

    <!-- 입력 폼 DIV -->
    <div class="data-form position-relative">
      <div class="data-info">
        <p>회원가입 성공</p>
        <p>로그인 페이지에서 로그인을 해주세요!</p>
        <!-- <p>메인페이지로 이동해 주세요!</p> -->
      </div>

      <div class="next-btn">
        <router-link to="/">
          <button type="button">로그인으로 >&nbsp;</button>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onBeforeMount, onBeforeUnmount, onMounted } from 'vue';
import { useStore } from 'vuex';

  // 혹시모르니 초기화해둠
  onBeforeMount(() => {
    const store = useStore();
    if(store.state.auth.registInfo || store.state.auth.errMsg) {
      store.commit('auth/resetRegist');
      store.commit('auth/resetErrMsg');
    }
  });

  const preventBackNavigation = (e) => {
    e.preventDefault(); // 뒤로 가기 방지
    history.pushState(null, '', window.location.href); // 페이지 URL을 강제로 다시 설정
  }

  onMounted(() => {
    // 뒤로, 앞으로 버튼 이동시
    window.addEventListener('popstate', preventBackNavigation);
    
    // 히스토리 상태 변경을 강제로 만들어서 뒤로 가기를 막음
    history.pushState(null, '', window.location.href);
  });

  // 다음 컴포넌트로 넘어갈 때 이벤트를 제거
  onBeforeUnmount(() => {
    window.removeEventListener('popstate', preventBackNavigation);
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

  /* 컨테이너 */
  .container {
    background-color: #fff;
    width: 100%;
    width: 1610px;
    max-width: 90vw;
    height: 700px;
    max-height: 100vh;
  }

  /* 앱솔르트 이동을 위해 포지션 설정 */
  .position-relative {
    position: relative;
  }

  /* 암색 배경 */
  .data-form {
    background-color: #E8E8E8;
    width: 710px;
    max-width: 38vw;
    height: 500px;
  }

  /* 텍스트 문단 */
  .data-info {
    text-align: center;
    font-size: 2.5rem;
  }

  /* 텍스트 간격 */
  .data-info > p {
    margin: 70px 0;
  }

  /* 다음 버튼 끝쪽으로 배치 */
  .next-btn {
    width: 100%;
    display: flex;
    justify-content: flex-end;
    align-items: flex-end;
  }

  /* 다음 버튼 */
  .next-btn button {
    position: absolute;
    top: 107%;
    left: calc(100% - 230px); /* 컨테이너 너비에서 버튼 너비를 뺀 위치 */
    background-color: transparent;
    font-size: 2.3rem;
    width: 250px;
    max-width: 70vw;
    text-align: right;
  }

  /* 버튼 호버 효과 삭제 */
  .next-btn button:hover {
    background-color: initial;
    color: initial;
  }

  /* 기본 버튼 호버 */
  button:hover {
    background-color: #737373;
    color: #fff;
  }

  /* 컨테이너 */
  .container {
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

    .data-form {
      width: 100%;
      max-width: 80vw;
      margin-top: 80px;
    }
  }
</style>