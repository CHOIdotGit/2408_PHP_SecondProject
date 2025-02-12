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
                    <p>년 월 일
                        <!-- 이걸 클릭하면 최신순, 오래된순 정렬하는 기능 추가 -->
                    </p>
                    <p>출금 금액</p>
                    <p>맡기신 금액</p>
                    <p>거래 후 잔액</p>
                    <p>거래 내용</p>
                </div>
                <div class="bankbook-item">
                    <div class="testing">
                        <div class="bankbook-number">
                            <p v-for="num in 20" :key="num" class="num">{{ num }}</p>
                        </div>
                        <div class="main-content">
                            <div v-for="item in pointList" :key="item" class="bankbook-transactions"> 
                                <p>{{ item.payment_at }}</p>
                                <p v-if="item.point_code === '3'" class="text-end">{{ item.point }}</p>
                                <p v-else></p>
                                <p v-if="['0', '1', '2', '4'].includes(item.point_code)" class="text-end">{{ item.point.toLocaleString() }}</p>
                                <p v-else></p>
                                <p class="text-end">100
                                    <!-- total -->
                                </p>
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

import { computed, onMounted } from 'vue';
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
    store.dispatch('point/printPointList', {child_id: route.params.child_id, page: 1});
});

</script>
  
<style scoped>
  /* 중앙 정렬 */
  .bankbook-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 1000px;
  }
  
  /* 통장 스타일 */
  .bankbook {
    width: 1000px;
    height: 1000px;
    background: linear-gradient(180deg, #f3f4f6, #ffffff);
    border: 1px solid #aaa;
    border-radius: 30px 30px 0 0;
    box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.2);
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
  }
  
  /* .bankbook::before {
    content: "";
    position: absolute;
    top: 63.7%;
    left: 0;
    width: 100%;
    height: 1px;
    background: linear-gradient(to right, rgba(0, 0, 0, 0.1), transparent 50%, rgba(0, 0, 0, 0.1));
    box-shadow: 0 -2px 2px rgba(0, 0, 0, 0.05), 0 2px 2px rgba(255, 255, 255, 0.3);
    transform: translateY(-45%);
  } */
  
  /* 상단 헤더 */
  .bankbook-header {
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
  .bankbook-info {
    width: 100%;
    height: 250px;
    display: flex;
    justify-content: center;
    /* padding-left: 187px;
    gap: 185px; */
  }
  
  .info-detail {
    /* width: 350px;
    font-size: 2.5rem; */
    display: grid;
    grid-template-columns: repeat(2, 350px);
    gap: 25px;
    font-size: 1.5rem;
    text-align: center;
    padding: 25px 0;
    align-items: center;
  }
  
  /* 거래 내역 테이블 */
  .bankbook-table {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
  }
  
  .bankbook-title {
    width: 900px;
    height: 40px;
    display: grid;
    grid-template-columns: 184px 154px 154px 154px 254px;
    align-items: center;
    text-align: center;
    border: 1px solid #000;
    background-color: #c8e5f3;
    font-size: 1.3rem;
  }
  
  .bankbook-item {
    /* display: flex; */
    width: 900px;
  }
  
  .bankbook-number {
    width: 30px;
    text-align: center;
    border-right: 1px solid black;
  }
  
  .num {
    padding: 5px;
  }
  
  .bankbook-number > p {
    border-bottom: 1px solid black;
  }
  
  .bankbook-transactions {
    width: 870px;
    display: grid;
    grid-template-columns: 154px 154px 154px 154px 254px;
    text-align: center;
  }
  
  .bankbook-transactions > p {
    border-right: 1px solid black;
    border-bottom: 1px solid black;
    height: 29px;
    padding-top: 5px;
    font-size: 1.2rem;
  }
  
  /* 정보 정렬 */
  .b-info {
    height: 50px;
    display: flex;
    align-items: center;
    border-radius: 8px;
    gap: 45px;
  }

  /* 자녀 프로필 사진 $이름 */
.bankbook-profile {
    width: 150px;
    height: 200px;
    border: 1px solid #000;
    background-color: #b3b3b3;
    margin: 25px 0 25px 25px;
}

.margin-left {
    margin-left: 40px;
}

.img-size {
    width: 148px;
    max-height: 150px;
    object-fit: cover;
}

.div-child-name {
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 1.5rem;
    padding-top: 13%;
}

.p-first {
    color: rgba(51, 51, 51, 0.75);
    font-weight: 600;
}

.p-rate {
    margin-left: 50px;
}

/* 페이지네이션 css */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    margin-top: 12px;
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

.main-content {
    width: 870px;
}

.testing {
    display: flex;
}

.loading {

}

.text-end {
    text-align: end;
    padding-right: 1px;
}
</style>
  