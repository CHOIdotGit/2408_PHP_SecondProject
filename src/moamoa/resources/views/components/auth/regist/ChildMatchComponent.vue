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
        <span>
          <div class="profile-info">
            <div class="icon-img">
              <!-- <img src="/img/profile-icon/icon-girl-5.png"> -->
              <img :src="parent.profile">
            </div>
          </div>
          <span class="color-green">{{ parent.name }}</span> 님의 <br>자녀 입니까? <!-- 가 맞으십니까? -->
        </span>

        <span class="mini-text">
          <!-- 아니라면 뒤로 돌아가 가족코드를 확인해주세요 -->
        </span>

        <div class="btn-group">
          <button @click="$store.commit('auth/setRegistFlg', true);" type="button">아닙니다</button>
          <button @click="nextComplete" type="button">맞습니다</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, defineProps } from 'vue';
import { useStore } from 'vuex';

  const store = useStore();
  
  // 매칭 부모 정보 --------------------------------------------------------------------------------------------
  const parent = computed(() => store.state.auth.matchInfo);
  
  // 전송할 자녀 가입 정보 --------------------------------------------------------------------------------------------
  const props = defineProps({
    registInfo: Object,
  });

  // 맞습니다 버튼
  const nextComplete = () => {
    // 가입정보에 매칭부모 아이디를 추가
    props.registInfo.parent_id = parent.value.parent_id;

    // 회원가입 수행
    store.dispatch('auth/storeUser', props.registInfo);
  }
</script>

<style scoped>

  .color-green {
    color:#008000;
  }

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

  .btn-group button:hover {
    background-color: #737373;
    color: #fff;
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
    font-size: 3rem;
    height: 100%;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
    max-width: 80vw;
  }

  /* 아이콘 문단 */
  .profile-info {
    display: inline-block;
    background-color: #8A9FF6;
    border-radius: 50%;
    width: 90px;
    height: 90px;
    margin-right: 10px;
    vertical-align: bottom;
  }

  /* 아이콘 크기 */
  .icon-img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    overflow: hidden;
    margin: 5px auto;
  }

  .icon-img > img {
    width: 100%;
    height: 100%;
  }

  /* 풋터 텍스트 */
  .mini-text {
    display: block;
    font-size: 1.7rem;
    margin: 30px;
  }

  .btn-group {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .btn-group button {
    background-color: #fff;
    padding: 15px 50px;
    margin: 30px;
    font-size: 2.3rem;
    max-width: 40vw;
  }
  
  /* 모바일 설정: 화면 너비가 일정 크기 이하일 때 */
  @media(max-width: 1260px) {
    /* 우측 박스 너비 조절 */
    .data-form {
      width: 93%;
      max-width: 80vw;
      padding: 10px;
    }

    /* 글자크기 조절 */
    .data-info {
      font-size: 2.3rem;
      margin-top: 10px;
    }

    .icon-img {
      width: 60px;
      height: 60px;  
    }

    .btn-group {
      flex-direction: column;
    }

    .btn-group button {
      font-size: 1.8rem;
      padding: 10px 30px;
      margin: 10px;
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
      height: 90%;
    }
  }
</style>