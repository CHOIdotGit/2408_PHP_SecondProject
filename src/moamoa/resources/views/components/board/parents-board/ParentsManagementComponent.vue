<template>
    <div class="d-flex">
        <div class="div-container">
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
                            <p class="recent-expenses" @click="goSpendList(item.child_id)">지출 내역</p>
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
                            <p class="mission" @click="goMissionList(item.child_id)">승인 대기 중인 미션</p>
                            <div class="chk-div">
                                <div v-if="item.missions && item.missions.length === 0" class="margin-top">
                                <p class="no-mission">승인 대기 중인 미션이 없습니다.</p>
                                </div>
                                <div v-else>
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
            <div v-else>
                <p class="no-child">등록된 자녀가 없습니다.</p>
            </div>
        </div>
    </div>
</template>
<script setup>

import { computed, ref, onMounted, watch, nextTick } from 'vue';
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

// ------------------------------------------------------
const store = useStore();

// 미션 리스트 가져오기
const parentHome = computed(() => store.state.mission.parentHome);

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
onMounted(() => {
    store.dispatch('mission/parentHome');
});
</script>
<style scoped>

/* 메인 화면 */
.d-flex {
    width: 1670px;
    height: 800px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.div-container {
    width: 1150px;
    height: 720px;
}

.div-swiper{
    width: 100%;
    height: 700px;
    background-color: white;
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
    border-radius: 50%;
    padding: 3px;
    margin-left: 90px;
}

.mission-title{
    
}
.child-div {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.child {
    background-color: white;
    border: solid #A2CAAC 5px;
    width: 300px;
    height: 500px;
    border-radius: 25px;
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

.child-mission {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.mission {
    background-color: #A2CAAC;
    color: #FFFFFF;
    border-radius: 10px;
    width: 250px;
    height: 40px;
    font-size: 1.7rem;
    margin-top: 15px;
    text-align: center;
    cursor: pointer;
    padding-top: 5px;
}

.mission-box {
    height: 45px;
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
    width: 140px;
    height: 40px;
    font-size: 1.7rem;
    margin-top: 15px;
    text-align: center;
    cursor: pointer;
    padding-top: 5px;
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

/* 버튼 설정 */
.btn-border{
    display: flex;
    justify-content: center;
    align-items: center;
}

.btn-div{
    display: flex;
    justify-content: space-evenly;
    align-items: center;
    /* margin-top: 20px; */
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
    pointer-events: none; /* 클릭 이벤트 막기 */
}

.margin-top {
    margin-top: 4rem;
}

.approve {
    border: none;
    background-color: #a2caac;
    color: black;
    font-size: 1.5rem;
}

.chk-div {
    display: flex;
    flex-direction: column;
    margin-top: 10px;
    font-size: 1.3rem;
}

.chk-div-box {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 10px;
}

/* 기본 체크박스 숨기기 */
.chk-div input[type="checkbox"] {
    display: none; /* 기본 체크박스 숨김 */
}

/* 사용자 정의 체크박스 스타일 */
.chk-div label {
    position: relative;
    padding-left: 30px; /* 체크박스 공간 확보 */
    cursor: pointer;
    display: flex;
    justify-content: start;
}

/* 체크박스의 사용자 정의 스타일 */
.chk-div label::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 20px; /* 체크박스 너비 */
    height: 20px; /* 체크박스 높이 */
    border: 2px solid #a2caac; /* 테두리 색상 */
    border-radius: 4px; /* 모서리 둥글게 */
    background-color: #FFFFFF; /* 배경색 */
}

/* 체크박스가 체크되었을 때 스타일 */
.chk-div input[type="checkbox"]:checked + label::before {
    background-color: #A2CAAC; /* 체크된 배경색 */
    border-color: #a2caac; /* 체크된 테두리 색상 */
}

/* 체크 표시 */
.chk-div input[type="checkbox"]:checked + label::after {
    content: '✔'; /* 체크 표시 */
    position: absolute;
    left: 5px; /* 체크 표시 위치 조정 */
    top: 50%;
    transform: translateY(-50%);
    color: #FFFFFF; /* 체크 표시 색상 */
    font-size: 16px; /* 체크 표시 크기 */
}

.blank {
    color: transparent;
    height: 5px;
}

.child-list-triangle {
    background-color: transparent;
    /* width: 40px;
    height: 40px; */
    color: #A2CAAC;
    font-size: 4rem;
    border: none;
}
.text-center{
    text-align: center;
    margin-top: 60px;
}

.no-mission {
    text-align: center;

}
</style>