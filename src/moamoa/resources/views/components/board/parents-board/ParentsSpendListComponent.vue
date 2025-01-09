<template>
    <div class="container">
        <div class="list-container">
            <p class="who">{{ childName }}의 지출 리스트</p>
            <div class="mission-title-bar">
                <p class="top-title">제목</p>
                <span class="category">종류</span>
                <p class="charge">금액</p>
                <p class="due-date">지출 일자</p>
            </div>
            <div class="scroll">
                <div v-if="transactionList && transactionList.length" v-for="item in transactionList" :key="item" class="mission-inserted-list">
                    <div class="mission-content">
                        <p @click="goTransactionDetail(item.transaction_id)" class="title">{{ getTruncatedTitle(item.title) }}</p>
                        <p class="category">{{ getCategoryText(item.category) }}</p>
                        <p class="charge">{{ Number(item.amount).toLocaleString() }}원</p>
                        <p class="due-date">{{ item.transaction_date }}</p>
                    </div>
                </div>
            </div>
            <div class="for-buttons">
                <router-link to="/parent/home" class="h-50"><button class="btn-bottom mission-go-back">뒤로가기</button></router-link>
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
    if(childId.value) {
        store.dispatch('transaction/transactionList', childId.value);
    }
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

.who {
    font-size: 1.5rem;
    margin-right: 1150px;
}

.list-container {
    margin-top: 20px;
    width: 1500px;
    height: 720px;
    background-color: white;
    display: flex;
    flex-direction: column;
    gap: 20px;
    justify-content: center;
    align-items: center;   
}

.mission-title-bar {
    display: grid;
    grid-template-columns:210px 90px 150px 280px;
    height: 60px;
    gap: 150px;
    background-color: #F5F5F5;
    font-size: 2rem;
    align-items: center;
    width: 1400px;
    margin-top: 15px;
    text-align: center;
    padding-left: 50px;
}

.mission-content {
    display: grid;
    grid-template-columns: 210px 90px 150px 280px;
    min-height: 60px;
    gap: 150px;
    font-size: 1.3rem;
    align-items: center;
    width: 1400px;
    text-align: center;
    padding-left: 50px;
}

.for-buttons{
    display: flex;
    justify-content: right;
    /* gap: 30px; */
    margin-left: 1170px;
    height: 50px;
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

.scroll {
    overflow-y: auto;
    overflow-x: hidden;
    height: 420px;
}

.title {
    cursor: pointer;
}

.title:hover {
    color: #A2CAAC;
}
</style>