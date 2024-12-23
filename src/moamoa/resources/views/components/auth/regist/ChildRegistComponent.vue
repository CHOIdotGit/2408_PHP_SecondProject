<template>
  <component v-if="registFlg" :is="childFormComponent" :registInfo="registInfo" />
  <component v-else :is="matchingComponent" :registInfo="registInfo" />

</template>

<script setup>
import { computed, defineAsyncComponent, reactive, onBeforeUnmount, onMounted } from 'vue';
import { useStore } from 'vuex';
import { onBeforeRouteLeave } from 'vue-router';

  const childFormComponent = defineAsyncComponent(() => import('./ChildFormComponent.vue'));
  const matchingComponent = defineAsyncComponent(() => import('./ChildMatchComponent.vue'));
  
  const store = useStore(); 
  const registFlg = computed(() => store.state.auth.registFlg);
  const registInfo = reactive({
    name: ''
    ,account: ''
    ,password: ''
    ,password_chk: ''
    ,email: ''
    ,nick_name: null
    ,tel: ''
    ,profile: null
    ,fam_code: ''
  });

  // 이동 반응 이벤트 ---------------------------------------------------------------------------------------------

  // 가입정보 리셋
  const clearRegistState = () => {
    store.commit('auth/resetRegist');
  };

  // 뒤로, 앞으로 버튼 이동시
  const handlePopState = () => {
    clearRegistState();
  };

  // 주소를 바꾸거나 새로고침시
  const handleBeforeUnload = () => {
    clearRegistState();
  };

  onMounted(() => {
    // 이벤트 리스너 추가
    window.addEventListener('popstate', handlePopState);
    window.addEventListener('beforeunload', handleBeforeUnload);
  });

  onBeforeUnmount(() => {
    // 컴포넌트가 넘어갈 때 이벤트 리스너를 제거
    window.removeEventListener('popstate', handlePopState);
    window.removeEventListener('beforeunload', handleBeforeUnload);
  });

  // 다른 페이지 이동 시 state에 담긴 회원정보를 없애야함
  onBeforeRouteLeave((to, from, next) => {
    clearRegistState();
    next();
  });

</script>

<style scoped>
  
</style>

