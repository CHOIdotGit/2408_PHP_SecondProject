<template>
    <div class="container">
        <div class="list-container">
            <div class="route"> 홈  > 지출 </div>
            <div class="c-for-buttons">
                <button @click="delOpenModal" class="c-btn c-mission-delete">지출 삭제</button>
                <button @click="goSpendCreate" class="c-btn c-mission-insert">지출 등록</button>
            </div>
            <div class="search-menu">
                <div class="search-option">
                    <div class="search-date">
                        <p>⦁ 지출 일자</p> 
                        <input type="date" min="2000-01-01" v-model="filters.date">

                    </div>
                    <div class="search-filter">
                        <p>⦁ 지출 종류 </p> 
                        <select name="spend-type" v-model="filters.category">
                            <option value="">전체</option>
                            <option value="0">교통비</option>
                            <option value="1">식비</option>
                            <option value="2" >쇼핑</option>
                            <option value="3">기타</option>
                        </select>
                    </div>
                    <div class="search">
                        <input type="search" v-model="filters.keyword" placeholder="검색어를 입력해주세요">
                    </div>
                </div>
                <div class="search-btn">
                    <button @click="search">검색</button>
                </div>
            </div>
            <div class="mission-title">
                <div class="chk-div">
                    <input type="checkbox" id="checkbox" class="checkbox" name="checkAll" @change="checkAll" :checked="isAllChecked">
                </div>
                <p class="mission-type">제목</p>
                <span class="status">종류</span>
                <p class="charge">금액</p>
                <p class="due-date">지출 일자</p>
            </div>
            <div class="scroll">
                <div v-for="item in transactionList" :key="item" class="mission-inserted-list">
                    <div class="mission-content">
                        <div class="chk-div">
                            <input v-model="checkboxItem" type="checkbox" id="checkbox" class="checkbox" :value="item.transaction_id" name="checkbox">
                        </div>
                        <p @click="goSpendDetail(item.transaction_id)" class="content-title">{{ item.title }}</p> 
                        <p class="category">{{ getCategoryText(item.category) }}</p>
                        <p class="charge">{{ Number(item.amount).toLocaleString() }}원</p>
                        <p class="transaction-date">{{ item.transaction_date }}</p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    <!-- 페이지네이션 UI by 최상민 -->
    <div class="pagination">
        <!-- 이전 버튼 -->
        <button v-if="currentPage > 1"
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
        <button v-if="currentPage < lastPage"
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
                <button @click="delCloseModal" class="modal-close">취소</button>
                <button @click="deleteCheckedSpend()" class="modal-del">지출 삭제</button>
            </div>
        </div>
    </div>
    <!-- ************************* -->
    <!-- ********안내 모달********* -->
    <!-- ************************* -->
    <div class="base-modal-overlay" v-show="infoModal">
    <div class="base-modal-box">
      <div class="base-modal-content">
        <div>삭제할 미션을 선택하세요</div>
      </div>

      <div class="base-modal-btn">
        <button type="button" class="base-modal-submit" @click="delCloseModal">확인</button>
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

// const getTruncatedTitle =(title) => {
//   return title.length > maxLength 
//     ? title.substring(0, maxLength) + '...' 
//     : title;
// };

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
        // alert("삭제할 지출을 선택하세요");
        infoModal.value = true;
        return;
    }
    store.dispatch('childTransaction/deletcheckedTransaction', checkboxItem.value);
    delModal.value = false;
    
    store.dispatch('childTransaction/transactionList'); //삭제후 지출 리스트 새로 불러오기
    checkboxItem.value = [];

}

// ****************************
// *******모달창 설정***********
// ****************************
// 안내 모달창
const infoModal = ref(false);

// 삭제 모달창
const delModal = ref(false);

const delOpenModal = () => { //모달창 열기
    if(checkboxItem.value.length === 0) {
        infoModal.value = true;
        return;
    }
    else {
        delModal.value = true;
    }
}

const delCloseModal = () => { //모달창 닫기
    delModal.value = false;
    infoModal.value = false;
}

// +=================+
// +    검색 필터     +
// +=================+
// 기본값
const today = ref(new Date().toISOString().slice(0, 10));

console.log('날짜타입', typeof(today));
const filters = ref({
    category: "",
    date: today,
    keyword: "",
});

const search = () => {
    console.log(filters.value.date);
    store.dispatch('childTransaction/transactionSearch', filters.value);
};

</script>

<style scoped>
@import url(../../../../css/childboardCommon.css);

.container {
    display: flex;
    justify-content: center;
    align-items: center;
}

.c-mission-delete {
    margin-left: 1130px;
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

/* 금액 : 오른쪽 정렬 */
.mission-content>.charge {
    text-align: end;
    padding-right: 30px;
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
.modal-close {
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

/* ********** */
/* 안내 모달 */
/* ********** */
  /* 버튼 손모양 */
  button {
    cursor: pointer;
  }

  /* 뒷배경 */
  .base-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
  }

  /* 모달 박스 */
  .base-modal-box {
    background-color: #fff;
    padding: 25px;
    border-radius: 10px;
    width: 430px;
    height: 330px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    border: 3px solid #A2CAAC;
  }

  /* 각 넓이 설정 */
  .base-modal-content, .base-modal-btn {
    width: 100%;
    font-size: 1.6rem;
    text-align: center;
    line-height: 227px;
  }

  /* 버튼 중앙 정렬 */
  .base-modal-btn {
    display: flex;
    justify-content: center;
    align-items: center;
    column-gap: 75px;
  }

  /* 각 버튼 설정 */
  .base-modal-btn > button {
    padding: 12px 40px;
    border: none;
    border-radius: 5px;
    font-size: 1.1rem;
  }

  /* 확인 버튼 */
  .base-modal-submit {
    background-color: #A2CAAC;
    color: #fff;
  }

  /* 취소 버튼 */
  .base-modal-cancel {
    background-color: #F3F3F3;
  }
</style>