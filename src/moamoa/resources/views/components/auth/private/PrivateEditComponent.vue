<template>
  <div class="edit-container">
    <div class="edit-main-box">

      <!-- 프로필 영역 DIV -->
      <div class="edit-main-profile">
        <label for="file">
          <div class="edit-profile-preview">
            <img :src="preview ? preview : userInfo.profile">
          </div>
          
          <div class="edit-camera">
            <img class="fa-camera" src="/img/icon-camera.webp">
          </div>
        </label>

        <input @change="fileSetting" type="file" name="file" id="file" accept="image/*">
      </div>

      <!-- 공용 에러 -->
      <p v-if="errMsg.common" class="err-msg">
        {{ errMsg.common }}
      </p>
      <p v-else>
        <span>*</span>가 있는 항목은 필수 입력사항입니다.
      </p>

      
      <!-- 회원가입 작성 폼 DIV -->
      <div class="edit-main-content">
        
        <!-- 제목 타이틀 DIV -->
        <div class="edit-item-title">
          <label for="account">
            아이디
            <!-- <span>*</span> -->
          </label>
          <label for="password">
            현재 비밀번호
            <span>*</span>
          </label>
          <label for="passwordChk">
            새 비밀번호
            <!-- <span>*</span> -->
          </label>
          <label for="passwordChk">
            새 비밀번호 확인
            <!-- <span>*</span> -->
          </label>
          <label for="name">
            이름
            <span>*</span>
          </label>
          <label for="email">
            이메일
            <span>*</span>
          </label>
          <label for="tel">
            휴대폰
            <span>*</span>
          </label>
        </div>

        <!-- 내용 컨텐츠 DIV -->
        <div class="edit-item-content">
          <!-- 아이디 입력 DIV -->
          <div>
            <p>user1234</p>
          </div>

          <!-- 현재 비밀번호 입력 DIV -->
          <div>
            <input v-model="editInfo.password" :class="{ 'err-border' : errMsg.password }" type="password" name="password" id="password" autocomplete="off" required>
            <p v-if="errMsg.password" class="err-msg">
              {{ errMsg.password }}
            </p>
          </div>

          <!-- 새 비밀번호 확인 입력 DIV -->
          <div>
            <input v-model="editInfo.passwordChk" :class="{ 'err-border' : errMsg.passwordChk }" type="password" name="passwordChk" id="passwordChk" autocomplete="off" required>
            <p v-if="errMsg.passwordChk" class="err-msg">
              {{ errMsg.passwordChk }}
            </p>
            <p v-else class="ann-msg">
              영문 대소문자와 숫자, 특수문자 2종류 이상 조합 6~18자 사용 가능.
            </p>
          </div>

          <!-- 새 비밀번호 확인 입력 DIV -->
          <div>
            <input v-model="editInfo.passwordChk" :class="{ 'err-border' : errMsg.passwordChk }" type="password" name="passwordChk" id="passwordChk" autocomplete="off" required>
            <p v-if="errMsg.passwordChk" class="err-msg">
              {{ errMsg.passwordChk }}
            </p>
            <p v-else class="ann-msg">
              비밀번호를 변경하실 경우 새 비밀번호 확인을 입력해주세요.
            </p>
          </div>

          <!-- 이름 입력 DIV -->
          <div>
            <input v-model="editInfo.name" :class="{ 'err-border' : errMsg.name }" type="text" name="name" id="name" autocomplete="off" required>
            <p v-if="errMsg.name" class="err-msg">
              {{ errMsg.name }}
            </p>
          </div>

          <!-- 이메일 입력 DIV -->
          <div>
            <input 
              v-model="editInfo.email" 
              :class="{ 'err-border' : errMsg.email, 'pass-border' : isEmailPass }"
              @input="onlyEmail"
              type="email" 
              name="email" 
              id="email" 
              autocomplete="off" 
              required
            >
            <p v-if="errMsg.email" class="err-msg">
              {{ errMsg.email }}
            </p>
          </div>

          <!-- 전화번호 입력 DIV -->
          <div>
            <input 
              v-model="editInfo.tel" 
              :class="{ 'err-border' : errMsg.tel }" 
              @input="onlyNumber"
              type="text" 
              name="tel" 
              id="tel" 
              autocomplete="off" 
              required
            >
            <p v-if="errMsg.tel" class="err-msg">
              {{ errMsg.tel }}
            </p>
          </div>
          
        </div>
      </div>

      <div class="edit-footer">
        <button @click="registBtn" type="button" class="btn-submit">
          수정확인
        </button>
      </div>

    </div>
  </div>
</template>

<script setup>
import { computed, onBeforeMount, reactive, ref, watch } from 'vue';
import { useStore } from 'vuex';

  const store = useStore();

  // 에러 정보 ---------------------------------------------------------------------------------------------
  const errMsg = computed(() => store.state.auth.errMsg);


  // 유저 정보 ---------------------------------------------------------------------------------------------
  const userInfo = computed(() => store.state.auth.parentFlg ? store.state.auth.parentInfo : store.state.auth.childInfo);

  // 수정 정보 ---------------------------------------------------------------------------------------------
  const editInfo = reactive({
    password: '',
    newPassword: null,
    newPasswordChk: null,
    name: '',
    email: '',
    tel: '',
    profile: null,
  });

  // watch를 사용해 유저 정보가 로드되면 editInfo를 업데이트
  watch(() => userInfo.value, (newUserInfo) => {
    if(newUserInfo) {
      editInfo.name = newUserInfo.name;
      editInfo.email = newUserInfo.email;
      editInfo.tel = newUserInfo.tel;
    }
  }, { immediate: true });

  // 파일 세팅 및 프리뷰 ---------------------------------------------------------------------------------------------

  // 프로필 프리뷰 세팅
  const preview = ref();

  // 이미지 파일 업로드 및 프리뷰
  const fileSetting = (e) => {
    const file = e.target.files[0];

    // 이미지 파일 여부 검사
    if(file && !file.type.startsWith('image/')) {
      alert('이미지 파일만 업로드할 수 있습니다.');
      e.target.value = '';
      return;
    }else {
      editInfo.profile = e.target.files[0];
      preview.value = URL.createObjectURL(editInfo.profile);
    }
  };
  

  // 이벤트 처리 ---------------------------------------------------------------------------------------------
  
  onBeforeMount(() => {
    // 에러 정보 리셋
    if(Object.values(errMsg).some(value => value !== '' || value !== null || value !== undefined)) {
      store.commit('auth/resetErrMsg');
    }

    // 유저 정보 로드
    store.dispatch(store.state.auth.parentFlg ? 'auth/parentInfo' : 'auth/childInfo' );
  });

</script>

<style scoped>
  /* 버튼 커서 손모양 */
  button, img {
    cursor: pointer;
  }

  /* 인풋 기본설정 */
  input {
    border: 1px solid #CCC;
  }

  /* 파일 인풋 숨김 */
  input[type="file"] {
    display: none;
  }
  
  /* 증감 UI 제거 */
  input[type="number"] {
    -moz-appearance: textfield;
    -webkit-appearance: none;
    appearance: none;
  }
  input[type="number"]::-webkit-outer-spin-button,
  input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
  }
  input[type="number"]::-moz-spin-button {
    display: none;
  }

  /* 포커스 시 강조표시 */
  input:focus {
    border: 1.9px solid #101010;
    border-radius: 2px;
    outline: none;
  }

  /* 배경 설정 */
  .edit-container {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  /* 메인 박스 */
  .edit-main-box {
    width: 980px;
    height: 860px;
    padding: 15px 55px;
    display: flex;
    align-items: center;
    flex-direction: column;
    /* justify-content: center; */
  }
  
  /* ------------------------------------------------------------------------ */

  /* 프로필 사진 이미지란 */
  .edit-main-profile {
    margin-bottom: 15px;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  /* 프로필 이미지 박스 */
  .edit-profile-preview {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 10px;
    background-color: #EFF6FF;
  }

  /* 이미지 채움 */
  .edit-profile-preview > img {
    width: 100%;
    height: 100%;
  }

  /* 카메라 아이콘 영역 */
  .edit-camera {
    position: relative;
    width: 100%;
    height: 100%;
  }

  /* 카메라 아이콘 */
  .fa-camera {
    width: 45px;
    height: 45px;
    position: absolute;
    left: 76%;
    bottom: 50%;
  }
  
  /* ------------------------------------------------------------------------ */

    /* 필수입력 안내사항 */
    .edit-main-profile + p {
      font-size: 0.8rem;
      font-weight: 500;
      text-align: left;
      width: 100%;
    }

    /* 안내사항 별 */
    .edit-main-profile + p > span {
      padding: 0;
      color: #ff0000;
    }

    /* 필수항목 별 */
    .edit-item-title > label > span {
      padding: 0;
      color: #ff0000;
    }

  /* ------------------------------------------------------------------------ */
  
  /* 회원수정 작성 폼 박스 */
  .edit-main-content {
    display: grid;
    grid-template-columns: 0.4fr 1fr; /* 270px */
    border-top: 2.5px solid #cccccc;
    border-bottom: 2.5px solid #CCC;
    width: 100%;
    margin-top: 10px;
  }

  /* 제목 내용 위치 조정 */
  .edit-item-title, .edit-item-content {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
  }
  /* 제목 내용 크기 조정 */
  .edit-item-title > label, .edit-item-content > div {
    width: 100%;
    height: 68px;
    border-top: 1.2px solid #ECECEC;
  }
  /* 제목 섹션 위치 조정 */
  .edit-item-title > label {
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 1.2rem;
    background-color: #F6F6F6;
  }

  /* 내용 섹션 위치 조정 */
  .edit-item-content > div:not(:first-child) {
    padding: 17px 0 0 10px;
  }
  .edit-item-content > div:first-child {
    display: flex;
    align-items: center;
    font-size: 1.2rem;
    padding-left: 10px;
    color: #5a5a5a;
  }
  /* 내용 안의 인풋들 */
  .edit-item-content > div input {
    padding: 5px;
    font-size: 0.9rem;
    width: 280px;
  }

  /* ------------------------------------------------------------------------ */

    /* 아래 버튼 영역 */
    .edit-footer {
      display: flex;
      justify-content: center;
      align-items: center; 
    }
    
    /* 수정확인 버튼 */
    .btn-submit {
      margin-top: 27.5px;
      padding: 10px;
      width: 100px;
      border: none;
      background-color: #3B82F6;
      color: #fff;
      font-size: 1.2rem;
    }
    /* 버튼 호버 */
    .btn-submit:hover {
      background-color: #2563EB;
    }

  /* ------------------------------------------------------------------------ */

  /* 안내사항 및 에러 메시지 */
  .ann-msg, .err-msg, .pass-msg {
    font-size: 0.68rem;
    padding-top: 3.5px;
  }

  /* 안내 메세지 */
  .ann-msg {
    color: #7537AA;
  }

  /* 에러 메세지 */
  .err-msg {
    color: #ff0000;
  }

  /* 통과 메세지 */
  .pass-msg {
    color: #00a500;
  }

  /* 테두리 있는건 포커스 비활성화 */
  input.err-border:focus,
  input.pass-border:focus {
    border: none;
  }

  /* 빨간 테두리 */
  .err-border {
    border: 2px solid rgba(255, 0, 0, 0.6);
    box-shadow: 0 0 2px #ff0000;
  }

  /* 초록 테두리 */
  .pass-border {
    border: 2px solid rgba(0, 165, 0, 0.6);
    box-shadow: 0 0 2px #00a500;
  }


</style>