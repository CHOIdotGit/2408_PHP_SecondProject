<template>
    <div class="bankbook-wrapper">
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
                    <div class="div-child-name">
                        <p>{{ pointList[0].child.name }}</p>
                    </div>
                </div>
            </div>
            <!-- 거래 내역 -->
            <div class="bankbook-table">
                <div class="bankbook-title">
                    <p>년 월 일</p>
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

import { computed, onBeforeMount } from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const route = useRoute();
const pointList = computed(() => store.state.point.pointList);
const totalPoint = computed(() => store.state.point.totalPoint);
// const childId = computed(() => store.state.point.childId);

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
        "4": '만기 적금',
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
        store.dispatch('point/childPrintPointList', { child_id: route.params.child_id, page });
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

onBeforeMount(() => {
    store.dispatch('point/childPrintPointList', {child_id: route.params.child_id, page: 1});
});

</script>
  
<style scoped>
@import url('../../../../css/bankbook.css');
</style>
  