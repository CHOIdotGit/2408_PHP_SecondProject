<template>
<div class="main-container">
    <div class="board-container">
        <div class="route"> 홈   > 미션  >   상세 </div>
        <div class="content-list">
            <div class="content" v-if="missionDetail">
                <p class="title">제목</p>
                <p class="ms-title">{{ missionDetail.title }}</p>
            </div>
            
            <div class="content">
                <p class="title">날짜</p>
                <div class="date-detail">
                    <div class="start-date">
                        <p class="date-title">시작일</p>
                        <span class="ms-date span-div">{{ missionDetail.start_at }}</span>
                    </div>
                    <div class="end-date">
                        <p class="date-title">종료일</p>
                        <span class="ms-date span-div">{{ missionDetail.end_at }}</span>
                    </div>
                </div>
            </div>
            <div class="content-cate">
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
            <div class="buttons">
                <div class="bottom-btn left">
                    <button @click="goBack(missionDetail.child_id)" class="ms-cancel">뒤로가기</button>
                </div>
                <div class="bottom-btn right">
                    <button @click="delOpenModal('mission', missionDetail.mission_id)" class="ms-del bottom-btn">미션 삭제</button>
                    <button @click="goUpdate(missionDetail.mission_id)" class="ms-up bottom-btn">미션 수정</button>
                    <button v-if="missionDetail.status === '0'" @click="approvalMission" class="ms-confirm bottom-btn">미션 승인</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ********삭제 모달********* -->
<!-- 삭제 모달 모듈 작업 중 -->
<!-- <div class="delModal" v-if="delModal">
    <ModalComponent :title="modalType" @click="delCloseModal" />
</div> -->
<!-- ************************* -->
<!-- ********삭제 모달********* -->
<!-- ************************* -->
<div class="del-modal-black" v-show="delModal">
    <div class="del-modal-white">
        <div class="modal-content">
            <img src="/img/icon-trash.png" class="modal-img" alt=".">
            <p class="modal-name"></p>
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
import { useRoute, useRouter } from 'vue-router';

// *****미션 상세 정보******
const store = useStore();
const router = useRouter();
const route = useRoute();

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
const modalType = ref(''); // 모달 타입 : transaction 인지 mission 인지
// 해당 게시물 모달창 열기
const delOpenModal = (mission_id) => {
    store.dispatch('mission/showMissionDetail', mission_id);
    // if(type === 'mission') {
    //     console.log("이걸 확인하냐?",type);
    //     store.dispatch('mission/showMissionDetail', mission_id);
    // }
    delModal.value = true;
}

const delCloseModal = () => { //모달창 닫기
    delModal.value = false;
}

// 뒤로가기
const goBack = (child_id) => {
    store.dispatch('mission/missionList', {child_id: route.params.id, page: 1});
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

/* 미션(지출) 카테고리 */
.category-btn {
    margin-left: 30px;
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
    margin-left: 500px;
    margin-right: 480px;
    /* margin-right: 350px; */
}

/* 삭제버튼 */
.ms-del {
    /* margin-left: 250px; */
    color: #ffffff;
    background-color: #A2CAAC;
    font-size: 1.3rem;
    border: 1px solid #A2CAAC;
    width: 120px;
    border-radius: 0px;
    margin-right: 20px;
    cursor: pointer;
}

.ms-confirm{
    color: #ffffff;
    background-color: #A2CAAC;
    font-size: 1.3rem;
    border: 1px solid #A2CAAC;
    width: 120px;
    border-radius: 0px;
    cursor: pointer;
}

.ms-up {
    color: #ffffff;
    background-color: #A2CAAC;
    font-size: 1.3rem;
    border: 1px solid #A2CAAC;
    padding: 5px;
    width: 120px;
    border-radius: 0px;
    
    margin-right: 20px;
    cursor: pointer;
}

.bottom-btn{
    height: 50px;
}

.ms-date{
    margin-left: 25px;
}

.right {
    display: flex;
}

.buttons {
    display: flex;
    width: 1000px;
    margin-top: 20px;
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
    /* background-color: #FFFFFF; */
    /* border: 3px solid #5589e996; */
    border-radius: 50px;
    padding: 3px;
}

.category-name {
    margin-top: 20px;
    font-size: 1.3rem;
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