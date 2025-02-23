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

            <div class="find-content-idbox">
              <button @click="copyAccount" type="button">
                <img src="/img/icon-copy.webp">
              </button>
  
              <!-- 복사 알림창 DIV -->
              <div v-show="copyboard" class="find-alarm-copy">
                <div>
                  <img @click="copyboardClose" src="/img/icon-cross.png">
                  
                  <br>
  
                  <p>아이디가 복사되었습니다</p>
                </div>
                
                <img src="/img/alarm-reverse.webp">
              </div>
            </div>
          </div>
        </div>
        
        <div v-else class="find-content-pwd">
          <p>
            입력하신 정보로 회원({{ userInfo.account }})님의 비밀번호를 재설정 하였습니다. 
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
import { computed, onBeforeMount, onMounted, onUnmounted, reactive, ref } from 'vue';
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

  // 클립보드 복사 처리 ---------------------------------------------------------------------------------------------
  
  // 복사 알림창 스위칭
  const copyboard = ref(false);

  const copyboardClose = () => {
    copyboard.value = false;
  }
  
  const copyAccount = () => {

    // 새요소 생성
    const textCode = document.createElement('textarea');

    // 요소에 가족코드를 저장
    textCode.value = userInfo.value.account;

    // 요소 삽입
    document.body.appendChild(textCode);
    
    // 해당 요소 선택
    textCode.select();

    // 요소를 클립보드에 복사
    document.execCommand('copy'); // 클립보드에 복사

    // 요소 제거
    document.body.removeChild(textCode);
    
    // 메세지로 알림
    copyboard.value = true;
  };

  // 이벤트 처리 ---------------------------------------------------------------------------------------------
  

  // 클릭 외부 감지 함수
  const handleCopyBoard = (e) => {
    const copyIcon = document.querySelector('.find-alarm-copy');

    // 알림창이 활성화 되어있고 타겟이 해당DOM의 이외를 클릭했을때
    if(copyIcon && !copyIcon.contains(e.target) && copyboard.value) {
      // 외부 클릭시 알림 숨김
      copyboard.value = false;
    }
  };

  onMounted(() => {
    document.addEventListener('mousedown', handleCopyBoard);
    
    // 히스토리 상태 변경을 강제로 만들어서 뒤로 가기를 막음
    history.pushState(null, '', window.location.href);
  });

  // 이벤트 해제
  onUnmounted(() => {
    document.removeEventListener('mousedown', handleCopyBoard);
  });  

  onBeforeMount(() => {
    // 유저 정보 로드
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
  .find-main-content > div,
  .find-main-content > div > div {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .find-main-content,  
  .find-main-content > div {
    flex-direction: column;
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
    font-size: 1.5rem;
  }

  /* -------------------------------------------------------------------- */

  /* 하단 부분 */
  .find-footer-btn {
    width: 100%;
    height: 100%;
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

  /* -------------------------------------------------------------------- */

  .find-content-idbox {
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
  }
  .find-content-idbox > button > img {
    width: 100%;
    height: 100%;
  }
  /* 복사 버튼 설정 */
  .find-content-idbox > button {
    background-color: transparent;
    border: none;
    margin-left: 10px;
    width: 20px;
    height: 20px;
  }

  /* 복사 알림창 */
  .find-alarm-copy {
    position: absolute;
    display: flex;
    justify-content: center;
    align-items: flex-end;
    flex-direction: column;
    /* left: 54.6%;
    top: -9%; */
    bottom: 190%;
  }

  /* 알림 박스 설정 */
  .find-alarm-copy > div {
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    width: 190px;
    background-color: #fff;
    border: 1px solid #eee;
    border-radius: 5px;
    padding: 0 20px 0 5px;
  }

  /* 알림 x 버튼 */
  .find-alarm-copy > div > img {
    position: absolute;
    left: 92%;
    top: 17%;
    width: 8px;
    height: 8px;
    cursor: pointer;
  }

  /* 복사 알리 텍스트 */
  .find-alarm-copy > div > p {
    font-size: 0.8rem;
    height: 100%;
    /* margin: 10px; */
  }

  /* 꼬다리 이미지 */
  .find-alarm-copy > img {
    position: absolute;
    left: 46.5%;
    top: 97%;
    width: 20px;
    height: 20px;
  }
  
  /* -------------------------------------------------------------------- */
  
  @media(max-width: 390px) {
    .find-container {
      white-space: wrap;
    }

    /* 메인 박스 */
    .find-main-box {
      max-width: 97vw;
      padding: 30px 15px;
      height: 495px;
      margin-bottom: 55px;
    }

    /* 아이콘 크기 조절 */
    .find-header-icon > div {
      width: 100px;
      height: 100px;
    }
    /* 헤더 타이틀 크기 조절 */
    .find-header-icon > h1 {
      font-size: 1.8rem;
    }

    /* 메인 서브 타이틀 크기 */
    .find-main-content > div > p {
      font-size: 1.2rem;
    }

    /* 음영 박스 크기 변경 */
    .find-content-id {
      padding: 10px 0 0 0;
    }
    /* 아이디 크기 조절 */
    .find-content-id > div {
      font-size: 1.6rem;
    } 

    /* 하단 버튼 간격 조절 */
    .find-footer-btn {
      column-gap: 30px;
      padding: 0;
    }
    /* 하단 버튼들 */
    .find-footer-btn button {
      font-size: 1.1rem;
      padding: 20px 0;
    }

    /* 비밀번호 완료 텍스트 조절 */
    .find-content-pwd {
      padding: 0 15px;
    }

  }

</style>