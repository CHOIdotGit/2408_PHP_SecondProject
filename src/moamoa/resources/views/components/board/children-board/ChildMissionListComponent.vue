<template>
    <div class="container">
        <div class="list-container">
            <div class="for-buttons">
                <button class="btn btn-top mission-delete">삭제</button>
            </div>
            <div class="mission-title-bar">
                <div class="chk-div">
                    <input type="checkbox" id="checkbox9">
                </div>
                <span class="mission-name">미션이름</span>
                <span class="status">상태</span>
                <p class="mission-type">종류</p>
                <p class="charge">금액</p>
                <p class="due-date">기한</p>
            </div>
            <div class="mission-inserted-list scroll">
                <div v-for="item in missionList" :key="item" class="mission-content">
                    <div class="chk-div">
                        <input type="checkbox" id="checkbox9">
                    </div>
                    <span @click="getMissionId(item.mission_id)" class="mission-name">{{ getTruncatedTitle(item.title) }}</span>
                    <p class="state" :class="getStatusClass(item.status)">{{ getStatusText(item.status) }}</p>
                    <p class="mission-type-selected">{{ getCategoryText(item.category) }}</p> 
                    <p class="charge">{{ item.amount.toLocaleString() }}원</p>
                    <p class="due-date">{{ item.start_at }} ~ {{ item.end_at }}</p>
                </div>
            </div>
            <div class="for-buttons">
                <button class="btn btn-bottom mission-go-back">뒤로가기</button>
                <button @click="getChildId" class="btn btn-bottom mission-insert">+ 등록</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';
// import router from '../../../../js/router';
const route = useRoute();
const store = useStore();

// onMount
// onMounted(() => {
//     store.dispatch('childMission/setChildMissionList', route.params.child_id);
// });

onMounted(() => {
    store.dispatch('childMission/setChildMissionList');
});

// 미션 리스트 가져오기
const missionList = computed(() => store.state.childMission.childMissionList);

// 12글자 이후 '...'으로 표기
const maxLength = 12;

const getStatusText = (status) => {
    const statusMapping = {
        0: '진행중',
        1: '대기중',
        2: '완료',
        3: '취소',
    };
    return statusMapping[status]; // 기본값 없이 반환
};

// 카테고리를 문자열로 변환하는 함수
const getCategoryText = (category) => {
    const categoryMapping = {
        0: '학습',
        1: '취미',
        2: '집안일',
        3: '생활습관',
        4: '기타',
    };
    return categoryMapping[category]; // 기본값 없이 반환
};

// status 값에 따라 class 변화
const getStatusClass = (status) => {
    switch (status) {
        case '0': // 진행중
            return 'state-in-progress';
        case '1': // 대기중
            return 'state-waiting';
        case '2': // 완료
            return 'state-complete';
        case '3': // 취소
            return 'state-cancel';
    }
};

const getTruncatedTitle =(title) => {
  return title.length > maxLength 
    ? title.substring(0, maxLength) + '...' 
    : title;
};



// 미션아이디 확인해서 상세 페이지 이동하기 위해서
const getMissionId = (mission_id) => {
    console.log('미션 아이디 획득', mission_id);
    store.dispatch('childMission/showMissionDetail', mission_id);
}

// 자녀 아이디 확인해서 작성 페이지로 이동하기 위해서
const getChildId = () => {
    store.dispatch('childMission/goCreateMission');
}

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
    height: 740px;
    background-color: white;
    display: flex;
    flex-direction: column;
    gap: 20px;
    justify-content: center;
    align-items: center;   
}

.mission-title-bar {
    display: grid;
    grid-template-columns: 70px 220px 90px 90px 90px 300px;
    height: 60px;
    gap: 100px;
    background-color: #F5F5F5;
    font-size: 2rem;
    margin: 10px;
    align-items: center;
    width: 1400px;
    text-align: center;
}

.mission-content {
    display: grid;
    grid-template-columns: 70px 220px 90px 90px 90px 300px;  
    height: 40px;
    gap: 100px;
    /* background-color: #F5F5F5; */
    font-size: 1.3rem;
    margin: 10px;
    align-items: center;
    width: 1400px;
    text-align: center;
    /* border-bottom: 2px solid black; */
}

.btn {
    cursor: pointer;
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
    background-color:#5589e996 ;
    margin-top: 30px;
}

.btn-bottom {
    width: 100px;
    height: 50px;
    font-size: 1.5rem;
    border: none;
    background-color:#5589e996 ;
    margin-bottom: 30px;
}

#checkbox9 {
    margin: 15px;
}

.state {
    width: 90px;
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

.scroll {
    display: flex;
    flex-direction: column;
    gap: 20px;
    height: 400px;
    overflow-y: scroll;
    overflow-x: hidden;
}
</style>