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
            <img src="/img/m-search.png" alt="" class="m-search-button" @click="searchOpenModal">
        </div>
        <div class="m-expense-list">
            <!-- 한 게시물  -->
            <div class="m-explanation">
                <div class="m-kr-bank">
                    <div class="m-kr-bank-headline">이달의 한국은행 기준 금리</div>
                        <!-- ### 한국은행 기준금리 api ### -->
                        <p class="m-red" v-if="koreaBankInterest">{{ Number(koreaBankInterest.interest).toFixed(1) }} %</p>
                    </div>
                    <p class="m-p-explanation m-m-t">* 기준금리는 한국은행이 발표하는 정책금리로, 금융기관 간 거래의 기준이 되는 금리입니다.</p>
                    <p class="m-p-explanation">* 기준 금리는 30일 적금 상품에 적용되는 금리입니다.</p>
                    <p class="m-p-explanation">* 적금은 은행 예금 상품의 하나로, 정기적 또는 비정기적으로 돈을 불입*하여 계약 기간이 만료된 후 이자와 함께 돌려받는 것을 의미합니다.</p>
                    <p class="m-p-explanation">* 최대 3개의 적금 상품을 가입하실 수 있습니다.</p>
                </div>
                <div class="m-moa-each-box">
                    <div @click="goPointPage" class="m-moa-box go-point">
                    <p class="m-have-point">보유중인 모아 포인트</p>
                    <!-- 온클릭으로 링크는 부모 포인트 페이지로 설정하기 -->
                    <p class="m-have-moa">{{ Number(point).toLocaleString() }} moa</p>
                    <!-- totalPoint 가져오면 -->
                    <p class="m-subscribe">현재 가입한 적금 상품 : {{ productCount }} 개</p>
                    <!-- 가입한 적금 상품 개수 쿼리문으로 가져오기 -->
                </div>
            </div>
            <div class="m-moa-box" v-for="item in savingList" :key="item">
                <div @click="goSavingDetail(item.saving_sign_up_id)">
                    <p class="m-have-point">모아 적금통장</p>
                    <p class="m-have-moa" >⭐ {{ item.saving_product_name }} 적금 ⭐</p>
                    <div class="m-div-box-item">
                        <p >잔액</p>
                        <div>{{ item.total }}moa</div>
                    </div>
                    <div class="m-div-box-item">
                        <p>이자율 : </p>
                        <div>{{ item.saving_product_interest_rate }} %</div>
                    </div>
                </div>
            </div>
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

<!-- 검색 모달 -->
        <div class="del-modal-back" v-show="serachModal.isOpen">
            <div class="m-modal-list-container">
                <div class="m-search-filter">
                    <div class="m-search-category">
                        <p>지출 종류 </p> 
                        <select name="spend-type">
                            <option value="">전체</option>
                            <option value="0">교통비</option>
                            <option value="1">식비</option>
                            <option value="2" >쇼핑</option>
                            <option value="3">기타</option>
                        </select>
                    </div>
                    <div class="m-search-date">
                        <p>지출 일자</p> 
                        <input type="date" min="2000-01-01" >
                    </div>
                    <div class="m-modal-search">
                        <input type="search" placeholder="검색어를 입력해주세요">
                    </div>
                </div>
            </div>
            <div class="m-modal-btn">
                <button @click="searchCloseModal" class="modal-cancel">취소</button>
                <button> 검색</button>
            </div>
        </div>    
</div>

    <div v-else class="bank">
        <div class="explanation">
            <div class="kr-bank">
                <div class="kr-bank-headline">이달의 한국은행 기준 금리</div>
                <!-- ### 한국은행 기준금리 api ### -->
                <p class="red" v-if="koreaBankInterest">{{ Number(koreaBankInterest.interest).toFixed(1) }} %</p>
            </div>
            <p class="p-explanation m-t">* 기준금리는 한국은행이 발표하는 정책금리로, 금융기관 간 거래의 기준이 되는 금리입니다.</p>
            <p class="p-explanation">* 기준 금리는 30일 적금 상품에 적용되는 금리입니다.</p>
            <p class="p-explanation">* 적금은 은행 예금 상품의 하나로, 정기적 또는 비정기적으로 돈을 불입*하여 계약 기간이 만료된 후 이자와 함께 돌려받는 것을 의미합니다.</p>
            <p class="p-explanation">* 최대 3개의 적금 상품을 가입하실 수 있습니다.</p>
        </div>
        <div class="account">
            <!-- 가입 날짜로 정렬할 예정 -->
            <!-- <div @click="router.push('/parent/point/' + route.params.child_id)" class="div-box"> -->
            <div @click="goPointPage" class="div-box go-point">
                <p class="have-point">보유중인 모아 포인트</p>
                <!-- 온클릭으로 링크는 부모 포인트 페이지로 설정하기 -->
                <p class="have-moa">{{ Number(point).toLocaleString() }} moa</p>
                <!-- totalPoint 가져오면 -->
                <p class="subscribe">현재 가입한 적금 상품 : {{ productCount }} 개</p>
                <!-- 가입한 적금 상품 개수 쿼리문으로 가져오기 -->
            </div>
            <!-- v-if -->
            <div class="div-box" v-for="item in savingList" :key="item">
                <div @click="goSavingDetail(item.saving_sign_up_id)">
                    <p class="have-point">모아 적금통장</p>
                    <p class="have-moa" >⭐ {{ item.saving_product_name }} 적금 ⭐</p>
                    <div class="div-box-item">
                        <p >잔액</p>
                        <div>{{ item.total }}moa</div>
                    </div>
                    <div class="div-box-item">
                        <p>이자율 : </p>
                        <div>{{ item.saving_product_interest_rate }} %</div>
                    </div>
                </div>
            </div>
            <!-- v-else -->
            <div class="div-box" v-for="n in emptySlots" :key="'empty-' + n">
                <p class="have-point">모아 적금통장</p>
                <p class="non-product p-t">가입한 적금 상품이 없습니다.</p>
                <!-- <p class="non-product">새로운 적금 상품을 가입하시겠습니까?</p> -->
            </div>
        </div>
    </div>
</template>


<script setup>
import { computed, onBeforeMount, onMounted, ref , reactive} from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';
import router from '../../../../js/router';

const store = useStore();
const route = useRoute();

const point = computed(() => store.state.bank.point);
// const childId = computed(() => store.state.bank.childId);
const productCount = computed(() => store.state.bank.productCount);
// 한국은행 기준금리 api 가져오기
const koreaBankInterest = computed(()=> store.state.bank.bankInterest);
// 자녀 적금 상품 가져 오기
const savingList = computed(()=> store.state.saving.childSavingList);

// const emptySlots = computed(() => {
//     return Math.max(3 - productCount.value.length, 0);
// });

// 자녀 포인트 페이지로 이동
const goPointPage = () => {
    store.dispatch('point/printPointList', {child_id: route.params.child_id, page: 1});
    router.push('/parent/point/' + route.params.child_id);
}

// 자녀 통장 페이지로 이동
const goSavingDetail = (saving_sign_up_id) => {
    router.push('/parent/bankbook/' + saving_sign_up_id);
}

// 가입한 적금 상품 개수 
onBeforeMount(() => {
    store.dispatch('bank/koreaBank');
    // store.dispatch('bank/savingProductList');
    store.dispatch('bank/signProductCount', route.params.child_id);
    store.dispatch('saving/parentChildSaving', route.params.child_id);
});

// +==========================+
// +    모바일 화면 전환       +
// +==========================+
// v-if="ismobile"적으면 모바일 화면으로 이동
const isMobile = store.state.mobile.isMobile;

// --------------- 모바일 모달 -----------------

const serachModal = reactive({ isOpen: false });

const searchOpenModal = () => {
    serachModal.isOpen = true;
};

const searchCloseModal = () => {
    serachModal.isOpen = false;
};

</script>


<style scoped>
.bank {
    width: 1600px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* 설명 적는 div */
.explanation {
    width: 85%;
    height: 350px;
    padding: 50px;
    padding-left: 75px;
    background-color: #f8f8f8;
}

.kr-bank {
    display: flex;
    align-items: center;
    border-radius: 10px;
    width: 60%;
}

.kr-bank-headline {
    font-size: 2.3rem;
    padding: 5px;
    margin-right: 20px;
    font-weight: 700
}

.red {
    color: red;
    font-family: 'LAB디지털';
    font-size: 2rem;
    width: 150px;
    padding: 5px;
    text-align: center;
}

.p-explanation {
    margin-top: 20px;
    font-size: 1rem;
}

.m-t{
    margin-top: 30px;
}

.p-t {
    padding-top: 50px;
}

/* 통장 관리 부분 */
.account {
    width: 100%;
    margin: 20px 0 20px 0;
    padding: 50px;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    gap: 50px
}


.div-box {
    width: 500px;
    height: 250px;
    margin-right: 50px;
    text-align: center;
    background: #c9e6d7;
    border-radius: 30px;
    cursor: pointer;
}

.div-box:hover {
    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
}

.have-point {
    margin-top: 20px;
    font-size: 2rem;
}

.go-point {
    cursor: pointer;

}

.have-moa {
    margin-top: 60px;
    font-size: 1.5rem;
}

.subscribe {
    margin-top: 50px;
    font-size: 1.2rem;

}

.non-product {
    margin-top: 10px;
    font-size: 1.3rem;
    font-weight: 600;
}

.div-box-item {
    display: flex;
    justify-content: end;
    align-items: center;
    gap: 10px;
    padding-right: 50px;
    font-size: 1.3rem;
}

.interest-rate {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-top: 50px;
    padding-left: 230px;
    font-size: 1.2rem;
}

.more {
    cursor: pointer;
}

.more:hover {
    text-decoration: underline;
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

.m-child-profile {
    width: 100px;
    height: 100px;
    border-radius: 50px;
    border: 3px solid #A2CAAC;
    background-color: white;
}

.m-child {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 20px;
}   

.m-child-name {
    margin-top: 20px;
    font-size: 1.5rem;
    text-align: center;
    font-weight: 600;
}

.m-home-title-e{
    font-size: 1.3rem;
    margin-left: 70px;
    background-color: #A2CAAC;
    color: white;
    border-radius: 15px;
    font-weight: 600;
    width: 100px;
    text-align: center;
    margin-top: 20px;
    height: 50px;
    line-height: 50px;
}

.m-home-title-c{
    font-size: 1.3rem;
    margin-left: 35px;
    background-color: #A2CAAC;
    color: white;
    border-radius: 15px;
    font-weight: 600;
    width: 180px;
    text-align: center;
    margin-top: 20px;
    height: 50px;
    line-height: 50px;
}

.m-home-content {
    text-align: center;
    font-size: 1.2rem;
    height: 30px;
    line-height: 30px;
}

.m-first {
    margin-top: 20px;
}

.m-main-content {
    margin-top: 20px;
    width: 250px;
    border: 3px solid #A2CAAC;
    border-radius: 20px;
    height: 420px;
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

.m-state-in-progress {
    background-color: rgba(138, 160, 246, 0.5);
    color: #0010BE;
    line-height: 23px;
}

.m-state-complete {
    background-color: rgba(255, 222, 92, 0.5);
    color: #2F8A1D;
    line-height: 23px;
}
.m-state-waiting {
    background-color: rgba(22, 200, 150, 0.5);
    color: #165442;
    line-height: 23px;
}

.m-state-cancel {
    background-color: rgba(254, 135, 105, 0.5);
    color: #FF3300;
    line-height: 23px;
}

.m-sec-expense {
    display: flex;
    gap: 20px;
}
.menu-sec-first {
    margin-left: 15px;
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

.m-create-btn {
    width: 50px;
    height: 50px;
    position: sticky;
    border: none;
    background-color: transparent;
}

.m-search-opt {
    margin-left: 20px;
    font-size: 1.2rem;
    height: 35px;
}

.m-expense-section {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-left: 20px;
    margin-bottom: 20px;
}

.m-expense-amount {
    margin-right: 20px;
    font-size: 1.3rem;
    
}

.m-expense-first {
    display: flex;
    font-size: 1.3rem;
}

.m-expense-second {
    display: flex;
    gap: 15px;
    justify-content: space-between;
    font-size: 1.1rem;
}

.m-expense-thrid {
    display: flex;
    gap: 15px;
    font-size: 1.1rem;
}

.m-expense-date {
    font-size: 0.9rem;
}
.m-expense-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    overflow: scroll;
    white-space: nowrap;
    height: 640px;
}

.m-expense-status{
    min-width: 50px;
}

.m-create-btn {
    position: fixed;
    width: 50px;
    height: 50px;
    left: 325px;
    bottom: 80px;
}

/* --- 모바일 모달 ---- */

.del-modal-back {
    background-color: rgba(88, 88, 88, 0.637);
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

.m-search-filter {
    display: flex;
    flex-direction: column;
    font-size: 1.5rem; 
    gap: 20px;
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
    display: flex;
}

.m-modal-btn{
    display: flex;
    gap: 30px;
    button {
        width: 80px;
        height: 40px;
        font-size: 1.2rem;
    }
}
.m-explanation {
    background-color: #f8f8f8;
}

.m-p-explanation {
    white-space: normal;
    padding: 10px;
    font-size: 1.1rem;
    margin-left: 20px;
}

.m-kr-bank {
    display: flex;
    font-size: 1.2rem;
    gap: 30px;
    height: 70px;
}

.m-red {
    color: red;
    font-weight: 600;
    padding: 20px;
}

.m-kr-bank-headline{
    font-size: 1.2rem;
    margin-bottom: 30px;
    padding: 20px;
}

.m-moa-box {
    width: 300px;
    height: 200px;
    margin-left: 40px;
    text-align: center;
    background: #c9e6d7;
    border-radius: 30px;
    cursor: pointer;
    margin-bottom: 20px;
}

.m-have-point {
    margin-top: 10px;
    font-size: 1.7rem;
    padding-top: 20px;
}

.m-have-moa {
    margin-top: 15px;
    font-size: 1.5rem;
}

.m-subscribe {
    margin-top: 30px;
    font-size: 1.2rem;
}

.m-div-box-item {
    display: flex;
    justify-content: end;
    align-items: center;
    gap: 10px;
    padding-right: 50px;
    font-size: 1.1rem;
    margin-bottom: 10px;
}

/* ********** */
/* 안내 모달 */
/* ********** */
/* 버튼 손모양 */
button {
cursor: pointer;
}

/* 뒷배경 */
.base-modal-overlay {
position: fixed;
top: 0;
left: 0;
width: 100%;
height: 100%;
background-color: rgba(0, 0, 0, 0.5);
display: flex;
justify-content: center;
align-items: center;
z-index: 1000;
}

/* 모달 박스 */
.base-modal-box {
background-color: #fff;
padding: 25px;
border-radius: 10px;
width: 430px;
height: 330px;
display: flex;
flex-direction: column;
justify-content: space-between;
align-items: center;
border: 3px solid #A2CAAC;
}

/* 각 넓이 설정 */
.base-modal-content, .base-modal-btn {
width: 100%;
font-size: 1.6rem;
text-align: center;
line-height: 227px;
}

/* 버튼 중앙 정렬 */
.base-modal-btn {
display: flex;
justify-content: center;
align-items: center;
column-gap: 75px;
}

/* 각 버튼 설정 */
.base-modal-btn > button {
padding: 12px 40px;
border: none;
border-radius: 5px;
font-size: 1.1rem;
}

/* 확인 버튼 */
.base-modal-submit {
background-color: #A2CAAC;
color: #fff;
}

/* 취소 버튼 */
.base-modal-cancel {
background-color: #F3F3F3;
}

</style>