<template>
    <div class="main-container">
        <div class="create-container">
            <div class="content-list">
                <div class="content">
                    <p class="title">제목</p>
                    <input type="text" class="ms-title" id="ms-title" maxlength="10" v-model="transactionDetail.title" autofocus>
                    <div class="date">
                        <input type="date" class="ms-date" id="ms-date" min="2000-01-01" v-model="transactionDetail.transaction_date">
                    </div>
                </div>
                <div class="content">
                    <p class="title">종류</p>
                    <div class="category-btn" v-for="item in categories" :key="item">
                        <input type="radio" name="category" :value="item.index" :id="'category-' + item.index" v-model="transactionDetail.category"></input>
                        <label :for="'category-' + item.index" :class="[{'checked-category-btn': item.index === transactionDetail.category}, 'ms-category-btn']">
                            <img class="ms-category-img" :src="item.img" >
                            <p class="category-name">{{ item.name }}</p>
                        </label>
                    </div>
                </div>
                <div class="content">
                    <p class="title">사용 내역</p>
                    <textarea v-model="transactionDetail.memo" class="ms-content" id="ms-content" placeholder="사용 내역을 입력하세요"></textarea>
                </div>
                <div class="content">
                    <p class="title">금액(원)</p>
                    <input v-model="transactionDetail.amount" type="number" class="ms-amount" id="ms-amount" required>
                </div>
                <div class="bottom-btn">
                    <button @click="$router.push('/child/spend/detail/${transaction_id}')" class="create-btn">수정 취소</button>
                    <button @click="updateTransaction" class="create-btn">수정 완료</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onBeforeMount, reactive } from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';

const route = useRoute();
const store = useStore();

// 해당 날짜 가져오기
const today = new Date().toISOString().split('T')[0];

// 마운트
onBeforeMount(() => {
    const transaction_id = route.params.transaction_id;
    console.log('수정할 transaction id 획득 : ', transaction_id);
    store.dispatch('childTransaction/goUpdateTransaction', transaction_id); //mission_id 불러오기
});

// *****미션 상세 정보******
const transactionDetail = store.state.childTransaction.transactionDetail;

// 카테고리 변환
const categories = reactive([
    {name: '교통비' , img:'/img/icon-bus.png', index : 0}
    ,{name: '취미' , img:'/img/icon-fastfood.png', index : 1}
    ,{name: '쇼핑' , img:'/img/icon-shoppingbag.png', index : 2}
    ,{name: '기타' , img:'/img/Pngtreestationery_icon_3728043.png', index : 3}
]);

const updateTransaction = () => {
    store.dispatch('childTransaction/UpdateTransaction', transactionDetail);
};

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

/* .category-name {
    margin-top: 15px;
} */

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
    width: 450px;
    border: none;
    outline: none;
    border-radius: 10px;
    font-size: 2rem;
    padding-left: 5px;
    display: flex;
    align-items: center;
}

/* 미션 날짜 */
.date {
    border: none;
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
    display: flex;
    align-items: center;
}

.category-btn > input {
    display: none;
}

.ms-category-btn {
    /* width: 80px;
    height: 80px;
    border-radius: 50px;
    background-color: #c9cfca;
    text-align: center;
    cursor: pointer; */
}

.checked-category-btn {
    background-color: #5589e996;
}

.ms-category-img {
    width: 60px;
    height: 60px;
    background-size: cover;
    background-repeat: no-repeat;
    border: none;
    background-color: #c9cfca;
    cursor: pointer;
    border-radius: 50px;
    padding: 5px;

}

/* 미션 종류 카테고리 이미지 */
.category-btn {
    display: flex;
    flex-direction: column;
    text-align: center;
    padding-right: 30px;
}

/* .category-btn > label {
    color: #A4D8E1; */
    /* border: 1px solid #A4D8E1; */
    /* font-size: 0.9rem;
    padding-top: 5px;

} */

/* .category-btn > input {
    display: none;
} */



.ms-category {
    width: 60px;
    height: 60px;
    background-size: cover;
    background-repeat: no-repeat;
    border: none;
    background-color: #c9cfca;
    cursor: pointer;
    border-radius: 50px;
    padding: 5px;
}

/* 미션 내용 */
.ms-content {
    width: 500px;
    height: 150px;
    resize: none;
    padding: 10px;
    outline: none;
    border-radius: 10px;
    border: none;
    font-size: 1.5rem;
}

/* 미션 금액 */
.ms-amount {
    width: 300px;
    border: none;
    outline: none;
    border-radius: 10px;
    font-size: 2rem;
    padding-left: 5px;
    display: flex;
    align-items: center;
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
    margin-right: 300px;
    margin-top: 30px;
}


/* 취소/미션등록 버튼 */
.create-btn {
    width: 120px;
    height: 50px;
    font-size: 1.5rem;
    border: none;
    color: #FFFFFF;
    background-color: #5589e996 ;
    margin-bottom: 30px;
    cursor: pointer;
}
</style>