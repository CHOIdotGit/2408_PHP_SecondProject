<template>
    <div v-if="isMobile">
        <div class="m-container">
        <div class="m-header">
            <img src="/img/icon-girl-4.png" alt="" class="m-user-image">
            <p class="m-user-profile">김주연</p>
            <p class="go-update"> > </p>
        </div>
        <div class="m-child-select-section">
            <div class="m-search-section">
                <!-- 자식 선택은 스와이퍼추가 / 자식 수가 늘어나면 옆으로 넘길수 있도록
                    선택한 자식은 클래스 추가로 백그라운드 컬러만 m-main-content 에 추가해주시면됩니다 -->
                <p class="m-child-select menu-sec-first"> 배현진 </p>
                <p class="m-child-select"> 김현석 </p>
                <p class="m-child-select"> 최상민 </p>
                <p class="m-child-select"> 최상민 </p>
                <p class="m-child-select"> 최상민 </p>
                <p class="m-child-select"> 최상민 </p>
            </div>
            <img src="/img/m-search.png" alt="" class="m-search-button">
        </div>
        <div class="m-expense-list">
            <div class="calendar">
                    <div class="m-sec-header">
                    <!-- 이전/다음 버튼 -->
                        <pre class="m-left" @click="prevMonth">◀</pre>
                            <div class="m-header-display">
                                <!-- 현재 연월 표시 -->
                                <p class="date-display">{{ formattedDate }}</p>
                            </div>
                        <pre class="m-right" @click="nextMonth">▶</pre>
                    </div>
                    <div class="m-week">
                        <div style="color: red;">일</div>
                        <div>월</div>
                        <div>화</div>
                        <div>수</div>
                        <div>목</div>
                        <div>금</div>
                        <div style="color: blue;">토</div>
                    </div>
                    <div class="m-days">
                        <!-- 시작 요일 빈 칸 -->
                        <div v-for="n in startDay" :key="'empty-' + n" class="day empty"></div>
                        <!-- 날짜 표시 -->
                        <div v-for="day in daysInMonth" :key="day">
                            <p @click="openModal(day)" :class="{ 'm-circle-class': isToday(day) }">
                                {{ day }}
                            </p>
                            <p class="m-minus">{{ getDailyIncomeExpense(day, dailyOutgoData, false) }}</p>
                            <p class="m-plus">{{ getDailyIncomeExpense(day, dailyIncomeData, true) }}</p>
                        </div>
                    </div>
                </div>
        </div>
        
        <footer>
            <div class="m-footer-menu">
                <div class="m-menu">
                    <div class="m-menu-section" @click="router.push('/parent/home')">
                        <img src="/img/icon-home.png" alt="" class="m-home menu-sec-first">
                        <p class="m-menu-title   menu-sec-first"> 홈 </p>
                    </div>
                    <div class="m-menu-section" @click="router.push('/parent/mission/list/1')">
                        <img src="/img/icon-piggy-bank.png" alt="" class="m-mission">
                        <p class="m-menu-title"> 미션 </p>
                    </div>
                    <div class="m-menu-section" @click="router.push('/parent/spend/list/1')">
                        <img src="/img/icon-coin.png" alt="" class="m-expense">
                        <p class="m-menu-title"> 지출 </p>
                    </div>
                    <div class="m-menu-section" @click="goParentCalendar">
                        <img src="/img/icon-calendar.png" alt="" class="m-calendar">
                        <p class="m-menu-title"> 달력 </p>
                    </div>
                    <div class="m-menu-section" @click="router.push('/parent/moabank/1')">
                        <img src="/img/icon-sack-dollar.png" alt="" class="m-bank">
                        <p class="m-menu-title"> 모아통장 </p>
                    </div>
                    <div class="m-menu-section" >
                        <img src="/img/mobile-etc.png" alt="" class="m-etc">
                    </div>
                </div>
            </div>
        </footer>
    </div>  
</div>

    <div class="cal-container" v-else>    
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
                        <div v-for="day in daysInMonth" :key="day">
                            <p @click="openModal(day)" :class="{ 'circle-class': isToday(day) }" class="day">
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
    <ParentCalendarModalComponent v-if="isModalOpen" :selectedDate="selectedDay.value" @close="closeModal" />

</template>

<script setup>
import { ref, computed, reactive, onBeforeMount } from "vue";
import { useStore } from "vuex";
import { useRoute, useRouter } from "vue-router";
import ParentCalendarModalComponent from "../../modal/ParentCalendarModalComponent.vue";

const store = useStore();
const route = useRoute();
const router = useRouter();

const dailyIncomeData = computed(() => store.state.calendar.calendarInfo.dailyIncomeData);
const dailyOutgoData = computed(()=> store.state.calendar.calendarInfo.dailyOutgoData);


const isMobile = store.state.mobile.isMobile;

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
    const currentDate = new Date(dateToday.value);
    currentDate.setMonth(currentDate.getMonth() - 1);
    dateToday.value = currentDate; // dateToday를 바로 업데이트
    await store.dispatch("calendar/parentCalendarInfo", {date: dateToday.value, child_id: route.params.child_id});
}

// 다음 월로 이동
async function nextMonth() {
    const currentDate = new Date(dateToday.value);
    currentDate.setMonth(currentDate.getMonth() + 1);
    dateToday.value = currentDate; // dateToday를 바로 업데이트
    await store.dispatch("calendar/parentCalendarInfo", {date: dateToday.value, child_id: route.params.child_id});
}

// 모달 상태 관리
const isModalOpen = ref(false);
const selectedDate = ref(null);
const selectedDay = ref(''); // 클릭한 날짜를 저장할 상태

// 날짜 클릭 시 실행되는 함수
const openModal = async (day) => {
    selectedDay.value = getYearMonth(day);

    // day를 인자로 데이터를 불러오는 액션 실행
    await store.dispatch('calendar/parentCalendarModal', {child_id: route.params.child_id, strDate: selectedDay.value})
    
    // 모달 열기
    isModalOpen.value = true
}

const closeModal = () => {
    isModalOpen.value = false;
    // selectedDay.value = null; // 선택한 날짜 초기화 (필요시)
};

// -----------------------
onBeforeMount(() => {
    store.dispatch("calendar/parentCalendarInfo", {date:dateToday.value, child_id:route.params.child_id});
});

// 각 날짜에 맞는 값입력 << 이거 중요!!!
function getYearMonth(day) {
    return `${dateToday.value.getFullYear()}-${String(dateToday.value.getMonth() + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;
}

function getDailyIncomeExpense(day, data, incomFlg) {
    const item = data?.find(item => item.target_at === getYearMonth(day));
    // console.log('item', item);
    const symbol = incomFlg ? '+' : '-';
    if(item) {
        const money = incomFlg ? item.income : item.outgo;
        return item ? symbol + Number(money).toLocaleString() : '';
    }
    // return item ? symbol + Number(item.income).toLocaleString() : '';
}

// 모바일 ----------------------------------

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
    width: 350px;
    background-color: rgba(255, 255, 255, 0);  
    word-spacing: 0.5rem;
}

.days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    padding: 0 20px;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    padding: auto;
}

.day {
    cursor: pointer;
}

.week {
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

.days div {
    text-align: center;
    font-size: 2rem;
    height: 95px;
    opacity: 0.5;
    border-radius: 50%;
    opacity: 1;
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
/* ------------- 모바일 버전 css ------------ */

.m-container {
    width: 100vw;
    height: 100vh;
}

.m-header {
    display: flex;
    height: 70px;
    background-color: #A2CAAC;
    width: 100vw;
    align-items: center;
    gap: 10px;
}

.m-user-image {
    height: 50px;
    width: 50px;
    border-radius: 50px;
    border: 2px white solid;
    background-color: white;
    margin-left: 20px;
}

.m-user-profile {
    font-size: 1.7rem;
    width: 100px;
}

.go-update {
    font-size: 2rem;
    margin-left: 180px;
}


.m-menu {
    display: flex;
    gap: 15px;
    border-top: 2px solid black;
    img {
        filter: contrast(0.5);
        margin-top: 10px;
        width: 25px;
        height: 25px;
    }
}

.m-menu-section {
    display: flex;
    flex-direction: column;
    width: 60px;
    justify-content: center;
    align-items: center;
    gap: 3px;
}
.m-menu-title {
    font-size: 0.8rem;
}

.m-home {
    margin-left: 15px;
}

.m-child-select-section {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}
.m-search-button {
    width: 30px;
    height: 30px;
    margin-right: 10px;
}

.m-search-section {
    max-width: 360px;
    height: 50px;
    width: 100vw;
    display: flex;
    gap: 15px;
    align-items: center;
    scrollbar-width: 10px;
    overflow: scroll;
    white-space: nowrap;
}

.m-child-select {
    min-width: 70px;
    height: 30px;
    border-radius: 15px;
    border: 3px solid #A2CAAC;
    text-align: center;
    line-height: 25px;
}

.m-search-btn{
    background-color: transparent;
    border: none;

}

.m-search-opt {
    margin-left: 20px;
    font-size: 1.2rem;
    height: 35px;
}

.m-expense-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    overflow: scroll;
    white-space: nowrap;
    min-height: 630px;
}

.m-expense-route {
    margin-left: 20px;
}

.m-expense-title {
    font-size: 1.3rem;
    max-width: 330px;
    margin-left: 30px;
    white-space: normal;
}

.m-expense-content {
    margin-left: 30px;
    border-top: 2px solid #636363;
    padding-top: 20px;
    max-width: 330px;
    white-space: normal;
    line-height: 22px;
    min-height: 300px;
}

.m-expense-detail-date{
    max-width: 330px;
    margin-left: 30px;
    display: flex;
    gap: 10px;
    padding-bottom: 10px;
    border-bottom: 2px solid #636363;
}

.m-minus {
    color: red;
    font-size: 0.8rem;
}

.m-plus {
    color: blue;
    font-size: 0.8rem;
}

.menu-sec-first {
    margin-left: 15px;
}

.m-detail-btn {
    display: flex;
    gap: 20px;
    min-height: 50px;
    border-top: 2px solid #000000;
}
.m-back-to-list {
    background-color: transparent;
    border: none;
    margin-left: 10px;
    font-size: 1.2rem;
    padding-top: 15px;
    display: flex;
    img{
        margin-left: 10px;
        width: 20px;
        height: 20px;
    }
}

.m-expense-category {
    display: flex;
    max-width: 350px;
    margin-left: 20px;
    height: 80px;
    gap: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #636363;
    img {
        border-radius: 40px;
        width: 50px;
        height: 50px;
    }
}

 .m-expense-amount{
    
    max-width: 350px;
    padding-top: 10px;
    font-size: 1.3rem;
    margin-left: 20px;
    border-top: 2px solid #636363;
 }

.detail-update {
    margin-left: 100px;
}

.m-category-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    background-color: #c9cfca;
    border-radius: 50px;
    width: 50px;
    height: 50px;
}

.m-categorybtn-green {
    background-color: #A2CAAC;
}

.m-ms-category-img {
    width: 35px;
    height: 35px;
    margin-top: 7px;
}

.m-category-name {
    margin-top: 20px;
}

.m-content-cate {
    margin-left: 40px;
    display: flex;
    gap: 40px;
    min-height: 70px;
}

.m-header-display {
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 1.5rem;
    width: 120px;
    background-color: rgba(255, 255, 255, 0);  
    word-spacing: 0.5rem;
}

.m-left , .m-right {
    color: #a2caac;
    font-size: 1.5rem;
    cursor: pointer;
}

.m-sec-header {
    gap: 30px;
    height: 50px;
    line-height: 50px;
    display: flex;
    justify-content: center;
    padding: 10px ;
    margin-bottom: 20px;
}

.m-week {
    display: grid;
    height: 40px;
    grid-template-columns: repeat(7, 1fr);
    padding: 0 20px;
    justify-content: space-between;
    align-items: center;
    div{
        font-size: 1.7rem;
        text-align: center;
        line-height: 30px;
        border-bottom: 7px double #a2caac;
    }
}
.m-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    padding: 0 20px;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    padding: auto;
}
.m-days div {
    text-align: center;
    font-size: 1.2rem;
    height: 85px;
    opacity: 0.5;
    border-radius: 50%;
    opacity: 1;
    p {
        line-height: 30px;
        height: 30px;
    }
}

.m-circle-class {
    width: 30px; 
    border-radius: 50%;
    display: inline-block; /* 원 크기 유지 */
    text-align: center; /* 텍스트 가운데 정렬 */
    box-sizing: border-box; /* 테두리 포함 */
    background-color: #a2caacb0;
}
/* --- 모바일 모달 ---- */

.del-modal-back {
    background-color: rgba(0, 0, 0, 0.5);
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    flex-direction: column;
    justify-content: center;
    gap: 30px;
}

.m-delete-modal-sec {
    font-size: 1.5rem; 
    img {
        width: 100px;
        height: 100px;
        margin-left: 100px;
        margin-top: 70px;
    }
}

.spend-type {
    height: 30px;
}

.m-search-category {
    display: flex;
}

.m-search-date {
    display: flex;
}

.m-modal-list-container {
    background-color: white;
    width: 300px;
    height: 400px;
}

.m-modal-btn{
    display: flex;
    gap: 30px;
    justify-content: center;
    margin-top: 100px;
    button {
        width: 80px;
        height: 40px;
        font-size: 1.2rem;
        background-color: #A2CAAC;
        color: white;
        border: none;
    }
}

.m-modal-ms-title {
    text-align: center;
    max-width: 270px;
    white-space: normal;
    justify-items: center;
}

.m-del-guide {
    text-align: center;
    margin-top: 20px;
}

.m-checked-category-btn {
    background-color: #A2CAAC;
}


</style>