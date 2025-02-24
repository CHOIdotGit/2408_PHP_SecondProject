<template>
    <div  class="main-container" v-if="!isMobile">
        <div class="board-container">
            <div class="route"> 홈   > 미션  >  수정 </div>
            <div class="content-list">
                <div class="content" >
                    <p class="title">제목</p>
                    <input type="text" v-model="missionDetail.title" value="" class="ms-title deco" id="ms-title" maxlength="10" autofocus>
                </div>
                <div class="content">
                    <p class="title">날짜</p>        
                    <div class="date-detail">
                        <div class="start-date">
                            <p class="date-title">시작일</p>
                            <input type="date" v-model="missionDetail.start_at" class="ms-date" id="ms-date" min="2000-01-01" style="margin-left: 15px;">
                        </div>
                        <div class="end-date">
                            <p class="date-title">종료일</p>
                        <input type="date" v-model="missionDetail.end_at" class="ms-date" id="ms-date" min="2000-01-01" style="margin-left: 15px;">
                        </div>
                        <!-- value="today" -->
                    </div>
                </div>
                <div class="content-cate">
                    <p class="title">종류</p>
                    <div class="category-btn" v-for="item in categories" :key="item">
                        <input type="radio" name="category" :value="item.index" :id="'category-' + item.index" v-model="missionDetail.category"></input>
                        <label :for="'category-' + item.index" :class="[{'checked-category-btn': item.index === missionDetail.category}, 'ms-category-btn']">
                            <img class="ms-category-img" :src="item.img" >
                            <p class="category-name">{{ item.name }}</p>
                        </label>
                    </div>
                </div>
                <div class="content">
                    <p class="title">내용</p>
                    <textarea v-model="missionDetail.content" class="ms-content deco" id="ms-content"></textarea>
                    <!-- {{ missionDetail.content}} -->
                </div>
                <div class="content">
                    <p class="title">금액</p>
                    <input v-model="missionDetail.amount" type="number" class="ms-amount deco" id="ms-amount" required>
                    <!-- {{ missionDetail.amount}} -->
                </div>
                <div class="bottom-btn">
                    <button @click="$router.push(`/parent/mission/detail/${mission_id}`)" class="create-btn cancel">수정 취소</button>
                    <button @click="getUpdateMission" class="create-btn confirm">수정 완료</button>
                </div>
            </div>
        </div>
    </div>
    <div v-else>
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
            </div>
            <img src="/img/m-search.png" alt="" class="m-search-button">
        </div>
        <div class="m-expense-list">
            <div class="m-expense-route">
                <p> 미션  >   수정 </p>
            </div>
            <div class="m-expense-title">
                <input type="text" v-model="missionDetail.title" value="" class="m-title" id="" maxlength="10" autofocus>
            </div>
            <div class="m-expense-detail-date">
                <div class="m-date-detail">
                    <div class="m-sd">
                        <p>시작일</p>
                        <input type="date" v-model="missionDetail.start_at" class="m-ms-date" id="" min="2000-01-01">
                    </div>
                    <div class="m-ed">    
                        <p>종료일</p>
                        <input type="date" v-model="missionDetail.end_at" class="m-ms-date" id="" min="2000-01-01">
                    </div>
                </div>
            </div>
            <div class="m-content-cate">
                <div class="m-category-btn" v-for="item in categories" :key="item">
                    <input type="radio" name="category" :value="item.index" :id="'category-' + item.index" v-model="missionDetail.category"></input>
                    <label :for="'category-' + item.index" :class="[{'m-checked-category-btn': item.index === missionDetail.category}, 'm-ms-category-btn']">
                        <img class="m-ms-category-img" :src="item.img" >
                        <p class="m-category-name">{{ item.name }}</p>
                    </label>
                </div>
            </div>
            <div class="m-expense-content">
                <textarea v-model="missionDetail.content" class="m-ms-content m-deco" id=""></textarea>
            </div>
            <div class="m-expense-amount">
                <input v-model="missionDetail.amount" type="number" class="m-ms-amount m-deco" id="" required>
                <span class="span-div">원</span>
            </div>
        </div>
        <div class="m-detail-btn">
            <button class="m-back-to-list" @click="$router.push(`/parent/mission/detail/${mission_id}`)" > < 목록으로</button>
            <button class="m-back-to-list m-regist" @click="getUpdateMission"> 등록</button>
            
        </div>
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
    </div>
</template>

<script setup>
import { useStore } from 'vuex';
import { computed, onBeforeMount, reactive  } from 'vue';
import { useRoute } from 'vue-router';
// import mission from '../../../../js/store/modules/mission';

const route = useRoute();
const store = useStore();

onBeforeMount(() => {
    const mission_id = route.params.mission_id;
    console.log('수정할 mission id 획득 : ', mission_id);
    store.dispatch('mission/goUpdateMission', mission_id); //mission_id 불러오기
});


const isMobile = store.state.mobile.isMobile;


// *****미션 상세 정보******
const missionDetail = store.state.mission.missionDetail;


// 미션 내용 수정
// const upDateMission = reactive({
//     title: ''
//     ,content: ''
//     ,category: ''
//     ,amount: 0
//     ,start_at: ''
//     ,end_at: ''
//     ,mission_id: route.params.mission_id
// });

const categories = reactive([
    {name: '학습' , img:'/img/icon-pencil.png', index: "0"}
    ,{name: '취미' , img:'/img/icon-bicycle.png', index: "1"}
    ,{name: '집안일' , img:'/img/icon-cleaner.png', index: "2"}
    ,{name: '생활습관' , img:'/img/icon-clock.png', index: "3"}
    ,{name: '기타' , img:'/img/icon-checklist7.png', index: "4"}
]);

const getUpdateMission = () => {
    store.dispatch('mission/UpdateMission', missionDetail);
};




</script>

<style scoped>
@import url('../../../../css/boardCommon.css');
@import url('../../../../css/category.css');

/* 선택된 카테고리 색깔 */
.checked-category-btn {
    background-color: #A2CAAC;
}

/* 입력란 테두리 */
.deco {
    border: 3px solid #A2CAAC;
}

/* 하단 버튼 영역 */
.bottom-btn{
    display: flex;
    gap: 30px;
    margin-top: 20px;
    cursor: pointer;
    color: #FFFFFF;
}

.right{
    display: flex;
    margin-left: 70px;
}

.bottom-btn {
    margin-right: 500px;
}
/* 취소/미션등록 버튼 */
.create-btn {
    height: 50px;
    color: #ffffff;
    background-color: #A2CAAC;
    font-size: 1.3rem;
    border: 1px solid #A2CAAC;
    width: 120px;
    border-radius: 0px;
    cursor: pointer;
}

.category-name {
    margin-top: 10px;
    font-size: 1.3rem;
}

/* 취소버튼 */
.cancel {
    color: #ACACAC;
    background-color: #FFFFFF;
    border: 1px solid #ACACAC;
    margin-left: 500px;
}

.confirm {
    margin-left: 730px;
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
    min-height: 250px;
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
    display: flex;
    max-width: 330px;
    padding-top: 10px;
    font-size: 1.3rem;
    margin-left: 30px;
    border-top: 2px solid #636363;
 }

 .m-ms-date {
    height: 30px;
    font-size: 1rem;
 }


 .m-title {
    font-size: 1.1rem;
    width: 250px;
    height: 30px;
 }
.detail-update {
    margin-left: 100px;
}

.m-category-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: #c9cfca;
    border-radius: 50px;
    width: 25px;
    height: 25px;
}

.m-regist {
    margin-left: 220px;
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
    margin-top: 15px;
}

.m-content-cate {
    margin-left: 50px;
    display: flex;
    gap: 40px;
    min-height: 70px;
}

.m-category-btn > input {
    display: none 
}
.m-checked-category-btn {
    background-color: #A2CAAC;
}

.m-ms-content {
    font-size: 1.1rem;
    width: 330px;
    height: 200px;
}

.m-ms-category-btn {
    width: 50px;
    height: 50px;
    border-radius: 50px;
    background-color: #c9cfca;
    text-align: center;
    cursor: pointer;
}

.m-ms-amount {
    font-size: 1.1rem;
    width: 150px;
    height: 30px;
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

.m-date-detail {
    display: flex;
    gap: 30px;
    p {
        font-size: 1rem;
        margin-bottom: 10px;
    }
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
    max-width: 270px;
    white-space: normal;
    justify-items: center;
}

.m-del-guide {
    text-align: center;
    margin-top: 20px;
}

</style>