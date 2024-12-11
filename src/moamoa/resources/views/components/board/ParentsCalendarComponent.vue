<template>
    <div class="cal-container">
        <div class="nav-section">
            <div class="select-kids">
                <img class=selected-kid src="/img/icon-boy-1.png" alt="" width="90px" height="90px">
                <img class="not-selected" src="/img/icon-boy-5.png" alt="" width="90px" height="90px">
                <img class="not-selected" src="/img/icon-girl-1.png" alt="" width="90px" height="90px">
                <img class="not-selected" src="/img/icon-girl-3.png" alt="" width="90px" height="90px">
            </div>
            <div class="blank"></div>
            <div  class="btn-box">
                <button class="content-btn" :class="{ active: showHistory }"  @click="showHistory = true">내역</button>
                <button class="data-btn" :class="{ active: !showHistory }"  @click="showHistory = false">통계</button>
            </div>
            <div v-show="showHistory" class="money-history" >
                <div class="money-title">
                    <li>식비</li>
                    <li>교통비</li>
                    <li>쇼핑</li>
                    <li>교통비</li>
                    <li>보너스</li>
                    <li>미션</li>
                    <li>총합</li>
                </div>
                <div class="money-cost">
                    <ul>
                        <li class="cost">7,000원</li>
                        <li class="cost">15,000원</li>
                        <li class="cost">12,000원</li>
                        <li class="cost">8,000원</li>
                        <li class="cost">15,000원</li>
                        <li class="cost">40,000원</li>
                        <li class="cost">6,000원</li>
                    </ul>
                </div>
            </div>
            <div v-show="!showHistory" class="data-chart">
                <p>차트표시</p>
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
                                <p class="display">{{ formattedDate }}</p>
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
                            {{ day }} 
                            <p class="minus">-5,000</p>
                            <p class="plus">+3,000</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup>

const showHistory = ref(true);


import { ref, computed } from "vue";

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

// 이전 월로 이동
function prevMonth() {
  const currentDate = new Date(dateToday.value);
  currentDate.setMonth(currentDate.getMonth() - 1);
  dateToday.value = currentDate; // 새로운 객체로 업데이트
}

// 다음 월로 이동
function nextMonth() {
  const currentDate = new Date(dateToday.value);
  currentDate.setMonth(currentDate.getMonth() + 1);
  dateToday.value = currentDate; // 새로운 객체로 업데이트
}



</script>


<style>
.cal-container {
    width: 93%;
    display: flex;

    gap: 30px;
    margin-top: 20px;
    margin-left: 50px;
}

.nav-section {
    background-color: white;
    height: 750px;
}

.selected-kid {
    border: 3px solid #ffBDD0;
    background-color: white;
    border-radius: 50px;
    padding: 3px;
}

.select-kids {
    margin-top: 30px;
    margin-left: 10px;
    display: flex;
    gap: 20px;
}
.not-selected {
    border: 3px solid #cacaca;
    background-color: #cacaca;
    border-radius: 50px;
    padding: 3px;
}

.blank {
    height: 40px;
}

.btn-box {
    display: flex;
    justify-content: left;

}

.content-btn{
    width: 140px;
    height: 50px;
    margin-left: 20px;
    line-height: 50px;
    font-size: 2rem;
    border: none;
    color: white;
    background-color: #B0BFC1;
}

.data-btn  {
    width: 140px;
    height: 50px;
    line-height: 50px;
    font-size: 2rem;
    border: none;
    color: white;
    background-color: #B0BFC1;
}

li {
    height: 50px;
    font-size: 1.5rem;
    margin-left: 30px;
    line-height: 50px;
    margin-top: 20px;
}


.money-history {
    width: 400px;
    height: 520px;
    margin-left: 20px;
    margin-right: 20px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    font-size: 1.5rem;
    line-height: 50px;
    background-color: #E2F1F3;
}

.data-chart {
    width: 400px;
    height: 520px;
    margin-left: 20px;
    margin-right: 20px;
    background-color: #E2F1F3;
}

.cost  {
    list-style-type: none;
    text-align: right;
    padding-right: 50px;
}



:root {
    --white: white;
    --main: #A4D8E1;
    --accent: #cacaca;
    --accent-2: #ffbdd0;
}

.sec-container {
    /* display: flex; */
    height: 700px;
    justify-content: center;
    align-items: center;
    background: var(--white);
}

.cal-sec-container {
    width: 1290px;
    height: 750px;
    background: var(--white);   
    padding: 0 1em;
}

.sec-header {
    /* position: sticky; */
    height: 115px;
    line-height: 115px;
    display: flex;
    justify-content: space-between;
    padding: 10px ;
}

.header-display {
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 3rem;
    color: var(--accent-2);
    word-spacing: 0.5rem;
}

pre {
    padding: 10px;
    font-size: 1.2rem;
    color: var(--accent-2);
    cursor: pointer;
}

.days{
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    padding: 0 20px;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    div{
        text-align: center;
        font-size: 2rem;
        width: 168px;
        height: 130px;
        opacity: 0.5;
    }
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
        border-bottom: 7px double #ffBDD0;
    }
}

.days div{
    
    border-radius: 50%;
    opacity: 1;
    height: 95px;
    /* &:hover {
        background: var(--accent-2);
        color: #737373;
        cursor: pointer;
        
    } */
}

.display-selected {
    text-align: center;
    padding: 20px;
    margin-top: 10px;
}

.current-date {
    background: var(--accent);
    color: var(--white);
} 

.right, .left {
    font-size: 3rem;
    line-height: 3rem;
    line-height: 95px;
}

.minus {
    color: red;
    font-size: 0.9rem;
}

.plus {
    color: blue;
    font-size: 0.9rem;
}

.active {
  background-color: #E2F1F3; /* 활성화된 버튼 배경색 */
  color: black;
}

.money-title, .money-cost {
    margin-top: 15px;
}
</style>