<template>
    <div class="main-container">
        <div class="create-container">
            <div class="content-list">
                <div class="content">
                    <p class="title">제목</p>
                    <input type="text" class="ms-title" id="ms-title" maxlength="10" required autofocus v-model="transactionCreate.title">
                    <div class="date">
                        <input type="date" class="ms-date" id="ms-date" min="2000-01-01" required v-model="transactionCreate.transaction_date">
                    </div>
                </div>
                <div class="content">
                    <p class="title">종류</p>
                    <div class="category-btn" v-for="item in categories" :key="item">
                        <input type="radio" name="category" :value="item.index" :id="'category-' + item.index" v-model="transactionCreate.category"></input>
                        <label :for="'category-' + item.index" :class="[{'checked-category-btn': item.index === transactionCreate.category}, 'ms-category-btn']">
                            <img class="ms-category" :src="item.img" >
                            <p class="categoryName">{{ item.name }}</p>
                        </label>
                    </div>
                </div>
                <div class="content">
                    <p class="title">사용 내역</p>
                    <textarea class="ms-content" id="ms-content" placeholder="사용 내역을 입력하세요" v-model="transactionCreate.memo"></textarea>
                </div>
                <div class="content">
                    <p class="title">금액</p>
                    <input type="number" class="ms-amount" id="ms-amount" required v-model="transactionCreate.amount">
                </div>
                <div class="bottom-btn">
                    <button @click="$router.replace('/child/spend/list')" class="create-btn">취소</button>
                    <button @click="$store.dispatch('childTransaction/createTransaction', transactionCreate)" class="create-btn">작성</button>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup>
import { reactive, ref } from 'vue';



// 오늘 날짜 가져오기
const today = new Date().toISOString().split('T')[0];

// const onDay = ref(new Date().toISOString().slice(0, 10));

const categories = reactive([
    {name: '교통비' , img:'/img/icon-bus.png', index : "0"}
    ,{name: '취미' , img:'/img/icon-fastfood.png', index : "1"}
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
</script>


<style scoped>

.main-container{
    margin-left: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.create-container {
    background-color: #FFFFFF;
    width: 1500px;
    margin-top: 20px;
    height: 720px;
}

.content-list {
    display: grid;
    margin-top: 50px;
    margin-left: 20px;
    justify-content: center;
}


.content {
    display: flex;
    padding: 20px;
    margin: 0 300px;
    gap: 10px;
    border-bottom: 2px solid #dfdfdf;
    width: 1000px;
}


.title {
    font-size: 2rem;
    border-right: 2px solid #dfdfdf;
    padding: 10px;
    width: 160px;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* 미션 제목 */
.ms-title {
    width: 600px;
    border: 3px solid #5589e996;
    outline: none;
    border-radius: 10px;
    font-size: 1.8rem;
    padding-left: 5px;
}

/* 미션 날짜 */
.date {
    border: 3px solid #5589e996;
    border-radius: 10px;
    padding: 10px;
    margin-left: 30px;
}

span {
    padding: 5px;
}

.ms-date {
    border: none;
    outline: none;
    /* width: 200px; */
    font-size: 1.5rem;
    text-align: center;
}

/* 미션 종류 카테고리 이미지 */
.category-btn {
    width: 80px;
    height: 80px;
    display: flex;
    flex-direction: column;
    text-align: center;
    margin-right: 25px;
}

.category-btn > input {
    display: none;
}

.ms-category-btn {
    width: 70px;
    height: 70px;
    border-radius: 50px;
    background-color: #c9cfca;
    text-align: center;
    cursor: pointer;
}

/* .category-btn > label {
    color: #A4D8E1;
    border: 1px solid #A4D8E1;
    font-size: 0.9rem;
    padding-top: 5px;
} */

.checked-category-btn {
    background-color: #A2CAAC;
}

.ms-category {
    margin-top: 13px;
    width: 50px;
    height: 50px;
    background-size: contain;
    background-repeat: no-repeat;
    border: none;
}

/* 미션 내용 */
.ms-content {
    width: 500px;
    height: 150px;
    resize: none;
    padding: 10px;
    outline: none;
    border-radius: 10px;
    border: 3px solid #5589e996;
    font-size: 1.5rem;
}

/* 미션 금액 */
.ms-amount {
    width: 300px;
    border: 3px solid #5589e996;
    outline: none;
    border-radius: 10px;
    font-size: 2rem;
    padding-left: 5px;
}

.ms-amount::-webkit-outer-spin-button,
.ms-amount::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

.bottom-btn{
    display: flex;
    justify-content: right;
    gap: 30px;
    margin: auto;
    margin-top: 10px;
    margin-right: 300px;

}


/* 취소/미션등록 버튼 */
.create-btn {
    width: 100px;
    height: 50px;
    font-size: 1.5rem;
    border: none;
    color: #FFFFFF;
    background-color: #5589e996 ;
    margin-bottom: 30px;
    cursor: pointer;
}

</style>