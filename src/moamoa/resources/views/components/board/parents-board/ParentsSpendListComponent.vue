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
            <div class="m-expense-section">
                <div class="m-expense-first">
                    <p class="m-expense-title">가나다라마바사아자차</p>
                    <p class="m-expense-amount">60,000</p>
                </div>
                <div class="m-expense-second">
                    <p class="m-expense-category">쇼핑</p>
                    <p class="m-expense-date">2025.02.19</p>
                </div>
            </div>
            <div class="m-expense-section">
                <div class="m-expense-first">
                    <p class="m-expense-title">가나다라마바사아자차</p>
                    <p class="m-expense-amount">60,000</p>
                </div>
                <div class="m-expense-second">
                    <p class="m-expense-category">쇼핑</p>
                    <p class="m-expense-date">2025.02.19</p>
                </div>
            </div>
            <div class="m-expense-section">
                <div class="m-expense-first">
                    <p class="m-expense-title">가나다라마바사아자차</p>
                    <p class="m-expense-amount">60,000</p>
                </div>
                <div class="m-expense-second">
                    <p class="m-expense-category">쇼핑</p>
                    <p class="m-expense-date">2025.02.19</p>
                </div>
            </div>
            <div class="m-expense-section">
                <div class="m-expense-first">
                    <p class="m-expense-title">가나다라마바사아자차</p>
                    <p class="m-expense-amount">60,000</p>
                </div>
                <div class="m-expense-second">
                    <p class="m-expense-category">쇼핑</p>
                    <p class="m-expense-date">2025.02.19</p>
                </div>
            </div>
            <div class="m-expense-section">
                <div class="m-expense-first">
                    <p class="m-expense-title">가나다라마바사아자차</p>
                    <p class="m-expense-amount">60,000</p>
                </div>
                <div class="m-expense-second">
                    <p class="m-expense-category">쇼핑</p>
                    <p class="m-expense-date">2025.02.19</p>
                </div>
            </div>
            <div class="m-expense-section">
                <div class="m-expense-first">
                    <p class="m-expense-title">가나다라마바사아자차</p>
                    <p class="m-expense-amount">60,000</p>
                </div>
                <div class="m-expense-second">
                    <p class="m-expense-category">쇼핑</p>
                    <p class="m-expense-date">2025.02.19</p>
                </div>
            </div>
            <div class="m-expense-section">
                <div class="m-expense-first">
                    <p class="m-expense-title">가나다라마바사아자차</p>
                    <p class="m-expense-amount">60,000</p>
                </div>
                <div class="m-expense-second">
                    <p class="m-expense-category">쇼핑</p>
                    <p class="m-expense-date">2025.02.19</p>
                </div>
            </div>
            <div class="m-expense-section">
                <div class="m-expense-first">
                    <p class="m-expense-title">가나다라마바사아자차</p>
                    <p class="m-expense-amount">60,000</p>
                </div>
                <div class="m-expense-second">
                    <p class="m-expense-category">쇼핑</p>
                    <p class="m-expense-date">2025.02.19</p>
                </div>
            </div>
            <div class="m-expense-section">
                <div class="m-expense-first">
                    <p class="m-expense-title">가나다라마바사아자차</p>
                    <p class="m-expense-amount">60,000</p>
                </div>
                <div class="m-expense-second">
                    <p class="m-expense-category">쇼핑</p>
                    <p class="m-expense-date">2025.02.19</p>
                </div>
            </div>
            <div class="m-expense-section">
                <div class="m-expense-first">
                    <p class="m-expense-title">가나다라마바사아자차</p>
                    <p class="m-expense-amount">60,000</p>
                </div>
                <div class="m-expense-second">
                    <p class="m-expense-category">쇼핑</p>
                    <p class="m-expense-date">2025.02.19</p>
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




<!--  웹 ㄴ -->
<div class="div-container" v-else>
    <div class="list-container">
        <div class="route"> 홈  > 지출 </div>
        <div class="top-btn">
            <div></div>
        </div>
        <div class="search-menu">
            <div class="search-option">
                <div class="search-date">
                    <p>⦁ 지출 일자</p> 
                    <input type="date" min="2000-01-01" v-model="filters.date">
                </div>
                <div class="search-filter">
                    <p>⦁ 지출 종류 </p> 
                    <select name="spend-type" v-model="filters.category">
                        <option value="">전체</option>
                        <option value="0">교통비</option>
                        <option value="1">식비</option>
                        <option value="2" >쇼핑</option>
                        <option value="3">기타</option>
                    </select>
                </div>
                <div class="search">
                    <input type="search" v-model="filters.keyword" placeholder="검색어를 입력해주세요">
                </div>
            </div>
            <div class="search-btn">
                <button @click="search(childId)">검색</button>
            </div>
        </div>
        <div class="mission-title-bar">
            <p class="top-title">제목</p>
            <span class="category">종류</span>
            <p class="charge">금액</p>
            <p class="due-date">지출 일자</p>
        </div>
        <div class="scroll">
            <div v-if="transactionList && transactionList.length" v-for="item in transactionList" :key="item" class="mission-inserted-list">
                <div class="mission-content">
                    <p @click="goTransactionDetail(item.transaction_id)" class="sp-title">{{ getTruncatedTitle(item.title) }}</p>
                    <p class="category">{{ getCategoryText(item.category) }}</p>
                    <p class="charge">{{ Number(item.amount).toLocaleString() }}원</p>
                    <p class="due-date">{{ item.transaction_date }}</p>
                </div>
            </div>
        </div>
        <!-- 페이지네이션 UI by 최상민 -->
        <div class="pagination">
            <!-- 이전 버튼 -->
            <button v-if="currentPage > 1"
                class="paginate-btn" 
                @click="goToPrevious" 
                :disabled="currentPage === 1">
                < 이전
            </button>
            <!-- 페이지 번호 출력 4가 현재 페이지일때 (예: 1 ... 3 4 5 6) -->
            <span v-for="page in pageNumbers" :key="page" class="paginate-span">
                <!-- '...'인 경우 span 태그 사용 -->
                <!-- <button class="paginate-btn" @click="goToPage(page)" :disabled="page === currentPage || page === '...'">{{ page }}</button> -->
                    <!-- 페이지 번호 버튼 -->
                <button 
                    v-if="page !== '...'" 
                    class="paginate-btn" 
                    @click="goToPage(page)" 
                    :disabled="page === currentPage"
                    :class="{'no-pointer': page === currentPage}"
                >
                    {{ page }}
                </button>

                <!-- '...' 버튼 스타일을 위해 별도의 클래스 적용 -->
                <button 
                    v-else 
                    class="dots" 
                    disabled
                >
                    {{ page }}
                </button>
            </span>
            <!-- 다음 버튼 -->
            <button v-if="currentPage < lastPage"
                class="paginate-btn" 
                @click="goToNext" 
                :disabled="currentPage === lastPage">
                다음 >
            </button>
        </div>
    </div>
</div>
</template>

<script setup>

import { computed, onMounted, reactive, ref } from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const route = useRoute();


// +==========================+
// +    모바일 화면 전환       +
// +==========================+
// v-if="ismobile"적으면 모바일 화면으로 이동
const isMobile = store.state.mobile.isMobile;



// 거래 리스트 가져오기
const transactionList = computed(() => store.state.transaction.transactionList);
console.log('transactionList : ', store.state.transaction.transactionList);

// 첫 번째 자녀의 name을 가져오는 computed
const childName = computed(() => {
    const child = transactionList.value?.[0]?.child;
    return child ? child.name : 'Loading...';
});

// 자녀 id 파라미터 세팅
const childId = computed(() => store.state.transaction.childId);

const getCategoryText = (category) => {
    const categoryMapping = {
        0: '교통비',
        1: '식비',
        2: '쇼핑',
        3: '기타',
    };
    return categoryMapping[category]; // 기본값 없이 반환
};

// 특정 글자 길이 이후 '...'으로 표기
const maxLength = 20;

const getTruncatedTitle =(title) => {
  return title.length > maxLength 
    ? title.substring(0, maxLength) + '...' 
    : title;
};

const goTransactionDetail = (transaction_id) => {
    store.dispatch('transaction/showTransactionDetail', transaction_id);
}

// onMount
onMounted(() => {
    // if(childId.value) {
    //     store.dispatch('transaction/transactionList', childId.value);
    // }
    store.dispatch('transaction/transactionList', {child_id: route.params.id, page: 1});
});

// ********** 페이지네이션 **********
const currentPage = computed(() => store.state.transaction.currentPage);
const lastPage = computed(() => store.state.transaction.lastPage);

// 페이지네이션을 위한 페이지 번호 배열 생성
const pageNumbers = computed(() => {
    const numbers = [];
    const range = 1; // 현재 페이지 앞뒤로 표시할 페이지 수

    // 첫 번째 페이지 추가
    if (currentPage.value > range + 1) {
        numbers.push(1);
        numbers.push('...');  // 첫 번째 페이지 앞에 '...'
    }

    // 페이지 번호를 배열에 추가 (예: 1 ... 3 4 5 6)
    for (let i = Math.max(currentPage.value - range, 1); i <= Math.min(currentPage.value + range, lastPage.value); i++) {
        numbers.push(i);
    }

    // 마지막 페이지가 포함되지 않으면 추가
    if (numbers[numbers.length - 1] !== lastPage.value) {
        if (currentPage.value < lastPage.value - range - 1) {
            numbers.push('...');  // 마지막 페이지 뒤에 '...'
        }
        numbers.push(lastPage.value);
    }

    // 마지막 페이지가 1이면 추가하지 않도록 설정
    if (lastPage.value === 1) {
        numbers.pop();
    }
    
    return numbers;
});

// 페이지 이동 함수
const goToPage = (page) => {
    if (page >= 1 && page <= lastPage.value) {
        store.dispatch('transaction/transactionList', { child_id: route.params.id, page });
    }
};

// 이전 페이지로 이동하는 함수
const goToPrevious = () => {
    if (currentPage.value > 1) {
        goToPage(currentPage.value - 1);
    }
};

// 다음 페이지로 이동하는 함수
const goToNext = () => {
    if (currentPage.value < lastPage.value) {
        goToPage(currentPage.value + 1);
    }
};

// +=================+
// +    검색 필터     +
// +=================+
// 기본값
const today = ref(new Date().toISOString().slice(0, 10));
const filters = reactive({
    category: "",
    date: today.value,
    keyword: "",
    child_id: ""
});

const search = (childId) => {
    filters.child_id = childId;
    // console.log(filters);
    store.dispatch('transaction/transactionSearch', filters);
};

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
@import url('../../../../css/list.css');

.div-container {
    /* margin-left: 50px; */
    display: flex;
    /* justify-content: center; */
    align-items: center;
    /* padding-bottom: 40px; */
}

.list-container {
    background-color: white;
    display: flex;
    flex-direction: column;
    gap: 20px;  
}


.mission-title-bar {
    width: 1400px;
    display: grid;
    grid-template-columns:450px 150px 150px 280px;
    height: 60px;
    gap: 75px;
    background-color: #d1d1d1;
    font-size: 2rem;
    align-items: center;
    text-align: center;
}

.mission-content {
    display: grid;
    grid-template-columns: 450px 150px 150px 280px;
    min-height: 60px;
    gap: 75px;
    font-size: 1.3rem;
    align-items: center;
    width: 1400px;
    text-align: center;
}

/* 금액 : 오른쪽 정렬 */
.mission-content>.charge {
    text-align: end;
    padding-right: 30px;
}

.top-btn div {
    height: 80px;
}
.for-buttons{
    display: flex;
    justify-content: right;
    /* gap: 30px; */
    margin-left: 1170px;
    height: 50px;
}
.search-menu{
    display: flex;
    flex-direction: row;
    background-color: #d3e2d7;
    height: 200px;
    gap: 30px;
}

.search-btn button{
    width: 120px;
    height: 150px;
    margin-top: 20px;
    font-weight: 800;
    align-content: space-between;
    margin-left: 210px;
    background-color: #A2CAAC;
    font-size: 1.5rem;
    border: none;
  box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);

}

.search-btn button:hover {
    color: #ffffff;
    background-color: #6a8f73;
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
    width: 120px;
    height: 50px;
    font-size: 1.5rem;
    border: none;
    color: #ACACAC;
    background-color: #FFFFFF;
    border: 1px solid #ACACAC;
    cursor: pointer;
}

#checkbox9 {
    margin: 15px;
}


.mission-inserted-list {
    height: 60px;
    display: grid;
}

.sp-title {
    cursor: pointer;
}

.sp-title:hover {
    color: #A2CAAC;
}

/* 페이지네이션 css */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
}

.paginate-span {
    font-size: 1.3rem;
    font-weight: 600;
}

.paginate-btn {
    all: unset;
    cursor: pointer;
    font-size: 1.3rem;
}

.paginate-btn:hover {
    color: #A2CAAC;
}

.no-pointer {
    cursor: default;
    background-color: #a2caac;
    border-radius: 50%;
    text-align: center;
    width: 30px;
    height: 30px;
}

.no-pointer:hover {
    color: #000000;
}

.dots {
    /* cursor: default; */
    all: unset;
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
}

.m-expense-first {
    display: flex;
    justify-content: space-between;
    font-size: 1.3rem;
}

.m-expense-second {
    display: flex;
    gap: 15px;
    font-size: 1.1rem;
}
.m-expense-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    overflow: scroll;
    white-space: nowrap;
    max-height: 640px;
}

/* --- 모바일 모달 ---- */

.del-modal-back {
    background-color: rgba(255, 224, 224, 0.637);
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
</style>