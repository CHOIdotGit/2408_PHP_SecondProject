<template>
<div v-if="isMobile">
    <div class="m-container">
        <div class="m-header">
            <img src="/img/icon-girl-4.png" alt="" class="m-user-image">
            <p class="m-user-profile">김주연</p>
            <p class="go-update"> > </p>
        </div>
        <div class="m-child-select-section">
            <div class="m-search-section">
                <!-- 자식 선택은 스와이퍼추가 / 자식 수가 늘어나면 옆으로 넘길수 있도록
                    선택한 자식은 클래스 추가로 백그라운드 컬러만 m-main-content 에 추가해주시면됩니다 -->
                <p class="m-child-select menu-sec-first"> 배현진 </p>
                <p class="m-child-select"> 김현석 </p>
                <p class="m-child-select"> 최상민 </p>
                <p class="m-child-select"> 최상민 </p>
                <p class="m-child-select"> 최상민 </p>
                <p class="m-child-select"> 최상민 </p>
            </div>
            <img src="/img/m-search.png" alt="" class="m-search-button">
        </div>
        <div class="m-expense-list">
            <div class="m-expense-route">
                <p> 미션 > 상세 </p>
            </div>
            <div class="m-expense-title">
                <p>{{missionDetail.title}}</p>
            </div>
            <div class="m-expense-detail-date">
                <p>{{ missionDetail.start_at }}</p>
                <p>~</p>
                <p>{{ missionDetail.end_at }}</p>
            </div>
            <div class="m-content-cate">
                <div class="m-category-btn"  v-for="item in categories" :key="item" :class="{'m-categorybtn-green' : item.index === Number(category) }">
                    <img class="m-ms-category-img" :src="item.img" >
                    <p class="m-category-name">{{ item.name }}</p>
                </div>
            </div>
            <div class="m-expense-content">
                <p> {{ missionDetail.content}}
                </p>
            </div>
            <div class="m-expense-amount">
                <p>{{ Number(missionDetail.amount).toLocaleString()}}원</p>
            </div>
        </div>
        <div class="m-detail-btn">
            <button @click="goBack(missionDetail.child_id)" class="m-back-to-list"> < 목록으로</button>
            <button @click="goUpdate(missionDetail.mission_id)" class="m-back-to-list detail-update" > 수정</button>
            <button @click="delOpenModal('mission', missionDetail.mission_id)" class="m-back-to-list"> 삭제</button>
        </div>
        <button v-if="missionDetail.status === '1'" @click="approvalMission" class="m-back-to-list m-regist"> 승인</button>
        <footer>
            <div class="m-footer-menu">
                <div class="m-menu">
                    <div class="m-menu-section">
                        <img src="/img/icon-home.png" alt="" class="m-home menu-sec-first">
                        <p class="m-menu-title   menu-sec-first"> 홈 </p>
                    </div>
                    <div class="m-menu-section">
                        <img src="/img/icon-piggy-bank.png" alt="" class="m-mission">
                        <p class="m-menu-title"> 미션 </p>
                    </div>
                    <div class="m-menu-section">
                        <img src="/img/icon-coin.png" alt="" class="m-expense">
                        <p class="m-menu-title"> 지출 </p>
                    </div>
                    <div class="m-menu-section">
                        <img src="/img/icon-calendar.png" alt="" class="m-calendar">
                        <p class="m-menu-title"> 달력 </p>
                    </div>
                    <div class="m-menu-section">
                        <img src="/img/icon-sack-dollar.png" alt="" class="m-bank">
                        <p class="m-menu-title"> 모아통장 </p>
                    </div>
                    <div class="m-menu-section">
                        <img src="/img/mobile-etc.png" alt="" class="m-etc">
                    </div>
                </div>
            </div>
        </footer>
    </div>


    <!-- 검색 모달 -->
    <div class="del-modal-back" v-show="delModal.isOpen">
            <div class="m-modal-list-container">
                <div class="m-delete-modal-sec">
                    <img src="/img/m-modal-delete.png" alt="">
                    <p class="m-modal-ms-title">미션 : {{missionDetail.title}}</p>
                    <!-- <p class="m-modal-ms-title">미션이름이좀많이길면 그때의 대비도 해야겠지</p> -->
                    <div class="m-del-guide">해당 미션이 삭제됩니다.</div>
                </div>
                <div class="m-modal-btn">
                    <button class="m-modal-delete"> 삭제</button>
                    <button @click="mDeleteCloseModal" class="m-modal-cancel">취소</button>
                </div>
            </div>
        </div>    
</div>



<!--  웹 -->
<div class="main-container" v-else>
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
                    <button @click="goBack(missionDetail.child_id)" class="ms-cancel">목록으로</button>
                </div>
                <div class="bottom-btn right">
                    <button v-if="missionDetail.status === '1'" @click="approvalMission" class="ms-confirm bottom-btn">미션 승인</button>
                    <button @click="delOpenModal('mission', missionDetail.mission_id)" class="ms-del bottom-btn">미션 삭제</button>
                    <button @click="goUpdate(missionDetail.mission_id)" class="ms-up bottom-btn">미션 수정</button>
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
            <button @click="delCloseModal" class="modal-close">취소</button>
            <button @click="deleteMission(missionDetail.mission_id)" class="modal-del">삭제</button>
        </div>
    </div>
</div>

<!-- ************************* -->
<!-- ********안내 모달********* -->
<!-- ************************* -->
<div class="base-modal-overlay" v-show="infoModal">
<div class="base-modal-box">
    <div class="base-modal-content">
    <div>미션이 승인되었습니다</div>
    </div>

    <div class="base-modal-btn">
    <button type="button" class="base-modal-submit" @click="delCloseModal">확인</button>
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

// +==========================+
// +    모바일 화면 전환       +
// +==========================+
// v-if="ismobile"적으면 모바일 화면으로 이동
const isMobile = store.state.mobile.isMobile;

// --------------- 모바일 모달 -----------------


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

// ****************************
// *******모달창 설정***********
// ****************************
// 안내 모달창
const infoModal = ref(false); 

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
    // alert('해당 미션의 승인이 완료되었습니다.');
    infoModal.value = true;
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
    position: absolute;
    color: #ffffff;
    background-color: #A2CAAC;
    font-size: 1.3rem;
    border: 1px solid #A2CAAC;
    width: 120px;
    bottom: 53px;
    left: 1120px;
    border-radius: 0px;
    cursor: pointer;
    margin-right: 20px;
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
    margin-left: 140px;
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
.modal-close {
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

/* ------------- 모바일 버전 css ------------ */

.m-container {
    width: 100vw;
    height: 100vh;
}

.m-header {
    display: flex;
    height: 70px;
    background-color: #A2CAAC;
    width: 100vw;
    align-items: center;
    gap: 10px;
}

.m-user-image {
    height: 50px;
    width: 50px;
    border-radius: 50px;
    border: 2px white solid;
    background-color: white;
    margin-left: 20px;
}

.m-user-profile {
    font-size: 1.7rem;
    width: 100px;
}

.go-update {
    font-size: 2rem;
    margin-left: 180px;
}


.m-menu {
    display: flex;
    gap: 15px;
    border-top: 2px solid black;
    img {
        filter: contrast(0.5);
        margin-top: 10px;
        width: 25px;
        height: 25px;
    }
}

.m-menu-section {
    display: flex;
    flex-direction: column;
    width: 60px;
    justify-content: center;
    align-items: center;
    gap: 3px;
}
.m-menu-title {
    font-size: 0.8rem;
}

.m-home {
    margin-left: 15px;
}

.m-child-select-section {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}
.m-search-button {
    width: 30px;
    height: 30px;
    margin-right: 10px;
}

.m-search-section {
    max-width: 360px;
    height: 50px;
    width: 100vw;
    display: flex;
    gap: 15px;
    align-items: center;
    scrollbar-width: 10px;
    overflow: scroll;
    white-space: nowrap;
}

.m-child-select {
    min-width: 70px;
    height: 30px;
    border-radius: 15px;
    border: 3px solid #A2CAAC;
    text-align: center;
    line-height: 25px;
}

.m-search-btn{
    background-color: transparent;
    border: none;

}

.m-search-opt {
    margin-left: 20px;
    font-size: 1.2rem;
    height: 35px;
}

.m-expense-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    overflow: scroll;
    white-space: nowrap;
    min-height: 580px;
}

.m-expense-route {
    margin-left: 20px;
}

.m-expense-title {
    font-size: 1.3rem;
    max-width: 330px;
    margin-left: 30px;
    white-space: normal;
}

.m-expense-content {
    margin-left: 30px;
    border-top: 2px solid #636363;
    padding-top: 20px;
    max-width: 330px;
    white-space: normal;
    line-height: 22px;
    min-height: 300px;
}

.m-expense-detail-date{
    max-width: 330px;
    margin-left: 30px;
    display: flex;
    gap: 10px;
    padding-bottom: 10px;
    border-bottom: 2px solid #636363;
}

.menu-sec-first {
    margin-left: 15px;
}

.m-detail-btn {
    display: flex;
    gap: 20px;
    min-height: 50px;
    border-top: 2px solid #000000;
}
.m-back-to-list {
    background-color: transparent;
    border: none;
    margin-left: 10px;
    font-size: 1.2rem;
    padding-top: 15px;
    display: flex;
    img{
        margin-left: 10px;
        width: 20px;
        height: 20px;
    }
}

.m-expense-category {
    display: flex;
    max-width: 350px;
    margin-left: 20px;
    height: 80px;
    gap: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #636363;
    img {
        border-radius: 40px;
        width: 50px;
        height: 50px;
    }
}

 .m-expense-amount{
    
    max-width: 350px;
    padding-top: 10px;
    font-size: 1.3rem;
    margin-left: 20px;
    border-top: 2px solid #636363;
 }

.detail-update {
    margin-left: 160px;
}

.m-category-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: #c9cfca;
    border-radius: 50px;
    width: 50px;
    height: 50px;
}

.m-categorybtn-green {
    background-color: #A2CAAC;
}

.m-ms-category-img {
    width: 35px;
    height: 35px;
    margin-top: 7px;
}

.m-category-name {
    margin-top: 20px;
}

.m-content-cate {
    margin-left: 30px;
    display: flex;
    gap: 20px;
    min-height: 70px;
}

.m-regist {
    position: absolute;
    right: 150px;
    bottom: 86px;
}
/* --- 모바일 모달 ---- */

.del-modal-back {
    background-color: rgba(0, 0, 0, 0.5);
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    flex-direction: column;
    justify-content: center;
    gap: 30px;
}

.m-delete-modal-sec {
    font-size: 1.5rem; 
    img {
        width: 100px;
        height: 100px;
        margin-left: 100px;
        margin-top: 70px;
    }
}

.spend-type {
    height: 30px;
}

.m-search-category {
    display: flex;
}

.m-search-date {
    display: flex;
}

.m-modal-list-container {
    background-color: white;
    width: 300px;
    height: 400px;
}

.m-modal-btn{
    display: flex;
    gap: 30px;
    justify-content: center;
    margin-top: 100px;
    button {
        width: 80px;
        height: 40px;
        font-size: 1.2rem;
        background-color: #A2CAAC;
        color: white;
        border: none;
    }
}

.m-modal-ms-title {
    text-align: center;
    white-space: normal;
}

.m-del-guide {
    text-align: center;
    margin-top: 20px;
}
</style>