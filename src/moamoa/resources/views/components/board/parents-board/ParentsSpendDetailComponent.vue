<template>
    <div class="main-container">
        <div v-if="transactionDetail"  class="detail-container">
            <div class="content-list">
                <div class="content" >
                    <p class="title h-60">제목</p>
                    <p class="ms-title h-60">{{ transactionDetail.title }}</p>
                    <div class="date">
                        <span class="ms-date h-60">{{ transactionDetail.transaction_date }}</span>
                    </div>
                </div>
                <div class="content">
                    <p class="title">종류</p>
                    <div class="category-btn" v-for="item in categories" :key="item" :class="{'category-btn-green' : item.index === Number(category) }">
                        <img class="ms-category-img" :src="item.img">
                        <p class="category-name">{{ item.name }}</p>
                    </div>
                </div>
                <div class="content">
                    <p class="title">사용 내역</p>
                    <div class="ms-content">{{ transactionDetail.memo}}</div>
                </div>
                <div class="content">
                    <p class="title">금액</p>
                    <p class="ms-amount">{{ Number(transactionDetail.amount).toLocaleString() }}원</p>
                </div>
                <div class="create-btn">
                    <button @click="goBack(transactionDetail.child_id)" class="ms-cancel">뒤로가기</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref, onMounted, reactive  } from 'vue';
import { useStore } from 'vuex';

// *****지출 상세 정보******
const store = useStore();
const transactionDetail = computed(() => store.state.transaction.transactionDetail);
onMounted(() => {
    store.dispatch('transaction/showTransactionDetail', store.state.transaction.transactionId);
});

// 뒤로가기
const goBack = (child_id) => {
    store.dispatch('transaction/transactionList', child_id);
}

// 지출 카테고리 정보 가져오기
const category = computed(() => store.state.transaction.transactionDetail.category);

const categories = reactive([
    {name: '교통비' , img:'/img/icon-bus.png', index : 0}
    ,{name: '취미' , img:'/img/icon-fastfood.png', index : 1}
    ,{name: '쇼핑' , img:'/img/icon-shoppingbag.png', index : 2}
    ,{name: '기타' , img:'/img/icon-checklist7.png', index : 3}
]);
</script>

<style scoped>

.main-container{
    margin-left: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.detail-container {
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
    text-align: center;
    display: flex;
    align-items: center;
}

/* 미션 제목 */
.ms-title {
    width: 450px;
    /* border: 3px solid #A2CAAC; */
    outline: none;
    border-radius: 10px;
    font-size: 1.8rem;
    padding-left: 5px;
    display: flex;
    align-items: center;
}

/* 미션 날짜 */
.date {
    /* border: 3px solid #A2CAAC; */
    border-radius: 10px;
    padding: 10px;
    margin-left: 30px;
    background-color: #A2CAAC;
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

.h-60 {
    height: 60px;
}

/* 미션 종류 카테고리 이미지 */
.category-btn {
    display: flex;
    flex-direction: column;
    width: 80px;
    height: 80px;
    background-color: #c9cfca;
    border-radius: 50px;
    align-items: center;
    margin-right: 20px;
    font-size: 1.2rem;
}

.category-btn-green {
    background-color: #A2CAAC;
}


.ms-category-img {
    margin-top: 13px;
    width: 50px;
    height: 50px;
    background-size: contain;
    background-repeat: no-repeat;
    border: none;

}

.category-name {
    margin-top: 15px;
}

/* 미션 내용 */
.ms-content {
    width: 500px;
    height: 150px;
    padding: 10px;
    border-radius: 10px;
    /* border: 3px solid #A2CAAC; */
    font-size: 1.3rem;
}

/* 미션 금액 */
.ms-amount {
    width: 300px;
    /* border: 3px solid #A2CAAC; */
    border-radius: 10px;
    font-size: 1.3rem;
    padding-left: 5px;
    line-height: 45px;
    display: flex;
    /* justify-content: center; */
    align-items: center;
}

/* 취소버튼 */
.create-btn {
    margin: 25px 0 0 170px;
    display: flex;
    align-items: center;
}

.ms-cancel {
    color: #ACACAC;
    background-color: #FFFFFF;
    font-size: 1.2rem;
    border: 1px solid #ACACAC;
    padding: 5px;
    width: 100px;
    height: 50px;
    border-radius: 0px;
    cursor: pointer;
    margin-left: 130px;
    margin-right: 350px;
}

/* 삭제버튼 */
.ms-del {
    /* margin-left: 250px; */
    color: #A2CAAC;
    background-color: #FFFF;
    font-size: 1.2rem;
    border: 1px solid #A2CAAC;
    padding: 5px;
    width: 100px;
    border-radius: 0px;
    cursor: pointer;
}

.bottom-btn{
    /* width: 60px; */
    gap: 30px;
    display: flex;
    margin-top: 50px;
}

/* 수정버튼 */
.ms-up {
    color: #FFFF;
    background-color: #A2CAAC;
    font-size: 1.2rem;
    border: 1px solid #A2CAAC;
    padding: 5px;
    width: 100px;
    border-radius: 0px;
    cursor: pointer;
}

/* 승인버튼 */
.ms-comfirm {
    color: #FFFF;
    background-color: #A2CAAC;
    font-size: 1.2rem;
    border: 1px solid #A2CAAC;
    padding: 5px;
    width: 100px;
    border-radius: 0px;
    cursor: pointer;
}

.btn-right {
    display: flex;
}

/* ********************* */
/* *******삭제 모달****** */
/* ********************* */

.del-modal-black {
    background-color: rgba(0, 0, 0, 0.5);
    position: fixed;
    /* top: 182px;
    left: 177px; */
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    /* margin-top: 500px; */
    justify-content: center;
}

.del-modal-white {
    width: 400px;
    height: 500px;
    background-color: #FFFFFF;
    border: 3px solid #A2CAAC;
    /* margin: 170px; */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

}

.modal-content {
    text-align: center;
    margin: 60px;
}

.modal-name {
    font-size: 1.3rem;
    padding: 10px;
}

.modal-ms-title {
    font-size: 1.3rem;
    padding: 10px;
}

.del-guide {
    font-size: 1.4rem;
    padding: 15px;
}


.modal-img{
    width: 100px;
    height: 100px;
    background-color: #FFFFFF;
    border: 3px solid #5589e996;
    border-radius: 50px;
    padding: 3px;
}



/* 버튼 */
.modal-cancel {
    color: #ACACAC;
    background-color: #FFFFFF;
    font-size: 1.2rem;
    border: 1px solid #ACACAC;
    padding: 5px;
    width: 100px;
    cursor: pointer;
    margin: 10px;
}

.modal-del {
    color: #FFFF;
    background-color: #A2CAAC;
    font-size: 1.2rem;
    border: 1px solid #A2CAAC;
    padding: 5px;
    width: 100px;
    cursor: pointer;
    margin: 10px;
}

</style>