<template>
    <div class="container">
        <div class="list-container">
            <div class="for-buttons">
                <button class="btn-top mission-delete">삭제</button>
                <button class="btn-top mission-confirm">승인</button>
            </div>
            <div class="mission-title-bar">
                <div class="chk-div">
                    <input type="checkbox" class="checkbox" name="checkAll" @change="checkAll" :checked="isAllChecked">
                </div>
                <span class="kids-name">자녀이름</span>
                <span class="status">상태</span>
                <p class="mission-type">종류</p>
                
                <p class="mission-name">미션이름</p>
                <p class="charge">금액</p>
                <p class="due-date">기한</p>
            </div>
            <div class="scroll">
                <div v-if="missionList && missionList.length" v-for="item in missionList" :key="item" class="mission-inserted-list">
                    <div class="mission-content">
                        <div class="chk-div">
                            <input v-model="checkboxItem" class="checkbox" type="checkbox" :value="item.mission_id" name="checkbox">
                            {{item.mission_id}}
                        </div>
                        <button @click="getMissionId(item.mission_id)"><span class="kids-name">{{ item.child.name }}</span></button>
                        <p :class="getStatusClass(item.status)">{{ getStatusText(item.status) }}</p>
                        <p class="mission-type-selected">{{ getCategoryText(item.category) }}</p>
                        <p class="mission-title">{{ item.title }}</p>
                        <p class="mission-amount">{{ item.amount.toLocaleString() }}원</p>
                        <p class="mission-due-date">{{ item.start_at }} ~ {{ item.end_at }}</p>
                        
                    </div>
                </div>
            </div>
            <div class="for-buttons margin-top">
                <router-link to="/parent/home"><button class="btn-bottom mission-goback">뒤로가기</button></router-link>
                <!-- <router-link to="/parent/mission/create"><button  class="btn-bottom mission-insert">+ 등록</button></router-link> -->
                <button @click="getChildId(childId)" class="btn-bottom mission-insert">+ 등록</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
// import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
const route = useRoute();
const store = useStore();
// const router = useRouter();
// const router = useRouter();

// 라우터에서 쿼리 파라미터 받기
// const childId = router.query.child_id;

// 자녀 id 파라미터 세팅
const childId = computed(() => store.state.mission.childId);

// ***체크박스 선택하기***
// 선택된 체크박스 데이터
const checkboxItem = ref([]); // 모두 선택되면 전체 체크박스에도 선택 표시하기 위해서

// 모든 체크박스가 선택되었는지 확인 (computed : 반응형 데이터로 다루기 위해)
const isAllChecked = computed(() => {
    return checkboxItem.value.length > 0 && missionList.value.every((item) => checkboxItem.value.includes(item.mission_id));
});


const checkAll = (e) => {
    if(e.target.checked) {
        checkboxItem.value = missionList.value.map(item => item.mission_id);
        console.log('체크박스 모두 선택');
        console.log('체크박스 선택된 데이터 : ', missionList.value.length);
    } else {
        checkboxItem.value = []; //전체 해제
        console.log('체크박스 모두 해제');
    }
}

// 체크박스 모두 선택
// const checkboxItem = ref(false);
// const checkAll = () => {
//     checkboxItem.value = !checkboxItem.value;
//     console.log('체크박스 선택');
// }

// 미션 리스트 가져오기
const missionList = computed(() => store.state.mission.missionList);
console.log('missionList : ', store.state.mission.missionList);

// 상태를 문자열로 변환하는 함수
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
        "0": '학습',
        "1": '취미',
        "2": '집안일',
        "3": '생활습관',
        "4": '기타',
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

// onMount
onMounted(() => {
    store.dispatch('mission/missionList', route.params.id);
});

// 미션아이디 확인해서 상세 페이지 이동하기 위해서
const getMissionId = (mission_id) => {
    console.log('미션 아이디 획득', mission_id);
    store.dispatch('mission/showMissionDetail', mission_id);
}

// 자녀 아이디 확인해서 작성 페이지로 이동하기 위해서
const getChildId = (child_id) => {
    console.log('자녀 아이디 확인', child_id)
    store.dispatch('mission/goCreateMission', child_id);
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
    /* justify-content: center; */
    align-items: center;
}

.mission-title-bar {
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

.checkbox {
    margin: 15px;
    width: 16px;
    height: 16px;
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
    height: 60px;
    display: grid;
}

.margin-top {
    margin-top: 20px;
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