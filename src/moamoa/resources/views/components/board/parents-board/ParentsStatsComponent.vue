<template>
<div class="stat-container"> 
    <div class="stat-section">
      <div class="each-part">
        <!-- 그래프 섹션 -->
        <div class="graph-section">
          <!-- 막대 그래프 -->
          <div class="graph">
            <canvas ref="graphCanvas"></canvas>
          </div>
          <!-- 도넛 그래프 -->
          <div class="doughnut">
            <canvas ref="doughnutCanvas"></canvas>
          </div>
        </div>

        <!-- 통계 정보 섹션 -->
        <div>
          <div class="notice-section" v-if="parentStatis">
            <!-- {{ statis.value.mostSpendAmount }} -->
            <p>
              가장 큰 지출 :
                    {{ parentStatis.transactions_max_amount }}원
              </p>

                <p>
                  가장 자주 쓴 카테고리 : 
                  {{ getCategoryText(parentStatis.transactions_max_category) }}
                </p>

                <p>
                  지출 총합 : {{ parentStatis.totalExpenses }}원
                </p>

                <p>
                  용돈 총합 : {{ parentStatis.missions_sum_amount }}원
                </p>
              </div>
            </div>

        </div>
      </div>
    </div>
</template>

<script setup>
import { computed, onBeforeMount, onMounted, ref, watch } from 'vue';
import { useStore } from 'vuex';
import Chart from 'chart.js/auto';
import { useRoute } from 'vue-router';

const store = useStore();
const route = useRoute();

const parentStatis = computed(() => store.state.transaction.parentStats);
// const doughnutGraph = computed(() => store.state.transacion);

const getCategoryText = (category) => {
  const categoryMapping = {
    0: '교통비',
    1: '취미',
    2: '쇼핑',
    3: '기타',
  };
  return categoryMapping[category] || '알 수 없음';
};

// ✅ **막대 그래프 설정**
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
      backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#ffffff', '#000000'],
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
});

onMounted(async () => {
  await store.dispatch('transaction/parentStats', route.params.child_id);
  doughnutChartData.datasets[0].data = doughnutData.value;
  graphChartData.datasets[0].data = weeklyOutgoData.value;
  renderGraphChart();
  renderDoughnutChart();
});

watch(
  () => doughnutData.value
  , newQuestion => {
    doughnutChartData.datasets[0].data = doughnutData.value;
    renderDoughnutChart();
  }
);
watch(
  () => weeklyOutgoData.value
  , newQuestion => {
    weekly.value = weeklyOutgoData.value.map((val, key) => ++key + '주차');
    graphChartData.labels = weekly.value;
    graphChartData.datasets[0].data = weeklyOutgoData.value;
    renderGraphChart();
  }
);
</script>


<style scoped>

.stat-container {
  width: 1500px;
  height: 720px;
  margin-top: 20px;
  background-color: white;
  margin-left: 240px;
  
}

.stat-section {
  display: grid;
  /* grid-template-columns: 1fr 1fr; */
  gap: 30px;
  margin-right: 20px;
  margin-bottom: 20px;
  margin-left: 20px;
  /* width: 90%;
  height: 88%;
  overflow: auto; */
}

.graph-section {
  height: 500px;
  background-color: #a2caac;
  display: grid;
  grid-template-columns: 800px 600px;
  margin-top: 20px;
  /* margin-top: 40px; */
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
      /* text-indent: 30px; */
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


.graph, .doughnut {
  width: 100%;
  height: 90%;
  /* margin: auto; */
}


</style>