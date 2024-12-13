<template>
    <div class="header">
        <div class="logo"></div>
        <div class="navi-bar">
            <div class="item">
                <a href="#" class="item-btn">홈</a>
            </div>
            <div class="item">
                <a href="#" class="item-btn">지출</a>
                    <div class="child-dropdown">
                        <p class="child">배현진</p>
                        <p class="child">김주연</p>
                    </div>
            </div>
            <div class="item">
                <a href="#" class="item-btn">미션</a>
                    <div class="child-dropdown">
                        <p class="child">배현진</p>
                        <p class="child">김주연</p>
                    </div>
            </div>
            <div class="item">
                <a href="#" class="item-btn">캘린더</a>
                    <div class="child-dropdown">
                        <p class="child">배현진</p>
                        <p class="child">김주연</p>
                    </div>
            </div>
            <div class="item">
                <a href="#" class="item-btn">통계</a>
            </div>
            <div class="drop-bar">
                <div class="item">
                    <button @click="hamDropDown" class="hamburger-btn">
                        <img class="hamburger-icon" src="/img/icon-hamburger.png" width="40px" height="40px">
                    </button>
                    <!-- 햄버거 드롭 메뉴 -->
                    <div class="dropdown" v-show="dropDownMenu">
                        <a href="#" class="info-page">개인정보 수정</a>
                        <a href="#" class="info-page">가족정보</a>
                        <button class="logout-btn">로그아웃</button>
                    </div>
                </div> 
                
                <div class="item">
                    <button @click="bellDropDown" class="bell-btn">
                        <img class="bell-icon" src="/img/icon-bell.png" width="40px" height="40px">
                    </button>
                    <!-- 벨 드롭 메뉴 -->
                    <div class="dropdown-bell" v-show="bellListDropMenu">
                        <a href="#" class="alram">
                            <img class="alram-pro" src="/img/profile-icon/icon-girl-1.png" width="50px" height="50px">
                            <div class="bell-content">
                                <p>[미션]배현진(이)의 미션이 등록되었어요!</p>
                                <p>2024. 12. 04</p>
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

import { ref } from 'vue';

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