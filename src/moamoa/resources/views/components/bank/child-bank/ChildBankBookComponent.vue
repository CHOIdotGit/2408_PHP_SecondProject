<template>
<div class="bankbook-wrapper">
    <div class="bankbook">
        <!-- 상단 헤더(은행명) -->
        <div class="bankbook-header">
            <p class="bank-name">모아은행</p>
        </div>
        <!-- 통장정보 -->
        <div class="bankbook-info" v-if="savingDetail">
            <div class="info-detail">
                <div class="b-info">
                    <p class="p-first">통장 종류</p>
                    <p>{{ savingDetail.saving_product_name }} 적금</p>
                </div>
                <div class="b-info">
                        <p class="margin-left p-first">나의 모아</p>
                        <p>1000모아</p>
                </div>
                <div class="b-info">
                    <p class="p-first">가입한 날</p>
                    <p>2025-02-01</p>
                </div>
                <div class="b-info">
                    <p class="margin-left p-first">금리</p>
                    <p class="p-rate">3.0 %</p>
                </div>
            </div>
            <div class="bankbook-profile">
                <img src="" class="img-size">
                <div class="div-child-name">
                    <p>이름</p>
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
                <div class="bankbook-number">
                    <p v-for="num in 20" class="num">{{ num }}</p>
                </div>
                <div class="main-content" >
                    <div v-for="item in savingDetail" :key="item" class="bankbook-transactions">
                        <p>{{ formatDate(item.saving_detail_created_at) }}</p>
                        <p>{{ item.saving_detail_outcome }}</p>
                        <p>{{ item.saving_detail_income }}</p>
                        <p>{{ item.saving_detail_left }}</p>
                        <p>{{ getCategoryText(item.saving_detail_category) }}</p>
                    </div>
                </div>

            </div>

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

// 자녀 통장 내역 불러오기
const savingDetail = computed(()=>store.state.saving.savingDetail);
onMounted(()=> {
    const bankbookId = route.params.bankbook_id;
    console.log('파라미터 bankbook_id:', bankbookId);
    store.dispatch('saving/childSavingDetail', bankbookId);
})

// 날짜 형태 바꾸기 yyyy-mm-dd
const formatDate = (date) =>  {
    const day = new Date(date);
    return day.toISOString().split('T')[0];
}

// 카테고리 문자로 변환
const getCategoryText = (saving_detail_category) => {
    const categoryMapping = {
        "0": '입금',
        "1": '이자',
    };
    return categoryMapping[saving_detail_category]; // 기본값 없이 반환
};


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
    height: 60px;
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
    display: flex;
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
    height: 28.9px;
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

</style>