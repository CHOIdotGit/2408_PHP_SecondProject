<!-- !!!! 2차 때 부모 헤더 !!!!-->
<!-- 더이상 사용안 함 -->
<!-- 공부용이니 지우지 마세요 -->
<template>
    <div class="item" v-for="item in baseMenuInfo" :key="item">
        <div v-if="childNameList.length > 0">
            <router-link :to="item.path + item.segmentPath" @click="$store.dispatch(item.actionName, firstChildId)" class="link-deco"><p class="item-btn">{{ item.name }}</p></router-link>
            <div class="child-dropdown" v-if="$store.state.auth.parentFlg && item.name !== '홈' " v-for="child in childNameList" :key="child">
                <router-link v-if="item.name === '캘린더'" :to="item.path + '/' + child.child_id" @click="$store.dispatch(item.actionName, {date:new Date(), child_id:child.child_id})" class="link-deco"><p class="child" >{{ child.name }}</p></router-link>
                <router-link v-else :to="item.path + '/' + child.child_id" @click="$store.dispatch(item.actionName, child.child_id)" class="link-deco"><p class="child" >{{ child.name }}</p></router-link>
            </div>
            <!-- <div class="child-dropdown" v-if="$store.state.auth.parentFlg" v-for="child in childNameList" :key="child">
                <router-link :to="item.path + '/' + child.child_id" @click="$store.dispatch(item.actionName, child.child_id)" class="link-deco"><p class="child" >{{ child.name }}</p></router-link>
            </div> -->
        </div>
        <div v-else>
            <router-link :to="item.name === '홈' ? item.path : ''" class="link-deco"><p class="item-btn">{{ item.name }}</p></router-link>
        </div>
    </div>
</template>

<script setup>
import { computed, ref, onBeforeMount } from 'vue';
import { useStore } from 'vuex';

// 헤더 메뉴 자녀 이름 출력
const store = useStore();
const childNameList = computed(() => store.state.header.childNameList || []);

const baseMenuInfo = ref([
            {name: "홈", path: "/parent/home", segmentPath: '', actionName: ''}
            ,{name: "지출", path: "/parent/spend/list", segmentPath: '', actionName: ''}
            ,{name: "미션", path: "/parent/mission/list", segmentPath: '', actionName: ''}
            ,{name: "캘린더", path: "/parent/calendar", segmentPath: '', actionName: ''}
            ,{name: "통계", path: "/parent/stats", segmentPath: '', actionName: ''}
        ]);
const firstChildId = ref(0);

onBeforeMount(async () => {
    await store.dispatch('header/childNameList');
    if(store.state.header.childNameList.length > 0) {
        firstChildId.value = store.state.header.childNameList[0].child_id;
        baseMenuInfo.value = [
            {name: "홈", path: "/parent/home", segmentPath: '', actionName: ''}
            ,{name: "지출", path: "/parent/spend/list", segmentPath: '/' + firstChildId.value, actionName: 'transaction/transactionList'}
            ,{name: "미션", path: "/parent/mission/list", segmentPath: '/' + firstChildId.value, actionName: 'mission/missionList'}
            ,{name: "캘린더", path: "/parent/calendar", segmentPath: '/' + firstChildId.value, actionName: 'calendar/parentCalendarInfo'}
            ,{name: "통계", path: "/parent/stats", segmentPath: '/' + firstChildId.value, actionName: 'transaction/parentStats'}
        ];
    }
    console.log('애들이름출력');
})


</script>
<style>
.link-deco .child-dropdown {
    cursor: pointer;
}
    
</style>