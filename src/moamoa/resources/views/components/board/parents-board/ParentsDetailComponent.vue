<template>
<div class="main-container">
    <div class="board-container">
        <div class="content-list">
            <div class="content" v-if="missionDetail">
                <p class="title">제목</p>
                <p class="ms-title">{{ missionDetail.title }}</p>
                <div class="date deco">
                    <span class="ms-date">{{ missionDetail.start_at }}</span>
                    <span>⁓</span>
                    <span class="ms-date">{{ missionDetail.end_at }}</span>
                </div>
            </div>
            <div class="content">
                <p class="title">종류</p>
                <div class="category-btn"  v-for="item in categories" :key="item" :class="{'categorybtn-green' : item.index === Number(category) }">
                    <img class="ms-category-img" :src="item.img" >
                    <p class="category-name">{{ item.name }}</p>
                </div>
            </div>
            <div class="content">
                <p class="title">내용</p>
                <div class="ms-content">{{ missionDetail.content}}</div>
            </div>
            <div class="content">
                <p class="title">금액</p>
                <p class="ms-amount">{{ Number(missionDetail.amount).toLocaleString()}}원</p>
            </div>
            <div class="create-btn">
                <div class="bottom-btn left">
                    <button @click="goBack(missionDetail.child_id)" class="ms-cancel">뒤로가기</button>
                    <!-- <div class="btn-flex"> -->
                </div>
                <div class="bottom-btn right">
                    <button @click="delOpenModal" class="ms-del">미션 삭제</button>
                    <button @click="goUpdate(missionDetail.mission_id)" class="ms-up">미션 수정</button>
                    <button v-if="missionDetail.status === '0'" @click="approvalMission" class="ms-comfirm">미션 승인</button>
                </div>
                <!-- </div> -->
            </div>
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
                <p class="modal-name">{{ missionDetail.name }}</p>
                <p class="modal-ms-title">미션 : {{missionDetail.title}}</p>
                <div class="del-guide">해당 미션이 삭제됩니다.</div>
            </div>
            <div class="del-btn">
                <button @click="delCloseModal" class="modal-cancel">취소</button>
                <button @click="deleteMission(missionDetail.mission_id)" class="modal-del">삭제</button>
            </div>
        </div>
    </div>

    
</template>

<script setup>
import { computed, ref, onMounted, reactive  } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

// *****미션 상세 정보******
const store = useStore();
const router = useRouter();

const missionDetail = computed(() => store.state.mission.missionDetail);
onMounted(() => {
    store.dispatch('mission/showMissionDetail', store.state.mission.missionId);
});


//****미션 카테고리 정보 출력****
const category = computed(() => store.state.mission.missionDetail.category);


const categories = reactive([
    {name: '학습' , img:'/img/icon-pencil.png', index : 0}
    ,{name: '취미' , img:'/img/icon-bicycle.png', index : 1}
    ,{name: '집안일' , img:'/img/icon-cleaner.png', index : 2}
    ,{name: '생활습관' , img:'/img/icon-clock.png', index : 3}
    ,{name: '기타' , img:'/img/icon-checklist7.png', index : 4}
]);


// *****삭제 모달창********** 
const delModal = ref(false);

const delOpenModal = () => { //모달창 열기
    delModal.value = true;
}

const delCloseModal = () => { //모달창 닫기
    delModal.value = false;
}

// 뒤로가기
const goBack = (child_id) => {
    store.dispatch('mission/missionList', child_id);
    router.push('/parent/mission/list/' + child_id);
}
// *****미션 삭제*******
const deleteMission = (mission_id) => {
    console.log('삭제 요청 mission_id : ', mission_id)
    store.dispatch('mission/deleteMission', mission_id);
}

// *****미션 수정*******
const goUpdate = (mission_id) => {
    console.log('수정 요청 mission_id : ', mission_id);
    store.dispatch('mission/goUpdateMission', mission_id);
    router.push('/parent/mission/update/'+ mission_id);
}

// 승인
const approvalMission = () => {
    const missionItem = ref([missionDetail.value.mission_id]);
    store.dispatch('mission/approvalMission', missionItem.value);
    alert('해당 미션의 승인이 완료되었습니다.');
    goBack(missionDetail.value.child_id);
}

</script>

<style scoped>
@import url('../../../../css/boardCommon.css');
@import url('../../../../css/category.css');

/* 미션 제목 입력란 */
.ms-title {
    display: flex;
    align-items: center;
}

/* 날짜 입력란 배경색 */
.deco {
    background-color: #A2CAAC;
}

/* 미션 금액 입력란 */
.ms-amount {
    display: flex;
    align-items: center;
}

/* 미션(지출) 카테고리 */
.category-btn {
    width: 80px;
    height: 80px;
}

/* db에 저장된 카테고리 표시 */
.categorybtn-green {
    background-color: #A2CAAC;
}

/* 취소버튼 */
.ms-cancel {
    color: #ACACAC;
    background-color: #FFFFFF;
    font-size: 1.3rem;
    border: 1px solid #ACACAC;
    padding: 5px;
    width: 120px;
    height: 50px;
    border-radius: 0px;
    cursor: pointer;
    /* margin-left: 130px;
    margin-right: 350px; */
}

/* 삭제버튼 */
.ms-del {
    /* margin-left: 250px; */
    color: #A2CAAC;
    background-color: #FFFF;
    font-size: 1.3rem;
    border: 1px solid #A2CAAC;
    padding: 5px;
    width: 120px;
    border-radius: 0px;
    cursor: pointer;
}

.bottom-btn{
    /* width: 60px; */
    gap: 30px;
    margin-top: 10px;
    display: flex;

}

/* .right {
    margin-right: 350px;
}

.left {
    margin-left: 200px;
} */

/* 수정버튼 */
.ms-up {
    color: #FFFF;
    background-color: #A2CAAC;
    font-size: 1.3rem;
    border: 1px solid #A2CAAC;
    padding: 5px;
    width: 120px;
    border-radius: 0px;
    cursor: pointer;
}

/* 승인버튼 */
.ms-comfirm {
    color: #FFFF;
    background-color: #A2CAAC;
    font-size: 1.2rem;
    border: 1px solid #A2CAAC;
    padding: 5px;
    width: 120px;
    border-radius: 0px;
    cursor: pointer;
}

.btn-right {
    display: flex;
}

.create-btn {
    display: flex;
    margin-top: 10px;
    justify-content: space-between;
    width: 1200px;
}



/* .btn-flex {
    display: flex;
} */

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
    /* background-color: #FFFFFF; */
    /* border: 3px solid #5589e996; */
    border-radius: 50px;
    padding: 3px;
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