<template>
    <div class="main-container">
        <div class="detail-container">
            <div class="content-list">
                <div class="content" v-if="transactionDetail">
                    <p class="title">제목</p>
                    <span class="ms-title">{{ transactionDetail.title }}</span>
                    <div class="date">
                        <span class="ms-date">{{ transactionDetail.transaction_date }}</span>
                    </div>
                </div>
                <div class="content">
                    <p class="title">종류</p>
                    <div class="category-btn" v-for="item in categories" :key="item">
                        <img class="ms-category" :src=item.img :class="{'categorybtn-green' : item.index === Number(category) }">
                        <p>{{ item.name }}</p>
                    </div>
                </div>
                <div class="content">
                    <p class="title">사용 내역</p>
                    <div class="ms-content">{{ transactionDetail.memo }}</div>
                </div>
                <div class="content">
                    <p class="title">금액</p>
                    <p class="ms-amount">{{ Number(transactionDetail.amount).toLocaleString() }}원</p>
                </div>
                <div class="bottom-btn">
                    <button @click="goBack" class="ms-cancel left">뒤로가기</button>
                    <button @click="delOpenModal" class="btn">지출 삭제</button>
                    <button @click="goUpdate(transactionDetail.transaction_id)" class="btn">지출 수정</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ************************* -->
    <!-- ********삭제 모달********* -->
    <!-- ************************* -->
    <div class="del-modal-black" v-show="delModal">
        <div class="del-modal-white">
            <div class="modal-content">
                <img src="/img/icon-trash.png" class="modal-img">
                <p class="modal-ms-title">지출 : {{ transactionDetail.title }}</p>
                <div class="del-guide">해당 지출이 삭제됩니다.</div>
            </div>
            <div class="del-btn">
                <button @click="delCloseModal" class="modal-cancel">취소</button>
                <button @click="deleteTransaction(transactionDetail.transaction_id)" class="modal-del">삭제하기</button>
            </div>
        </div>
    </div>

</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';

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
    ,{name: '취미' , img:'/img/icon-fastfood.png', index : 1}
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

const delOpenModal = () => {
    delModal.value = true;
}

const delCloseModal = () => {
    delModal.value = false;
}


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
    justify-content: center;
}

/* 미션 제목 */
.ms-title {
    width: 450px;
    border: 3px solid #5589e996;
    outline: none;
    border-radius: 10px;
    font-size: 2rem;
    padding-left: 5px;
    display: flex;
    align-items: center;
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
    display: flex;
    flex-direction: column;
    text-align: center;
    padding-right: 30px;
}

.category-btn > label {
    color: #c9cfca;
    font-size: 0.9rem;
    padding-top: 5px;
}

.category-btn > input {
    display: none;
}

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

/* db에 저장된 카테고리 표시 */
.categorybtn-green {
    background-color: #A2CAAC;
}

/* 미션 내용 */
.ms-content {
    width: 500px;
    height: 150px;
    padding: 10px;
    border-radius: 10px;
    border: 3px solid #5589e996;
    font-size: 1.3rem;
    display: flex;
    align-items: center;
}

/* 미션 금액 */
.ms-amount {
    width: 300px;
    border: 3px solid #5589e996;
    border-radius: 10px;
    font-size: 1.3rem;
    padding-left: 5px;
    line-height: 45px;
    display: flex;
    align-items: center;
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
    background-color: #5589e996;
    font-size: 1.2rem;
    border: 1px solid #5589e996;
    padding: 5px;
    width: 100px;
    cursor: pointer;
    margin: 10px;
}

</style>