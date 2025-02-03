<template>
    <div class="d-flex">
        <div class="div-container">
            <!-- :slides-per-view="parentHome.length === 2 ? 2 : 3" -->
            <Swiper v-if="parentHome.length > 0"
                :slides-per-view="parentHome.length === 2 ? 2 : 3"
                ref="swiper"
                :loop="parentHome.length > 2"
                :modules="modules"
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
                                    <div v-else>
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
                                    <div v-else class="mission-detail">
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
            </div>
            <div class="wave-wrapper">
                <div class="wave-container wave1"></div>
                <div class="wave-container wave2"></div>
                <div class="wave-container wave3"></div>
                <div class="wave-container wave4"></div>
            </div>
        </div>
    </div>
</template>
<script setup>

import { computed, ref, onBeforeMount } from 'vue';
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
const store = useStore();

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

// 미션 리스트로 이동
const goMissionList = (child_id) => {
    store.dispatch('mission/missionList', child_id);
};

// 지출 리스트로 이동
const goSpendList = (child_id) => {
    // 거래 정보를 가져오는 액션 호출
    store.dispatch('transaction/transactionList', child_id);
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
    width: 1620px;
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

.mission-title {
    font-size: 1.5rem;
    text-align: center;
    margin-top: 10px;
}

.wave-wrapper {
    position: relative;
    width: 1600px;
    height: 240px; /* 물결이 차지하는 높이 */
    /* overflow: hidden; 넘치는 부분 숨김  */
    bottom: 240px;
    left: -155px;
}

.wave-container {
    width: 100%;
    height: 240px;
    position: absolute;
}

.wave1 {
  clip-path: path('M0,140 C0,120 533,170 900,80 S1333,120 1600,160 V300 H0 Z');
  background: rgba(173, 216, 230, 0.2);
}

.wave2 {
  clip-path: path('M0,90 C267,180 533,30 800,90 S1333,180 1600,90 V300 H0 Z');
  background: rgba(173, 216, 230, 0.4);
}

.wave3 {
  clip-path: path('M0,180 C0,160 533,210 900,120 S1333,160 1600,200 V300 H0 Z');
  background: rgba(173, 216, 230, 0.6);
}

.wave4 {
  clip-path: path('M0,130 C267,220 533,70 800,130 S1333,220 1600,130 V300 H0 Z');
  background: rgba(173, 216, 230, 0.8);
}

/* 현재 사용하지 않는 css 효과 */
/* .blank {
    color: transparent;
    height: 5px;
}

.child-list-triangle {
    background-color: transparent;
    width: 40px;
    height: 40px;
    color: #A2CAAC;
    font-size: 4rem;
    border: none;
}

.text-center{
    text-align: center;
    margin-top: 60px;
} 

.approve {
    border: none;
    background-color: #a2caac;
    color: black;
    font-size: 1.5rem;
}

버튼 설정 
.btn-border{
    display: flex;
    justify-content: center;
    align-items: center;
}

.btn-div{
    display: flex;
    justify-content: space-evenly;
    align-items: center;
    margin-top: 20px;
    vertical-align: middle;
}

.btn {
    width: 160px;
    height: 40px;
    cursor: pointer;
}

.btn-disable {
    display: none;
    width: 160px;
    height: 40px;
    cursor: default;
    pointer-events: none;  클릭 이벤트 막기 
}*/
</style>