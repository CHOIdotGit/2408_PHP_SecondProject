<template>
<div class="stat-container">
    <div class="container">
        <div class="graph-section">
            <div class="date-menu">
                <button @click="prevMonth">◀</button>
                <p class="date-display">{{ formattedDate }}</p>
                <button @click="nextMonth">▶</button>
            </div>
            <div class="doughnut">
                <canvas ref="doughnutCanvas" height="650" width="650"></canvas>
            </div>
        </div>
        <div class="">
            <div class="info">
                <div class="d-grid">
                  <div>
                    <p class="info-title">가장 큰 지출</p>
                    <!-- <p class="info-content">최근 소비한 내역이 없습니다.</p> -->
                    <p class="info-content">{{ mostSpendAmount.amount > 0 ? Number(mostSpendAmount.amount).toLocaleString() + '원' : '최근 소비한 내역이 없습니다.' }}</p>
                </div>
                <div>
                    <p class="info-title">가장 많이 사용한 카테고리</p>
                    <p class="info-content">{{ mostUsedCategory.category ? getCategoryText(mostUsedCategory.category) : '최근 사용한 카테고리가 없습니다.' }}</p>
                </div>
                <div>
                    <p class="info-title">지출 총 합</p>
                    <p class="info-content">{{ totalAmount ? Number(totalAmount).toLocaleString() + '원' : '최근 지출 내역이 없습니다.' }}</p>
                </div>
                <div>
                    <p class="info-title">용돈 총 합</p>
                    <p class="info-content">{{ totalExpenses ? Number(totalExpenses).toLocaleString() + '원' : '최근 받은 용돈이 없습니다.' }}</p>
                </div>
        </div>
    </div>
    <div class="mission-box">
        <div class="Recently-registered-mission missions">
            <p class="mission-title">미션</p>
            <!-- <p v-if="homeMission.status === []" class="mission-content">최근 등록된 미션이 없습니다</p>
            <p v-else v-for="item in homeMission" :key="item" class="mission-content">{{ item.title }}</p> -->
        </div>
        <div class="Recently-completed-mission missions">
            <p class="mission-title">적금</p>
            <!-- <p v-if="!(homeMission.status === '2')" class="mission-content">최근 완료한 미션이 없습니다</p>
            <p v-else v-for="item in homeMission" :key="item" class="mission-content">{{ item.title }}</p> -->
        </div>
    </div>
</div>
</div>
  </div>
</template>

<script setup>

import { computed, onMounted , ref, watch} from 'vue';
import { useStore } from 'vuex';
import Chart from 'chart.js/auto';
import { useRoute } from 'vue-router';

const store = useStore();
const route = useRoute();


const dateToday = ref(new Date());

// 현재 연월 표시 (반응형 데이터)
const formattedDate = computed(() =>
    dateToday.value.toLocaleString("ko-KR", {
        year: "numeric",
        month: "long",
        timeZone: "Asia/Seoul",
    })
);

// 가장 큰 지출과 가장 많이 사용한 카테고리
const mostSpendAmount = computed(() => store.state.childTransaction.transactionAmount);
const mostUsedCategory = computed(() => store.state.childTransaction.mostUsedCategory);
const totalAmount = computed(() => store.state.childTransaction.totalAmount);
const totalExpenses = computed(() => store.state.childTransaction.totalExpenses);

// 이전 월로 이동
async function prevMonth() {
    const currentDate = new Date(dateToday.value.setMonth(dateToday.value.getMonth() - 1));
    await store.dispatch("transaction/childStats",  {date:dateToday.value, child_id:route.params.child_id});
    dateToday.value = currentDate;
}

// 다음 월로 이동
async function nextMonth() {
    const currentDate = new Date(dateToday.value.setMonth(dateToday.value.getMonth() + 1));
    await store.dispatch("transaction/childStats",  {date:dateToday.value, child_id:route.params.child_id});
    dateToday.value = currentDate;
}

const childStatis = computed(() => store.state.transaction.childStats);


const categoryMapping = [
    '교통비',
    '식비',
    '쇼핑',
    '기타',
];

const getCategoryText = (category) =>  {
  return categoryMapping[category] || '알 수 없음';
}

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
    setDoughuntDrawData();
    renderDoughnutChart();
  }
);

// ✅ **마운트 시 그래프 렌더링**
onMounted(async () => {
  await store.dispatch('transaction/childStats', {date:dateToday.value});
  setDoughuntDrawData(); // v3 add
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
</script>

<style scoped>

.container {
    margin-top: 20px;
    background-color: white;
    display: flex;
    gap: 70px;
    justify-content: start;
    align-items: center;   
    margin-left: 100px;

}

.info {
    width: 625px;
    height: 250px;
}

.d-grid{
    display: grid;
    grid-template-columns: 300px 300px;
    gap: 25px;
    height: 250px;
    border: black 3px solid;
}

.mission-box {
    display: flex;
    /* justify-content: center; */
    align-items: center;
    gap: 25px;
    margin-right: 25px;
}

.date-menu {
    margin-top: 10px;
    display: flex;
    margin-left: 50px;
    justify-content: space-around;
    font-size: 3rem;
    align-items: center;
    button {
        color: #5589e996;
        font-size: 3rem;
        cursor: pointer;
        background-color: transparent;
        border: none;
  }
}

.graph-section {
  display: flex;
  flex-direction: column;
  gap: 30px;
  
}

.info-title {
    padding: 10px;
    font-size: 1.5rem;
}

.info-content {
    padding: 10px;
    font-size: 1.3rem;
}

.missions {
    width: 300px;
    height: 415px;
    margin-top: 25px;
    background-color: #D9D9D9;
    text-align: center;
}

.mission-title {
    padding: 25px;
    font-size: 2rem;
}
.mission-content {
    font-size: 1.5rem;
    margin-top: 15px;
}
</style>