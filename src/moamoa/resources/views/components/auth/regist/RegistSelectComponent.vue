<template>
  <!-- 배경 컨테이너 DIV -->
  <div class="regist-container">
    <!-- 메인 박스 DIV -->
    <div class="regist-main-box">
      <!-- 메인 타이틀 DIV -->
      <div class="regist-main-title">
        >&nbsp;회원&nbsp;유형&nbsp;선택
      </div>

      <!-- 중앙 메인 제목 DIV -->
      <div class="regist-main-subtitle">
        <p>가입하실 회원의 유형을 선택해 주세요.</p>
      </div>

      <!-- 부모자녀 선택 박스 DIV -->
      <div class="regist-select-box">
        <!-- 부모 박스 DIV -->
        <div>
          <div class="regist-main-profile">
            <div class="regist-profile-preview">
              <img src="/user-img/default3.webp">
            </div>
          </div>

          <p class="regist-select-title">
            보호자
          </p>

          <p class="regist-select-subtitle">
            자녀의 활동을 관리하고 모니터링하실 수 있습니다
          </p>

          <p class="regist-select-content">
            가입 시 가족코드가 발급됩니다
          </p>

          <div class="regist-select-btn">
            <router-link to="/regist/parent">
              <button type="button">선택하기</button>
            </router-link>
          </div>
        </div>

        <!-- 자녀 박스 DIV -->
        <div>
          <div class="regist-main-profile">
            <div class="regist-profile-preview">
              <img src="/user-img/default2.webp">
            </div>
          </div>

          <p class="regist-select-title">
            자녀
          </p>

          <p class="regist-select-subtitle">
            보호자의 관리하에 서비스를 이용하실 수 있습니다
          </p>

          <p class="regist-select-content">
            가입을 위해 보호자의 가족코드가 필요합니다
          </p>

          <div class="regist-select-btn">
            <router-link to="/regist/child">
              <button type="button">선택하기</button>
            </router-link>
          </div>
        </div>
      </div>

      <div class="regist-footer-btn">
        <router-link to="/regist/agree" class="btn-cancel">
          <button type="button">뒤로</button>
        </router-link>
      </div>

    </div>
  </div>
</template>

<script setup>
  //
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

  /* 중앙 메인 제목 */
  .regist-main-subtitle {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 80px 0 70px 0;
    font-size: 1.8rem;
    color:#5a5a5a;
  }

  /* 그리드셀 나누기 */
  .regist-select-box {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    padding: 0 30px;
  }

  /* 부모자녀 선택 박스 */
  .regist-select-box > div {
    display: flex;
    /* justify-content: center; */
    align-items: center;
    border: 1px solid #AAA;
    border-radius: 10px;
    height: 550px;
    padding: 20px;
    flex-direction: column;
  }

  /* 프로필 사진 이미지란 */
  .regist-main-profile {
    margin: 15px 0;
    width: 100%;
    display: flex;
    justify-content: center;
    /* align-items: center; */
  }

  /* 프로필 이미지 박스 */
  .regist-profile-preview {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 10px;
    background-color: #EFF6FF;
  }

  /* 이미지 채움 */
  .regist-profile-preview > img {
    width: 80%;
    height: 80%;
  }

  /* 보호자 자녀 */
  .regist-select-title {
    font-size: 1.9rem;
    font-weight: 700;
    margin: 10px 0 20px 0;
  }

  /* 설명 */
  .regist-select-subtitle {
    font-size: 1.1rem;
    margin: 20px 0;
    color: #a5a5a5;
  }

  /* 추가 설명 */
  .regist-select-content {
    font-size: 1rem;
    margin: 20px 0;
    color: #2563EB;
  }
  
  /* 하단의 버튼 */
  .regist-select-btn button {
    width: 200px;
    height: 50px;
    font-size: 1.1rem;
    border: none;
    border-radius: 5px;
    background-color: #3B82F6;
    color: #fff;
    margin-top: 50px;
  }

  /* 버튼 호버 */
  .regist-select-btn button:hover {
    background-color: #2563EB;
  }
  
  /* ------------------------------------------------------------------------- */
  
  /* 하단 뒤로 버튼 */
  .regist-footer-btn {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 22px;
  }

  .btn-cancel > button {
    padding: 10px;
    width: 100px;
    border: none;
    font-size: 1.2rem;
    background-color: #F3F3F3;
  }
  
  /* ------------------------------------------------------------------------- */

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
    .regist-main-title {
      font-size: 1.2rem;
    }

    /* 서브 타이틀 */
    .regist-main-subtitle {
      font-size: 1.25rem;
      margin: 30px 0;
    }
    
    /* */

    /* 선택박스 그리드셀 변경 */
    .regist-select-box {
      grid-template-columns: 1fr;
      gap: 20px;
      padding: 0 10px;
    }
    /* 선택박스 내부여백 조정 */
    .regist-select-box > div {
      padding: 10px 15px;
      height: 312px;
    }

    /* 프로필 영역 */
    .regist-main-profile {
      margin: 0 0 10px 0;
    }
    /* 프로필 크기 조정 */
    .regist-profile-preview {
      width: 100px;
      height: 100px;
      margin-top: 0;
    }

    /* 메인 대제목 */
    .regist-select-title {
      font-size: 1.4rem;
      margin: 5px 0 10px 0;
    }
    /* 메인 소제목 */
    .regist-select-subtitle {
      font-size: 0.92rem;
      margin: 10px 0;
    }
    .regist-select-content {
      font-size: 0.85rem;
      margin: 10px 0;
    }

    /* 선택 버튼 설정 */
    .regist-select-btn, .regist-select-btn button {
      width: 100%;
    }
    .regist-select-btn button {
      margin-top: 20px;
    }
    .regist-select-btn button {
      background-color: #2563EB;
    }
    
    /* 하단 버튼 설정 */
    .regist-footer-btn {
      margin-top: 15px;
    }
  }
  
</style>