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
                    <p>{{ savingInfo.saving_product_name }} 적금</p>
                </div>
                <div class="b-info">
                    <p class="margin-left p-first">납입 유형</p>
                    <p>{{ savingInfo.saving_product_type }}</p>
                </div>
                <div class="b-info">
                    <p class="p-first">가입한 날</p>
                    <p>{{ savingInfo.saving_detail_created_at }}</p>
                </div>
                <div class="b-info">
                    <p class="margin-left p-first">금리</p>
                    <p class="p-rate">{{ Number(savingInfo.saving_product_interest_rate).toFixed(1) }} %</p>
                </div>
            </div>
            <div class="bankbook-profile">
                <img src="" class="img-size">
                <div class="div-child-name">
                    <p>{{ childInfo.name }}</p>
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
                <div class="main-content" >
                    <div v-for="(item, index) in savingDetail" :key="item" class="bankbook-transactions">
                        <p>{{ index + 1 }}</p>
                        <p>{{ formatDate(item.saving_detail_created_at) }}</p>
                        <p class="bankbook-amount">{{ item.saving_detail_outcome }}</p>
                        <p class="bankbook-amount">{{ item.saving_detail_income }}</p>
                        <p class="bankbook-amount">{{ item.saving_detail_left }}</p>
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

// 통장 정보
const savingInfo = computed(()=> store.state.saving.savingInfo);

// 자녀 정보
const childInfo = store.state.header.childInfo;

onMounted(()=> {
    const bankbookId = route.params.bankbook_id;
    console.log('파라미터 bankbook_id:', bankbookId);
    store.dispatch('saving/childSavingDetail', bankbookId);
    store.dispatch('header/childInfo')
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
@import url('../../../../css/bankbook.css');

</style>