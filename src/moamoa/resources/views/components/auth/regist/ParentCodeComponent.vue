<template>
  <!-- 중앙 박스 컨테이너 DIV -->
  <div class="container">
    <!-- 로고 정렬용 DIV -->
    <div class="logo-img">
      <img src="/user-img/logo.png">
    </div>

    <!-- 중앙 세로선 DIV -->
    <div class="center-line">
      <hr>
    </div>

    <!-- 입력 폼 DIV -->
    <div class="data-form position-relative">
      <div class="data-info">
        <p>{{ $store.state.auth.parentInfo.name }} 님의 가족코드</p>

        <div class="fam-code">
          <p>{{ $store.state.auth.parentInfo.family_code }}</p>
        </div>
        
        <!-- <span class="mini-text">
          가족코드는 이후 내정보에서 다시보실수 있습니다
        </span> -->
      </div>

      <div class="next-btn">
        <router-link to="/regist/complete">
          <button @click="nextComplete" type="button">다음 >&nbsp;</button>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onBeforeMount, onBeforeUnmount, onMounted } from 'vue';
import { onBeforeRouteLeave, useRouter } from 'vue-router';

import { useStore } from 'vuex';

  // 스토어 호출
  const store = useStore(); 
  const router = useRouter();

  const nextComplete = () => {
    clearParentInfo();
    router.replace('/regist/complete');
  };

  // 이동 반응 이벤트 ---------------------------------------------------------------------------------------------

  // 세션 정보 리셋
  const clearParentInfo = () => {
    sessionStorage.removeItem('parentInfo');
  };

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

  // 다른 페이지로 이동 시 작동함
  onBeforeRouteLeave((to, from, next) => {
    clearParentInfo();
    next();
  });

  onBeforeMount(() => {
    // 마운트 되기전에 받아온 정보가 없으면 다시 로드
    const parentInfo = sessionStorage.getItem('parentInfo');
    if(parentInfo) {
      store.commit('auth/setParentInfo', JSON.parse(parentInfo));
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

  /* 암색 배경 */
  .data-form {
    background-color: #E8E8E8;
    width: 710px;
    max-width: 38vw;
    height: 100%;
    max-height: 500px;
  }

  /* 텍스트 문단 */
  .data-info {
    font-size: 3.5rem;
    height: 100%;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  /* ~님의 가족코드 텍스트 */
  .data-info > p:nth-child(1) {
    margin-top: 60px;
  }

  /* 가족코드 문단 */
  .fam-code {
    margin-top: 30px;
  }

  /* 가족코드 텍스트 */
  .fam-code > p {
    background-color: #fff;
    width: 100%;
    max-width: 80vw;
    padding: 50px;
  }

  /* 풋터 텍스트 */
  .mini-text {
    display: block;
    font-size: 1.7rem;
    margin-top: 80px;
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

  /* 모바일 설정: 화면 너비가 일정 크기 이하일 때 */
  @media(max-width: 1260px) {
    /* 우측 박스 너비 조절 */
    .data-form {
      width: 93%;
      max-width: 80vw;
    }

    /* 코드 흰창 여백 제거 */
    .fam-code > p {
      padding: 0;
    }

    /* 글자크기 조절 */
    .data-info {
      font-size: 2.8rem;
    }
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

    /* 높이 조절 */
    .data-form {
      margin-top: 80px;
    }
  }
  
</style>