<template>
<div class="main-container">
    <div class="board-container">
        <div class="content-list">
            <div class="content" v-if="transactionDetail">
                <p class="title">지출 제목</p>
                <span class="ms-title">{{ transactionDetail.title }}</span>
                <div class="date deco">
                    <span class="ms-date">{{ transactionDetail.transaction_date }}</span>
                </div>
            </div>
            <div class="content">
                <p class="title">지출 종류</p>
                <div class="category-btn" v-for="item in categories" :key="item">
                    <img class="ms-category-img" :src=item.img :class="{'categorybtn-green' : item.index === Number(category) }">
                    <p class="category-name">{{ item.name }}</p>
                </div>
            </div>
            <div class="content">
                <p class="title">사용 내역</p>
                <div class="ms-content">{{ transactionDetail.memo }}</div>
            </div>
            <div class="content">
                <p class="title">지출 금액</p>
                <p class="ms-amount">{{ Number(transactionDetail.amount).toLocaleString() }}원</p>
            </div>
            <div class="bottom-btn">
                <button @click="goBack" class="ms-cancel left">뒤로가기</button>
                <button @click="delOpenModal(transactionDetail.transaction_id)" class="btn">지출 삭제</button>
                <button @click="goUpdate(transactionDetail.transaction_id)" class="btn">지출 수정</button>
            </div>
        </div>
    </div>
</div>
<!-- ********삭제 모달********* -->
<div class="delModal child-theme" v-if="delModal">
    <ModalComponent @click="delCloseModal" />
</div>

</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';
import ModalComponent from '../../modal/ModalComponent.vue';

const store = useStore();
const router = useRouter();

// 지출 상세 정보 불러오기기
const transactionDetail = computed(() => store.state.childTransaction.transactionDetail);

// 마운트트
onMounted(() => {
    store.dispatch('childTransaction/showTransactionDetail', store.state.childTransaction.transactionId);
});

//****지출 카테고리 정보 출력****
const category = computed(() => store.state.childTransaction.transactionDetail.category);

// 카테고리 변환
const categories = reactive([
    {name: '교통비' , img:'/img/icon-bus.png', index : 0}
    ,{name: '식비' , img:'/img/icon-fastfood.png', index : 1}
    ,{name: '쇼핑' , img:'/img/icon-shoppingbag.png', index : 2}
    ,{name: '기타' , img:'/img/Pngtreestationery_icon_3728043.png', index : 3}
]);

// 뒤로가기
const goBack = (child_id) => {
    store.dispatch('childTransaction/transactionList', child_id);
    router.push('/child/spend/list');

}
// *****지출 삭제*******
const deleteTransaction = (transaction_id) => {
    console.log('삭제 요청 transaction_id : ', transaction_id)
    store.dispatch('childTransaction/deleteTransaction', transaction_id);
}

// *****지출 수정*******
const goUpdate = (transaction_id) => {
    console.log('수정 요청 transaction_id : ', transaction_id);
    store.dispatch('childTransaction/goUpdateTransaction', transaction_id);
    router.push('/child/spend/update/'+ transaction_id);
}

// 모달
const delModal = ref(false);

const delOpenModal = (transaction_id) => {
    store.dispatch('childTransaction/showTransactionDetail', transaction_id);
    delModal.value = true;
}

const delCloseModal = () => {
    delModal.value = false;
}


</script>

<style scoped>
@import url('../../../../css/boardCommon.css');
@import url('../../../../css/boardCommon.css');

/* 미션(지출) 제목 입력란 */
.ms-title {
    display: flex;
    align-items: center;
}

/* 날짜 입력란 배경색 */
.deco {
    background-color: #5589e996;
}

/* 미션 금액 입력란 */
.ms-amount {
    display: flex;
    align-items: center;
}

/* 미션(지출) 종류 카테고리 */
.category-btn {
    width: 80px;
    height: 80px;
}

/* db에 저장된 카테고리 표시 */
.categorybtn-green {
    background-color: #5589e996;
}


/* 취소버튼 */
.ms-cancel {
    color: #ACACAC;
    background-color: #FFFFFF;
    font-size: 1.5rem;
    border: 1px solid #ACACAC;
    padding: 5px;
    width: 120px;
    height: 50px;
    border-radius: 0px;
    cursor: pointer;
    margin-left: 300px;
    /* margin-right: 640px; */
}

.btn {
    color: #FFFFFF;
    background-color: #5589e996;
    font-size: 1.5rem;
    border: 1px solid #5589e996;
    padding: 5px;
    width: 120px;
    border-radius: 0px;
    cursor: pointer;
}

/* 삭제버튼 */
.ms-del {
    /* margin-left: 250px; */
    color: #5589e996;
    background-color: #FFFFFF;
    font-size: 1.2rem;
    border: 1px solid #5589e996;
    padding: 5px;
    width: 100px;
    border-radius: 0px;
    cursor: pointer;
}

/* 승인버튼 */
.ms-comfirm {
    color: #FFFF;
    background-color: #5589e996;
    font-size: 1.2rem;
    border: 1px solid #5589e996;
    padding: 5px;
    width: 120px;
    border-radius: 0px;
    cursor: pointer;
}

.bottom-btn{
    gap: 30px;
    margin-top: 30px;
    display: flex;
}

.left {
    margin-right: 580px;
}

/* 수정버튼 */
.ms-up {
    color: #FFFF;
    background-color: #5589e996;
    font-size: 1.2rem;
    border: 1px solid #5589e996;
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
    border: 3px solid #5589e996;
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
    /* border: 3px solid #5589e996; */
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
    background-color: #5589e996;
    font-size: 1.2rem;
    border: 1px solid #5589e996;
    padding: 5px;
    width: 100px;
    cursor: pointer;
    margin: 10px;
}

.child-theme {
        background-color: #5589e996;
}

</style>