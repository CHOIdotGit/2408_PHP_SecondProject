<template>
    <div class="bank-product-container">
        <div class="savings-product">   
            <h1>만기된 적금 상품</h1>
            <div class="outline">
                <div class="expired-saving-name">상품명</div>
                <div class="saving-status">구분</div>
                <div class="cancellation-date">해지일</div>
            </div>
            <div v-for="item in expiredSavingList" :key="item" class="expired-saving-list">
                <div class="expired-saving-name">{{ item.saving_product_name }} 적금</div>
                <div class="saving-status">{{ getStatusText(item.saving_sign_up_status) }}</div>
                <div class="cancellation-date">{{ item.saving_sign_up_end_at }}</div>
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
import { computed, ref, onMounted, onBeforeMount } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const route = useRoute();
const router = useRouter();

const page = 1; // 기본 값 설정

// 만기된 적금 정보 가져오기
const expiredSavingList = computed(() =>store.state.saving.expiredSavings);

// 카테고리를 문자열로 변환하는 함수
const getStatusText = (saving_sign_up_status) => {
    const statusMapping = {
        // 0: '진행중',
        1: '중도 해지',
        2: '만기 해지',
    };
    return statusMapping[saving_sign_up_status]; // 기본값 없이 반환
};

// ********** 페이지네이션 **********
const currentPage = computed(() => store.state.saving.currentPage);
const lastPage = computed(() => store.state.saving.lastPage);

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
        store.dispatch('saving/childExpiredSavings', { child_id: route.params.child_id, page });
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
    store.dispatch('saving/childExpiredSavings', { child_id: route.params.child_id, page });
})

</script>


<style scoped>
.bank-product-container {
    display: flex;
    width: 100%;
    justify-content: center;
}

.savings-product {
    width: 1000px;
}

.outline {
    display: flex;
    align-items: center;
    text-align: center;
    background-color: #e8ecdc;
    border-radius: 15px;
    height: 45px;
    font-size: 1.5rem;
    margin-top: 30px;
}

/* 설명 적는 div */
.expired-saving-name {
    width: 500px;
}

.saving-status {
    width: 250px;
}

.cancellation-date {
    width: 250px;
}

.expired-saving-list {
    display: flex;
    text-align: center;
    align-items: center;
    font-size: 1.2rem;
    margin-top: 10px;
    height: 30px;
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
    color: #5589e996;
}

.no-pointer {
    cursor: default;
    background-color: #5589e996;
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