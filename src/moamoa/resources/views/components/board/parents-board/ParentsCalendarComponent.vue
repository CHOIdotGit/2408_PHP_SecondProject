<template>
    <div class="cal-container">    
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
                        <div v-for="day in daysInMonth" :key="day" class="day">
                            <p @click="delOpenModal" :class="{ 'circle-class': isToday(day) }">
                                {{ day }}
                            </p>
                            <p class="minus">{{ getDailyIncomeExpense(day, dailyOutgoData, false) }}</p>
                            <p class="plus">{{ getDailyIncomeExpense(day, dailyIncomeData, true) }}</p>
                            
                        </div>
                    </div>
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
                    <p class="due-date">작성일자</p>
                </div>
                <div class="mission-inserted-list">
                    <div class="modal-mission-content">
                        <p class="mission-name">설거지</p>
                        <p class="expense-type">집안일</p>
                        <p class="inout-come income">수입</p>
                        <p class="charge">5,000</p>
                        <p class="due-date">2024.12.17</p>
                    </div>
                    <div class="mission-content">
                        <div class="modal-mission-content">
                            <p class="mission-name">올리브영가서 세일하길래 쿠션삼</p>
                            <p class="expense-type">쇼핑</p>
                            <span class="inout-come outgo">지출</span>
                            <p class="charge">32,000</p>
                            <p class="due-date">2024.12.17</p>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="del-btn">
                    <button @click="delCloseModal" class="modal-cancel">취소</button>
                </div>
            </div>
        </div>


</template>


<script setup>

import { ref, computed, reactive, onBeforeMount } from "vue";
import axios from "axios";
import { useStore } from "vuex";
import { useRoute } from "vue-router";

const route = useRoute();
const store = useStore();
const dailyIncomeData = computed(() => store.state.calendar.calendarInfo.dailyIncomeData);
const dailyOutgoData = computed(()=> store.state.calendar.calendarInfo.dailyOutgoData);


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
  await store.dispatch("calendar/parentCalendarInfo", {date:dateToday.value, child_id:route.params.child_id});
  dateToday.value = currentDate;
}

// 다음 월로 이동
async function nextMonth() {
  const currentDate = new Date(dateToday.value.setMonth(dateToday.value.getMonth() + 1));
  await store.dispatch("calendar/parentCalendarInfo", {date:dateToday.value, child_id:route.params.child_id});
  dateToday.value = currentDate;
}

// 모달 상태 관리
const delModal = reactive({ isOpen: false });

const delOpenModal = () => {
  delModal.isOpen = true;
};

const delCloseModal = () => {
  delModal.isOpen = false;
};

// -----------------------



onBeforeMount(() => {
    store.dispatch("calendar/parentCalendarInfo", {date:dateToday.value, child_id:route.params.child_id});
  });


// 각 날짜에 맞는 값입력
function getYearMonth(day) {
  return `${dateToday.value.getFullYear()}-${String(dateToday.value.getMonth() + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
}

function getDailyIncomeExpense(day, data, incomFlg) {
    const item = data.find(item => item.target_at === getYearMonth(day));
    console.log('item', item);
    const symbol = incomFlg ? '+' : '-';
    if(item) {
        const money = incomFlg ? item.income : item.outgo;
        return item ? symbol + Number(money).toLocaleString() : '';
    }
    // return item ? symbol + Number(item.income).toLocaleString() : '';
}

</script>


<style scoped>

.cal-container {
    /* width: 93%; */
    display: flex;
    gap: 30px;
    margin-top: 20px;
    margin-left: 50px;
}

.sec-container {
    /* display: flex; */
    height: 700px;
    justify-content: center;
    align-items: center;
    background-color: rgb(255, 255, 255);
}

.cal-sec-container {
    width: 1500px;
    height: 765px;
    margin: auto;    
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
    width: 270px;
    background-color: rgba(255, 255, 255, 0);  
    word-spacing: 0.5rem;
}

.days{
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    padding: 0 20px;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    padding: auto;

}

.week{
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    padding: 0 20px;
    justify-content: space-between;
    align-items: center;
    margin-top: 10px;
    div{
        /* margin-bottom: 10px;  */
        height: 55px;
        font-size: 2rem;
        text-align: center;
        line-height: 55px;
        border-bottom: 7px double #a2caac;
    }
}

.days div{
    /* padding: auto; */
    text-align: center;
    font-size: 2rem;
    /* width: 168px; */
    height: 95px;
    opacity: 0.5;
    border-radius: 50%;
    opacity: 1;
    /* height: 110px; */
    
}

.minus {
    color: red;
    font-size: 0.9rem;
}

.plus {
    color: blue;
    font-size: 0.9rem;
}

.left , .right {
    color: #a2caac;
    font-size: 3rem;
    cursor: pointer;

}

.circle-class {
    width: 50px; 
    height: 50px;
    border-radius: 50%;
    display: inline-block; /* 원 크기 유지 */
    text-align: center; /* 텍스트 가운데 정렬 */
    line-height: 40px; /* 텍스트를 원 안의 중앙에 위치 */
    box-sizing: border-box; /* 테두리 포함 */
    background-color: #a2caacb0;
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
    border: 3px solid #a2caac;
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
    border: 3px solid #a2caac;
    border-radius: 50px;
    padding: 3px;
}



/* 버튼 */
.modal-cancel {
    color: #a2caac;
    background-color: #FFFFFF;
    font-size: 1.5rem;
    border: 1px solid #a2caac;
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