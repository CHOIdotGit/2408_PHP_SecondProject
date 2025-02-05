<!-- !!!! 2차 때 자녀 헤더 !!!!-->
<!-- 더이상 사용안 함 -->
<!-- 공부용이니 지우지 마세요 -->
<template>
    <div class="item" v-for="item in baseMenuInfo" :key="item">
        <router-link :to="item.path" class="link-deco"><p class="item-btn">{{ item.name }}</p></router-link>
    </div>
</template>

<script setup>
import { ref, onBeforeMount } from 'vue';

// 헤더 메뉴 자녀 이름 출력
const baseMenuInfo = ref([]);

onBeforeMount(() => {
    baseMenuInfo.value = [
        {name: "홈", path: "/child/home"}
        ,{name: "지출", path: "/child/spend/list"}
        ,{name: "미션", path: "/child/mission/list"}
        ,{name: "캘린더", path: "/child/calendar"}
        ,{name: "", path: ""}

    ];
});

</script>
<style>

    
</style>