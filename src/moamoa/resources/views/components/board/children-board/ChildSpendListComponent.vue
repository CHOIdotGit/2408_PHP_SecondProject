<template>
    <div class="container">
        <div class="list-container">
            <div class="mission-title">
                <div class="chk-div">
                    <input type="checkbox" id="checkbox" class="checkbox" name="checkAll" @change="checkAll" :checked="isAllChecked">
                </div>
                <p class="mission-type">제목</p>
                <span class="status">종류</span>
                <p class="charge">금액</p>
                <p class="due-date">소비일자</p>
            </div>
            <div class="scroll">
                <div v-for="item in transactionList" :key="item" class="mission-inserted-list">
                    <div class="mission-content">
                        <div class="chk-div">
                            <input v-model="checkboxItem" type="checkbox" id="checkbox" class="checkbox" :value="item.transaction_id" name="checkbox">
                        </div>
                        <p @click="goSpendDetail(item.transaction_id)" class="title">{{ getTruncatedTitle(item.title) }}</p> 
                        <p class="category">{{ getCategoryText(item.category) }}</p>
                        <p class="charge">{{ item.amount.toLocaleString() }}원</p>
                        <p class="transaction-date">{{ item.transaction_date }}</p>
                    </div>
                </div>
            </div>
            <div class="for-buttons">
                <button @click="delOpenModal" class="btn-bottom mission-go-back">지출 삭제</button>
                <button @click="goSpendCreate" class="btn-bottom mission-insert">지출 작성</button>
            </div>
        </div>
    </div>
    <!-- ************************* -->
    <!-- ********삭제 모달********* -->
    <!-- ************************* -->
    <div class="del-modal-black" v-show="delModal">
        <div class="del-modal-white">
            <div class="modal-content">
                <img src="/img/icon-trash.png" class="modal-img" alt=".">
                <div class="del-guide">선택한 {{(checkboxItem).length}} 개의 지출이 </div>
                <div class="del-guide">삭제됩니다.</div>
            </div>
            <div class="del-btn">
                <button @click="delCloseModal" class="modal-cancel">취소</button>
                <button @click="deleteCheckedSpend()" class="modal-del">지출 삭제</button>
            </div>
        </div>
    </div>
</template>

<script setup>

import { computed, onMounted, ref } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

// 마운트트
onMounted(() => {
    store.dispatch('childTransaction/transactionList');
});

// 거래 리스트 가져오기
const transactionList = computed(() => store.state.childTransaction.childTransactionList);

const getCategoryText = (category) => {
    const categoryMapping = {
        0: '교통비',
        1: '식비',
        2: '쇼핑',
        3: '기타',
    };
    return categoryMapping[category]; // 기본값 없이 반환
};

// 특정 글자 길이 이후 '...'으로 표기
const maxLength = 20;

const getTruncatedTitle =(title) => {
  return title.length > maxLength 
    ? title.substring(0, maxLength) + '...' 
    : title;
};

// 거래 아이디 확인 후 상세 페이지 이동
const goSpendDetail = (transaction_id) => {
    console.log('거래 아이디 확인', transaction_id);
    store.dispatch('childTransaction/showTransactionDetail', transaction_id);
}

// 자녀 아이디 확인 후 작성 페이지로 이동
const goSpendCreate = () => {
    store.dispatch('childTransaction/goSpendCreate');
}

// ************** 체크 박스 ******************
// 모든 체크박스가 선택되었는지 확인 (computed : 반응형 데이터로 다루기 위해)
const isAllChecked = computed(() => {
    return checkboxItem.value.length > 0 && transactionList.value.every((item) => checkboxItem.value.includes(item.mission_id));
});

//체크박스 모두 선택/ 모두해제
const checkAll = (e) => {
    if(e.target.checked) {
        checkboxItem.value = transactionList.value.map(item => item.transaction_id);
        console.log('체크박스 모두 선택');
        console.log('체크박스 선택된 데이터 : ', transactionList.value.length);
    } else {
        checkboxItem.value = []; //전체 해제
        console.log('체크박스 모두 해제');
    }
}

const checkboxItem = ref([]);
console.log('체크박스 선택된 데이터 : ', checkboxItem.value);

// 체크된 지출만 삭제 처리 하기
const deleteCheckedSpend = () => {
    if(checkboxItem.value.length === 0) {
        alert("삭제할 지출을 선택하세요");
        console.log("삭제할 지출 ",checkboxItem.value);
        return;
    }
    store.dispatch('childTransaction/deletcheckedTransaction', checkboxItem.value);
    delModal.value = false;
    
    store.dispatch('childTransaction/transactionList'); //삭제후 지출 리스트 새로 불러오기
    checkboxItem.value = [];

}

// *****삭제 모달창********** 
const delModal = ref(false);

const delOpenModal = () => { //모달창 열기
    if(checkboxItem.value.length === 0) {
        alert("선택하신 지출이 없습니다.");
        return;
    }
    else {
        delModal.value = true;

    }
}

const delCloseModal = () => { //모달창 닫기
    delModal.value = false;
}

</script>

<style scoped>
.container {
    margin-left: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    /* padding-bottom: 40px; */
}

.list-container {
    margin-top: 20px;
    width: 1500px;
    height: 720px;
    background-color: white;
    display: flex;
    flex-direction: column;
    gap: 20px;
    justify-content: center;
    align-items: center;   
}

.mission-title {
    display: grid;
    grid-template-columns: 70px 210px 400px 150px 300px;  
    height: 60px;
    gap: 75px;
    background-color: #F5F5F5;
    font-size: 2rem;
    margin: 10px;
    align-items: center;
    width: 1420px;
    text-align: center;
}

.checkbox {
    margin: 15px;
    width: 16px;
    height: 16px;
}

.mission-content {
    display: grid;
    grid-template-columns: 48px 225px 400px 150px 300px;
    min-height: 60px;
    gap: 75px;
    font-size: 1.3rem;
    margin: 10px;
    align-items: center;
    width: 1400px;
    text-align: center;
}

.title {
    cursor: pointer;
}

.title:hover {
    color: #5589e996;
}

.for-buttons{
    display: flex;
    justify-content: right;
    gap: 30px;
    margin-left: 1170px;
    height: 60px;
}

.btn-top {
    width: 120px;
    height: 50px;
    font-size: 1.5rem;
    border: none;
    background-color:#5589e996;
    margin-top: 30px;
}

.btn-bottom {
    width: 120px;
    height: 50px;
    font-size: 1.5rem;
    border: none;
    color: white;
    background-color:#5589e996;
    margin: 30px 0;
    cursor: pointer;
}

#checkbox9 {
    margin: 15px;
}

.who-div {
    margin-right: 1150px;
}

.who {
    font-size: 1.5rem;
}

.mission-inserted-list {
    height: 60px;
    display: grid;
}
.scroll {
    overflow-y: auto;
    overflow-x: hidden;
    height: 420px;
    background-color: #F5F5F550;
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
    border-radius: 50px;
}

/* 삭제 모달 버튼 */
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