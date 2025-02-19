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
import { useRoute } from "vue-router";
import ParentCalendarModalComponent from "../../modal/ParentCalendarModalComponent.vue";

const store = useStore();
const route = useRoute();

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

function getDailyIncomeExpense(day, data, incomeFlg) {
    const yearMonth = getYearMonth(day); // 2025-02-18
    if (!data || !Array.isArray(data) || data.length === 0) {
        return ''; // data가 없으면 빈 문자열 반환
    }

    const item = data.find(item => {
        const targetMonth = `${item.target_at.year}-${String(item.target_at.month).padStart(2, '0')}`;
        return targetMonth === yearMonth.slice(0, 7); // 비교는 'YYYY-MM'만
    });

    // console.log('item', item);
    const symbol = incomeFlg ? '+' : '-';
    if (item) {
        const money = incomeFlg ? item.income : item.outgo;
        return symbol + Number(money).toLocaleString();
    }
    return '';
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
</style>