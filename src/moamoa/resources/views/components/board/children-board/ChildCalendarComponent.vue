<template>   
    <div class="cal-sec-container">
        <div class="sec-container">
            <div class="calendar">
                <div class="sec-header">
                <!-- 이전/다음 버튼 -->
                    <pre class="left" @click="prevMonth">◀</pre>
                        <div class="header-display">
                            <!-- 현재 연월 표시 -->
                            <p class="date-display">{{ formattedDate }}</p>
                        </div>
                    <pre class="right" @click="nextMonth">▶</pre>
                </div>
                <div class="week">
                    <div style="color: red;">일</div>
                    <div>월</div>
                    <div>화</div>
                    <div>수</div>
                    <div>목</div>
                    <div>금</div>
                    <div style="color: blue;">토</div>
                </div>
                <div class="days">
                    <!-- 시작 요일 빈 칸 -->
                    <div v-for="n in startDay" :key="'empty-' + n" class="day empty"></div>
                    <!-- 날짜 표시 -->
                        <div v-for="day in daysInMonth" :key="day" class="day" >
                            <p @click="openModal(day)" :class="{ 'circle-class': isToday(day) }">{{ day }}</p>
                            <p class="minus">{{ getDailyIncomeExpense(day, dailyOutgoData, false) }}</p>
                            <p class="plus">{{ getDailyIncomeExpense(day, dailyIncomeData, true) }}</p>
                            <!-- '2024-12-' + String(i).padStart(2,'0')); -->
                        </div>
                    </div>
                </div>
                <div class="money-summary">
                    <p class="summary-title">항목별 합계</p>
                    <div class="section-sum">
                        <div class="money-history traffic">
                            <p class="cost-title">교통비</p>
                            <p class="cost">{{ Number(sidebarData.traffic).toLocaleString() }} 원</p>
                        </div>
                        <div class="money-history meal">
                            <p class="cost-title">식비</p>
                            <p class="cost">{{ Number(sidebarData.meal).toLocaleString() }} 원</p>
                        </div>
                        <div class="money-history shopping">    
                            <p class="cost-title">쇼핑</p>
                            <p class="cost">{{ Number(sidebarData.shopping).toLocaleString() }} 원</p>
                        </div>
                        <div class="money-history etc">    
                            <p class="cost-title">기타</p>
                            <p class="cost">{{ Number(sidebarData.etc).toLocaleString() }} 원</p>
                        </div>
                        <div class="money-history missions">    
                            <p class="cost-title">미션</p>
                            <p class="cost" >{{ Number(sidebarMission).toLocaleString() }} 원</p>
                        </div>
                    </div>
                    <div class="money-history total">    
                        <p class="cost-title">총합</p>
                        <p class="cost" >{{ totalAmount.toLocaleString() }} 원</p>
                    </div>
                </div>
            </div>
        </div>
                    


    

    <!-- ************************* -->
    <!-- ********상세 모달********* -->
    <!-- ************************* -->
    <div class="del-modal-black" v-show="delModal.isOpen">
        <div class="del-modal-white">
            <div class="modal-list-container">
                <div class="modal-mission-title">
                    <p class="mission-name">제목</p>
                    <p class="expense-type">종류</p>
                    <p class="inout-come">분류</p>
                    <p class="charge">금액</p>
                    <p class="due-date">완료일자</p>
                </div>
                <div class="mission-inserted-list">
                    <div v-for="item in transactionsAndMissions" :key="item" class="modal-mission-content">
                        <p class="mission-name">{{ item.title }}</p>
                        <p class="expense-type">{{ getCategoryText(item.category) }}</p>
                        <p class="inout-come income">{{ getCodeText(item.transaction_code) }}</p>
                        <p class="charge">{{ Number(item.amount).toLocaleString() }}</p>
                        <p class="due-date">{{ new Date(item.updated_at).toISOString().split('T')[0] }}</p>
                    </div>
                </div>
            </div>
            <div class="del-btn">
                <button @click="closeModalTransactionOnDay" class="modal-cancel">닫기</button>
            </div>
        </div>
    </div>
</template>
<script setup>

import { ref, computed, reactive, onBeforeMount } from "vue";
import { useStore } from "vuex";

const store = useStore();
const calendarData = computed(() => store.state.calendar.calendarInfo.calendarData);
const sidebarData = computed(()=> store.state.calendar.calendarInfo.sidebarData);
const sidebarMission = computed(() => store.state.calendar.calendarInfo.sidebarMission);
const dailyIncomeData = computed(() => store.state.calendar.calendarInfo.dailyIncomeData);
const dailyOutgoData = computed(()=> store.state.calendar.calendarInfo.dailyOutgoData);

const totalAmount = computed(() => {
    return (
        (Number(sidebarMission.value) || 0) - (
            (Number(sidebarData.value.traffic) || 0) +
            (Number(sidebarData.value.meal) || 0) +
            (Number(sidebarData.value.shopping) || 0) +
            (Number(sidebarData.value.etc) || 0)
        )
    );
});

// 현재 날짜 상태 관리
const dateToday = ref(new Date());

// 현재 연월 표시 (반응형 데이터)
const formattedDate = computed(() =>
    dateToday.value.toLocaleString("ko-KR", {
        year: "numeric",
        month: "long",
        timeZone: "Asia/Seoul",
    })
);


// 해당 월의 시작 요일 계산
const startDay = computed(() => {
    const firstDayOfMonth = new Date(dateToday.value.getFullYear(), dateToday.value.getMonth(), 1);
    return firstDayOfMonth.getDay(); // 0: 일요일, 1: 월요일, ..., 6: 토요일
});

// 해당 월의 일수 계산
const daysInMonth = computed(() => {
    const year = dateToday.value.getFullYear();
    const month = dateToday.value.getMonth();
    return new Array(new Date(year, month + 1, 0).getDate()).fill(null).map((_, i) => i + 1);
});

// 오늘 날짜인지 확인하는 함수
function isToday(day) {
    const today = new Date();
    const year = dateToday.value.getFullYear();
    const month = dateToday.value.getMonth();
    return today.getFullYear() === year && today.getMonth() === month && today.getDate() === day;
}

// 이전 월로 이동
async function prevMonth() {
    const currentDate = new Date(dateToday.value.setMonth(dateToday.value.getMonth() - 1));
    await store.dispatch("calendar/childCalendarInfo", dateToday.value);
    dateToday.value = currentDate;
}

// 다음 월로 이동
async function nextMonth() {
    const currentDate = new Date(dateToday.value.setMonth(dateToday.value.getMonth() + 1));
    await store.dispatch("calendar/childCalendarInfo", dateToday.value);
    dateToday.value = currentDate;
}
// 모달 관련 --------------------------------------------------------------------
// 모달 상태 관리
const delModal = reactive({ isOpen: false });

const openModalTransactionOnDay = () => {
    delModal.isOpen = true;
};

const closeModalTransactionOnDay = () => {
    delModal.isOpen = false;
};

// 날짜 클릭 시 모달 열기 및 데이터 필터링
function openModal(day) {
    store.dispatch('calendar/transactionsOnDay', getYearMonth(day));
    openModalTransactionOnDay();
}

// // transactions 데이터 가져오기
// const transactionsOnDay = computed(() => store.state.calendar.transactionsOnDay);

// // missions 데이터 가져오기
// const missionList = computed(() => store.state.calendar.missionList);

// transactionsOnDay와 missionList 병합
const transactionsAndMissions = computed(() => {
  const transactionsOnDay = store.state.calendar.transactionsOnDay;
  const missionList = store.state.calendar.missionList;
  
  return [...transactionsOnDay, ...missionList];
});

// 카테고리 변환
const getCategoryText = (category) => {
    const categoryMapping = {
        0: '교통비',
        1: '식비',
        2: '쇼핑',
        3: '기타',
    };
    return categoryMapping[category]; // 기본값 없이 반환
};

// 코드 변환
const getCodeText = (code) => {
    const codeMapping = {
        0: '용돈',
        1: '지출',
    };
    return codeMapping[code] || '용돈'; // 기본값 - 용돈
};
// -----------------------

onBeforeMount(() => {
    store.dispatch("calendar/childCalendarInfo", dateToday.value);
});


// 각 날짜에 맞는 값입력

// function getYearMonth(day) {
//     return `${dateToday.value.getFullYear()}-${String(dateToday.value.getMonth() + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
// }

// function getDailyIncomeExpense(day, data, incomFlg) {
//     const item = data.find(item => item.target_at === getYearMonth(day));
//     const symbol =incomFlg ? '+' : '-';
//     return item ? symbol + Number(item.income).toLocaleString() : '';
// }

// YYYY-MM-DD 형식의 날짜 반환
function getYearMonth(day) {
    return `${dateToday.value.getFullYear()}-${String(dateToday.value.getMonth() + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
}

// 일별 수입/지출 반환
function getDailyIncomeExpense(day, data, incomFlg) {
    const item = data.find(item => item.target_at === getYearMonth(day));
    // console.log('item', item);
    const symbol = incomFlg ? '+' : '-';
    if(item) {
        const money = incomFlg ? item.income : item.outgo;
        return item ? symbol + Number(money).toLocaleString() : '';
    }
    // return item ? symbol + Number(item.income).toLocaleString() : '';
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

.calendar {
    width: 950px;
    border-right: 4px solid #5589e996;
}

.money-history {
    background-color: #5589e996;
    display: flex;
    font-size: 1.2rem;
    width: 250px;
    height: 70px;
    gap: 30px;
    align-items: center;
}

.sec-container {
    width: 1300px;
    height: 700px;
    margin-left: 100px;
    justify-content: center;
    align-items: center;
    display: flex;
    gap: 50px;
}
.cal-sec-container {
    height: 720px;
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
    width: 910px;
    justify-content: space-between;
    align-items: center;
    /* padding-top: 15px; */
}

.summary-title {
    font-size: 1.5rem;
    font-weight: 700;
    margin: auto;
}

.week{
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    width: 910px;
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
    opacity: 1;
    height: 90px;
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

.money-summary {
    display: flex;
    gap: 20px;
    flex-direction: column;
}

.money-cost {
    height: 500px;
    height: 50px;
    font-size: 1.5rem;
    margin-left: 30px;
    line-height: 50px;
    margin-top: 20px;
}

.cost-title {
    margin-left: 30px;
    width: 65px;
    font-size: 1.3rem;
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
    height: 600px;
    background-color: #FFFFFF;
    border: 3px solid #5589e996;
    /* margin: 170px; */
    position: relative;
    flex-direction: column;
    overflow-y: auto;
    overflow-x: hidden;
    align-items: center;
    justify-content: center;

}

.cost {
    justify-content: center;
    align-items: end;
    width: 100px;
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
    margin-left: 360px;
    margin-bottom: 20px;
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