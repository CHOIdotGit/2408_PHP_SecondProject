<template>
    <!-- TODO: 모달 분리는 최후에 -->
    <div class="del-modal-black" v-show="delModal.isOpen">
        <div class="del-modal-white">
            <div class="modal-list-container">
                <div class="modal-mission-title">
                    <p class="mission-name">제목</p>
                    <p class="expense-type">종류</p>
                    <p class="inout-come">분류</p>
                    <p class="charge">금액</p>
                    <p class="due-date">작성일자</p>
                </div>
                <div class="mission-inserted-list">
                    <div v-if="transactions.length === 0" class="modal-mission-content">
                        <p>작성된 내용이 없습니다.</p>
                    </div>
                    <div v-else>
                        <div v-for="item in transactions" :key="item" class="modal-mission-content">
                            <p class="mission-name">{{ item.title }}</p>
                            <p class="expense-type">{{ item.category }}</p>
                            <p class="inout-come income">{{ item.transaction_code }}</p>
                            <p class="charge">{{ item.amount }}</p>
                            <p class="due-date">{{ item.transaction_date }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="del-btn">
                <button @click="delCloseModal" class="modal-cancel">닫기</button>
            </div>
        </div>
    </div>
</template>
<script setup>

import { ref, computed, reactive, onBeforeMount } from "vue";
import { useStore } from "vuex";

const store = useStore();


// 모달 상태 관리
const delModal = reactive({ isOpen: false });

// 모달 닫기
const delCloseModal = () => {
    delModal.isOpen = false;
};

// 현재 날짜를 기준으로 year와 month 계산
// 현재 날짜와 월을 동적으로 처리
const currentDate = new Date();
const currentYear = currentDate.getFullYear();
const currentMonth = currentDate.getMonth() + 1; // 월은 0부터 시작하므로 1을 더해줘야 함


// 모달 날짜별 데이터 들고오기
const transactions = computed(() => store.state.calendar.transactions);
console.log('transactions 상태:', Array.from(transactions.value));

// handleDayClick 함수 정의
const handleDayClick = (day) => {
    // year, month는 현재 날짜 기반으로 동적으로 가져옴
    const selectedDate = { year: currentYear, month: currentMonth, day };
    console.log('handleDayClick.selectedDate:', selectedDate);
    // Vuex 액션 호출 후 모달 열기
    store.dispatch('calendar/transactions', selectedDate)
    .then(response => {
        console.log('API 응답 데이터:', response.data); // 서버 응답 데이터 확인
        context.commit('setTransactions', response.data.transactions); // Vuex 상태 업데이트
    })
    .catch(error => {
        console.error('API 호출 오류:', error);
    });
    // store.dispatch('calendar/transactions', selectedDate)
    // .then(() => {
    //     delOpenModal(); // 데이터 갱신 후 모달 열기
    // });
    // delOpenModal(); // 모달 열기
};

// -----------------------

onBeforeMount(() => {
    store.dispatch("calendar/calendarInfo", dateToday.value);
    const selectedDate = { year: currentYear, month: currentMonth };
    console.log('onBeforeMount.selectedDate:', selectedDate); // 얘는 데이터 잘 받아 옴 -> 날짜는 잘 받아 옴
});


// 각 날짜에 맞는 값입력
function getYearMonth(day) {
  return `${dateToday.value.getFullYear()}-${String(dateToday.value.getMonth() + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
}

function getDailyIncomeExpense(day, data, incomFlg) {
    const item = data.find(item => item.target_at === getYearMonth(day));
    const symbol =incomFlg ? '+' : '-';
    return item ? symbol + Number(item.income).toLocaleString() : '';
}

</script>

<style scoped >
.cal-container {
    width: 93%;
    display: flex;
    gap: 30px;
    margin-top: 20px;
    margin-left: 50px;
}
.nav-section {
    background-color: white;
    height: 765px;
}
.selected-kid {
    margin-left: 30px;
    margin-top: 30px;
    margin-bottom: 30px;
    /* width: 80px; */
    border: 5px solid #5589e996; 
    background-color: white;
    border-radius: 50%;
    padding: 3px;
}



.money-history {
    width: 400px;
    height: 500px;
    margin-left: 20px;
    margin-right: 20px;
    margin-top: 20px;
    display: grid;
    grid-template-columns: 170px 200px;
    font-size: 1.5rem;
    line-height: 50px;
    background-color: #5589e996;
}

.cost-title {
    height: 50px;
    font-size: 1.5rem;
    margin-left: 30px;
    line-height: 50px;
    margin-top: 20px;
}

.cost  {
    list-style-type: none;
    text-align: right;
    /* padding-right: 50px; */
    height: 50px;
    font-size: 1.5rem;
    margin-left: 30px;
    line-height: 50px;
    margin-top: 20px;
}

.sec-container {
    height: 700px;
    justify-content: center;
    align-items: center;
    /* background: ; */
}
.cal-sec-container {
    width: 1290px;
    height: 765px;
    background-color: white;
    padding: 0 1em;
}
.sec-header {
    /* position: sticky; */
    gap: 30px;
    height: 100px;
    line-height: 100px;
    display: flex;
    justify-content: center;
    padding: 10px ;
}
.header-display {
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 3rem;
    color: black;
    word-spacing: 0.5rem;
}

.days{
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    padding: 0 20px;
    justify-content: space-between;
    align-items: center;
    /* padding-top: 15px; */
}

.week{
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    padding: 0 20px;
    justify-content: space-between;
    align-items: center;
    div{
        height: 55px;
        font-size: 2rem;
        text-align: center;
        line-height: 55px;
        border-bottom: 7px double #5589e996;
    }
}
.days div{
    text-align: center;
    font-size: 2rem;
    width: 130px;
    opacity: 1;
    height: 90px;
    margin-left: 20px;
    margin-top: 10px;
}


.right, .left {
    color: #5589e996;
    font-size: 3rem;
    cursor: pointer;
    
}
.minus {
    color: red;
    font-size: 0.9rem;
}
.plus {
    color: blue;
    font-size: 0.9rem;
}


.select-kids {
    display: flex;
    margin: 20px;
}

.name-plate {
    display: flex;
    flex-direction: column;
    width: 180px;
    margin-left: 50px;
    align-items: center;
    /* justify-content: space-around; */
}

.name-plate-name {
    margin-top: 30px;
    font-size: 3rem;
}

.name-plate-nickname {
    margin-top: 10px;
    font-size: 2rem;
}

.money-title {
    height: 500px;
    height: 50px;
    font-size: 1.5rem;
    margin-left: 30px;
    line-height: 50px;
    margin-top: 20px;
}

.money-cost {
    height: 500px;
    height: 50px;
    font-size: 1.5rem;
    margin-left: 30px;
    line-height: 50px;
    margin-top: 20px;
}
.circle-class {
    width: 50px; 
    height: 50px;
    border-radius: 50%;
    display: inline-block; /* 원 크기 유지 */
    text-align: center; /* 텍스트 가운데 정렬 */
    line-height: 40px; /* 텍스트를 원 안의 중앙에 위치 */
    box-sizing: border-box; /* 테두리 포함 */
    background-color: #5589e996;
    /* margin-bottom: 15px; */
    padding: 5px;
}

.date-display {
    margin-top: 15px;
}

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
    width: 800px;
    height: 500px;
    background-color: #FFFFFF;
    border: 3px solid #5589e996;
    /* margin: 170px; */
    position: relative;
    flex-direction: column;
    align-items: center;
    justify-content: center;

}

.modal-name {
    font-size: 1.3rem;
    padding: 10px;
}

.modal-mission-title {
    display: grid;
    grid-template-columns: 250px 100px 100px 100px 100px;  
    height: 40px;
    gap: 20px;
    background-color: #F5F5F5;
    font-size: 1rem;
    margin: 10px;
    align-items: center;
    width: 750px;
    text-align: center;
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
    color: #5589e996;
    background-color: #FFFFFF;
    font-size: 1.5rem;
    border: 1px solid #5589e996;
    padding: 5px;
    width: 100px;
    height: 50px;
    cursor: pointer;
    margin: 10px;
    position: absolute;
    top: 400px;
    right: 350px;
}

.modal-mission-content{
    display: grid;
    grid-template-columns: 250px 100px 100px 100px 100px;  
    min-height: 40px;
    gap: 20px;
    /* background-color: #F5F5F5; */
    font-size: 1rem;
    margin: 10px;
    align-items: center;
    width: 1400px;
    text-align: center;
    /* border-bottom: 2px solid black; */
}
</style>