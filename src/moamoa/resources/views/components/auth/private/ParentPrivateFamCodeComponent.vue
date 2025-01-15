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
        <p>{{ parentInfo.name }} 님의 가족코드</p>

        <div class="fam-code">
          <p>{{ parentInfo.family_code }}</p>
        </div>
        
        <p class="middle-text">
          {{ parentInfo.name }} 님의 자녀
        </p>

        <div class="profile-field" :class="{'overflow-auto' : parentInfo?.children?.length > 4}">
          <div v-if="parentInfo?.children?.length === 0">
            <p>등록된 자녀의 정보가 없습니다.</p>
          </div>
          <div v-else v-for="item in parentInfo.children" :key="item" class="profile-info">
            <div class="icon-img">
              <div class="icon-img-size">
                <img :src="item.profile">
              </div>
            </div>
            <p>{{ item.name }}</p>
          </div>

          <!-- <div class="profile-info">
            <div class="icon-img">
              <div class="icon-img-size">
                <img src="/img/profile-icon/icon-girl-5.png">
              </div>
            </div>
            <p>OOO</p>
          </div>
          
          <div class="profile-info">
            <div class="icon-img">
              <div class="icon-img-size">
                <img src="/img/profile-icon/icon-girl-5.png">
              </div>
            </div>
            <p>OOO</p>
          </div>
          
          <div class="profile-info">
            <div class="icon-img">
              <div class="icon-img-size">
                <img src="/img/profile-icon/icon-girl-5.png">
              </div>
            </div>
            <p>OOO</p>
          </div>
          
          <div class="profile-info">
            <div class="icon-img">
              <div class="icon-img-size">
                <img src="/img/profile-icon/icon-girl-5.png">
              </div>
            </div>
            <p>OOO</p>
          </div> -->

        </div>
      </div>
        
      <div class="next-btn">
        <router-link to="/parent/home">
          <button type="button">확인 >&nbsp;</button>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onBeforeMount } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

const parentInfo = computed(() => store.state.auth.parentInfo);

onBeforeMount(() => {
  store.dispatch('auth/parentInfo');
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
    margin: 0 auto;
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
    background-color: #E8E8E8;
    width: 710px;
    max-width: 38vw;
    height: 100%;
    max-height: 500px;
  }

  /* 텍스트 문단 */
  .data-info {
    font-size: 2.8rem;
    height: 100%;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  /* ~님의 가족코드 텍스트 */
  .data-info > p:nth-child(1) {
    margin: 10px;
  }

  /* 가족코드 문단 */
  .fam-code {
    width: 520px;
    max-width: 40vw;
    margin: 0 auto;
    text-align: center;
  }

  /* 가족코드 텍스트 */
  .fam-code > p {
    background-color: #fff;
    width: 100%;
    max-width: 80vw;
    padding: 20px;
  }

  /* 중간 텍스트 */
  .middle-text {
    margin-top: 80px;
    padding: 5px;
  }

  /* 자녀 프로필 영역 */
  .profile-field {
    display: flex; 
    justify-content: center;
    align-items: center;
    flex-wrap: wrap; 
    padding: 10px;
    gap: 50px;
    /* height: 100%; */
  }

  /* 자녀 프로필 세로 정렬 */
  .profile-info {
    flex-direction: column;
  }

  /* 자녀 이름 텍스트 */
  .profile-info > p {
    font-size: 1.5rem;
    padding-right: 10px;
  }

  /* 아이콘 문단 */
  .icon-img {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #fff;
    border: 3px solid #A4D8E1;
    padding: 2px;
    border-radius: 50%;
    width: 100px;
    height: 100px;
    margin-right: 10px;
  }

  .icon-img-size {
    width: 90px;
    height: 90px;
    border-radius: 50%;
    overflow: hidden;
  }

  /* 아이콘 크기 */
  .icon-img-size > img {
    width: 100%;
    height: 100%;

  }/* 다음 버튼 끝쪽으로 배치 */
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

  .overflow-auto {
    overflow: auto;
  }

  @media(max-width: 1660px) {
    /* JS로 4개 이상일시 자동으로 붙으도록 설정헤야함 */
    .profile-field { 
      overflow: auto;
    }
  }

  @media(max-width: 1360px) {
    /* 우측 박스 너비 조절 */
    .data-form {
      width: 93%;
      max-width: 80vw;
    }
    .data-info {
      font-size: 2.3rem;
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
      max-height: 650px;
    }
    
    .fam-code {
      max-width: 60vw;
    }

    .fam-code > p {
      padding: 10px;
    }
  }
  
</style>