<template>
    <div class="d-flex" v-if="!isMobile">
        <div class="div-container">
            <!-- :slides-per-view="parentHome.length === 2 ? 2 : 3" -->
            <!-- :loop="parentHome.length > 2" -->
            <Swiper v-if="parentHome.length > 0"
                :slides-per-view="parentHome.length === 2 ? 2 : 3"
                ref="swiper"
                :modules="modules"
                :loop="false"
                :navigation="parentHome.length > 2"
                :scrollbar="true"
                :centeredSlides="parentHome.length < 2"
                class="div-swiper"
            >
                <SwiperSlide v-for="item in parentHome" :key="item.child_id" class="v-loop">
                    <div class="child-div">
                        <img class="profile-img" :src="item.profile" :style="{ objectFit: 'cover' }">
                        <div class="child">
                            <h3 class="name">{{ item.name }}</h3>
                            <div class="expense-box">
                                <div class="recent-expenses menu SMN_effect-34" @click="goSpendList(item.child_id)"><span>지출 내역</span></div>
                                <div class="amount-div">
                                    <div v-if="item.transactions && item.transactions.length === 0">
                                        <p class="no-amount">최근 지출한 금액이 없습니다.</p>
                                    </div>
                                    <div v-else class="amount-middle-div">
                                        <div class="amount" v-for="transaction in item.transactions" :key="transaction">
                                            {{ transaction.amount.toLocaleString() }}원
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="child-mission">
                                <p class="mission menu SMN_effect-34" @click="goMissionList(item.child_id)"><span>승인 대기 중인 미션</span></p>
                                <div class="chk-div">
                                    <div v-if="item.missions && item.missions.length === 0" class="margin-top">
                                        <p class="no-mission">승인 대기 중인 미션이 없습니다.</p>
                                    </div>
                                    <div v-else class="mission-detail" :class="{'centered-mission': item.missions.length < 3}" :style="{
                                        marginTop: item.missions.length === 1 ? '60px' : item.missions.length === 2 ? '50px' : '0' }">
                                        <div class="chk-div-box" v-for="mission in item.missions" :key="mission.mission_id">
                                            <p class="mission-title">{{ getTruncatedTitle(mission.title) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </SwiperSlide>
            </Swiper>
            <div v-if="noChildFlg">
                <p class="no-child">등록된 자녀가 없습니다.</p>
                <p class="no-child">자녀를 등록해주세요. 가족정보 페이지에서 자녀를 승인하시면 가족으로 등록됩니다.</p>
            </div>
        </div>
    </div>

<div class="m-container" v-if="isMobile">
    <div class="m-header">
        <img src="/img/icon-girl-4.png" alt="" class="m-user-image">
        <p class="m-user-profile">김주연</p>
        <p class="go-update"> > </p>
    </div>
    <div class="m-child">
        <img src="/img/icon-boy-4.png" alt="" class="m-child-profile">
        <div class="m-main-content">
            <p class="m-child-name"> 배현진 </p>
            <div class="m-expenses">
                <p class="m-home-title-e">지출내역</p>
                <p class="m-home-content m-first">2,400원</p>
                <p class="m-home-content">1,700원</p>
                <p class="m-home-content">32,600원</p>
            </div>
            <div class="m-expenses">
                <p class="m-home-title-c"> 승인 대기중인 내역</p>
                <p class="m-home-content m-first"> 테스트 </p>
                <p class="m-home-content"> 방청소하기</p>
                <p class="m-home-content"> 프로젝트 마무리하기</p>
            </div>
        </div>
    </div>

    <footer>
        <div class="m-footer-menu">
            <div class="m-menu">
                <div class="m-menu-section">
                    <img src="/img/icon-home.png" alt="" class="m-home menu-sec-first">
                    <p class="m-menu-title   menu-sec-first"> 홈 </p>
                </div>
                <div class="m-menu-section">
                    <img src="/img/icon-piggy-bank.png" alt="" class="m-mission">
                    <p class="m-menu-title"> 미션 </p>
                </div>
                <div class="m-menu-section">
                    <img src="/img/icon-coin.png" alt="" class="m-expense">
                    <p class="m-menu-title"> 지출 </p>
                </div>
                <div class="m-menu-section">
                    <img src="/img/icon-calendar.png" alt="" class="m-calendar">
                    <p class="m-menu-title"> 달력 </p>
                </div>
                <div class="m-menu-section">
                    <img src="/img/icon-sack-dollar.png" alt="" class="m-bank">
                    <p class="m-menu-title"> 모아통장 </p>
                </div>
                <div class="m-menu-section">
                    <img src="/img/mobile-etc.png" alt="" class="m-etc">
                </div>
        </div>
    </div>
</footer>
</div>

</template>

<script setup>

import { computed, ref, onBeforeMount } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';

// 스와이퍼
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Navigation, Scrollbar } from 'swiper/modules';

const modules = [Navigation, Scrollbar];
// Swiper 스타일 임포트
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

// 스와이퍼 인스턴스
const swiper = ref(null);

const originalWarn = console.warn;

console.warn = function(message, ...args) {
  if (message.includes("Swiper Loop Warning")) {
    return; // Swiper 관련 경고 무시
  }
  originalWarn.call(console, message, ...args); // 다른 경고는 그대로 출력
};

// ------------------------------------------------------

// 스토어, 라우터, 라우트
const store = useStore();
const router = useRouter();
const route = useRoute();

// 미션 리스트 가져오기
const parentHome = computed(() => store.state.mission.parentHome);

// 자녀없음 문구 출력 여부 플래그
const noChildFlg = ref(false);

// 12글자 이후 '...'으로 표기
const maxLength = 12;

const getTruncatedTitle =(title) => {
    return title.length > maxLength 
    ? title.substring(0, maxLength) + '...' 
    : title;
};

const isMobile = store.state.mobile.isMobile;
// 미션 리스트로 이동
const goMissionList = (child_id) => {
    // store.dispatch('mission/missionList', child_id);
    store.dispatch('mission/missionList', {child_id: route.params.id, page: 1});
    router.push('/parent/mission/list/' + child_id);
};

// 지출 리스트로 이동
const goSpendList = (child_id) => {
    // 거래 정보를 가져오는 액션 호출
    store.dispatch('transaction/transactionList', child_id);
    router.push('/parent/spend/list/' + child_id);
};


// onMount
onBeforeMount(async () => {
    try {
        await store.dispatch('mission/parentHome');
        noChildFlg.value = parentHome.value.length === 0 ? true : false;
    } catch(e) {
        console.log(e);
    }
});

</script>
<style scoped>
@import url('../../../../css/hoverEffect.css');

/* 메인 화면 */
.d-flex {
    margin-left: 80px;
    width: 100%;
    height: 800px;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 55px;
}

.div-container {
    width: 1300px;
    height: 100%;
}

.div-swiper{
    width: 100%;
    height: 780px;
    background-color: transparent;
    margin-top: 20px;
}

.no-child {
    font-size: 5rem;
}

.profile-img {
    display: flex;
    justify-content: center;
    width: 120px;
    height: 120px;
    border: 5px solid #A2CAAC; 
    background-color: white;
    border-radius: 100%;
    padding: 3px;
    margin-left: 90px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
}

.mission-detail{
    margin-top: 20px;
}

/* 미션 항목이 1개 또는 2개일 때 중앙 정렬 효과 */
.centered-mission {
    /* display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center; */
}

.mission-title {
    font-size: 1.5rem;
    text-align: center;
    margin-top: 10px;
}

.child-div {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.child {
    background-color: white;
    border: solid #A2CAAC 5px;
    width: 320px;
    height: 500px;
    border-radius: 25px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
}

/* 프로필 텍스트 */
.name {
    font-size: 2rem;
    margin-top: 20px;
    text-align: center;
    color: #000000;
}

.expense-box {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.nickname {
    font-size: 1.5rem;
    margin-top: 5px;
    text-align: center;
    color: #000000;
}


.d-flex-start {
    display: flex;
    justify-content: start;
    margin-left: 2rem;
}

.recent-expenses {
    background-color: #A2CAAC;
    color: #FFFFFF;
    border-radius: 10px;
    width: 160px;
    height: 50px;
    font-size: 1.7rem;
    font-weight: 600;
    margin-top: 15px;
    text-align: center;
    cursor: pointer;
    padding-top: 10px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
    /* 애니메이션 효과 */
    display: inline-block;
    overflow: hidden;
    position: relative;
}

.child-mission {
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 250px;
}

.mission {
    background-color: #A2CAAC;
    color: #FFFFFF;
    border-radius: 10px;
    width: 275px;
    height: 50px;
    font-size: 1.7rem;
    font-weight: 600;
    margin-top: 15px;
    text-align: center;
    cursor: pointer;
    padding-top: 10px;
    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
}

.mission-box {
    height: 45px;
}

.amount-div {
    margin-top: 10px;
}

.amount-middle-div {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
}

.amount {
    font-size: 1.5rem;
    text-align: center;
    margin-top: 10px;
}

.no-amount {
    font-size: 1.3rem;
    margin: 50px 0;
    text-align: center;
}

.margin-top {
    margin-top: 4rem;
}

.no-mission {
    text-align: center;
    font-weight: 600;
    font-size: 1.3rem;
}

/* ------------- 모바일 버전 css ------------ */

.m-container {
    width: 100vw;
    height: 100vh;
}
.m-header {
    display: flex;
    height: 70px;
    background-color: #A2CAAC;
    width: 100vw;
    align-items: center;
    gap: 10px;
}

.m-user-image {
    height: 50px;
    width: 50px;
    border-radius: 50px;
    border: 2px white solid;
    background-color: white;
    margin-left: 20px;
}

.m-user-profile {
    font-size: 1.7rem;
    width: 100px;
}

.go-update {
    font-size: 2rem;
    margin-left: 180px;
}

.m-child-profile {
    width: 100px;
    height: 100px;
    border-radius: 50px;
    border: 3px solid #A2CAAC;
    background-color: white;
}

.m-child {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 690px;
    margin-top: 20px;
}   

.m-child-name {
    margin-top: 20px;
    font-size: 1.5rem;
    text-align: center;
    font-weight: 600;
}

.m-home-title-e{
    font-size: 1.3rem;
    margin-left: 70px;
    background-color: #A2CAAC;
    color: white;
    border-radius: 15px;
    font-weight: 600;
    width: 100px;
    text-align: center;
    margin-top: 20px;
    height: 50px;
    line-height: 50px;
}

.m-home-title-c{
    font-size: 1.3rem;
    margin-left: 35px;
    background-color: #A2CAAC;
    color: white;
    border-radius: 15px;
    font-weight: 600;
    width: 180px;
    text-align: center;
    margin-top: 20px;
    height: 50px;
    line-height: 50px;
}

.m-home-content {
    text-align: center;
    font-size: 1.2rem;
    height: 30px;
    line-height: 30px;
}

.m-first {
    margin-top: 20px;
}

.m-main-content {
    margin-top: 20px;
    width: 250px;
    border: 3px solid #A2CAAC;
    border-radius: 20px;
    height: 420px;
}

.m-menu {
    display: flex;
    gap: 15px;
    border-top: 2px solid black;
    img {
        filter: contrast(0.5);
        margin-top: 10px;
        width: 25px;
        height: 25px;
    }
}

.menu-sec-first {
    margin-left: 15px;
}
.m-menu-section {
    display: flex;
    flex-direction: column;
    width: 60px;
    justify-content: center;
    align-items: center;
    gap: 3px;
}
.m-menu-title {
    font-size: 0.8rem;
}

.m-home {
    margin-left: 15px;
}
</style>