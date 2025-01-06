<template>
    <div class="item" v-for="item in baseMenuInfo" :key="item">
        <router-link :to="item.path + item.segmentPath" @click="$store.dispatch(item.actionName, firstChildId)" class="link-deco"><p class="item-btn">{{ item.name }}</p></router-link>
        <div class="child-dropdown" v-if="$store.state.auth.parentFlg && item.name !== '홈' && item.name !=='통계' " v-for="child in childNameList" :key="child">
            <router-link :to="item.path + '/' + child.child_id" @click="$store.dispatch(item.actionName, child.child_id)" class="link-deco"><p class="child" >{{ child.name }}</p></router-link>
        </div>
        <!-- <div class="child-dropdown" v-if="$store.state.auth.parentFlg" v-for="child in childNameList" :key="child">
            <router-link :to="item.path + '/' + child.child_id" @click="$store.dispatch(item.actionName, child.child_id)" class="link-deco"><p class="child" >{{ child.name }}</p></router-link>
        </div> -->
    </div>
</template>

<script setup>
import { computed, ref, onBeforeMount } from 'vue';
import { useStore } from 'vuex';

// 헤더 메뉴 자녀 이름 출력
const store = useStore();
const childNameList = computed(() => store.state.header.childNameList);

const baseMenuInfo = ref([]);
const firstChildId = ref(0);

onBeforeMount(async () => {
    await store.dispatch('header/childNameList');
    firstChildId.value = store.state.header.childNameList[0].child_id;
    baseMenuInfo.value = [
        {name: "홈", path: "/parent/home", segmentPath: ''}
        ,{name: "지출", path: "/parent/spend/list", segmentPath: '/' + firstChildId.value, actionName: 'transaction/transactionList'}
        ,{name: "미션", path: "/parent/mission/list", segmentPath: '/' + firstChildId.value, actionName: 'mission/missionList'}
        ,{name: "캘린더", path: "/parent/calendar", segmentPath: '/' + firstChildId.value, actionName: 'calendar/calendarInfo'}
        ,{name: "통계", path: "/parent/stats", segmentPath: ''}
    ];
    console.log('애들이름출력');
})


</script>
<style>

    
</style>