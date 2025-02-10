<template>
    <div class="container">
        <div class="list-container">
            <div class="route"> 홈  > 지출 </div>
            <div class="for-buttons">
                <button @click="delOpenModal" class="btn mission-delete">지출 삭제</button>
                <button @click="goSpendCreate" class="btn mission-insert">지출 작성</button>
            </div>
            <div class="search-menu">
                <div class="search-option">
                    <div class="search-date">
                        <p> 등록 일자</p> 
                        <input type="date" min="2000-01-01" >
                        <p>~</p>
                        <input type="date" min="2000-01-01" >
                    </div>
                    <div class="search-filter">
                        <p> 종류 </p> 
                        <select name="mission-type">
                            <option value="woori">전체</option>
                            <option value="all">쇼핑</option>
                            <option value="shinhan">교통비</option>
                            <option value="kb" >식비</option>
                            <option value="hana">기타</option>
                        </select>
                    </div>
                    <div class="search">
                        <input type="search" placeholder="검색어를 입력해주세요">
                    </div>
                </div>
                <div class="search-btn">
                    <button>검색</button>
                </div>
            </div>
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
                        <p @click="goSpendDetail(item.transaction_id)" class="content-title">{{ getTruncatedTitle(item.title) }}</p> 
                        <p class="category">{{ getCategoryText(item.category) }}</p>
                        <p class="charge">{{ item.amount.toLocaleString() }}원</p>
                        <p class="transaction-date">{{ item.transaction_date }}</p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <!-- 페이지네이션 UI by 최상민 -->
    <div class="pagination">
        <!-- 이전 버튼 -->
        <button 
            class="paginate-btn" 
            @click="goToPrevious" 
            :disabled="currentPage === 1">
            < 이전
        </button>
        <!-- 페이지 번호 출력 4가 현재 페이지일때 (예: 1 ... 3 4 5 6) -->
        <span v-for="page in pageNumbers" :key="page" class="paginate-span">
            <!-- 페이지 번호 버튼 -->
            <button 
                v-if="page !== '...'" 
                class="paginate-btn" 
                @click="goToPage(page)" 
                :disabled="page === currentPage"
                :class="{'no-pointer': page === currentPage}"
            >
                {{ page }}
            </button>

            <!-- '...' 버튼 스타일을 위해 별도의 클래스 적용 -->
            <button 
                v-else 
                class="dots" 
                disabled
            >
                {{ page }}
            </button>
        </span>
        <!-- 다음 버튼 -->
        <button 
            class="paginate-btn" 
            @click="goToNext" 
            :disabled="currentPage === lastPage">
            다음 >
        </button>
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
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const route = useRoute();

// 마운트트
onMounted(() => {
    // store.dispatch('childTransaction/transactionList');
    store.dispatch('childTransaction/transactionList', {child_id: route.params.id, page: 1});
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

// ********** 페이지네이션 **********
const currentPage = computed(() => store.state.childTransaction.currentPage);
const lastPage = computed(() => store.state.childTransaction.lastPage);

// 페이지네이션을 위한 페이지 번호 배열 생성
const pageNumbers = computed(() => {
    const numbers = [];
    const range = 1; // 현재 페이지 앞뒤로 표시할 페이지 수

    // 첫 번째 페이지 추가
    if (currentPage.value > range + 1) {
        numbers.push(1);
        numbers.push('...');  // 첫 번째 페이지 앞에 '...'
    }

    // 페이지 번호를 배열에 추가 (예: 1 ... 3 4 5 6)
    for (let i = Math.max(currentPage.value - range, 1); i <= Math.min(currentPage.value + range, lastPage.value); i++) {
        numbers.push(i);
    }

    // 마지막 페이지가 포함되지 않으면 추가
    if (numbers[numbers.length - 1] !== lastPage.value) {
        if (currentPage.value < lastPage.value - range - 1) {
            numbers.push('...');  // 마지막 페이지 뒤에 '...'
        }
        numbers.push(lastPage.value);
    }

    // 마지막 페이지가 1이면 추가하지 않도록 설정
    if (lastPage.value === 1) {
        numbers.pop();
    }
    
    return numbers;
});

// 페이지 이동 함수
const goToPage = (page) => {
    if (page >= 1 && page <= lastPage.value) {
        store.dispatch('childTransaction/transactionList', { child_id: route.params.id, page });
    }
};

// 이전 페이지로 이동하는 함수
const goToPrevious = () => {
    if (currentPage.value > 1) {
        goToPage(currentPage.value - 1);
    }
};

// 다음 페이지로 이동하는 함수
const goToNext = () => {
    if (currentPage.value < lastPage.value) {
        goToPage(currentPage.value + 1);
    }
};

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
@import url('../../../../css/list.css');

.container {
    /* margin-left: 50px; */
    display: flex;
    justify-content: center;
    align-items: center;
    /* padding-bottom: 40px; */
}

.list-container {
    background-color: transparent;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.mission-title {
    width: 1400px;
    display: grid;
    grid-template-columns: 40px 450px 150px 150px 280px;  
    height: 60px;
    gap: 75px;
    background-color: #d1d1d1;
    font-size: 2rem;
    align-items: center;
    text-align: center;
}

.checkbox {
    margin: 15px;
    width: 16px;
    height: 16px;
}

.mission-content {
    display: grid;
    grid-template-columns:40px 450px 150px 150px 280px;
    width: 1400px;
    min-height: 60px;
    gap: 75px;
    font-size: 1.3rem;
    align-items: center;
    text-align: center;
}

.content-title {
    cursor: pointer;
}

.content-title:hover {
    color: #5589e996;
}

.btn {
    width: 120px;
    height: 50px;
    font-size: 1.2rem;
    border: none;
    background-color:#5589e996 ;
    margin-top: 30px;
    cursor: pointer;
    color: #FFFFFF;
}

.mission-delete {
    margin-right: 20px;
    margin-left: 1140px;
}
#checkbox9 {
    margin: 15px;
}



.mission-inserted-list {
    height: 60px;
    display: grid;
}
/* .scroll {
    overflow-y: auto;
    overflow-x: hidden;
    height: 420px;
    background-color: #F5F5F550;
} */

.search-btn button{
    width: 120px;
    height: 150px;
    margin-top: 20px;
    font-weight: 800;
    align-content: space-between;
    margin-left: 210px;
    background-color: #5589e996;
    font-size: 1.5rem;
    border: none;
    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);

}

.search-btn button:hover {
    color: #ffffff;
    background-color: #214c9c96;
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

/* 페이지네이션 css */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
}

.paginate-span {
    font-size: 1.3rem;
    font-weight: 600;
}

.paginate-btn {
    all: unset;
    cursor: pointer;
    font-size: 1.3rem;
}

.paginate-btn:hover {
    color: #5589e996;
}

.no-pointer {
    cursor: default;
    background-color: #5589e996;
    border-radius: 50%;
    text-align: center;
    width: 30px;
    height: 30px;
}

.no-pointer:hover {
    color: #000000;
}

.dots {
    /* cursor: default; */
    all: unset;
}
</style>