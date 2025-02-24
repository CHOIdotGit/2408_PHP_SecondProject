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
            </div>
            <img src="/img/m-search.png" alt="" class="m-search-button" @click="searchOpenModal">
        </div>
        <div class="m-expense-list">
          <div class="m-date-menu">
            <button @click="prevMonth">◀</button>
            <p class="m-date-display">{{ formattedDate }}</p>
            <button @click="nextMonth">▶</button>
          </div>
          <div class="m-stat-section">
            <div class="each-part">
              <!-- 그래프 섹션 -->
              <div class="m-graph-section">
              <!-- 막대 그래프 -->
              <div class="graph" style="margin-bottom: 40px;">
                <canvas ref="graphCanvas" height="500" width="300" style="margin-left: 30px;"></canvas>
              </div>
              <!-- 도넛 그래프 -->
              <div class="doughnut">
                <canvas ref="doughnutCanvas" height="400" width="400" style="margin-bottom: 40px;"></canvas>
              </div>
            </div>
        </div>
      </div>
    </div> 
        <footer>
            <div class="m-footer-menu">
                <div class="m-menu">
                    <div class="m-menu-section">
                        <img src="/img/icon-home.png" alt="" class="m-home" style="margin-left: 15px;">
                        <p class="m-menu-title" style="margin-left: 15px;"> 홈 </p>
                    </div>
                    <div class="m-menu-section">
                        <img src="/img/icon-piggy-bank.png" alt="" class="m-mission">
                        <p class="m-menu-title"> 미션 </p>
                    </div>
                    <div class="m-menu-section">
                        <img src="/img/icon-coin.png" alt="" class="m-expense">
                        <p class="m-menu-title"> 지출 </p>
                    </div>
                    <div class="m-menu-section">
                        <img src="/img/icon-calendar.png" alt="" class="m-calendar">
                        <p class="m-menu-title"> 달력 </p>
                    </div>
                    <div class="m-menu-section">
                        <img src="/img/icon-sack-dollar.png" alt="" class="m-bank">
                        <p class="m-menu-title"> 모아통장 </p>
                    </div>
                    <div class="m-menu-section">
                        <img src="/img/mobile-etc.png" alt="" class="m-etc">
                    </div>
                </div>
            </div>
        </footer>
    </div>

<!-- 검색 모달 -->
        <div class="del-modal-back" v-show="serachModal.isOpen">
            <div class="m-modal-list-container">
                <div class="m-search-filter">
                    <div class="m-search-category">
                        <p>지출 종류 </p> 
                        <select name="spend-type">
                            <option value="">전체</option>
                            <option value="0">교통비</option>
                            <option value="1">식비</option>
                            <option value="2" >쇼핑</option>
                            <option value="3">기타</option>
                        </select>
                    </div>
                    <div class="m-search-date">
                        <p>지출 일자</p> 
                        <input type="date" min="2000-01-01" >
                    </div>
                    <div class="m-modal-search">
                        <input type="search" placeholder="검색어를 입력해주세요">
                    </div>
                </div>
            </div>
            <div class="m-modal-btn">
                <button @click="searchCloseModal" class="modal-cancel">취소</button>
                <button> 검색</button>
            </div>
        </div>   

</div>

<div class="stat-container" v-else> 
  <div class="date-menu">
    <button @click="prevMonth">◀</button>
    <p class="date-display">{{ formattedDate }}</p>
    <button @click="nextMonth">▶</button>
  </div>
    <div class="stat-section">
      <div class="each-part">
        <!-- 그래프 섹션 -->
        <div class="graph-section">
          <!-- 막대 그래프 -->
          <div class="graph">
            <canvas ref="graphCanvas" height="550" width="800"></canvas>
          </div>
          <!-- 도넛 그래프 -->
          <div class="doughnut">
            <canvas ref="doughnutCanvas" height="550" width="550"></canvas>
          </div>
        </div>
        <!-- <div class="graph-section" v-show="doughnutData?.length < 1">
          지출 없음
        </div> -->

        <!-- 통계 정보 섹션 -->
        <div>
          <div class="notice-section" v-if="parentStatis">
            <!-- {{ statis.value.mostSpendAmount }} -->
            <p>
              가장 큰 지출 :
                    {{ parentStatis.transactions_max_amount ? Number(parentStatis.transactions_max_amount).toLocaleString() + '원' : '최근 지출 내역이 없습니다.' }}
              </p>

                <p>
                  가장 자주 쓴 카테고리 : 
                  {{  getCategoryText(parentStatis.transactions_max_category) ? getCategoryText(parentStatis.transactions_max_category) : '최근 사용한 카테고리가 없습니다.' }}
                </p>

                <p>
                  지출 총합 : {{ parentStatis.totalExpenses ? Number(parentStatis.totalExpenses).toLocaleString() + '원' : '최근 지출 내역이 없습니다.' }}
                </p>

                <p>
                  용돈 총합 : {{ parentStatis.missions_sum_amount ? Number(parentStatis.missions_sum_amount).toLocaleString() + '원' : '최근 받은 용돈이 없습니다.'}}
                </p>
              </div>
            </div>

        </div>
      </div>
    </div>
</template>

<script setup>
import { computed, onMounted, ref, watch, reactive } from 'vue';
import { useStore } from 'vuex';
import Chart from 'chart.js/auto';
import { useRoute } from 'vue-router';

const store = useStore();
const route = useRoute();

// +==========================+
// +    모바일 화면 전환       +
// +==========================+
// v-if="ismobile"적으면 모바일 화면으로 이동
const isMobile = store.state.mobile.isMobile;

// --------------- 모바일 모달 -----------------

const serachModal = reactive({ isOpen: false });

const searchOpenModal = () => {
    serachModal.isOpen = true;
};

const searchCloseModal = () => {
    serachModal.isOpen = false;
};
const dateToday = ref(new Date());

// 현재 연월 표시 (반응형 데이터)
const formattedDate = computed(() =>
    dateToday.value.toLocaleString("ko-KR", {
        year: "numeric",
        month: "long",
        timeZone: "Asia/Seoul",
    })
);

// 이전 월로 이동
async function prevMonth() {
    const currentDate = new Date(dateToday.value.setMonth(dateToday.value.getMonth() - 1));
    await store.dispatch("transaction/parentStats",  {date:dateToday.value, child_id:route.params.child_id});
    dateToday.value = currentDate;
}

// 다음 월로 이동
async function nextMonth() {
    const currentDate = new Date(dateToday.value.setMonth(dateToday.value.getMonth() + 1));
    await store.dispatch("transaction/parentStats",  {date:dateToday.value, child_id:route.params.child_id});
    dateToday.value = currentDate;
}

const parentStatis = computed(() => store.state.transaction.parentStats);


// const doughnutGraph = computed(() => store.state.transacion);
const categoryMapping = [
    '교통비',
    '식비',
    '쇼핑',
    '기타',
];

const getCategoryText = (category) => {
  return categoryMapping[category] || '알 수 없음';
};

// --------------- ✅ **막대 그래프 설정** start ---------------
const graphCanvas = ref(null);
let graphChartInstance = null;
const weeklyOutgoData = computed(() => store.state.transaction.weeklyOutgoData)
const weekly = ref([]);

const graphChartData = {
  labels: weekly.value,
  datasets: [
    {
      label: '주차별 소비 합계',
      data: weeklyOutgoData.value,
      backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#462679', '#000000'],
      borderWidth: 0.8,
    },
  ],
};

const renderGraphChart = () => {
  if (graphChartInstance) graphChartInstance.destroy();

  if (graphCanvas.value) {
    graphChartInstance = new Chart(graphCanvas.value, {
      type: 'bar',
      data: graphChartData,
      options: {
        responsive: false,
      
        plugins: {
          legend: {
            position: 'top',
            display: false
          },
          title: {
            display: true,
            text: '주차별 소비 합계',
            font: {
                        size: 20
                }
          },
        },
        scales: {
          x: {
              ticks: {
                color: 'black', // X축 글자 색상 변경
                font: {
                        size: 16
                }
              },
              grid: {
                color: '#c9c9c9', // X축 눈금선 색상 변경
              },
          },
          y: {
              beginAtZero: true,
              ticks: {
                color: 'black',
                font: {
                        size: 16
                } // Y축 글자 색상 변경
              },
              grid: {
                // color: '#c9c9c9', // Y축 눈금선 색상 변경
              },
            },
        },
      },
    });
  }
};

watch(
  () => weeklyOutgoData.value
  , newQuestion => {
    weekly.value = weeklyOutgoData.value.map((val, key) => ++key + '주차');
    graphChartData.labels = weekly.value;
    graphChartData.datasets[0].data = weeklyOutgoData.value;
    renderGraphChart();
  }
);
// --------------- ✅ **막대 그래프 설정** end ---------------

// ✅ **도넛 그래프 설정**
const doughnutCanvas = ref(null);
let doughnutChartInstance = null;
const doughnutData = computed(() => store.state.transaction.doughnutData);


const doughnutChartData = {
  labels: ['교통비', '식비', '쇼핑', '기타'],
  datasets: [
    {
      label: '지출 비율',
      data: doughnutData.value,
      
      backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
      hoverOffset: 4,
    },
  ],
};

const renderDoughnutChart = () => {
  if (doughnutChartInstance) doughnutChartInstance.destroy();

  if (doughnutCanvas.value) {
    doughnutChartInstance = new Chart(doughnutCanvas.value, {
      type: 'doughnut',
      data: doughnutChartData,
      options: {
        responsive: false,
        plugins: {
          legend: {
            position: 'top',
          },
          title: {
            display: true,
            text: '지출 비율 도넛 그래프',
            font: {
                        size: 20
                }
          },
        },
      },
    });
  }
};

watch(
  () => doughnutData.value
  , newQuestion => {
    // doughnutChartData.datasets[0].data = doughnutData.value; // v3 del

    setDoughuntDrawData(); // v3 add
    renderDoughnutChart();
  }
);

// ✅ **마운트 시 그래프 렌더링**
onMounted(async () => {
  await store.dispatch('transaction/parentStats', {date:dateToday.value, child_id:route.params.child_id});
  // doughnutChartData.datasets[0].data = doughnutData.value; // v3 del
  setDoughuntDrawData(); // v3 add
  graphChartData.datasets[0].data = weeklyOutgoData.value;
  renderGraphChart();
  renderDoughnutChart();
});

// v3 add start
const setDoughuntDrawData = () => {
  doughnutChartData.datasets[0].data = store.getters['transaction/getDoughnutDataTotalAmount']; // v3 add
  doughnutChartData.labels = categoryMapping.filter((val, key) => {
    const labels = store.getters['transaction/getDoughnutDataLabels'];
    return labels.some(labelCode => labelCode == key);      
  });
}
// v3 add end
</script>


<style scoped>


.stat-container {
  height: 720px;
  margin-top: 20px;
  background-color: white;
  margin-left: 100px;
  
}

.stat-section {
  display: grid;
  gap: 30px;
  margin-right: 20px;
  margin-bottom: 20px;
  margin-left: 20px;
}

.graph-section {
  height: 550px;
  display: grid;
  grid-template-columns: 900px 500px;
  margin-top: 20px;
}

.notice-section {
  display: grid;
  grid-template-columns: 1fr 1fr;
  grid-template-rows: 1fr 1fr ;
  height: 200px;
  margin-top: 25px;
  p {
      margin-top: 15px;
      font-size: 1.4rem;
      text-indent: 30px;
      line-height: 30px;
      
  }
}

.graph-btn {
  padding-top: 20px;
  margin-left: 20px;
  height: 90px;
  button{
      height: 50px;
      width: 100px;
      font-size: 1.2rem;
      border: none;
  }
}

.date-menu {
  display: flex;
  margin-left: 50px;
  justify-content: space-around;
  font-size: 3rem;
  align-items: center;
  button {
    color: #a2caac;
    font-size: 3rem;
    cursor: pointer;
    background-color: transparent;
    border: none;
    
  }
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

.m-child-profile {
    width: 100px;
    height: 100px;
    border-radius: 50px;
    border: 3px solid #A2CAAC;
    background-color: white;
}

.m-child {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 20px;
}   

.m-child-name {
    margin-top: 20px;
    font-size: 1.5rem;
    text-align: center;
    font-weight: 600;
}

.m-home-title-e{
    font-size: 1.3rem;
    margin-left: 70px;
    background-color: #A2CAAC;
    color: white;
    border-radius: 15px;
    font-weight: 600;
    width: 100px;
    text-align: center;
    margin-top: 20px;
    height: 50px;
    line-height: 50px;
}

.m-home-title-c{
    font-size: 1.3rem;
    margin-left: 35px;
    background-color: #A2CAAC;
    color: white;
    border-radius: 15px;
    font-weight: 600;
    width: 180px;
    text-align: center;
    margin-top: 20px;
    height: 50px;
    line-height: 50px;
}

.m-home-content {
    text-align: center;
    font-size: 1.2rem;
    height: 30px;
    line-height: 30px;
}

.m-first {
    margin-top: 20px;
}

.m-main-content {
    margin-top: 20px;
    width: 250px;
    border: 3px solid #A2CAAC;
    border-radius: 20px;
    height: 420px;
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

.m-state-in-progress {
    background-color: rgba(138, 160, 246, 0.5);
    color: #0010BE;
    line-height: 23px;
}

.m-state-complete {
    background-color: rgba(255, 222, 92, 0.5);
    color: #2F8A1D;
    line-height: 23px;
}
.m-state-waiting {
    background-color: rgba(22, 200, 150, 0.5);
    color: #165442;
    line-height: 23px;
}

.m-state-cancel {
    background-color: rgba(254, 135, 105, 0.5);
    color: #FF3300;
    line-height: 23px;
}

.m-sec-expense {
    display: flex;
    gap: 20px;
}
.menu-sec-first {
    margin-left: 15px;
    background-color: #A2CAAC;
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

.m-create-btn {
    width: 50px;
    height: 50px;
    position: sticky;
    border: none;
    background-color: transparent;
}

.m-search-opt {
    margin-left: 20px;
    font-size: 1.2rem;
    height: 35px;
}

.m-expense-section {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-left: 20px;
    margin-bottom: 20px;
}

.m-expense-amount {
    margin-right: 20px;
    font-size: 1.3rem;
    
}

.m-expense-first {
    display: flex;
    font-size: 1.3rem;
}

.m-expense-second {
    display: flex;
    gap: 15px;
    justify-content: space-between;
    font-size: 1.1rem;
}

.m-expense-thrid {
    display: flex;
    gap: 15px;
    font-size: 1.1rem;
}

.m-expense-date {
    font-size: 0.9rem;
}
.m-expense-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    overflow: scroll;
    white-space: nowrap;
    height: 640px;
}

.m-expense-status{
    min-width: 50px;
}

.m-create-btn {
    position: fixed;
    width: 50px;
    height: 50px;
    left: 325px;
    bottom: 80px;
}

/* --- 모바일 모달 ---- */

.del-modal-back {
    background-color: rgba(88, 88, 88, 0.637);
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

.m-search-filter {
    display: flex;
    flex-direction: column;
    font-size: 1.5rem; 
    gap: 20px;
}

.spend-type {
    height: 30px;
}

.m-date-menu {
  display: flex;
  justify-content: space-around;
  font-size: 1.2rem;
  align-items: center;
  button {
    color: #a2caac;
    font-size: 1.8rem;
    cursor: pointer;
    background-color: transparent;
    border: none;
    
  }
}
.m-search-category {
    display: flex;
}

.m-graph-section {
  display: flex;
  flex-direction: column;
}

.m-search-date {
    display: flex;
}

.m-modal-list-container {
    background-color: white;
    display: flex;
}

.m-modal-btn{
    display: flex;
    gap: 30px;
    button {
        width: 80px;
        height: 40px;
        font-size: 1.2rem;
    }
}

.m-date-display {
  font-size: 1.8rem;
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