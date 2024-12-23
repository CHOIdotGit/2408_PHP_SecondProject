<template>
    <div class="d-flex">
        <div class="container">
            <div class="child-list-triangle">◀</div>   
            <!-- TODO: 페이지네이션 또는 스와이프 적용 해야함 -->
            <div v-for="item in parentHome" :key="item" class="child-box"> 
                <div class="blank">-</div>
                <img class="profile-img" :src="item.profile" :style="{ objectFit: 'contain' }">
                <div class="blank">-</div>
                <div class="child">
                    <h3 class="name">{{ item.name }}</h3>
                    <p class="nickname">{{ item.nick_name }}</p>
                    <div class="expense-box">
                        <p class="recent-expenses" @click="goSpendList(item.child_id)">지출 내역 ></p>
                        <div>
                            <div v-if="item.transactions.length === 0">
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
                        <!-- <p class="mission" @click="goMissionList(item.child_id)">승인 대기 중인 미션 ></p> -->
                        <p class="mission" @click="goMissionList(item.child_id)">승인 대기 중인 미션 ></p>
                        <div class="chk-div">
                            <div v-if="item.missions.length === 0" class="margin-top">
                                <p class="no-mission">대기중인 미션이 없습니다.</p> <!-- 대기 중인 미션이 없을 때 출력 -->
                            </div>
                            <div v-else>
                                <div class="chk-div-box" v-for="mission in item.missions" :key="mission">
                                    <input type="checkbox" class="checkbox" :id="'checkbox-' + mission.mission_id">
                                    <label :for="'checkbox-' + mission.mission_id">
                                        <div class="mission-title">{{ getTruncatedTitle(mission.title) }}</div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn-div" :class="{ 'btn-disable': item.missions.length === 0 }">
                        <button class="btn approve">미션 승인</button>
                    </div>
                </div>
            </div>
            <div class="child-list-triangle">▶</div>
        </div>
    </div>
</template>
<script setup>

import { computed, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const router = useRouter();
const route = useRoute();
// const child_id = route.query.child_id;

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

// const goMissionList = (id) => {
//     router.push(`/parent/mission/list/${id}`)
//     .then(response => {
//         console.log('전체 응답:', response);  // 응답 구조를 확인
//         console.log('Mission List:', response.data.missionList);
//         context.commit('setMissionList', response.data.missionList);
//     })
//     .catch(error => {
//         console.error('Error fetching mission list:', error);
//         if (error.response) {
//             console.log('응답 오류:', error.response);  // 응답 오류의 상세 정보
//         } else if (error.request) {
//             console.log('요청 오류:', error.request);  // 요청이 보내졌지만 응답이 없을 때
//         } else {
//             console.log('네트워크 또는 설정 오류:', error.message);  // 다른 오류 메시지
//         }
    
//     });
// };

// 지출 리스트로 이동
const goSpendList = (child_id) => {
    // 거래 정보를 가져오는 액션 호출
    store.dispatch('transaction/transactionList', child_id);
};

// onMount
onMounted(() => {
    store.commit('mission/resetState'); // 상태 초기화
    store.dispatch('mission/parentHome');
    // console.log(item.value.missions);
});
</script>
<style scoped>
/* 메인 화면 */
.container {
    margin-top: 20px;
    width: 1500px;
    height: 740px;
    background-color: white;
    display: flex;
    gap: 40px;
    justify-content: center;
    align-items: center;   
}

/* 플랙스 적용 */
.d-flex {
    margin-left: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    /* padding-bottom: 40px; */
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
    margin-left: 120px;
}

.mission-title{
    display: flex;
    align-items: center;
    margin-left: 1rem;
    height: 40px;
}

.child {
    background-color: white;
    border: solid #A2CAAC 5px;
    width: 360px;
    height: 550px;
    /* margin-top: 15px; */
}

/* 프로필 텍스트 */
.name {
    font-size: 2rem;
    margin-top: 5px;
    text-align: center;
    color: #000000;
}

.child-box {
    margin-bottom: 50px;
}
.nickname {
    font-size: 1.5rem;
    margin-top: 5px;
    text-align: center;
    color: #000000;
}

.child-mission {
    height: 184px;
}

.mission {
    width: 250px;
    border-bottom: 5px solid #A2CAAC;
    font-size: 1.7rem;
    margin-top: 20px;
    margin-left: 40px;
    text-align: center;
    cursor: pointer;
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
    border-bottom: 5px solid #A2CAAC;
    width: 200px;
    font-size: 1.7rem;
    margin-top: 20px;
    margin-left: 60px;
    text-align: center;
    cursor: pointer;
}

.amount {
    font-size: 1.5rem;
    margin-top: 10px;
    text-align: center;
}

.no-amount {
    font-size: 1.5rem;
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
    /* justify-content: center; */
    /* align-items: center; */
    margin-top: 10px;
    font-size: 1.5rem;
}

.chk-div-box {
    margin-left: 2rem;
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
    height: 30px;
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