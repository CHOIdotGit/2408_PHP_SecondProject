<template>
    <div class="d-flex">
        <div class="container">
            <div class="child-list-triangle">◀</div>   
    
            <div class="child-box"> 
                <div class="blank">-</div>
                <img class="profile-img" src="/img/icon-boy-2.png" width="120px" height="120px">
                <div class="blank">-</div>
                <div class="child">
                    <h3 class="name">{{ item.child_id }}</h3>
                    <p class="nickname">별명</p>
                    <div class="expense-box">
                        <p class="recent-expenses">가장 최근 지출</p>
                        <p class="amount">3,800원</p>
                        <p class="amount">6,100원</p>
                        <p class="amount">5,400원</p>
                    </div>
                    <div class="child-mission">
                        <p class="mission">승인 대기 중인 미션</p>
                        <div class="chk-div">
                            <input type="checkbox" id="checkbox7">
                            <label for="checkbox7">
                                <p>대기중인 미션 7</p>
                            </label>
                        </div>
                        <div class="chk-div">
                            <input type="checkbox" id="checkbox8">
                            <label for="checkbox8">
                                <p>대기중인 미션 8</p>
                            </label>
                        </div>
                        <div class="chk-div">
                            <input type="checkbox" id="checkbox9">
                            <label for="checkbox9">
                                <p>대기중인 미션 9</p>
                            </label>
                        </div>
                    </div>
                    <div class="btn-div">
                        <button class="btn approve">미션 승인</button>
                    </div>
                </div>
            </div>

            <div class="child-box"> 
                <div class="blank">-</div>
                <img class="profile-img" src="/img/icon-boy-2.png" width="120px" height="120px">
                <div class="blank">-</div>
                <div class="child">
                    <h3 class="name">이름</h3>
                    <p class="nickname">별명</p>
                    <div class="expense-box">
                        <p class="recent-expenses">가장 최근 지출</p>
                        <p class="amount">3,800원</p>
                        <p class="amount">6,100원</p>
                        <p class="amount">5,400원</p>
                    </div>
                    <div class="child-mission">
                        <p class="mission">승인 대기 중인 미션</p>
                        <div class="chk-div">
                            <input type="checkbox" id="checkbox7">
                            <label for="checkbox7">
                                <p>대기중인 미션 7</p>
                            </label>
                        </div>
                        <div class="chk-div">
                            <input type="checkbox" id="checkbox8">
                            <label for="checkbox8">
                                <p>대기중인 미션 8</p>
                            </label>
                        </div>
                        <div class="chk-div">
                            <input type="checkbox" id="checkbox9">
                            <label for="checkbox9">
                                <p>대기중인 미션 9</p>
                            </label>
                        </div>
                    </div>
                    <div class="btn-div">
                        <button class="btn approve">미션 승인</button>
                    </div>
                </div>
            </div>

            <div class="child-box"> 
                <div class="blank">-</div>
                <img class="profile-img" src="/img/icon-boy-2.png" width="120px" height="120px">
                <div class="blank">-</div>
                <div class="child">
                    <h3 class="name">이름</h3>
                    <p class="nickname">별명</p>
                    <div class="expense-box">
                        <p class="recent-expenses">가장 최근 지출</p>
                        <p class="amount">3,800원</p>
                        <p class="amount">6,100원</p>
                        <p class="amount">5,400원</p>
                    </div>
                    <div class="child-mission">
                        <p class="mission">승인 대기 중인 미션</p>
                        <div class="chk-div">
                            <input type="checkbox" id="checkbox7">
                            <label for="checkbox7">
                                <p>대기중인 미션 7</p>
                            </label>
                        </div>
                        <div class="chk-div">
                            <input type="checkbox" id="checkbox8">
                            <label for="checkbox8">
                                <p>대기중인 미션 8</p>
                            </label>
                        </div>
                        <div class="chk-div">
                            <input type="checkbox" id="checkbox9">
                            <label for="checkbox9">
                                <p>대기중인 미션 9</p>
                            </label>
                        </div>
                    </div>
                    <div class="btn-div">
                        <button class="btn approve">미션 승인</button>
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
    margin-top: 20px;
    width: 1500px;
    height: 765px;
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

.approve {
    border: none;
    background-color: #a2caac;
    color: black;
    font-size: 1.5rem;
}

.chk-div {
    display: flex;
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