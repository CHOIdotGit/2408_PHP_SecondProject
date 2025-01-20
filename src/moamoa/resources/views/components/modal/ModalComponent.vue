<template>
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
</style>