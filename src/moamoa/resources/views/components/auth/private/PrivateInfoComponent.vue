<template>
  <!-- 배경 컨테이너 DIV -->
  <div class="info-container">
    <!-- 메인 박스 DIV -->
    <div class="info-main-box">
      <!-- 프로필 및 타이틀 DIV -->
      <div class="info-header-title">
        <div class="info-header-profile"
          :class="{ 'parent-border': userType === 'parent', 'child-border': userType === 'child' }" 
        >
          <img :src="userInfo.profile">
        </div>

        <h2>
          <span :class="{ 'parent-color': userType === 'parent', 'child-color': userType === 'child' }">
            {{ userInfo.name }}
          </span>님의 가족정보
        </h2>
      </div>
      
      <!-- 메인 내용 DIV -->
      <div class="info-main-content">
        
        <!-- 부모 가족정보 DIV -->
        <div v-if="userType === 'parent'">
          <h2>가족코드</h2>
          
          <div class="info-copy-code">
            <span>{{ userInfo.family_code }}</span>

            <button @click="copyFamCode" type="button">
              <img src="/img/icon-copy.webp">
            </button>
            
            <!-- 복사 알림창 DIV -->
            <div v-show="copyboard" class="info-alarm-copy">
              <div>
                <img @click="copyboardClose" src="/img/icon-cross.png">
                
                <br>

                <p>가족코드가 복사되었습니다</p>
              </div>
              
              <img src="/img/alarm-reverse.webp">
            </div>
          </div>

          <p>이 코드를 자녀와 공유하여 계정을 연결하세요</p>
        </div>

        <!-- 자녀 가족정보 DIV -->
        <div v-else>
          <h2>부모정보</h2>

          <div class="info-connect-card">
            <div class="info-header-profile parent-border">
              <img :src="userInfo.parent?.profile">
            </div>

            <div class="info-connect-content">
              <h2>{{ userInfo.parent?.name }}</h2>
              <p>보호자</p>
            </div>

            <div class="info-connect-btn">
              <button type="button">연결됨</button>
            </div>
          </div>

          <p>부모님과 공유하여 해당 계정이 맞는지 확인하세요</p>
        </div>

      </div>

      <!-- 자녀 리스트 DIV -->
      <div v-if="userType === 'parent'" :class="{ 'overflow-auto' : userInfo.children?.length > 4 }" class="info-footer-list">
        <h2>자녀</h2>

        <div v-if="userInfo.children?.length === 0" class="empty-child">
          <p>등록된 자녀의 정보가 없습니다.</p>
        </div>

        <div v-else :class="{ 'over-gap' : userInfo.children?.length > 3 }">
          <div v-for="item in userInfo.children" :key="item" class="info-footer-item">

            <!-- 승인 대기중 DIV -->
            <div v-if="item.app_state === '0'" class="info-wait-apply">
              <p>승인 대기중</p>
            </div>
            
            <!-- 자녀 프로필 DIV -->
            <div class="info-footer-profile">
              <img :src="item.profile">
            </div>

            <p>{{ item.name }}</p>

            <div class="info-footer-btn">
              
              <div v-if="item.app_state === '0'">
                <div class="info-btn-position">
                  <button type="button" class="info-apply-btn">
                    <img @click="applyBtn(item)" src="/img/icon-agree-blue.webp" alt="승인">
                  </button>

                  <div class="info-alarm-apply">
                    <div>
                      <p>승인</p>
                    </div>
                    
                    <img src="/img/alarm-reverse.webp">
                  </div>
                </div>

                <div class="info-btn-position">
                  <button type="button" class="info-cancel-btn">
                    <img @click="cancelBtn(item)" src="/img/icon-disagree-red.webp" alt="취소">
                  </button>

                  <div class="info-alarm-cancel">
                    <div>
                      <p>취소</p>
                    </div>
                    
                    <img src="/img/alarm-reverse.webp">
                  </div>
                </div>
              </div>

              <div v-else class="info-btn-position">
                <button type="button" class="info-applied-btn btn-green">
                  <img src="/img/icon-agree-green.webp" alt="승인됨">
                </button>

                <div class="info-alarm-applied">
                  <div>
                    <p>승인됨</p>
                  </div>
                  
                  <img src="/img/alarm-reverse.webp">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <PrivateAskModalComponent
    v-if="privateModalFlg"
    :msg="actionType === 'apply' ? '해당 자녀를 승인하시겠습니까?' : '해당 자녀를 취소하시겠습니까?'"
    :successMsg="actionType === 'apply' ? '승인처리가 완료되었습니다.' : '취소처리가 완료되었습니다.'"
    @confirm="actionType === 'apply' ? runningApply() : runningCancel()"
    @cancel="store.commit('auth/setPrivateModalFlg', false)"
  />

</template>

<script setup>
import { computed, onBeforeMount, onMounted, onUnmounted, ref } from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';
import PrivateAskModalComponent from '../../modal/PrivateAskModalComponent.vue';

  const store = useStore();
  const route = useRoute();

  // 유저 정보 ---------------------------------------------------------------------------------------------
  const userInfo = computed(() => store.state.auth.parentFlg ? store.state.auth.parentInfo : store.state.auth.childInfo);
  const userType = ref(route.path.split('/')[1]);


  // 클립보드 복사 처리 ---------------------------------------------------------------------------------------------
  
  // 복사 알림창 스위칭
  const copyboard = ref(false);

  const copyboardClose = () => {
    copyboard.value = false;
  }

  // 복사 버튼
  const copyFamCode = () => {
    // 새요소 생성
    const textCode = document.createElement('textarea');

    // 요소에 가족코드를 저장
    textCode.value = userInfo.value.family_code;

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
  
  // 부모의 자녀 관리 ---------------------------------------------------------------------------------------------
  
  // 모달 스위칭
  const privateModalFlg = computed(() => store.state.auth.privateModalFlg);

  // 선택 자녀 저장용
  const selectChild = ref(null);

  // 실행한 버튼 액션
  const actionType = ref('');

  // 자녀 승인 처리
  const applyBtn = (child) => {
    // 선택된 자녀 저장
    selectChild.value = child;

    // 타입을 승인으로 세팅
    actionType.value = 'apply';

    // 모달 활성
    store.commit('auth/setPrivateModalFlg', true);
  };

  // 자녀 취소 처리
  const cancelBtn = (child) => {
    // 선택된 자녀 저장
    selectChild.value = child;

    // 타입을 취소로 세팅
    actionType.value = 'cancel';

    // 모달 활성
    store.commit('auth/setPrivateModalFlg', true);
  };

  // 승인시 실행
  const runningApply = () => {
    store.dispatch('auth/applyChild', selectChild.value.child_id).then(() => {
      setTimeout(() => {
        // 업데이트 성공시 수동 처리
        selectChild.value.app_state = '1';
      }, 500);
    });
  };

  // 취소시 실행
  const runningCancel = () => {
    store.dispatch('auth/deleteChild', selectChild.value.child_id).then(() => {
      setTimeout(() => {
        // 해당 자녀 정보를 배열에서 제거
        const index = userInfo.value.children.findIndex(item => item.child_id === selectChild.value.child_id);
        
        if(index !== -1) {
          userInfo.value.children.splice(index, 1);
        }
      }, 500);
    });
  };

  // 이벤트 처리 ---------------------------------------------------------------------------------------------

  // 클릭 외부 감지 함수
  const handleCopyBoard = (e) => {
    const copyIcon = document.querySelector('.info-alarm-copy');

    // 알림창이 활성화 되어있고 타겟이 해당DOM의 이외를 클릭했을때
    if(copyIcon && !copyIcon.contains(e.target) && copyboard.value) {
      // 외부 클릭시 알림 숨김
      copyboard.value = false;
    }
  };

  // 이벤트 등록
  onMounted(() => {
    document.addEventListener('mousedown', handleCopyBoard);
  });

  // 이벤트 해제
  onUnmounted(() => {
    document.removeEventListener('mousedown', handleCopyBoard);
  });  
  
  onBeforeMount(() => {
    // 유저 정보 로드
    store.dispatch(store.state.auth.parentFlg ? 'auth/parentInfo' : 'auth/childInfo' );
  });

</script>

<style scoped>
  
  /* 배경 설정 */
  .info-container {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  /* 메인 박스 */
  .info-main-box {
    width: 980px;
    height: 880px;
    padding: 0 55px;
    display: flex;
    align-items: center;
    flex-direction: column;
    /* justify-content: center; */
    /* border: 1px solid #000; */
  }
  /* ------------------------------------------------------------------------ */

  /* 헤더 박스 */
  .info-header-title, .info-main-content {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
  }

  .info-header-title > span {
    font-size: 2rem;
  }

  /* 헤더 프로필 아이콘 */
  .info-header-profile {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #EFF6FF;
    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
    object-fit: cover;
  }
  /* 이미지 맞춤 */
  .info-header-profile > img {
    width: 100%;
    height: 100%;
  }
  /* 부모 테두리 */
  .parent-border {
    border: 5px solid #A2CAAC;
  }
  /* 자녀 테두리 */
  .child-border {
    border: 5px solid #5589e996;
  }

  /* 헤더 텍스트 */
  .info-header-title > h2 {
    font-size: 2rem;
    margin: 30px 0;
  }
  /* 부모 폰트컬러 */
  .parent-color {
    color: #A2CAAC;
  }
  /* 자녀 폰트컬러 */
  .child-color {
    color: #5589e996;
  }

  /* ------------------------------------------------------------------------ */

  /* 부모 메인 박스 */
  .info-main-content {
    width: 100%;
    border: 1px solid #ccc;
    padding: 40px;
  }
  /* 내용 중앙 정렬 */
  .info-main-content > div {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
  }
  /* 가족코드 텍스트 */
  .info-main-content > div > h2 {
    font-size: 1.8rem;
    margin-bottom: 30px;
  }
  /* 하단 메세지 */
  .info-main-content > div > p {
    font-size: 1.2rem;
    margin-top: 30px;
    color: #5a5a5a;
  }

  /* 가족코드 표시 박스 */
  .info-copy-code {
    width: 100%;
    display: flex;
    justify-content: center;
    background-color: #f5f5f5;
    padding: 30px 0;
    position: relative;
  }
  /* 가족코드 출력 */
  .info-copy-code > span {
    font-size: 2.5rem;
    font-weight: 600;
    letter-spacing: 0.4rem;
    padding-left: 27px;
  }
  /* 복사 아이콘 크기 조절 */
  .info-copy-code > button {
    width: 20px;
    height: 20px;
  }
  .info-copy-code > button > img {
    width: 100%;
    height: 100%;
  }
  /* 복사 버튼 설정 */
  .info-copy-code > button {
    background-color: transparent;
    border: none;
    margin: 15px 0 0 10px;
    cursor: pointer;
  }

  /* 복사 알림창 */
  .info-alarm-copy {
    position: absolute;
    display: flex;
    justify-content: center;
    align-items: flex-end;
    flex-direction: column;
    left: 55.5%;
    top: -14.2%;
    /* transform: translate(-50%, -50%); */
  }

  /* 알림 박스 설정 */
  .info-alarm-copy > div {
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    width: 100%;
    background-color: #fff;
    border: 1px solid #eee;
    border-radius: 5px;
    padding: 10px;
    padding-right: 20px;
    /* width: 200px; */
  }

  /* 알림 x 버튼 */
  .info-alarm-copy > div > img {
    position: absolute;
    left: 92%;
    top: 17%;
    width: 8px;
    height: 8px;
    cursor: pointer;
  }

  /* 복사 알리 텍스트 */
  .info-alarm-copy > div > p {
    font-size: 0.9rem;
    height: 100%;
    /* margin: 10px; */
  }

  /* 꼬다리 이미지 */
  .info-alarm-copy > img {
    position: absolute;
    left: 46.5%;
    top: 97%;
    width: 20px;
    height: 20px;
  }

  /* ------------------------------------------------------------------------ */

  /* 자녀 가족정보 박스 */
  .info-connect-card {
    display: grid;
    grid-template-columns: 160px 1fr 160px;
    align-items: center;

    width: 100%;
    background-color: #f5f5f5;
    padding: 60px 50px;
  }
  /* 자녀 내용 */
  .info-connect-content {
    width: 100%;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    flex-direction: column;
    width: auto;
  }
  /* 자녀 가족정보 부모명 */
  .info-connect-content > h2 {
    font-size: 1.8rem;
  }
  /* 보호자 텍스트 */
  .info-connect-content > p {
    margin-top: 10px;
    padding-left: 2px;
    font-size: 1.2rem;
    color: #A5A5A5;
  }
  /* 우측 연결됨 버튼 */
  .info-connect-btn {
    display: flex;
    justify-content: flex-end;
    align-items: center;
  }
  .info-connect-btn > button {
    border: none;
    font-size: 1.4rem;
    background-color: #DBF5D5;
    color: #008000;
    padding: 15px 30px;
    border-radius: 40px;
  }

  /* ------------------------------------------------------------------------ */

  /* 자녀 리스트 전체 박스 */
  .info-footer-list {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
  }
  /* 자녀 텍스트 */
  .info-footer-list > h2 {
    font-size: 1.8rem;
    margin: 30px 0 20px 0;
  }
  /* 자녀 리스트 문단 */
  .info-footer-list > div {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 87px;
    height: 27.5vh;
  }

  /* 자녀없을시 출력문구 */
  .empty-child {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    font-size: 2.6rem;
    color: #db0202;
    padding-left: 12px;
  }

  /* 자녀 아이템 박스 */
  .info-footer-item {
    width: 170px;
    height: 230px;
    display: flex;
    align-items: center;
    flex-direction: column;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 0 3px 3px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
    position: relative;
  }
  /* 자녀 이름 크기 */
  .info-footer-item > p {
    font-size: 1.4rem;
  }
  /* 승인 대기중 */
  .info-wait-apply {
    position: absolute;
    top: -6%;
    left: 17%;
    width: 110px;
    height: 25px;
    border-radius: 10px;
    background-color: #e9e900;
    font-size: 1rem;
    color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  /* 자녀 아이콘 */
  .info-footer-profile {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    overflow: hidden;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #EFF6FF;
    object-fit: cover;
    margin: 15px 0;
  }
  /* 이미지 크기 조정 */
  .info-footer-profile > img {
    width: 100%;
    height: 100%;
  }

  /* 승인 취소 버튼 문단 */
  .info-footer-btn {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 15px;
  }
  /* 승인 아이콘 정렬 */
  .info-footer-btn > div {
    display: flex;
    justify-content: center;
    align-items: center;
    column-gap: 40px;
  }
  /* 버튼 설정 */
  .info-footer-btn button {
    width: 35px;
    height: 35px;
    border: none;
    background-color: transparent;
  }
  /* 녹색버튼은 제외 */
  .info-footer-btn button:not(.btn-green) {
    cursor: pointer;
  }
  /* 이미지 크기 조정 */
  .info-footer-btn img {
    width: 100%;
    height: 100%;
  }

  /* 포지션 설정 */
  .info-btn-position {
    position: relative;
  }
  
  /* 미니박스 공통 설정  */
  .info-alarm-apply
  ,.info-alarm-cancel
  ,.info-alarm-applied {
    height: 25px;
    border-radius: 50px;
    background-color: #e9e900;
    font-size: 0.9rem;
    background-color: #fff;
    border: 1px solid #eee;
    position: absolute;
    display: none;
  }
  /* 미니박스 꼬다리 공통 설정 */
  .info-alarm-apply > img
  ,.info-alarm-cancel > img
  ,.info-alarm-applied > img {
    position: absolute;
    width: 15px;
    height: 15px;
  }
  /* 호버시 중앙정렬 */
  .info-apply-btn:hover + .info-alarm-apply
  ,.info-cancel-btn:hover + .info-alarm-cancel
  ,.info-applied-btn:hover + .info-alarm-applied {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  /* 승인취소 좌표 */
  .info-alarm-apply
  ,.info-alarm-cancel {
    top: -65%;
    left: -18%;
    width: 45px;
  }

  /* 승인됨 좌표 */
  .info-alarm-applied {
    top: -75%;
    left: -28%;
    width: 55px;
  }

  /* 미니박스 꼬다리 좌표 */
  .info-alarm-apply > img
  ,.info-alarm-cancel > img
  ,.info-alarm-applied > img {
    left: 35%;
    top: 96%;
  }


  /* ------------------------------------------------------------------------ */

  /* 스크롤 선택사항 */
  /* .overflow-auto {
    overflow: auto;
  } */

  /* 4개 이상이면 간격 강제조정 */
  .over-gap {
    gap: 57px !important;
  }
  
  /* ------------------------------------------------------------------------ */

</style>