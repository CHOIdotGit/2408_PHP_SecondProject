<template>
    <div class="container">
        <div class="list-container">
            <div class="mission-title">
                <div class="chk-div">
                    <input type="checkbox" id="checkbox9">
                </div>
                <span class="status">종류</span>
                <p class="mission-type">제목</p>
                <p class="charge">금액</p>
                <p class="due-date">소비일자</p>
            </div>
            <div class="scroll">
                <div v-for="item in transactionList" :key="item" class="mission-inserted-list">
                    <div class="mission-content">
                        <div class="chk-div">
                            <input type="checkbox" id="checkbox9">
                        </div>
                        <p class="category">{{ getCategoryText(item.category) }}</p>
                        <p class="title">{{ getTruncatedTitle(item.title) }}</p> 
                        <p class="charge">{{ item.amount.toLocaleString() }}원</p>
                        <p class="transaction-date">{{ item.transaction_date }}</p>
                    </div>
                </div>
            </div>
            <div class="for-buttons">
                <button class="btn-bottom mission-go-back">삭제</button>
                <button class="btn-bottom mission-insert">작성</button>
            </div>
        </div>
    </div>
</template>

<script setup>

import { computed, onMounted } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

// 거래 리스트 가져오기
const transactionList = computed(() => store.state.transaction.transactionList);

const getCategoryText = (category) => {
    const categoryMapping = {
        0: '교통비',
        1: '취미',
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

// onMount
onMounted(() => {
    // store.dispatch('transaction/transactionListPagination');
});

</script>

<style scoped>
.container {
    margin-left: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    /* padding-bottom: 40px; */
}

.list-container {
    margin-top: 20px;
    width: 1500px;
    height: 740px;
    background-color: white;
    display: flex;
    flex-direction: column;
    gap: 20px;
    justify-content: center;
    align-items: center;   
}

.mission-title {
    display: grid;
    grid-template-columns: 70px 150px 400px 150px 300px;  
    height: 60px;
    gap: 100px;
    background-color: #F5F5F5;
    font-size: 2rem;
    margin: 10px;
    align-items: center;
    width: 1420px;
    text-align: center;
}

.mission-content {
    display: grid;
    grid-template-columns: 70px 150px 400px 150px 300px;  
    min-height: 60px;
    gap: 100px;
    font-size: 1.3rem;
    margin: 10px;
    align-items: center;
    width: 1400px;
    text-align: center;
}

.for-buttons{
    display: flex;
    justify-content: right;
    gap: 30px;
    margin-left: 1170px;
    height: 60px;
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
    width: 100px;
    height: 50px;
    font-size: 1.5rem;
    border: none;
    background-color:#5589e996 ;
    margin: 30px 0;
}

#checkbox9 {
    margin: 15px;
}


.mission-inserted-list {
    height: 60px;
    display: grid;
}
.scroll {
    overflow-y: auto;
    overflow-x: hidden;
    height: 420px;
    background-color: #F5F5F550;
}
</style>