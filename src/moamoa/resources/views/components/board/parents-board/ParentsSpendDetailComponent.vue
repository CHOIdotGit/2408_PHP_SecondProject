<template>
    <div class="main-container">
        <div v-if="transactionDetail"  class="board-container">
            <div class="content-list">
                <div class="content" >
                    <p class="title h-60">제목</p>
                    <p class="ms-title h-60">{{ transactionDetail.title }}</p>
                    <div class="date deco">
                        <span class="ms-date h-60">{{ transactionDetail.transaction_date }}</span>
                    </div>
                </div>
                <div class="content">
                    <p class="title">종류</p>
                    <div class="category-btn" v-for="item in categories" :key="item" :class="{'checked-category-btn' : item.index === Number(category) }">
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
    ,{name: '식비' , img:'/img/icon-fastfood.png', index : 1}
    ,{name: '쇼핑' , img:'/img/icon-shoppingbag.png', index : 2}
    ,{name: '기타' , img:'/img/icon-checklist7.png', index : 3}
]);
</script>

<style scoped>
@import url('../../../../css/boardCommon.css');
@import url('../../../../css/category.css');


/* 미션(지출) 제목 입력란 */
.ms-title {
    display: flex;
    align-items: center;
}

/* 미션(지출) 날짜 영역 배경색 */
.deco {
    background-color: #A2CAAC;
}

/* 미션 금액 */
.ms-amount {
    display: flex;
    align-items: center;
}

/* 미션(지출) 카테고리 */
.category-btn {
    width: 80px;
    height: 80px;
}

/* 선택된 카테고리 색깔 */
.checked-category-btn {
    background-color: #A2CAAC;
}



/* 버튼 영역 */
.create-btn {
    margin: 25px 0 0 170px;
    display: flex;
    align-items: center;
}

/* 취소 버튼 */
.ms-cancel {
    color: #ACACAC;
    background-color: #FFFFFF;
    font-size: 1.2rem;
    border: 1px solid #ACACAC;
    padding: 5px;
    width: 120px;
    height: 50px;
    border-radius: 0px;
    cursor: pointer;
    margin-left: 130px;
    margin-right: 350px;
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