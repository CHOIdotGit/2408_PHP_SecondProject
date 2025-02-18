<template>
<div class="stat-container"> 
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
import { computed, onMounted, ref, watch } from 'vue';
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



</style>