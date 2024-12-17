<template>
    <div class="d-flex">
        <div class="container">
            <div class="child-list-triangle">◀</div>   
            
            <div v-for="item in missionList" :key="item" class="child-box"> 
                <div class="blank">-</div>
                <img class="profile-img" :src="item.profile" :style="{ objectFit: 'contain' }">
                <div class="blank">-</div>
                <div class="child">
                    <h3 class="name">{{ item.name }}</h3>
                    <p class="nickname">{{ item.nick_name }}</p>
                    <div class="expense-box">
                        <router-link to="/parents/spend" style="text-decoration: none; color:black;">
                            <p class="recent-expenses">지출 내역 ></p>
                        </router-link>
                        <div v-for="transaction in item.transactions.slice().sort((a, b) => new Date(b.created_at) - new Date(a.created_at)).slice(0, 3)" :key="transaction">
                            <div v-if="transaction.amount > 0" class="amount">
                                {{ transaction.amount.toLocaleString() }}원
                            </div>
                            <div v-else>
                                <p>최근 지출한 금액이 없습니다.</p>
                            </div>
                        </div>
                    </div>
                    <div class="child-mission">
                        <router-link to="/parents/mission/list" style="text-decoration: none; color:black;">
                            <p class="mission">승인 대기 중인 미션 ></p>
                        </router-link>
                        <div v-if="item.missions.length > 0" class="chk-div">
                            <div v-for="mission in item.missions.slice().sort((a, b) => new Date(b.created_at) - new Date(a.created_at)).slice(0, 3)" :key="mission">
                                <div v-if="mission.status === '1'">
                                    <input type="checkbox" class="checkbox">
                                    <label>
                                        <div class="mission-title">{{ mission.title }}</div>
                                    </label>
                                </div>
                            </div>
                            <div v-if="!item.missions.some(m => m.status === '1')" class="margin-top">
                                <p>대기중인 미션이 없습니다.</p> <!-- 대기 중인 미션이 없을 때 출력 -->
                            </div>
                        </div>
                    </div>
                    <div class="btn-div">
                        <button class="btn approve" :class="{'btn-disable': !item.missions.some(m => m.status === 1)}">미션 승인</button>
                    </div>
                </div>
            </div>
            <div class="child-list-triangle">▶</div>   
        </div>
    </div>
</template>
<script setup>

import { computed, onMounted } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

// const item = ref({
//     missions: [] // 초기값 설정
// });

// 미션 리스트 가져오기
const missionList = computed(() => store.state.mission.missionList);

// onMount
onMounted(() => {
    store.dispatch('mission/missionListPagination');
    store.commit('mission/resetMissionList'); // 상태 초기화
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
    /* justify-content: center; */
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

/* .child-mission {
    display: flex;
    flex-direction: column;
    gap: 5px;
} */

.mission {
    width: 250px;
    border-bottom: 5px solid #A2CAAC;
    font-size: 1.7rem;
    margin-top: 20px;
    margin-left: 40px;
    text-align: center;
}

.recent-expenses {
    border-bottom: 5px solid #A2CAAC;
    width: 200px;
    font-size: 1.7rem;
    margin-top: 20px;
    margin-left: 60px;
    text-align: center;
}

.amount {
    font-size: 1.5rem;
    margin-top: 10px;
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
    justify-content: center;
    align-items: center;
    margin-top: 10px;
    font-size: 1.5rem;
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

</style>