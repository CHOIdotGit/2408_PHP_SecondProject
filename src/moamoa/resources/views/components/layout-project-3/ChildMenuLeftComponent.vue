<template>
<!-- 자녀가 로그인 했을 때 표시되는 왼쪽 메뉴 -->
    <div class="menu-left child-theme" v-if="!isMobile">
        <div class="menu-container" v-show="slidMenu">
            <img src="/img/logo4.png" class="logo" width="250px"  height="100px">
            <!-- 자녀 프로필 표시 영역  -->
            <div class="child-box" >
                <div class="child-profile">
                    <img :src="childInfo.profile || '/user-img/default.webp'">    
                </div>
                <div class="child-info">
                    <div class="child-name">{{ childInfo.name }}</div>
                </div>
            </div>
            <!-- 메뉴 -->
            <div class="menu-box">
                <router-link to="/child/home" class="link-deco">
                    <div class="menu-title">
                        <img src="/img/icon-home.png" alt="" class="menu-icon">
                        홈
                    </div>
                </router-link>
                <!-- 최상민 : 거래 모듈 변경에 따른 지출리스트 이동 방법 변경 -->
                <div class="menu-title" @click="goSpendList"> 
                    <img src="/img/icon-coin.png" alt="" class="menu-icon">
                    지출
                </div>
                <!-- 최상민 : 미션 모듈 변경에 따른 미션리스트 이동 방법 변경 -->
                <div class="menu-title" @click="goMissionList"> 
                    <img src="/img/icon-piggy-bank.png" alt="" class="menu-icon">
                    미션
                </div>
                <div class="menu-title" @click="goChildCalendar(child_id)">
                    <img src="/img/icon-calendar.png" alt="" class="menu-icon">
                    캘린더
                </div>
                <div class="menu-title go-bank">
                        <img src="/img/icon-sack-dollar.png" alt="" class="menu-icon">
                        <p @click="goBankbook" class="moabank-p">모아은행</p>
                    <span @click="toggleSavingProduct" class="arrow-icon">{{ isOpen ? '▲' : '▼' }}</span>
                </div>
                <div v-if="isOpen" @click="router.push('/moabank/product')" class="saving-product">ㄴ 적금 상품</div>
                <div v-if="isOpen" @click="router.push('/child/expired/saving')" class="saving-product">ㄴ 만기된 적금</div>

                <!-- 4순위 : 상점 -->
                <!-- <div class="menu-title">
                    <img src="/img/icon-shopping-cart.png" alt="" class="menu-icon">
                    상점
                </div> -->
    

            </div>
            <!-- 고객센터 -->
            <div class="cs">
    
            </div>
        </div>
    
    </div>
</template>
    
<script setup>
import { computed, onBeforeMount, onMounted, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const route = useRoute();
const router = useRouter();

// +==========================+
// +    모바일 화면 전환       +
// +==========================+
// v-if="ismobile"적으면 모바일 화면으로 이동
const isMobile = store.state.mobile.isMobile;

// 좌측 메뉴 열고 닫기
const  slidMenu = ref(true);
const closeMenubtn = () => {
    slidMenu.value = !slidMenu.value;
}

// ****로그인한 자녀 프로필 불러오기*****
// const childInfo = computed(()=> store.state.header.setChildInfo[0]);////
const childInfo = computed(()=> store.state.header.childInfo);

onBeforeMount(async () => {
    await store.dispatch('header/childInfo');
})
// *************************************


// 적금 상품 표시 여부 (true: 보임, false: 숨김)
const isOpen = ref(false);

// 클릭 시 토글 (열기/닫기)
const toggleSavingProduct = () => {
    isOpen.value = !isOpen.value;
};

// 자녀 지출 리스트 페이지로 이동
const goSpendList = () => {
    store.dispatch('childTransaction/transactionList', {child_id: route.params.id, page: 1});
    router.push('/child/spend/list');
}

// 자녀 미션 리스트 페이지로 이동
const goMissionList = () => {
    store.dispatch('childMission/setChildMissionList', {child_id: route.params.id, page: 1});
    router.push('/child/mission/list');
}

//  자녀 캘린더로 이동
const goChildCalendar = () => {
    store.dispatch('calendar/childCalendarInfo');
    router.push('/child/calendar');
}

// 자녀 은행으로 이동
const goBankbook = () => {
    router.push('/child/moabank');
}


</script>

<style scoped>
* {
    font-family: 'MangoDdobak-R';
}

button {
    background-color: #FFFFFF;
}

.link-deco {
    text-decoration: none;
    color: #000000;
}

@font-face {
    font-family: 'MangoDdobak-R';
    src: url('https://fastly.jsdelivr.net/gh/projectnoonnu/2405-3@1.1/MangoDdobak-R.woff2') format('woff2');
    font-weight: 400;
    font-style: normal;
}

@font-face {
    font-family: 'MangoDdobak-B';
    src: url('https://fastly.jsdelivr.net/gh/projectnoonnu/2405-3@1.1/MangoDdobak-B.woff2') format('woff2');
    font-weight: 700;
    font-style: normal;
}

.menu-left {
    height: 100%;
    background-color: #5589e996;
    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
}

/* 상단 메뉴 */
.header-container {
    height: 40px;
    width: 100vw;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    padding: 5px;
    gap: 5px;
    background-color: #e9e9e9;
}

/* 좌측 메뉴 */
.menu-container {
    width: 300px;
    height: 100%;
    min-height: 100vh;
    -webkit-transition: left .3s;
    -moz-transition: left .3s;
    -ms-transition: left .3s;
    -o-transition: left .3s;
    transition: left .3s;
}

/* 반응형 버튼 */
.menu-btn {
    height: 60px;
    padding: 10px;
    display: flex;
    justify-content: flex-end;
}

.btn-left, .btn-right {
    width: 35px;
    height: 35px;
    padding: 5px;

}

/* 자녀 프로필 */
.child-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: #eaf1ff96;
    padding: 10px;
}

.child-profile {
    width: 100px;
    height: 100px;
    margin-bottom: 10px;
}

.child-profile >img {
    width: 100px;
    height: 100px;
    padding: 3px;
    background-color: #FFFFFF;
    border-radius: 50px;
    margin: 5px;
    object-fit: cover;
}

.child-info {
    display: flex;
    text-align: center;
    padding-left: 10px;
    gap: 10px;
    align-items: center;
}
.child-name {
    font-size: 1.3rem;
}

.child-nickname {
    font-size: 1.1rem;
}

/* 자녀 선택 셀렉트 박스 */
#child {
    width: 120px;
    height: 30px;
    font-size: 1.2rem;
    border-radius: 4px;
    
}


/* 자녀 선택 버튼 */
.child-toggle-btn {
    /* width: 20px; */
    height: 30px;
    display: flex;
    align-items: center;
}

/* 메뉴  */
.menu-box {
    font-size: 1.6rem;
    font-family: 'MangoDdobak-B';
    font-weight: 700;
}

.menu-title {
    padding: 15px 20px 15px 20px;
    /* border: 1px solid; */
    cursor: pointer;
}

.menu-title > img {
    width: 15px;
}

.menu-title:hover {
    color: #ffffff;
    background-color: #c3d8ff96;
}

.menu-icon {
    width: 15px;
}

.moabank-p {
    padding-left: 8px;

}

.test {
    display: flex;
    gap: 5px;
}

.test >img{
    width: 15px;
}

/* 서비스 문의 */
.cs {
    background-color: #e9e9e9;
}

.go-bank {
    display: flex;
    align-items: center;
}

.saving-product {
    padding: 10px 0 10px 40px;
    font-size: 1.5rem;
}

.saving-product:hover {
    color: #ffffff;
    background-color: #c3d8ff96;
    cursor: pointer;
}

.arrow-icon {
    margin-left: 40px;
    width: 100px;
    text-align: center;
}

.logo {
    margin-left: 30px;
}

/* 상단 메뉴(사용자매뉴얼, 알람, 부모 프로필) */
</style>