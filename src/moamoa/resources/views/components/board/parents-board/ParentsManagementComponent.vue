<template>
    <div class="d-flex">
        <div class="container">
            <div class="btn-border">
                <button class="btn-page">
                    <div class="child-list-triangle"></div>
                </button>
            </div>
    
            <div v-for="item in missionList" :key="item" class="child-box">
                <div class="d-flex">
                    <div class="profile-img">
                        <img src="/img/icon-boy-2.png">
                    </div>
                </div>
                <div class="child">
                    <h3 class="name">{{ item.child_id }}</h3>
                    <p class="nickname">별명</p>
                    <div>
                        <p class="recent-expenses">가장 최근 지출</p>
                        <p class="amount">3,800원</p>
                        <p class="amount">6,100원</p>
                        <p class="amount">5,400원</p>
                    </div>
                    <div class="child-mission">
                        <p class="mission">승인 대기 중인 미션</p>
                        <div class="chk-div">
                            <input type="checkbox" id="checkbox1" class="checkbox">
                            <label for="checkbox1">
                                <p v>대기중인 미션 2</p>
                            </label>
                        </div>
                        <div class="chk-div">
                            <input type="checkbox" id="checkbox2" class="checkbox">
                            <label for="checkbox2">
                                <p>대기중인 미션 2</p>
                            </label>
                        </div>
                        <div class="chk-div">
                            <input type="checkbox" id="checkbox3" class="checkbox">
                            <label for="checkbox3">
                                <p>대기중인 미션 3</p>
                            </label>
                        </div>
                    </div>
                    <div class="btn-div">
                        <button class="btn approve">미션 승인</button>
                    </div>
                </div>
            </div>
                       
            <div class="btn-border">
                <button class="btn-page child-list-btn-right">
                    <div class="child-list-triangle"></div>
                </button>
            </div>
        </div>
    </div>
</template>
<script setup>

import { computed, onMounted } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

// 미션 리스트
const missionList = computed(() => store.state.mission.missionList);

// onMount
onMounted(() => {
    store.dispatch('mission/missionListPagination');
});

</script>
<style scoped>
/* 메인 화면 */
.container {
    width: 1536px;
    height: 650px;
    background-color: white;
    margin: 50px 50px 10px 50px;
    display: flex;
    gap: 16px;
    justify-content: center;
    align-items: center;   
}

/* 플랙스 적용 */
.d-flex {
    display: flex;
    justify-content: center;
    align-items: center;
}

/* 자녀 프로필 관련 */
.child-box {
    margin: 25px 0;
}

.profile-img {
    display: flex;
    justify-content: center;
    width: 80px;
    height: 80px;
    border: #FFBDD0 solid 5px;
    border-radius: 50%;
}

.profile-img > img {
    width: 60px;
    height: 60px;
}

.child {
    background-color: white;
    border: solid #A4D8E1 5px;
    width: 360px;
    height: 500px;
    margin-top: 15px;
}

/* 프로필 텍스트 */
.name {
    font-size: 30px;
    margin-top: 5px;
    text-align: center;
    color: #A4D8E1;
}

.nickname {
    font-size: 20px;
    margin-top: 5px;
    text-align: center;
    color: #A4D8E1;
}

/* .child-mission {
    display: flex;
    flex-direction: column;
    gap: 5px;
} */

.mission {
    font-size: 25px;
    margin: 20px 0;
    text-align: center;
}

.pre-mission {
    font-size: 15px;
    margin-top: 10px;
    text-align: center;
}

.recent-expenses {
    font-size: 25px;
    margin-top: 10px;
    text-align: center;
}

.amount {
    font-size: 20px;
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

.approve {
    border: #A4D8E1 solid 5px;
    background-color: #FFFFFF;
    color: #A4D8E1;
    font-size: 20px;
}

.chk-div {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 10px;
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
    border: 2px solid #A4D8E1; /* 테두리 색상 */
    border-radius: 4px; /* 모서리 둥글게 */
    background-color: #FFFFFF; /* 배경색 */
}

/* 체크박스가 체크되었을 때 스타일 */
.chk-div input[type="checkbox"]:checked + label::before {
    background-color: #A4D8E1; /* 체크된 배경색 */
    border-color: #A4D8E1; /* 체크된 테두리 색상 */
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






/* 페이지네이션 버튼 */
.btn-page {
    background: #A4D8E1;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    width: 60px;
    height: 60px;
    margin: 10px;
    padding: 5px;
    cursor: pointer;
}

.child-list-triangle {
    border-top: 25px solid transparent; /* 위 경계 투명 */
    border-bottom: 25px solid transparent; /* 아래 경계 투명 */
    border-right: 40px solid #ffffff; /* 오른쪽 경계 색상 */
}

.child-list-btn-right {
    transform: rotate(180deg);
}


</style>