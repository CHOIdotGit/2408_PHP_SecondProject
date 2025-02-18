<template>
<div class="main-container">
    <div class="board-container">
        <div class="c-route"> 홈  > 지출 > 상세</div>
        <div class="content-list">
            <div class="c-content" v-if="transactionDetail">
                <p class="c-list-title c-title">제목</p>
                <span class="ms-title">{{ transactionDetail.title }}</span>
            </div>
            <div class="c-content">
                <p class="c-list-title">날짜</p>
                <div class="date">
                    <span class="ms-date" >{{ transactionDetail.transaction_date }}</span>
                </div>
            </div> 
            <div class="c-content-cate">
                <p class="title">종류</p>
                <div class="category-btn" v-for="item in categories" :key="item">
                    <img class="c-ms-category-img" :src=item.img :class="{'c-checked-category-btn' : item.index === Number(category) }">
                    <p class="category-name">{{ item.name }}</p>
                </div>
            </div>
            <div class="c-content">
                <p class="c-list-title">사용 내역</p>
                <div class="ms-content">{{ transactionDetail.memo }}</div>
            </div>
            <div class="c-content">
                <p class="c-list-title">금액</p>
                <p class="ms-amount">{{ Number(transactionDetail.amount).toLocaleString() }}원</p>
            </div>
            <div class="c-bottom-btn">
                <button @click="goBack" class="ms-cancel left">뒤로가기</button>
                <button @click="delOpenModal(transactionDetail.transaction_id)" class="btn">지출 삭제</button>
                <button @click="goUpdate(transactionDetail.transaction_id)" class="btn">지출 수정</button>
            </div>
        </div>
    </div>
</div>
<!-- ********삭제 모달********* -->
<!-- 삭제 모달 모듈 작업 중 -->
<!-- <div class="delModal child-theme" v-if="delModal">
    <ModalComponent @click="delCloseModal" />
</div> -->

<!-- ************************* -->
<!-- ********삭제 모달********* -->
<!-- ************************* -->
<div class="del-modal-black" v-show="delModal">
    <div class="del-modal-white">
        <div class="modal-content">
            <img src="/img/icon-trash.png" class="modal-img" alt=".">
            <p class="modal-name"></p>
            <p class="modal-ms-title">지출 : {{transactionDetail.title}}</p>
            <div class="del-guide">해당 지출이 삭제됩니다.</div>
        </div>
        <div class="del-btn">
            <button @click="delCloseModal" class="modal-cancel">취소</button>
            <button @click="deleteTransaction(transactionDetail.transaction_id)" class="modal-del">삭제</button>
        </div>
    </div>
</div>

</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';
import ModalComponent from '../../modal/ModalComponent.vue';

const store = useStore();
const router = useRouter();
const route = useRoute();

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
    store.dispatch('childTransaction/transactionList', {child_id: route.params.id, page: 1});
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
@import url('../../../../css/childboardCommon.css');
@import url('../../../../css/category.css');

/* db에 저장된 카테고리 표시 */
.c-checked-category-btn {
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
    border: none;
    padding: 5px;
    width: 120px;
    border-radius: 0px;
    cursor: pointer;
}


.c-bottom-btn{
    gap: 30px;
    margin-top: 20px;
    display: flex;
}

.left {
    margin-right: 580px;
}

.category-name {
    margin-top: 10px;
    font-size: 1.3rem;
}

.date {
    margin-left: 15px;
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