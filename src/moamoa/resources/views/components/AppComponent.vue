<template>
    <header>
        <div class="header">
            <div class="logo"></div>
            <div class="navi-bar">
                <div class="item">
                    <button class="item-btn" v-if="isHomePage" :class="{ active: isActive }">홈</button>
                </div>
                <div class="item">
                    <button class="item-btn">지출</button>
                </div>
                <div class="item">
                    <button class="item-btn">미션</button>
                </div>
                <div class="item">
                    <button class="item-btn">캘린더</button>
                </div>
                <div class="drop-bar">
                    <div class="item">
                        <button class="hamburger-btn">
                            <img class="hamburger-icon" src="/img/icon-hamburger.png" width="40px" height="40px">
                        </button>
                        <!-- 햄버거 드롭 메뉴 -->
                        <div class="dropdown">
                            <a href="#" class="info-page">개인정보 수정</a>
                            <a href="#" class="info-page">가족정보</a>
                        </div>
                    </div> 
                    
                    <div class="item">
                        <button class="bell-btn">
                            <img class="bell-icon" src="/img/icon-bell.png" width="40px" height="40px">
                        </button>
                        <!-- 벨 드롭 메뉴 -->
                        <div class="dropdown-bell">
                            <a href="#" class="alram">
                                <img class="alram-pro" src="/img/profile-icon/icon-girl-1.png" width="50px" height="50px">
                                <div class="content">
                                    <p>[미션]배현진(이)의 미션이 등록되었어요!</p>
                                    <p>2024. 12. 04</p>
                                </div>
                            </a>
                            <a href="#" class="alram">
                                <img  class="alram-pro" rc="/img/profile-icon/icon-girl-1.png" width="50px" height="50px" >
                                <div class="content">
                                    <p>[미션]배현진(이)의 미션이 승인을 기다리고 있어요.</p>
                                    <p>2024. 12. 04</p>
                                </div>
                            </a>
                            <a href="#" class="alram">
                                <img  class="alram-pro" src="/img/profile-icon/icon-girl-1.png" width="50px" height="50px" >
                                <div class="content">
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
    </header>
    <!-- main -->
    <main>
        <router-view></router-view>

    </main>
    
    <!-- footer -->
    <footer>
        
    </footer>
</template>
<script setup>

import { computed } from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';

// vuex route, store 가져오기
// const store = useStore();
const route = useRoute();

const currentPath = computed(() => store.state.currentPath);


const isHomePage = computed(() => route.path === '/boards');
// const isOutlayPage = computed(() => route.path === '/outlays');
// const isMissionPage = computed(() => route.path === '/missions');
// const isCalendarPage = computed(() => route.path === '/calendars');

const isActive = computed(() => route.path === '/boards');

</script>
<style scoped>
    @import url("../../css/common.css");
    @import url("../../css/header.css");
</style>