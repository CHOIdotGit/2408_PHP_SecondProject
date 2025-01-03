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

  // state에 저장된 스토리지 회원 정보
  const registInfoState = computed(() => store.state.auth.registInfo);
  if(registInfoState.value) {
    registInfo.name = registInfoState.value.name;
    registInfo.account = registInfoState.value.account;
    registInfo.password = registInfoState.value.password;
    registInfo.password_chk = registInfoState.value.password_chk;
    registInfo.email = registInfoState.value.email;
    registInfo.nick_name = registInfoState.value.nick_name;
    registInfo.tel = registInfoState.value.tel;
    registInfo.fam_code = registInfoState.value.fam_code;
  }

  // 이동 반응 이벤트 ---------------------------------------------------------------------------------------------

  // 가입 정보 세팅
  const settingRegistInfo = () => {
    // 사용자가 실행한것이 새고로침이면
    if(performance.getEntriesByType('navigation')[0].type === 'reload') {
      // 스토리지와 회원정보를 세팅
      sessionStorage.setItem('registInfo', JSON.stringify(registInfo));
      store.commit('auth/setRegistInfo', registInfo);
    }else {
      // 그 이외는 리셋 실행
      clearRegistState();
    }
  }
  
  // 가입정보 리셋
  const clearRegistState = () => {
    // 입력된 회원 정보 값이 있는지 체크, 있으면 리셋
    if(Object.values(registInfo).some(value => value !== '' || value !== null || value !== undefined)) {
      sessionStorage.removeItem('registInfo');
      store.commit('auth/resetRegist');
    }
  };

  onMounted(() => {
    // 이벤트 리스너 추가
    window.addEventListener('popstate', clearRegistState());
    window.addEventListener('beforeunload', settingRegistInfo);
  });

  onBeforeUnmount(() => {
    // 컴포넌트가 넘어갈 때 이벤트 리스너를 제거
    window.removeEventListener('popstate', clearRegistState());
    window.removeEventListener('beforeunload', settingRegistInfo);
  });

  // 다른 페이지 이동 시 state에 담긴 회원정보를 없애야함
  onBeforeRouteLeave((to, from, next) => {
    clearRegistState();
    next();
  });

</script>

<style scoped>
  
</style>

