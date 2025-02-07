<template>
<!-- 3차 고정 메뉴 바 -->
<!-- 부모가 로그인 했을 때 -->
<ParentMenuLeftComponent v-if="$store.state.auth.parentFlg"/>

<!-- 자녀가 로그인 했을 때 -->
<ChildMenuLeftComponent v-else />
</template>

<script setup>
import ParentMenuLeftComponent from './ParentMenuLeftComponent.vue';
import ChildMenuLeftComponent from './ChildMenuLeftComponent.vue';





</script>

<style scoped>


</style>