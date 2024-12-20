<template>
    <div class="header">
        <div class="logo"></div>
        <div class="navi-bar">
            <!-- 부모/자녀에 따라 홈 경로 변경 -->
            <div class="item">
                <router-link v-if="$store.state.auth.authFlg" to="/parent/home" class=link-deco><p class="item-btn">홈</p></router-link>
                <router-link v-else to="/child/home" class=link-deco><p class="item-btn" >홈</p></router-link>
            </div>
            <!-- v-for를 이용한 지출/미션/캘린터 메뉴 -->
            <!-- 문제점: router-link 어려움 -->
            <!-- <div class="item" v-for="index in headerMenu" :key="index">
                <p class="item-btn" >{{ index }}</p>
                    <div class="child-dropdown" v-if="$store.state.auth.authFlg === true">
                        <router-link to=""><p class="child" v-for="child in childNameList" :key="child">{{ child.name }}</p></router-link>
                    </div>
            </div> -->

            <div class="item">
                <router-link to="/parent/spend/list/" class=link-deco><p class="item-btn" >지출</p></router-link>
                    <div class="child-dropdown" v-if="$store.state.auth.authFlg">
                        <router-link to="/child/spend/list/" class=link-deco><p class="child" v-for="child in childNameList" :key="child">{{ child.name }}</p></router-link>
                    </div>
            </div>

            <div class="item">
                <router-link to="/parent/mission/list" class=link-deco><p class="item-btn" >미션</p></router-link>
                    <div class="child-dropdown" v-if="$store.state.auth.authFlg">
                        <router-link to="/child/mission/list" class=link-deco><p class="child" v-for="child in childNameList" :key="child">{{ child.name }}</p></router-link>
                    </div>
            </div>

            <div class="item">
                <router-link to="/parent/calendar" class=link-deco><p class="item-btn" >캘린더</p></router-link>
                    <div class="child-dropdown" v-if="$store.state.auth.authFlg">
                        <router-link to="/child/calendar" class=link-deco><p class="child" v-for="child in childNameList" :key="child">{{ child.name }}</p></router-link>
                    </div>
            </div>
            <div class="item">
                <p class="item-btn">통계</p>
            </div>

            <!-- 햄버거/ 알람 -->
            <div class="drop-bar">
                <div class="item">
                    <button @click="hamDropDown" class="hamburger-btn">
                        <img class="hamburger-icon" src="/img/icon-hamburger.png" width="40px" height="40px">
                    </button>
                    <!-- 햄버거 드롭 메뉴 -->
                    <div class="dropdown" v-show="dropDownMenu">
                        <router-link to="" class="link-deco"><p class="info-page">개인정보 수정</p></router-link>
                        <router-link to="" class="link-deco"><p class="info-page">가족정보</p></router-link>
                        <button type="button" @click="$store.dispatch('auth/logout')" class="logout-btn">로그아웃</button>
                    </div>
                </div> 
                
                <div class="item">
                    <button @click="bellDropDown" class="bell-btn">
                        <img class="bell-icon" src="/img/icon-bell.png" width="40px" height="40px">
                    </button>
                    <!-- 벨 드롭 메뉴 -->
                    <div class="dropdown-bell" v-show="bellListDropMenu">
                        <a href="#" class="alram" v-for="item in bellContent" :key="item">
                            <img class="alram-pro" :src="item.img" width="50px" height="50px">
                            <div  class="bell-content">
                                <p>[미션](이)의 미션이 등록되었어요!</p>
                                <p></p>
                            </div>
                        </a>
                        <a href="#" class="alram">
                            <img  class="alram-pro" src="/img/profile-icon/icon-girl-1.png" width="50px" height="50px" >
                            <div class="bell-content">
                                <p>[미션]배현진(이)의 미션이 승인을 기다리고 있어요.</p>
                                <p>2024. 12. 04</p>
                            </div>
                        </a>
                        <a href="#" class="alram">
                            <img  class="alram-pro" src="/img/profile-icon/icon-girl-1.png" width="50px" height="50px" >
                            <div class="bell-content">
                                <p>[미션]배현진(이)의 지출이 등록되었어요!</p>
                                <p>2024. 12. 03</p>
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
import { computed, ref, onBeforeMount } from 'vue';
import { useStore } from 'vuex';

// *******미션 알람*******


// 헤더 메뉴
const headerMenu = 
    ['지출', '미션', '캘린더'];


// 헤더 메뉴 자녀 이름 출력
const store = useStore();
const childNameList = computed(() => store.state.header.childNameList);

onBeforeMount(() => {
    // if(store.state.header.childNameList.length < 1){
        store.dispatch('header/childNameList');
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

const bellDropDown = () => {
    console.log('열라라 참께');
    bellListDropMenu.value = !bellListDropMenu.value;
    // bellListDropMenu.value = true;
    // dropDownMenu.value = false;
    console.log('이거 닫겼나?');
}

</script>
<style>

    
</style>