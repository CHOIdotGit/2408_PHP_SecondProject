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
            </div>
            <img src="/img/m-search.png" alt="" class="m-search-button">
        </div>
        <div class="m-expense-list">
            <div class="m-expense-route">
                <p> 지출  >   상세 </p>
            </div>
            <div class="m-expense-title">
                <p>{{ transactionDetail.title }}</p>
            </div>
            <div class="m-expense-detail-date">
                <p>{{ transactionDetail.transaction_date }}</p>
            </div>
            <div class="m-content-cate">
                <div class="m-category-btn"  v-for="item in categories" :key="item" :class="{'m-checked-category-btn' : item.index === Number(category) }">
                    <img class="m-ms-category-img" :src="item.img" >
                    <p class="m-category-name">{{ item.name }}</p>
                </div>
            </div>
            <div class="m-expense-content">
                <p> {{ transactionDetail.memo}} </p>
            </div>
            <div class="m-expense-amount">
                <p>{{ Number(transactionDetail.amount).toLocaleString() }} 원</p>
            </div>
        </div>
        <div class="m-detail-btn">
            <button class="m-back-to-list"> < 목록으로</button>
            
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

    <div class="main-container" v-else>
        <div v-if="transactionDetail"  class="board-container">
            <div class="route"> 홈   > 지출  >   상세 </div>
            <div class="content-list">
                <div class="content" >
                    <p class="title">제목</p>
                    <p class="ms-title h-60">{{ transactionDetail.title }}</p>
                </div>
                <div class="content">
                    <p class="title">날짜</p>
                    <div class="date-detail"></div>
                    <div class="date deco">
                        <span class="ms-date h-60" span-div>{{ transactionDetail.transaction_date }}</span>   
                    </div>
                </div>
                <div class="content-cate">
                    <p class="title">종류</p>
                    <div class="category-btn" v-for="item in categories" :key="item" :class="{'checked-category-btn' : item.index === Number(category) }">
                        <img class="ms-category-img" :src="item.img">
                        <p class="category-name">{{ item.name }}</p>
                    </div>
                </div>
                <div class="content">
                    <p class="title">사용 내역</p>
                    <div class="ms-content">{{ transactionDetail.memo}}</div>
                </div>
                <div class="content">
                    <p class="title">금액</p>
                    <p class="ms-amount">{{ Number(transactionDetail.amount).toLocaleString() }}원</p>
                </div>
                <div class="create-btn">
                    
                    <button @click="goBack(transactionDetail.child_id)" class="ms-cancel">목록으로</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref, onMounted, reactive  } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';

// *****지출 상세 정보******
const store = useStore();
const router = useRouter();
const route = useRoute();
const transactionDetail = computed(() => store.state.transaction.transactionDetail);
onMounted(() => {
    store.dispatch('transaction/showTransactionDetail', store.state.transaction.transactionId);
});

// +==========================+
// +    모바일 화면 전환       +
// +==========================+
// v-if="ismobile"적으면 모바일 화면으로 이동
const isMobile = store.state.mobile.isMobile;


// 뒤로가기
const goBack = (child_id) => {
    store.dispatch('transaction/transactionList', {child_id: route.params.id, page: 1});
    router.push('/parent/spend/list/' + child_id);
}

// 지출 카테고리 정보 가져오기
const category = computed(() => store.state.transaction.transactionDetail.category);

const categories = reactive([
    {name: '교통비' , img:'/img/icon-bus.png', index : 0}
    ,{name: '식비' , img:'/img/icon-fastfood.png', index : 1}
    ,{name: '쇼핑' , img:'/img/icon-shoppingbag.png', index : 2}
    ,{name: '기타' , img:'/img/icon-checklist7.png', index : 3}
]);
</script>

<style scoped>
@import url('../../../../css/boardCommon.css');
@import url('../../../../css/category.css');


/* 미션(지출) 제목 입력란 */
.ms-title {
    display: flex;
    align-items: center;
}

/* 미션 금액 */
.ms-amount {
    display: flex;
    align-items: center;
}

.create-btn {
    width: 1000px;
    margin-top: 20px;
    margin-left: 170px;
}
/* 선택된 카테고리 색깔 */
.checked-category-btn {
    background-color: #A2CAAC;
}


/* 취소 버튼 */
.ms-cancel {
    color: #ACACAC;
    background-color: #FFFFFF;
    font-size: 1.2rem;
    border: 1px solid #ACACAC;
    padding: 5px;
    width: 120px;
    height: 50px;
    border-radius: 0px;
    cursor: pointer;
    margin-left: 330px;
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
    background-color: #FFFFFF;
    border: 3px solid #5589e996;
    border-radius: 50px;
    padding: 3px;
}

.category-name {
    margin-top: 20px;
    font-size: 1.3rem;
}

/* 버튼 */
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
    margin-left: 100px;
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
    margin-left: 40px;
    display: flex;
    gap: 40px;
    min-height: 70px;
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
    max-width: 270px;
    white-space: normal;
    justify-items: center;
}

.m-del-guide {
    text-align: center;
    margin-top: 20px;
}

.m-checked-category-btn {
    background-color: #A2CAAC;
}
</style>