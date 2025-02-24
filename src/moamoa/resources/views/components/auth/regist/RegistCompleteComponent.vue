<template>
  <!-- 배경 컨테이너 DIV -->
  <div class="regist-container">
    <!-- 메인 박스 DIV -->
    <div class="regist-main-box">
      <!-- 메인 타이틀 DIV -->
      <div class="regist-main-title">
        >&nbsp;회원가입&nbsp;완료
      </div>

      <!-- 표시 박스 DIV -->
      <div class="regist-complete-box">
        <!-- 완료 텍스트 DIV -->
        <p class="regist-complete-title">
          회원가입이&nbsp;완료되었습니다!
        </p>

        <!-- 서브 텍스트 DIV -->
        <p v-if="userType === 'parent'" class="regist-complete-subtitle">
          아래의 가족코드를 자녀의 회원가입 시 입력해주세요.
          
        </p>
        <p v-else class="regist-complete-subtitle">
          환영합니다! 이제 서비스를 이용하실 수 있습니다.
        </p>

        <!-- 음영 박스 DIV -->
        <div class="regist-complete-codebox">
          <!-- 부모 표시 DIV -->
          <div v-if="userType === 'parent'">
            <p>가족코드</p>

            <div class="regist-complete-code">
              <span>{{ famCode }}</span>

              <div class="regist-complete-copy">
                <button @click="copyFamCode" type="button">
                  <img src="/img/icon-copy.webp">
                </button>
  
                <!-- 복사 알림창 DIV -->
                <div v-show="copyboard" class="regist-alarm-copy">
                  <div>
                    <img @click="copyboardClose" src="/img/icon-cross.png">
                    
                    <br>
  
                    <p>가족코드가 복사되었습니다</p>
                  </div>
                  
                  <img src="/img/alarm-reverse.webp">
                </div>
              </div>
            </div>
          </div>

          <!-- 자녀 표시 DIV -->
          <div v-else>
            <div class="regist-complete-content">
              <p>시작하기 전에</p>
              <p>• 부모님과 함께 경제 목표를 설정해보세요</p>
              <p>• 매일 미션을 수행하면서 성장해보세요</p>
              <p>• 나만의 경제 계획을 세우고 실천해보세요</p>
            </div>
          </div>
        </div>

        <!-- 부모 표시 DIV -->
        <div v-if="userType === 'parent'" class="regist-complete-footer-text">
          <p>• 가족코드는 자녀 계정 가입 및 연동을 위해 필요합니다.</p>
          <p>• 이 코드는 나중에 가족정보에서도 확인하실 수 있습니다.</p>
        </div>
        
        <!-- 자녀 표시 DIV -->
        <div v-else class="regist-complete-footer-text">
          <p>• 해당 보호자가 승인 후에 로그인이 가능합니다.</p>
          <p>• 궁금한 점이 있다면 MOA팀에게 문의해주세요</p>
        </div>

        <div class="regist-complete-footer-btn">
          <router-link to="/login">
            <button type="button">로그인으로</button>
          </router-link>
        </div>


      </div>
    </div>
  </div>
</template>
<script setup>
import { onMounted, onUnmounted, ref } from 'vue';
import { useRoute } from 'vue-router';

  const route = useRoute();

  // 부모 or 자녀 ---------------------------------------------------------------------------------------------
  const userType = ref(route.path.split('/').pop());

  // 가족코드 ---------------------------------------------------------------------------------------------
  const famCode = sessionStorage.getItem('famCode');
  
  // 클립보드 복사 처리 ---------------------------------------------------------------------------------------------
  
  // 복사 알림창 스위칭
  const copyboard = ref(false);

  const copyboardClose = () => {
    copyboard.value = false;
  }
  
  const copyFamCode = () => {

    // 새요소 생성
    const textCode = document.createElement('textarea');

    // 요소에 가족코드를 저장
    textCode.value = famCode;

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
  
  // 뒤로가기 방지
  const preventBackNavigation = (e) => {
    e.preventDefault(); // 뒤로 가기 방지
    history.pushState(null, '', window.location.href); // 페이지 URL을 강제로 다시 설정
  }
  

  // 클릭 외부 감지 함수
  const handleCopyBoard = (e) => {
    const copyIcon = document.querySelector('.regist-alarm-copy');

    // 알림창이 활성화 되어있고 타겟이 해당DOM의 이외를 클릭했을때
    if(copyIcon && !copyIcon.contains(e.target) && copyboard.value) {
      // 외부 클릭시 알림 숨김
      copyboard.value = false;
    }
  };

  onMounted(() => {
    // 뒤로, 앞으로 버튼 이동시
    window.addEventListener('popstate', preventBackNavigation);
    document.addEventListener('mousedown', handleCopyBoard);
    
    // 히스토리 상태 변경을 강제로 만들어서 뒤로 가기를 막음
    history.pushState(null, '', window.location.href);
  });

  // 이벤트 해제
  onUnmounted(() => {
    document.removeEventListener('mousedown', handleCopyBoard);
  });  

</script>

<style scoped>
  /* 버튼 커서 손모양 */
  button, input[type="checkbox"] {
    cursor: pointer;
  }

  /* 배경 컨테이너 */
  .regist-container {
    background-image: url('/img/background.png');
    background-size: cover;
    background-repeat: no-repeat;
    width: 100vw;
    height: 100vh;
    display: flex;
    justify-content: center;
  }

  /* 메인 컨텐츠 박스 */
  .regist-main-box {
    width: 980px;
    height: 860px;
    margin-top: 50px;
    background-color: #fff;
    border-radius: 10px;
    padding: 15px 20px;
  }

  /* 메인 제목 타이틀 */
  .regist-main-title {
    font-size: 1.4rem;
    font-weight: 600;
  }

  /* 완료 컨텐츠 박스 */
  .regist-complete-box {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    width: 100%;
    height: 100%;
    flex-direction: column;
  }

  /* 완료 텍스트 */
  .regist-complete-title {
    font-size: 2rem;
    font-weight: 600;
  }

  /* 서브 설명 텍스트 */
  .regist-complete-subtitle {
    font-size: 1.2rem;
    color: #5a5a5a;
    margin: 30px 0;
  }

  /* 가족코드 박스 */
  .regist-complete-codebox {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 450px;
    height: 190px;
    background-color: #f5f5f5;
    border-radius: 10px;
    padding: 20px;
  }

  /* 가족코드 텍스트 */
  .regist-complete-codebox > div > p {
    font-size: 1.2rem;
    margin: 20px 0;
    color: #5a5a5a;
    text-align: center;
  }

  .regist-complete-content > p {
    font-size: 1rem;
    margin: 10px 0;
    color: #5a5a5a;
  }

  .regist-complete-content > p:nth-child(1) {
    font-size: 1.3rem;
    font-weight: 600;
    color: #000;
    text-align: center;
    padding-bottom: 5px;
  }

  /* 가족코드 표시 박스 */
  .regist-complete-code {
    display: flex;
    justify-content: space-between;
    width: 100%;
  }

  /* 가족코드 출력 */
  .regist-complete-code > span {
    font-size: 2.5rem;
    font-weight: 600;
    letter-spacing: 0.4rem;
    padding-left: 12px;
  }

  .regist-complete-copy {
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
  }

  .regist-complete-copy > button > img {
    width: 100%;
    height: 100%;
  }

  /* 복사 버튼 설정 */
  .regist-complete-copy > button {
    width: 20px;
    height: 20px;
    background-color: transparent;
    border: none;
    /* margin: 15px 0 0 10px; */
  }

  /* 하단 간격 조절 */
  .regist-complete-footer-text, .regist-complete-footer-text > p {
    margin: 15px 0;
  }

  /* 하단 텍스트 */
  .regist-complete-footer-text > p {
    font-size: 1rem;
    color: #5a5a5a;
  }

  /* 하단 버튼 */
  .regist-complete-footer-btn {
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .regist-complete-footer-btn button {
    margin-top: 55px;
    font-size: 1.4rem;
    padding: 25px 0;
    width: 450px;
    border-radius: 10px;
    border: none;
    background-color: #3B82F6;
    color: #fff;
  }
  
  .regist-complete-footer-btn button:hover {
    background-color: #2563EB;
  }

  /* -------------------------------------------------------------------- */
  
  /* 복사 알림창 */
  .regist-alarm-copy {
    position: absolute;
    display: flex;
    justify-content: center;
    align-items: flex-end;
    flex-direction: column;
    left: -415%;
    top: -81%;
    /* transform: translate(-50%, -50%); */
  }

  /* 알림 박스 설정 */
  .regist-alarm-copy > div {
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    width: 180px;
    background-color: #fff;
    border: 1px solid #eee;
    border-radius: 5px;
    padding: 10px;
    padding-right: 20px;
  }

  /* 알림 x 버튼 */
  .regist-alarm-copy > div > img {
    position: absolute;
    left: 92%;
    top: 17%;
    width: 8px;
    height: 8px;
    cursor: pointer;
  }

  /* 복사 알리 텍스트 */
  .regist-alarm-copy > div > p {
    font-size: 0.8rem;
    height: 100%;
    /* margin: 10px; */
  }

  /* 꼬다리 이미지 */
  .regist-alarm-copy > img {
    position: absolute;
    left: 46.5%;
    top: 97%;
    width: 20px;
    height: 20px;
  }

  /* -------------------------------------------------------------------------- */

  @media(max-width: 390px) {

    /* 메인 컨테이너 */
    .regist-container {
      overflow-y: auto;
      white-space: wrap;
    }
    /* 메인 박스 */
    .regist-main-box {
      max-width: 97vw;
      height: 834px;
      max-height: 100vh;
      margin: 5px 0;
      padding: 15px 10px;
    }
    
    /* */

    /* 메인 타이틀 제목 */
    .regist-main-title, .regist-main-title span {
      font-size: 1.2rem;
    }
    /* 내부 박스 여백 조정 */
    .regist-complete-box {
      padding: 5px;
    }

    /* 헤더 대제목 */
    .regist-complete-title {
      font-size: 1.7rem;
    }
    /* 헤더 소제목 */
    .regist-complete-subtitle {
      font-size: 1rem;
    }

    /* 음영 박스 */
    .regist-complete-codebox {
      width: 100%;
    }
    /* 가족코드 텍스트 */
    .regist-complete-code > span {
      font-size: 2.2rem;
    }
    /* 복사아이콘 간격 조정 */
    .regist-complete-code > button {
      margin: 15px 0 0 5px;
    }

    /* 복사 말풍선 위치 조정 */
    .regist-alarm-copy {
      left: -514%;
      top: -70%;
    }
    /* 말풍선 꼬다리 아이콘 */
    .regist-alarm-copy > img {
      left: 59.5%;
    }
    /* 복사 말풍선 내부조전 */
    .regist-alarm-copy > div {
      width: 170px;
      padding: 5px;
      justify-content: flex-start;
    }
    /* 복사 말풍선 텍스트 */
    .regist-alarm-copy > div > p {
      font-size: 0.75rem;
    }

    /* 하단 소제목 텍스트 조정 */
    .regist-complete-footer-text > p {
      font-size: 0.92rem;
    }

    .regist-complete-footer-btn button {
      background-color: #2563EB;
      width: 348px;
    }
  }


</style>