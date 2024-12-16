<template>
    <div class="container">
        <div class="list-container">
            <div class="for-buttons">
                <button class="btn-top mission-delete">삭제</button>
                <button class="btn-top mission-confirm">승인</button>
            </div>
            <div class="mission-title">
                <div class="chk-div">
                    <input type="checkbox" id="checkbox9">
                </div>
                <span class="kids-name">자녀이름</span>
                <span class="status">상태</span>
                <p class="mission-type">종류</p>
                <p class="mission-name">미션이름</p>
                <p class="charge">금액</p>
                <p class="due-date">기한</p>
            </div>
            <div class="mission-inserted-list" v-for="item in missionList" :key="item.id">
                <div class="mission-content">
                    <div class="chk-div">
                        <input type="checkbox" id="checkbox9">
                    </div>
                    <span class="kids-name">{{ item.name }}</span>
                    <div v-for="mission in item.missions.slice().sort((a, b) => new Date(b.end_at) - new Date(a.end_at)).slice(0, 1)" :key="mission">
                        <p class="state-in-progress">{{ getStatusLabel(mission.status) }}</p>
                        <p class="mission-type-selected">{{ getCategoryLabel(mission.category) }}</p>
                        <p class="mission-name-">{{ mission.title }}</p>
                    </div>
                    <div v-for="transaction in item.transactions" :key="transaction">
                        <p class="charge">{{ transaction.amount.toLocaleString() }}</p>
                    </div>
                    <div v-for="mission in item.missions" :key="mission">
                        <p class="due-date">{{ mission.start_at }} ~ {{ mission.end_at }}</p>
                    </div>
                </div>
            </div>
            <div class="for-buttons">
                <button class="btn-bottom mission-goback">뒤로가기</button>
                <button class="btn-bottom mission-insert">+ 등록</button>
            </div>
        </div>
    </div>
</template>

<script setup>

import { computed, onMounted } from 'vue';
import { useStore } from 'vuex';
const store = useStore();

// 미션 리스트 가져오기
const missionList = computed(() => store.state.child.childInfo);

// category 변환 함수
const getCategoryLabel = (category) => {
    switch (category) {
        case 0:
            return '학습';
        case 1:
            return '취미';
        case 2:
            return '집안일';
        case 3:
            return '생활습관';
        case 4:
            return '기타';
    }
    return '';
};

// status 변환 함수
const getStatusLabel = (status) => {
    switch (status) {
        case 0:
            return '교통비';
        case 1:
            return '취미';
        case 2:
            return '쇼핑';
        case 3:
            return '기타';
    }
    return '';
};

// child_id가 1인 자녀의 이름을 가져오는 computed property
const filteredChildName = computed(() => {
    const child = missionList.value.find(item => item.child_id === 1);
    return child ? child.name : ''; // child가 존재하면 이름을 반환, 아니면 빈 문자열
});


// onMount
onMounted(() => {
    store.dispatch('child/childInfo');
});

</script>

<style scoped>
.container {
    margin-left: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    /* padding-bottom: 40px; */
}

.list-container {
    margin-top: 20px;
    width: 1500px;
    height: 765px;
    background-color: white;
    display: flex;
    flex-direction: column;
    gap: 20px;
    justify-content: center;
    align-items: center;   
}

.mission-title {
    display: grid;
    grid-template-columns: 40px 190px 90px 90px 320px 90px 300px;
    height: 60px;
    gap: 50px;
    background-color: #F5F5F5;
    font-size: 2rem;
    margin: 10px;
    align-items: center;
    width: 1400px;
    text-align: center;
}

.mission-content {
    display: grid;
    grid-template-columns: 40px 190px 90px 90px 320px 90px 300px;
    height: 40px;
    gap: 50px;
    /* background-color: #F5F5F5; */
    font-size: 1.3rem;
    margin: 10px;
    align-items: center;
    width: 1400px;
    text-align: center;
    /* border-bottom: 2px solid black; */
}

.for-buttons{
    display: flex;
    justify-content: right;
    gap: 30px;
    margin-left: 1170px;
}

.btn-top {
    width: 100px;
    height: 50px;
    font-size: 1.5rem;
    border: none;
    background-color:#A2CAAC ;
    margin-top: 30px;
}

.btn-bottom {
    width: 100px;
    height: 50px;
    font-size: 1.5rem;
    border: none;
    background-color:#A2CAAC ;
    margin-bottom: 30px;
}

#checkbox9 {
    margin: 15px;
}

.state-in-progress {
    background-color: rgba(138, 160, 246, 0.5);
    color: #0010BE;
    height: 40px;
    line-height: 40px;
}

.state-waiting {
    background-color: rgba(22, 200, 150, 0.5);
    color: #165442;
    height: 40px;
    line-height: 40px;
}

.state-complete {
    background-color: rgba(255, 222, 92, 0.5);
    color: #2F8A1D;
    height: 40px;
    line-height: 40px;
}

.state-cancel {
    background-color: rgba(254, 135, 105, 0.5);
    color: #FF3300;
    height: 40px;
    line-height: 40px;
}

.mission-inserted-list {
    height: 800px;
    display: grid;
}
</style>