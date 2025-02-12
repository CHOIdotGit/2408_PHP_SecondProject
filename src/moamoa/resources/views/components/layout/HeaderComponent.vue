<!-- 2차 프로젝트 일때 헤더  -->
<!-- 더이상 작업하지 말것!! -->
<!-- 공부용이니 지우지 마세요 -->
<template>
    <div class="header">
        <div class="logo"></div>
        <div class="navi-bar">
            <ParentHeaderComponent v-if="$store.state.auth.parentFlg" />
            <ChildHeaderComponent v-else />
            <!-- 부모/자녀에 따라 메뉴 경로 변경 -->
            <!-- <div class="item" v-for="item in menu" :key="item"> -->
            <!-- <div class="item" v-for="item in baseMenuInfo" :key="item">
                <router-link :to="item.firstChildPath" class="link-deco"><p class="item-btn">{{ item.name }}</p></router-link>
                <div class="child-dropdown" v-if="$store.state.auth.parentFlg" v-for="child in childNameList" :key="child">
                    <router-link :to="item.path + '/' + child.child_id" class="link-deco"><p class="child" >{{ child.name }}</p></router-link>
                </div>
            </div> -->
            <!-- 통계 메뉴(자녀일때 표시 안함) -->
            <!-- <div class="item">
                <router-link v-if="$store.state.auth.parentFlg" to="/parent/stats" class="link-deco"><p class="item-btn">통계</p></router-link>
            </div> -->

            <!-- v-for를 이용한 지출/미션/캘린터 메뉴 -->
            <!-- 문제점: router-link 어려움 -->
            <!-- <div class="item" v-for="index in headerMenu" :key="index">
                <p class="item-btn" >{{ index }}</p>
                    <div class="child-dropdown" v-if="$store.state.auth.authFlg === true">
                        <router-link to=""><p class="child" v-for="child in childNameList" :key="child">{{ child.name }}</p></router-link>
                    </div>
            </div> -->

            <!-- <div class="item">
                <router-link v-if="$store.state.auth.parentFlg" :to="'/parent/spend/list/'+ child_id" class="link-deco"><p class="item-btn">지출</p></router-link>
                <router-link v-else to="/child/spend/list" class="link-deco"><p class="item-btn" >지출</p></router-link>
                
                    <div class="child-dropdown" v-if="$store.state.auth.parentFlg">
                        <router-link :to="'/parent/spend/list/'+ child.child_id" class="link-deco" v-for="child in childNameList" :key="child"><p class="child" >{{ child.name }}</p></router-link>
                    </div>
            </div>

            <div class="item">
                <router-link v-if="$store.state.auth.childFlg" to="/child/mission/list" class="link-deco"><p class="item-btn" >미션</p></router-link>
                <router-link v-else to="/parent/mission/list" class="link-deco"><p class="item-btn" >미션</p></router-link>
                    <div class="child-dropdown" >
                        <router-link :to="'/parent/mission/list/' + child.child_id" class="link-deco" v-for="child in childNameList" :key="child"><p class="child" >{{ child.name }}</p></router-link>
                        
                    </div>
            </div> -->

            <!-- <div class="item">
                <router-link v-if="$store.state.auth.parentFlg" to="/parent/calendar" class="link-deco"><p class="item-btn" >캘린더</p></router-link>
                <router-link v-else to="/child/calendar" class="link-deco"><p class="item-btn" >캘린더</p></router-link>
                    <div class="child-dropdown" v-if="$store.state.auth.parentFlg">
                        <router-link to="/parent/calendar" class="link-deco"><p class="child" v-for="child in childNameList" :key="child">{{ child.name }}</p></router-link>
                    </div>
            </div>
            <div class="item">
                <router-link v-if="$store.state.auth.parentFlg" to="/parent/stats"><p class="item-btn">통계</p></router-link>
            </div> -->

            <!-- 햄버거/ 알람 -->
            <div class="drop-bar">
                <div class="item">
                    <button @click="hamDropDown" class="hamburger-btn">
                        <img class="hamburger-icon" src="/img/icon-hamburger.png" width="40px" height="40px">
                    </button>
                    <!-- 햄버거 드롭 메뉴 -->
                    <div class="dropdown" v-show="dropDownMenu">
                        <router-link v-if="$store.state.auth.parentFlg" to="/parent/family/info" class="link-deco">
                            <p class="info-page">가족정보</p>
                        </router-link>
                        <!-- <router-link v-if="$store.state.auth.childFlg" to="/child/private/rematching" class="link-deco">
                            <p class="info-page">부모 재매칭</p>
                        </router-link> -->
                        <!-- <router-link :to="$store.state.auth.parentFlg ? '/parent/private/edit' : '/child/private/edit'" class="link-deco">
                            <p class="info-page">개인정보 수정</p>
                        </router-link> -->
                        <router-link :to="$store.state.auth.parentFlg ? '/parent/private/password' : '/child/private/password'" class="link-deco">
                            <p class="info-page">비밀번호 변경</p>
                        </router-link>
                        <!-- <router-link :to="$store.state.auth.parentFlg ? '/parent/private/withdrawal' : '/child/private/withdrawal'" class="link-deco">
                            <p class="info-page info-page-red">회원 탈퇴</p>
                        </router-link> -->
                        <button type="button" @click="$store.dispatch('auth/logout')" class="logout-btn">로그아웃</button>
                    </div>
                </div> 
                
                <div class="item" v-if="$store.state.auth.parentFlg">
                    <button @click="bellDropDown" class="bell-btn">
                        <img class="bell-icon" src="/img/icon-bell.png" width="40px" height="40px">
                    </button>
                    <!-- 벨 드롭 메뉴 -->
                    <div class="dropdown-bell" v-show="bellListDropMenu" v-if="$store.state.auth.parentFlg">
                        <a href="#" class="alram" v-for="item in bellContent" :key="item">
                            <!-- 이미지는 3차로 -->
                            <!-- <img class="alram-pro" :src="item.img" width="50px" height="50px"> -->
                            <div  class="bell-content">
                                <p>{{ item.name }}(이)의 미션 등록되었어요!</p>
                                <p>{{ item.created_at }}</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div> 
        </div>
    </div>
    <hr class="header-hr">
</template>
<script setup>
import ParentHeaderComponent from './ParentHeaderComponent.vue';
import ChildHeaderComponent from './ChildHeaderComponent.vue';
import { computed, ref, onBeforeMount, reactive } from 'vue';
import { useStore } from 'vuex';

// 헤더 메뉴 자녀 이름 출력
const store = useStore();
const childNameList = computed(() => store.state.header.childNameList);

// 부모/자녀 확인후 메뉴 경로 변경
// const parentMenu = ref([]);

// const childMenu = ref([
//     {name: "홈", path: "/child/home"}
//     ,{name: "지출", path: "/child/spend/list"}
//     ,{name: "미션", path: "/child/mission/list"}
//     ,{name: "캘린더", path: "/child/calendar"}
// ]);
const basePath = store.state.auth.parentFlg ? '/parent' : '/child';
const firstChildSegmentParam = ref('');
const baseMenuInfo = ref([
    {name: "홈", path: basePath + "/home", segmentFlg: false, firstChildPath: ''}
    ,{name: "지출", path: basePath + "/spend/list", segmentFlg: true, firstChildPath: ''}
    ,{name: "미션", path: basePath + "/mission/list", segmentFlg: true, firstChildPath: ''}
    ,{name: "캘린더", path: basePath + "/calendar", segmentFlg: true, firstChildPath: ''}
    ,{name: "통계", path: basePath + "/stats", segmentFlg: false, firstChildPath: ''}
]);

console.log('test', typeof store.state.auth.parentFlg);
onBeforeMount(async () => {
    // if(store.state.header.childNameList.length < 1){
    if(store.state.auth.parentFlg){
        await store.dispatch('header/childNameList');
        firstChildSegmentParam.value = '/' + store.state.header.childNameList[0];

        baseMenuInfo.value.forEach((item) => {
            if(item.segmentFlg) {
                item.firstChildPath = item.path + firstChildSegmentParam.value;
            }
        });
    }
    console.log('헤더 메뉴 정보',baseMenuInfo);
    console.log('애들이름출력');
    // }
})

// *******햄버거 드랍 메뉴 *******
const dropDownMenu = ref(false);
// if(bellListDropMenu.value === true) {
//     console.log('닫겼나?');
    
// }

// const closeModal = () => {
//     if(bellListDropMenu.value === true) {
//         dropDownMenu.value = false;
//     }
// }

const hamDropDown = () => {
    console.log('열려랴');
    dropDownMenu.value = !dropDownMenu.value; // 현재 상태를 반전시킴
    console.log('닫힘?');
}

// *******벨 드랍 메뉴 *******
const bellListDropMenu = ref(false);
const bellContent = computed(() => store.state.header.bellContent);
const bellDropDown = () => {
    console.log('열라라 참께');
    bellListDropMenu.value = !bellListDropMenu.value;
    store.dispatch('header/bellContent');
    // bellListDropMenu.value = true;
    // dropDownMenu.value = false;
    console.log('이거 닫겼나?');
}






console.log(store.state.header.bellContent);

</script>
<style scoped>
    .info-page-red {
        color: #ff0000;
    }
    
</style>