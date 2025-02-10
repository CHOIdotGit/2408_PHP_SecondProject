<template>
<div v-if="isMobile">
    여기는 모바일

</div>
<div class="container" v-else>
    <div class="list-container">
        <div class="route"> 홈  > 지출 </div>
        <div class="top-btn">
            <div></div>
        </div>
        <div class="search-menu">
            <div class="search-option">
                <div class="search-date">
                    <p> 지출 일자</p> 
                    <input type="date" min="2000-01-01" v-model="filters.date">
                </div>
                <div class="search-filter">
                    <p> 지출 종류 </p> 
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
            <button 
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
            <button 
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

import { computed, onMounted, ref } from 'vue';
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
const filters = ref({
    category: "",
    date: "",
    keyword: "",
    child_id: ""
});




const search = (childId) => {
    filters.value.child_id = childId;
    console.log(filters.value.date);
    store.dispatch('transaction/transactionSearch', filters.value);
};


</script>



<style scoped>
@import url('../../../../css/list.css');

.container {
    /* margin-left: 50px; */
    display: flex;
    justify-content: center;
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
</style>