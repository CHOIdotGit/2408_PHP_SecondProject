<template>
    <div class="cal-container" v-if="calendarData">
        <div class="nav-section">
            <div class="select-kids">
                <img class=selected-kid :src="calendarData.profile" alt="" width="120px" height="120px">
                <div class="name-plate">
                    <p class="name-plate-name"> {{ calendarData.name }}</p>
                    <p class="name-plate-nickname">{{ calendarData.nick_name}}</p>
                </div>    
            </div>
            
            <div class="money-history" >
                <div class="money-title">
                    <ul>
                        <li class="cost-title">교통비</li>
                        <li class="cost-title">식비</li>
                        <li class="cost-title">쇼핑</li>
                        <li class="cost-title">기타</li>
                        <li class="cost-title">미션</li>
                        <li class="cost-title">총합</li>
                    </ul>
                </div>
                <div class="money-cost">
                    <ul>
                        <li class="cost">{{ Number(sidebarData.traffic).toLocaleString() }} 원</li>
                        <li class="cost">{{ Number(sidebarData.meal).toLocaleString() }} 원</li>
                        <li class="cost">{{ Number(sidebarData.shopping).toLocaleString() }} 원</li>
                        <li class="cost">{{ Number(sidebarData.etc).toLocaleString() }} 원</li>
                        <li class="cost" >{{ Number(sidebarMission).toLocaleString() }} 원</li>
                        <li class="cost" >{{ totalAmount.toLocaleString() }} 원</li>
                    </ul>
                </div>
            </div>
            
        </div>
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
            </div>
        </div>
    </div>
    <div v-else>
        <p>Loading...</p>
    </div>

    <!-- ************************* -->
    <!-- ********상세 모달********* -->
    <!-- ************************* -->
    <!-- <div class="del-modal-black" v-show="delModal.isOpen">
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
                    <div v-for="item in transactionsOnDay" :key="item" class="modal-mission-content">
                        <p class="mission-name">{{ item.title }}</p>
                        <p class="expense-type">{{ item.category }}</p>
                        <p class="inout-come income">{{ item.transaction_code }}</p>
                        <p class="charge">{{ item.amount }}</p>
                        <p class="due-date">{{ item.transaction_date }}</p>
                    </div>
                </div>
            </div>
            <div class="del-btn">
                <button @click="closeModalTransactionOnDay" class="modal-cancel">닫기</button>
            </div>
        </div>
    </div> -->
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
    await store.dispatch("calendar/calendarInfo", dateToday.value);
    dateToday.value = currentDate;
}

// 다음 월로 이동
async function nextMonth() {
    const currentDate = new Date(dateToday.value.setMonth(dateToday.value.getMonth() + 1));
    await store.dispatch("calendar/calendarInfo", dateToday.value);
    dateToday.value = currentDate;
}
// 모달 관련 --------------------------------------------------------------------
// 모달 상태 관리
// const delModal = reactive({ isOpen: false });

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

// transactions 데이터 가져오기
const transactionsOnDay = computed(() => store.state.calendar.transactionsOnDay);

// -----------------------

onBeforeMount(() => {
    store.dispatch("calendar/calendarInfo", dateToday.value);
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