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
            <img src="/img/m-search.png" alt="" class="m-search-button" @click="searchOpenModal">
        </div>
        <div class="m-expense-list">
            <div class="m-bankbook">
            <!-- 상단 헤더 (은행명) -->
            <div class="m-bankbook-header">
                <p class="m-bank-name">모아은행</p>
            </div>
            <!-- 통장 정보 -->
            <div v-if="pointList.length > 0" class="m-bankbook-info">
                <div class="m-info-detail">
                    <div class="m-b-info">
                        <p class="m-p-first">통장 종류</p>
                        <p class="m-p-first">모아 통장</p>
                    </div>
                    <div class="m-info-set">
                        <p>나의 모아</p>
                        <p>{{ Number(totalPoint).toLocaleString() }} moa</p>
                    </div>
                </div>
                <div class="m-bankbook-profile">
                    <img :src="pointList[0].child.profile" class="m-img-size">
                    <!-- <div class="div-child-name">
                        <p>{{ pointList[0].child.name }}</p>
                    </div> -->
                </div>
            </div>
            <!-- 거래 내역 -->
            <div class="m-bankbook-table">
                <div class="m-bankbook-title">
                    <p class="m-change-date">년 월 일</p>
                    <p>출금</p>
                    <p>입금</p>
                    <p>잔액</p>
                    <p>거래 내용</p>
                </div>
                <div class="m-bankbook-item">
                    <div class="m-main-content">
                        <div v-for="item in pointList" :key="item" class="m-bankbook-transactions">
                            <!-- 날짜 -->
                            <p class="m-mm-date">{{ item.payment_at }}</p>
                            <!-- 출금 -->
                            <p class="m-bankbook-amount">{{ item.withdrawal === 0 ? '' : Number(item.withdrawal).toLocaleString() }}</p>
                            <!-- 입금 -->
                            <p class="m-bankbook-amount">{{ item.deposit === 0 ? '' : Number(item.deposit).toLocaleString() }}</p>
                            <!-- 거래 후 잔액 -->
                            <p class="m-bankbook-amount">{{ Number(item.cumulativeTotal).toLocaleString() }}</p>
                            <!-- 카테고리 -->
                            <p>{{ getCategoryText(item.point_code) }}</p>
                        </div>
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
</div>
</div>


    <div v-else class="bankbook-wrapper">
        <div class="bankbook">
            <!-- 상단 헤더 (은행명) -->
            <div class="bankbook-header">
                <p class="bank-name">모아은행</p>
            </div>
            <!-- 통장 정보 -->
            <div v-if="pointList.length > 0" class="bankbook-info">
                <div class="info-detail">
                    <div class="b-info">
                        <p class="p-first">통장 종류</p>
                        <p>모아 통장</p>
                    </div>
                    <div class="b-info">
                        <p class="margin-left p-first">나의 모아</p>
                        <p>{{ Number(totalPoint).toLocaleString() }}</p>
                    </div>
                    <div class="b-info">
                        <p class="p-first">가입한 날</p>
                        <p>{{ formatDate(pointList[0].child.created_at) }}</p>
                    </div>
                </div>
                <div class="bankbook-profile">
                    <img :src="pointList[0].child.profile" class="img-size">
                    <!-- <div class="div-child-name">
                        <p>{{ pointList[0].child.name }}</p>
                    </div> -->
                </div>
            </div>
            <!-- 거래 내역 -->
            <div class="bankbook-table">
                <div class="bankbook-title">
                    <p class="change-date">년 월 일</p>
                    <p>출금 금액</p>
                    <p>맡기신 금액</p>
                    <p>거래 후 잔액</p>
                    <p>거래 내용</p>
                </div>
                <div class="bankbook-item">
                    <div class="testing">
                        <div class="main-content">
                            <div v-for="item in pointList" :key="item" class="bankbook-transactions">
                                <!-- 날짜 -->
                                <p>{{ item.payment_at }}</p>
                                <!-- 출금 -->
                                <p class="bankbook-amount">{{ item.withdrawal === 0 ? '' : Number(item.withdrawal).toLocaleString() }}</p>
                                <!-- 입금 -->
                                <p class="bankbook-amount">{{ item.deposit === 0 ? '' : Number(item.deposit).toLocaleString() }}</p>
                                <!-- 거래 후 잔액 -->
                                <p class="bankbook-amount">{{ Number(item.cumulativeTotal).toLocaleString() }}</p>
                                <!-- 카테고리 -->
                                <p>{{ getCategoryText(item.point_code) }}</p>
                            </div>
                        </div>
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

import { computed, onMounted, ref , reactive} from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const route = useRoute();
const pointList = computed(() => store.state.point.childPointList);
const totalPoint = computed(() => store.state.point.totalPoint);

// 시분초 제외
const formatDate = (date) => {
    const d = new Date(date);
    return d.toISOString().split('T')[0]; // "YYYY-MM-DD" 형식으로 반환
}

// --------------- 모바일 모달 -----------------

const serachModal = reactive({ isOpen: false });

const searchOpenModal = () => {
    serachModal.isOpen = true;
};

const searchCloseModal = () => {
    serachModal.isOpen = false;
};

// +==========================+
// +    모바일 화면 전환       +
// +==========================+
// v-if="ismobile"적으면 모바일 화면으로 이동
const isMobile = store.state.mobile.isMobile;

// 카테고리를 문자열로 변환하는 함수
const getCategoryText = (point_code) => {
    const categoryMapping = {
        "0": '출석 체크',
        "1": '미션 수행',
        "2": '이자',
        "3": '적금',
        "4": '원금',
    };
    return categoryMapping[point_code]; // 기본값 없이 반환
};

// ********** 페이지네이션 **********
const currentPage = computed(() => store.state.point.currentPage);
const lastPage = computed(() => store.state.point.lastPage);

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
        store.dispatch('point/printPointList', { child_id: route.params.child_id, page });
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

onMounted(() => {
    store.dispatch('point/printPointList', {child_id: route.params.child_id, page: 1});
});

</script>
  
<style scoped>
@import url('../../../../css/bankbook.css');

.m-bankbook-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 1050px;
}

/* 통장 스타일 */
.m-bankbook {
    overflow: scroll;
    height: 640px;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
}



/* 상단 헤더 */
.m-bankbook-header {
    width: 100%;
    min-height: 60px;
    background-color: #e4eff4;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 2rem;
    font-weight: bold;
    color: #333;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* 통장 정보 */
.m-bankbook-info {
    width: 100%;
    height: 250px;
    display: flex;
    justify-content: center;
    /* padding-left: 187px;
    gap: 185px; */
}

.m-info-detail {
   display: flex;
   flex-direction: column;
}

/* 거래 내역 테이블 */
.m-bankbook-table {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
}

.m-bankbook-title {
    width: 380px;
    height: 40px;
    display: grid;
    grid-template-columns: 100px 65px 65px 65px 65px;
    align-items: center;
    text-align: center;
    border: 1px solid #000;
    background-color: #c8e5f3;
    font-size: 1rem;
}


.m-bankbook-item {
    display: flex;
    width: 360px;
}


/* ****통장 내역 번호**** */
.m-bankbook-number {
    width: 30px;
    text-align: center;
    border-right: 1px solid black;
}

.num {
    padding: 5px;
}

.m-bankbook-number > p {
    border-bottom: 1px solid black;
}

/****** 통장 내역 내용 *******/
.m-bankbook-transactions {
    width: 360px;
    height: 40px;
    display: grid;
    grid-template-columns: 90px 55px 55px 85px 65px;
    align-items: center;
    text-align: center;
    font-size: 0.9rem;
}


/* 금액 오른쪽 정렬 */
.m-bankbook-amount {
    text-align: end;
    padding-right: 2px;
}

.m-bankbook-date {
    border-left: 1px solid #000;
}


.m-bankbook-transactions > p {
    height: 28.9px;
    padding-top: 5px;
}

.m-mm-date {
    font-size:0.9rem ;
}

/******* 통장 정보 정렬 ********/
.m-b-info {
    margin-top: 20px;
    height: 50px;
    display: flex;
    align-items: center;
    border-radius: 8px;
    gap: 45px;
    font-size: 1.2rem;
    line-height: 50px;
}

.m-info-set {
    margin-top: 20px;
    height: 50px;
    display: flex;
    flex-direction: column;
    border-radius: 8px;
    font-size: 1.2rem;
    line-height: 50px;
    p {
        margin-top: -20px;
    }
}

  /* 자녀 프로필 사진 $이름 */
.m-bankbook-profile {
    width: 120px;
    height: 150px;
    border: 1px solid #000;
    background-color: #ffffff;
    margin: 25px 0 25px 25px;
    border-radius: 10px;
    overflow: hidden;
    justify-content: center;
}

.m-img-size {
    width: 120px;
    height: 150px;
}

.m-p-first {
    margin-top: 20px;
}



.text-end {
    text-align: end;
    padding-right: 2px;
}

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

</style>
  