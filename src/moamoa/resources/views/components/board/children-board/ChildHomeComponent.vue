<template>
    <div class="d-flex">
        <div class="container">
            <div class="h-690">
                <div class="doughnut">
            <canvas ref="doughnutCanvas"></canvas>
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
                        <p class="mission-title">최근 등록된 미션</p>
                        <!-- <p v-if="homeMission.status === []" class="mission-content">최근 등록된 미션이 없습니다</p>
                        <p v-else v-for="item in homeMission" :key="item" class="mission-content">{{ item.title }}</p> -->
                    </div>
                    <div class="Recently-completed-mission missions">
                        <p class="mission-title">최근 완료한 미션</p>
                        <!-- <p v-if="!(homeMission.status === '2')" class="mission-content">최근 완료한 미션이 없습니다</p>
                        <p v-else v-for="item in homeMission" :key="item" class="mission-content">{{ item.title }}</p> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

import { computed, onBeforeMount, onMounted , ref, watch} from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';

const store = useStore();
const route = useRoute();

// 미션 들고오기
const homeMission = computed(() => store.state.childMission.childHome);

// 가장 큰 지출과 가장 많이 사용한 카테고리
const mostSpendAmount = computed(() => store.state.childTransaction.transactionAmount);
const mostUsedCategory = computed(() => store.state.childTransaction.mostUsedCategory);
const totalAmount = computed(() => store.state.childTransaction.totalAmount);
const totalExpenses = computed(() => store.state.childTransaction.totalExpenses);

console.log('mostSpendAmount 확인', mostSpendAmount) // 이거 못 들고 옴(postMan은 들고 옴)
console.log('mostUsedCategory 확인', mostUsedCategory) // 이거 못 들고 옴(postMan은 들고 옴)

const getCategoryText = (category) => {
    const categoryMapping = {
        0: '교통비',
        1: '식비',
        2: '쇼핑',
        3: '기타',
    };
    return categoryMapping[category]; // 기본값 없이 반환
};


const graphCanvas = ref(null);
let graphChartInstance = null;

const graphChartData = {
  labels: ['1주', '2주', '3주', '4주'],
  datasets: [
    {
      label: '주차별 소비 합계',
      data: [15000, 30000, 45000, 60000],
      backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'],
      borderWidth: 1,
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
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
          },
          title: {
            display: true,
            text: '주차별 소비 합계',
          },
        },
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    });
  }
};

// ✅ **도넛 그래프 설정**
const doughnutCanvas = ref(null);
let doughnutChartInstance = null;
const doughnutData = computed(() => store.state.transaction.doughnutData);


const doughnutChartData = {
  labels: ['교통비', '취미', '쇼핑', '기타'],
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
        responsive: true,
        plugins: {
          legend: {
            position: 'top',
          },
          title: {
            display: true,
            text: '지출 비율 도넛 그래프',
          },
        },
      },
    });
  }
};

// ✅ **마운트 시 그래프 렌더링**
onBeforeMount(() => {
    store.dispatch('childMission/childHome');
});

onMounted(async () => {
    store.dispatch('childTransaction/childHomeTransaction');
    await store.dispatch('transaction/parentStats', route.params.child_id);
    doughnutChartData.datasets[0].data = doughnutData.value;
    renderGraphChart();
    renderDoughnutChart();
});

watch(() => doughnutData.value, newQuestion => {
    console.log('watch', doughnutData.value);
    doughnutChartData.datasets[0].data = doughnutData.value;
    renderDoughnutChart();
    }
);

</script>

<style scoped>
.d-flex {
    display: flex;
    justify-content: center;
    align-items: center;
}

.container {
    margin-top: 20px;
    width: 1500px;
    height: 720px;
    background-color: white;
    display: flex;
    gap: 25px;
    justify-content: start;
    align-items: center;   
}

.h-690 {
    width: 800px;
    height: 690px;
    background-color: #D9D9D9;
    margin-left: 25px;
}

.w-750 {
    width: 750px;
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