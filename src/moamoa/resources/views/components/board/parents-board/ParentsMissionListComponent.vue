<template>
    <div class="container">
        <div class="list-container">
            <div class="for-buttons">
                
                <button @click="approvalMission" class="btn-top mission-confirm">미션 승인</button>
                <button @click="delOpenModal" class="btn-top mission-delete">미션 삭제</button>
                
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
                        </div>
                        <span class="kids-name">{{ item.child.name }}</span>
                        <p :class="getStatusClass(item.status)">{{ getStatusText(item.status) }}</p>
                        <p class="mission-type-selected">{{ getCategoryText(item.category) }}</p>
                        <p @click="getMissionId(item.mission_id)" class="mission-title">{{ item.title }}</p>
                        <p class="mission-amount">{{ item.amount.toLocaleString() }}원</p>
                        <p class="mission-due-date">{{ item.start_at }} ~ {{ item.end_at }}</p>
                        
                    </div>
                </div>
            </div>
            <div class="for-buttons margin-top">
                <router-link to="/parent/home"><button class="btn-bottom mission-goback">뒤로가기</button></router-link>
                <!-- <router-link to="/parent/mission/create"><button  class="btn-bottom mission-insert">+ 등록</button></router-link> -->
                <button @click="getChildId(childId)" class="btn-bottom mission-insert">+ 미션 등록</button>
            </div>
        </div>
    </div>

    <!-- ************************* -->
    <!-- ********삭제 모달********* -->
    <!-- ************************* -->
    <div class="del-modal-black" v-show="delModal">
        <div class="del-modal-white">
            <div class="modal-content">
                <img src="/img/icon-trash.png" class="modal-img" alt=".">
                <div class="del-guide">선택한 {{(checkboxItem).length}} 개의 미션이 삭제됩니다.</div>
            </div>
            <div class="del-btn">
                <button @click="delCloseModal" class="modal-cancel">취소</button>
                <button @click="deleteCheckedMission()" class="modal-del">삭제</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';
const route = useRoute();
const store = useStore();

// 라우터에서 쿼리 파라미터 받기
// const childId = router.query.child_id;

// 자녀 id 파라미터 세팅
const childId = computed(() => store.state.mission.childId);

// *******체크박스 선택하기**********
// 선택된 체크박스 데이터
const checkboxItem = ref([]); // 모두 선택되면 전체 체크박스에도 선택 표시하기 위해서
console.log('체크박스 선택된 데이터 : ', checkboxItem.value);

// 모든 체크박스가 선택되었는지 확인 (computed : 반응형 데이터로 다루기 위해)
const isAllChecked = computed(() => {
    return checkboxItem.value.length > 0 && missionList.value.every((item) => checkboxItem.value.includes(item.mission_id));
});

// 체크된 미션만 삭제 처리 하기
const deleteCheckedMission = () => {
    if(checkboxItem.value.length === 0) {
        alert("삭제할 미션을 선택하세요");
        console.log("삭제할 미션 ",checkboxItem.value);
        return;
    }
    store.dispatch('mission/deletcheckedMission', checkboxItem.value);
    delModal.value = false;
    
    store.dispatch('mission/missionList', route.params.id); //삭제후 미션 리스트 새로 불러오기
    checkboxItem.value = [];
}

// *****삭제 모달창********** 
const delModal = ref(false);

const delOpenModal = () => { //모달창 열기
    if(checkboxItem.value.length === 0) {
        alert("선택하신 미션이 없습니다.");
        return;
    }
    else {
        delModal.value = true;

    }
}

const delCloseModal = () => { //모달창 닫기
    delModal.value = false;
}


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

// 미션 승인
const approvalMission = () => {
    if(checkboxItem.value.length > 0) {
        store.dispatch('mission/approvalMission', checkboxItem.value);
        store.dispatch('mission/missionList', route.params.id);
        checkboxItem.value = [];
        alert('진행중인 미션의 승인이 완료되었습니다.');
    }else {
        alert('선택되어있는 미션이 없습니다.');
    }
};

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
    height: 720px;
    background-color: white;
    display: flex;
    flex-direction: column;
    gap: 20px;
    /* justify-content: center; */
    align-items: center;
}

.mission-title-bar {
    display: grid;
    grid-template-columns: 40px 150px 100px 100px 300px 90px 350px;
    height: 60px;
    gap: 40px;
    background-color: #F5F5F5;
    font-size: 2rem;
    margin: 10px;
    align-items: center;
    width: 1400px;
    text-align: center;
}

.mission-title {
    cursor: pointer;
}

.mission-title:hover {
    color: #A2CAAC;
}

.mission-content {
    display: grid;
    grid-template-columns: 40px 150px 90px 120px 300px 90px 320px;
    height: 40px;
    gap: 40px;
    /* background-color: #F5F5F5; */
    font-size: 1.3rem;
    /* margin: 10px; */
    align-items: center;
    width: 1400px;
    text-align: center;
    /* border-bottom: 2px solid black; */
}

.for-buttons{
    display: flex;
    justify-content: right;
    gap: 30px;
    margin-left: 1100px;
}

.btn-top {
    width: 120px;
    height: 50px;
    font-size: 1.5rem;
    border: none;
    background-color:#A2CAAC ;
    margin-top: 30px;
    cursor: pointer;
    color: #FFFFFF;
}

.btn-bottom {
    width: 120px;
    height: 50px;
    font-size: 1.3rem;
    border: none;
    background-color:#A2CAAC ;
    margin-bottom: 30px;
    cursor: pointer;
    color: #FFFFFF;
}

.mission-goback {
    color: #ACACAC;
    background-color: #FFFFFF;
    border: 1px solid #ACACAC;
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
    width: 1400px;
}

/* ********************* */
/* *******삭제 모달****** */
/* ********************* */

.del-modal-black {
    background-color: rgba(0, 0, 0, 0.5);
    position: fixed;
    /* top: 182px;
    left: 177px; */
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    /* margin-top: 500px; */
    justify-content: center;
}

.del-modal-white {
    width: 400px;
    height: 500px;
    background-color: #FFFFFF;
    border: 3px solid #A2CAAC;
    /* margin: 170px; */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

}

.modal-content {
    text-align: center;
    margin: 60px;
}

.modal-name {
    font-size: 1.3rem;
    padding: 10px;
}

.modal-ms-title {
    font-size: 1.3rem;
    padding: 10px;
}

.del-guide {
    font-size: 1.4rem;
    padding: 15px;
}


.modal-img{
    width: 100px;
    height: 100px;
    border-radius: 50px;
}

/* 삭제 모달 버튼 */
.modal-cancel {
    color: #ACACAC;
    background-color: #FFFFFF;
    font-size: 1.2rem;
    border: 1px solid #ACACAC;
    padding: 5px;
    width: 100px;
    cursor: pointer;
    margin: 10px;
}

.modal-del {
    color: #FFFF;
    background-color: #A2CAAC;
    font-size: 1.2rem;
    border: 1px solid #A2CAAC;
    padding: 5px;
    width: 100px;
    cursor: pointer;
    margin: 10px;
}
</style>