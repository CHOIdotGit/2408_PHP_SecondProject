<template>
    <div class="bankbook-wrapper">
        <div class="bankbook">
            <!-- 상단 헤더 (은행명) -->
            <div class="bankbook-header">
                <p class="bank-name">모아은행</p>
            </div>
            <!-- 통장 정보 -->
            <div v-if="pointList.length" class="bankbook-info">
                <div class="info-detail">
                    <div class="b-info">
                        <p class="p-first">통장 종류</p>
                        <p>모아 통장</p>
                    </div>
                    <div class="b-info">
                        <p class="margin-left p-first">나의 모아</p>
                        <!-- <p class="margin-left p-first">모아 포인트</p>
                        <p class="margin-left p-first">보유중인 모아 포인트</p> -->
                        <p>{{ Number(totalPoint).toLocaleString() }}</p>
                    </div>
                    <div class="b-info">
                        <p class="p-first">가입한 날</p>
                        <p>{{ formatDate(pointList[0].child.created_at) }}</p>
                    </div>
                        <div class="b-info">
                        <p class="margin-left p-first">금리</p>
                        <p class="p-rate">3.0 %</p>
                    </div>
                    <!-- <p>보유중인 모아 포인트</p> -->
                </div>
                <div class="bankbook-profile">
                    <img :src="pointList[0].child.profile" class="img-size">
                    <div class="div-child-name">
                        <p>{{ pointList[0].child.name }}</p>
                    </div>
                </div>
            </div>
            <!-- 거래 내역 -->
            <div class="bankbook-table">
                <div class="bankbook-title">
                    <p @click="toggleSortOrder" class="change-date">년 월 일</p>
                    <p>출금 금액</p>
                    <p>맡기신 금액</p>
                    <p>거래 후 잔액</p>
                    <p>거래 내용</p>
                </div>
                <div class="bankbook-item">
                    <div class="testing">
                        <div class="main-content">
                            <div v-for="(item, index) in pointListWithBalance" :key="item.id || index" class="bankbook-transactions">
                                <!-- 번호 -->
                                <p>{{ index + 1 }}</p>
                                <!-- 날짜 -->
                                <p>{{ item.payment_at }}</p>
                                <!-- 출금: point_code가 '3'인 경우에만 표시 -->
                                <p class="text-end">{{ item.point_code === '3' ? Number(item.point).toLocaleString() : '' }}</p>
                                <!-- 입금: point_code가 '3'이 아닐 때 표시 -->
                                <p class="text-end">{{ item.point_code !== '3' ? Number(item.point).toLocaleString() : '' }}</p>
                                <!-- 거래 후 잔액 -->
                                <p class="text-end">{{ Number(item.cumulativeTotal).toLocaleString() }}</p>
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

import { computed, onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const route = useRoute();
const pointList = computed(() => store.state.point.pointList);
const totalPoint = computed(() => store.state.point.totalPoint);
// const childId = computed(() => store.state.point.childId);

// 정렬 순서: 'asc' = 오래된순, 'desc' = 최신순
const sortOrder = ref('asc');

// 정렬 토글 함수
const toggleSortOrder = () => {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
    fetchData(); // 정렬 변경 시 데이터 다시 요청
};

// 각 항목에 누적 잔액(거래 후 잔액)을 추가하는 computed property
const pointListWithBalance = computed(() => {
    let balance = 0;

    // payment_at 기준으로 정렬
    let sortedList = [...pointList.value].sort((a, b) => new Date(a.payment_at) - new Date(b.payment_at));

    // 정렬 순서 변경 (desc일 경우 뒤집기)
    if (sortOrder.value === 'desc') {
        sortedList.reverse();
    }

    // 정렬된 리스트에 누적 합계 계산
    return sortedList.map(item => {
        const withdrawal = item.point_code === '3' ? Number(item.point) : 0;
        const deposit = item.point_code !== '3' ? Number(item.point) : 0;
        balance += (deposit - withdrawal);
        return {
            ...item,
            cumulativeTotal: balance, // 누적 잔액(거래 후 잔액)
            deposit,                  // 입금 값 (필요시)
            withdrawal                // 출금 값 (필요시)
        };
    });
});

// 시분초 제외
const formatDate = (date) => {
    const d = new Date(date);
    return d.toISOString().split('T')[0]; // "YYYY-MM-DD" 형식으로 반환
}

// 카테고리를 문자열로 변환하는 함수
const getCategoryText = (point_code) => {
    const categoryMapping = {
        "0": '출석 체크',
        "1": '미션 수행',
        "2": '이자',
        "3": '적금',
        "4": '도전 과제',
        "5": '적금 만기',
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
    // store.dispatch('point/printPointList', {child_id: route.params.child_id, page: 1});
    store.dispatch('point/printPointList', {
        child_id: route.params.child_id,
        // page: currentPage.value,
        page: 1,
        sort: sortOrder.value // 서버로 정렬 정보 전달
    });
});

</script>
  
<style scoped>
@import url('../../../../css/bankbook.css');
</style>
  