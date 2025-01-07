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
        <div class="data-form">
            <!-- 아이디, 비밀번호 입력 DIV -->
            <div class="data-input">
                <div>
                    <!-- 공용 에러 메세지 -->
                    <div v-if="errMsg.common" class="err-msg">
                      <span> {{ errMsg.common }} </span>
                    </div>
                    
                    <label for="account">
                      아이디 &nbsp;
                      <span v-if="errMsg.account" class="err-msg">
                        {{ errMsg.account }}
                      </span>
                    </label>
                    <!-- <br> -->
                    <input v-model="userInfo.account" :class="{ 'err-border' : errMsg.account }" type="text" name="account" required>
                </div>
                <div>
                    <label for="password">
                      비밀번호 &nbsp;
                      <span v-if="errMsg.password" class="err-msg">
                        {{ errMsg.password }}
                      </span>
                    </label> 
                    <!-- <br> -->
                    <input v-model="userInfo.password" :class="{ 'err-border' : errMsg.password }" type="password" name="password" required>
                </div>
            </div>

            <!-- 로그인, 회원가입 버튼 DIV -->
            <div class="data-button">
                <button type="button" @click="$store.dispatch('auth/login', userInfo)">로그인</button> <br>
                <router-link to="/regist/main">
                  <button type="button">회원가입</button>
                </router-link>
            </div>
        </div>

    </div>
</template>

<script setup>
  import { computed, reactive, onMounted, onBeforeUnmount } from 'vue';
  import { onBeforeRouteLeave } from 'vue-router';
  import { useStore } from 'vuex';

  const store = useStore();

  // 에러 정보 ---------------------------------------------------------------------------------------------
  const errMsg = computed(() => store.state.auth.errMsg);

  // 회원 정보 ---------------------------------------------------------------------------------------------
  const userInfo = reactive({
    account: '',
    password: '',
  });
  
  // 이동 반응 이벤트 ---------------------------------------------------------------------------------------------

  // 예외 정보 리셋
  const clearState = () => {
    // 들어간 값이 하나라도 있으면 리셋
    if(Object.values(errMsg).some(value => value !== '' || value !== null || value !== undefined)) {
      store.commit('auth/resetErrMsg');
    }
  };
  
  // 페이지가 로드되면 DOM에 이벤트 추가
  onMounted(() => {
    window.addEventListener('popstate', clearState());
    window.addEventListener('beforeunload', clearState);
  });

  // 다음 컴포넌트로 넘어갈 때 이벤트를 제거
  onBeforeUnmount(() => {
    window.removeEventListener('popstate', clearState());
    window.removeEventListener('beforeunload', clearState);
  });

  // 다른 페이지로 이동 시 작동함
  onBeforeRouteLeave((to, from, next) => {
    clearState();
    next();
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

  /* 앱솔르트 이동을 위해 포지션 설정 */
  .position-relative {
    position: relative;
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
  .data-form {
    gap: 100px; /* input과 버튼 사이의 갭 */
    font-size: 2rem;
  }

  /* 박스간의 상하 간격 조절 */
  .data-input div:last-child, .data-button button:last-child {
    margin-top: 20px;
  }

  /* 입력 폼 안의 input과 버튼은 동일한 크기를 가짐 */
  .data-form input, .data-form button {
    padding: 8px;
    width: 470px;
    max-width: 45vw;
    font-size: 1.9rem;
  }

  /* 에러 메시지용 세팅 */
  /* 라벨 끝쪽에 출력 배치 */
  label { 
    display: flex;
    justify-content: space-between;
  }

  /* 메세지 설정 */
  .err-msg {
    font-size: 0.9rem;
    padding: 10px 0;
    color: #ff0000;
  }

  /* 빨간 테두리, 추가입력하면 사라짐 */
  .err-border:invalid {
    border: 2px solid rgba(255, 0, 0, 0.6);
    box-shadow: 0 0 5px #ff0000;
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

    .data-form input, .data-form button {
      width: 470px;
      max-width: 80vw;
    }
  }
</style>