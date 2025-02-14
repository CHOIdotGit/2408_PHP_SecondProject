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
        <h3>가족코드</h3>
        <div>
          <p>{{ userInfo.family_code }}</p>
        </div>
        <p>이 코드를 자녀와 공유하여 계정을 연결하세요</p>
      </div>

      <!-- 자녀 리스트 DIV -->
      <div v-if="userType === 'parent'" class="info-footer-list">

      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onBeforeMount, ref } from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';

  const store = useStore();
  const route = useRoute();

  // 유저 정보 ---------------------------------------------------------------------------------------------
  const userInfo = computed(() => store.state.auth.parentFlg ? store.state.auth.parentInfo : store.state.auth.childInfo);
  const userType = ref(route.path.split('/')[1]);

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
    height: 860px;
    padding: 15px 55px;
    display: flex;
    align-items: center;
    flex-direction: column;
    justify-content: center;
    border: 1px solid #000;
  }

  /* ------------------------------------------------------------------------ */

  /* 헤더 박스 */
  .info-header-title, .info-main-content {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
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
    font-size: 1.6rem;
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

  /* 메인 박스 */
  .info-main-content {
    width: 100%;
    border: 1px solid #ccc;
  }

</style>