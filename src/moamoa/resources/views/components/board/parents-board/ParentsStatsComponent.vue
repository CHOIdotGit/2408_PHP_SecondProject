<template>
  <div class="stat-container"> 
      <div class="graph-btn">
          <button @click="test='bar'; renderChart();"> 막대 </button>
          <button @click="test='doughnut'; renderChart();">도넛</button>
      </div>
      <!-- <div class="graph-btn">
        <button @click="setChartType('bar')">막대</button>
        <button @click="setChartType('doughnut')">도넛</button>
      </div> -->
      
      <div class="stat-section">  
          <div class="each-part">
              <div class="graph-section" >
                  <!-- 그래프를 표시할 canvas 요소 -->
                  <canvas ref="chartCanvas"></canvas>
              </div>
              <div class="notice-section" v-if="childNameList.length > 0" v-for="item in statis" :key="item">
                {{ item.mostSpendAmount }}
                  <p>{{item.name}} {{ item.mostSpendAmount && item.mostSpendAmount !== 0 
                          ? Number(item.mostSpendAmount).toLocaleString() + '원' 
                          : '최근 소비한 내역이 없습니다.' }}   </p>
                          
                  <p>가장 많이 쓴 카테고리 : {{ item.mostUsedCategory 
                          ? getCategoryText(item.mostUsedCategory) 
                          : '최근 사용한 카테고리가 없습니다.' }} </p>
                  <p>지출 총합 : {{ item.totalAmount ? Number(item.totalAmount).toLocaleString() + '원' : '최근 지출 내역이 없습니다.' }} </p>
                  <p>용돈 총합 : {{ item.totalExpenses ? Number(item.totalExpenses).toLocaleString() + '원' : '최근 받은 용돈이 없습니다.' }} </p>
              </div>
          </div>
          <div class="each-part">
              <div class="graph-section">
                  그래프들어갈자리
              </div>
              <div class="notice-section">
                  <p>가장 큰 지출 : {{  }}</p>
                  <p>가장 많이 쓴 카테고리 : 쇼핑</p>
                  <p>지출 총합 : {{ totalAmount ? Number(totalAmount).toLocaleString() + '원' : '최근 지출 내역이 없습니다.' }} </p>
                  <p>용돈 총합 : {{ totalExpenses ? Number(totalExpenses).toLocaleString() + '원' : '최근 받은 용돈이 없습니다.' }} </p>
                  
              </div>
          </div>
          <div class="each-part">
              <div class="graph-section">
                  그래프들어갈자리
              </div>
              <div class="notice-section">
                  <p>가장 큰 지출 : 54,000   </p>
                  <p>가장 많이 쓴 카테고리 : 쇼핑</p>
                  <p>지출 총합 : 270,000</p>
                  <p>용돈 총합 : 300,00</p>
              </div>
          </div>
          
      </div>
  </div>
          
</template>

<script setup>
import { computed, onBeforeMount, onMounted, ref } from 'vue';
import { useStore } from 'vuex';
import Chart from 'chart.js/auto';


const store = useStore();

// 자녀 수 확인
const childNameList = computed(() => store.state.header.childNameList);
console.log('자녀 수 확인', childNameList.name);


// 가장 큰 지출과 가장 많이 사용한 카테고리
const mostSpendAmount = computed(() => store.state.transaction.mostSpendAmount.child.child_id);
const mostUsedCategory = computed(() => store.state.transaction.mostUsedCategory);
const totalAmount = computed(() => store.state.transaction.totalAmount);
const totalExpenses = computed(() => store.state.transaction.totalExpenses);

const statis = computed(() => [
{ name: "가장 큰 지출 :", value: mostSpendAmount.transactions_max_amount},
{ name: "가장 많이 쓴 카데고리 :", value: mostUsedCategory.category},
{ name: "지출 총합 :", value: totalAmount},
{ name: "용돈 총합 :", value: totalExpenses},
])

const getCategoryText = (category) => {
  const categoryMapping = {
      "0": '교통비',
      "1": '취미',
      "2": '쇼핑',
      "3": '기타',
  };
  return categoryMapping[category]; // 기본값 없이 반환
};

// ✅ **Chart.js 관련 설정**
const chartCanvas = ref(null);
let chartInstance = null;

const chartData = ref({
labels: ['1주', '2주', '3주', '4주'],
datasets: [
  {
    label: '주차별 소비 합계',
    data: [15000, 30000, 45000, 60000], // 예시 데이터
    backgroundColor: 'white',
    borderColor: '#a2caac',
    borderWidth: 1,
  },
],
});

const test = ref ("bar");
const renderChart = () => {
if (chartInstance) {
  chartInstance.destroy(); // 기존 차트 제거
}

chartInstance = new Chart


(chartCanvas.value.getContext('2d'), {
  type: test.value,
  data: chartData.value,
  options: {
    responsive: true,
    plugins: {
      legend: {
        display: true,
        position: 'top',
      },
    },
    scales: {
      y: {
        beginAtZero: true,
        title: {
          display: true,
          text: '지출 금액 (원)',
        },
      },
      x: {
        title: {
          display: true,
          text: '주차',
        },
      },
    },
  },
});
};

// 마운트
onBeforeMount(() => {
  // store.commit('mission/resetState');
  // store.dispatch('mission/childHome');
  store.dispatch('transaction/childHomeTransaction');
})
onMounted(() => {
  renderChart();
})

</script>

<style scoped>
.stat-container {
  width: 1500px;
  height: 765px;
  margin-top: 20px;
  background-color: white;
  margin-left: 240px;
  
}

.stat-section {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 30px;
  margin-right: 20px;
  margin-bottom: 20px;
  margin-left: 20px;
  /* width: 90%; */
  height: 88%;
  overflow: auto;
}
.each-part {
  height: 620px;
}
.graph-section {
  height: 450px;
  background-color: #a2caac;
  /* margin-top: 40px; */
}

.notice-section {
  display: grid;
  grid-template-columns: 1fr 1fr;
  grid-template-rows: 1fr 1fr ;
  height: 150px;
  margin-top: 25px;
  p {
      font-size: 1.2rem;
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

canvas{
/* background-color: white;
padding-top: 30px; */
padding: auto;
font-size: 1.2rem;
}

</style>