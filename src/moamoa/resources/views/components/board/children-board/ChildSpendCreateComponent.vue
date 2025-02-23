<template>
    <div class="main-container">
        <div class="board-container">
            <div class="c-route"> 홈  > 지출 > 등록</div>
            <div class="content-list">
                <div class="c-content">
                    <p class="c-list-title">제목</p>
                    <input type="text" class="ms-title deco" id="ms-title" maxlength="10" autofocus placeholder="지출 제목을 입력하세요"  v-model="transactionCreate.title">
                </div>
                <div class="c-content">
                    <p class="c-list-title">날짜</p>
                    <div class="date">
                        <input type="date" class="ms-date" id="ms-date" min="2000-01-01" required v-model="transactionCreate.transaction_date">
                    </div>    
                </div>
                <div class="c-content-cate">
                    <p class="c-list-title">종류</p>
                    <div class="category-btn" v-for="item in categories" :key="item">
                        <input type="radio" name="category" :value="item.index" :id="'category-' + item.index" v-model="transactionCreate.category"></input>
                        <label :for="'category-' + item.index" :class="[{'checked-category-btn': item.index === transactionCreate.category}, 'ms-category-btn']">
                            <img class="ms-category-img" :src="item.img" >
                            <p class="category-name">{{ item.name }}</p>
                        </label>
                    </div>
                </div>
                <div class="c-content">
                    <p class="title">사용 내역</p>
                    <textarea class="ms-content deco" id="ms-content" placeholder="사용 내역을 입력하세요" v-model="transactionCreate.memo"></textarea>
                </div>
                <div class="c-content">
                    <p class="title">금액</p>
                    <input type="number" class="ms-amount deco" id="ms-amount" v-model="transactionCreate.amount" placeholder="금액을 입력하세요">
                    <span class="span-div">원</span>
                </div>
                <div class="c-bottom-btn">
                    <button @click="goBack" class="c-create-btn cancel">등록 취소</button>
                    <button @click="$store.dispatch('childTransaction/createTransaction', transactionCreate)" class="c-create-btn">지출 등록</button>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup>
import { reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const router = useRouter();
const route = useRoute();

// 오늘 날짜 가져오기
const today = new Date().toISOString().split('T')[0];

// const onDay = ref(new Date().toISOString().slice(0, 10));

const categories = reactive([
    {name: '교통비' , img:'/img/icon-bus.png', index : "0"}
    ,{name: '식비' , img:'/img/icon-fastfood.png', index : "1"}
    ,{name: '쇼핑' , img:'/img/icon-shoppingbag.png', index : "2"}
    ,{name: '기타' , img:'/img/icon-checklist7.png', index : "3"}
]);

const radioCategories = ref('');

const transactionCreate = reactive({
    title: '',
    transaction_date: today, 
    category: radioCategories,
    memo: '',
    amount: '',
    transaction_code: '1',
});

// 지출 리스트로 이동
const goBack = () => {
    store.dispatch('childTransaction/transactionList', {child_id: route.params.id, page: 1});
    router.replace('/child/spend/list');
}
</script>


<style scoped>
@import url('../../../../css/boardCommon.css');

/* 입력란 테두리 */
.deco {
    border: 3px solid #5589e996;
}


/* db에 저장된 카테고리 표시 */
.checked-category-btn {
    background-color: #5589e996;
}


.c-bottom-btn{
    gap: 30px;
    margin-top: 20px;
    display: flex;
}


/* 취소/미션등록 버튼 */
.c-create-btn {
    width: 120px;
    height: 50px;
    font-size: 1.5rem;
    border: none;
    color: #FFFFFF;
    background-color: #5589e996 ;
    margin-bottom: 30px;
    cursor: pointer;
}

.cancel {
    margin-left: 1030px;
}
</style>