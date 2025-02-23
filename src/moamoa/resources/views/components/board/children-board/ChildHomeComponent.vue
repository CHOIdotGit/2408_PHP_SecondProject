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
                    <div class="d-grid" v-if="data">
                        <div>
                            <p class="info-title">가장 큰 지출</p>
                            <!-- <p class="info-content">최근 소비한 내역이 없습니다.</p> -->
                            <p class="info-content">{{ data.transactions_max_amount > 0 ? Number(data.transactions_max_amount).toLocaleString() + '원' : '최근 소비한 내역이 없습니다.' }}</p>
                        </div>
                        <div>
                            <p class="info-title">가장 많이 사용한 카테고리</p>
                            <p class="info-content">{{ data.transactions_max_category ? getCategoryText(data.transactions_max_category) : '최근 사용한 카테고리가 없습니다.' }}</p>
                        </div>
                        <div>
                            <p class="info-title">지출 총 합</p>
                            <p class="info-content">{{ data.transactions_sum_amount ? Number(data.transactions_sum_amount).toLocaleString() + '원' : '최근 지출 내역이 없습니다.' }}</p>
                        </div>
                        <div>
                            <p class="info-title">용돈 총 합</p>
                            <p class="info-content">{{ data.missions_sum_amount ? Number(data.missions_sum_amount).toLocaleString() + '원' : '최근 받은 용돈이 없습니다.' }}</p>
                        </div>
                    </div>
                </div>
                <div class="mission-box">
                    <div class="Recently-registered-mission missions">
                        <p class="mission-title">매일매일 출석체크!</p>
                        <p class="mission-content">작은 한 걸음이 큰 변화를 만듭니다!</p>
                        <p class="mission-content">로그인 하시면 매일 2moa <br /> 포인트를 획득하실 수 있습니다.</p>
                        <p class="mission-content"></p>
                        <p v-if="childHome" class="mission-content">출석 날짜: {{ childHome.login_at }}</p>
                    </div>
                    <div class="Recently-completed-mission missions">
                        <p class="mission-title">내 적금</p>
                        <!-- 적금 통장이 없는 경우 -->
                        <div v-if="!savings.length">
                            <p class="mission-content">현재 가입된 적금 통장이 없습니다.</p>
                            <p class="mission-content">적금 통장을 가입하시려면</p>
                            <p class="mission-content"><strong>모아 은행</strong> 페이지로 이동해주세요.</p> 
                        </div>
                        <div v-else>
                            <div v-for="item in savings" :key="item">
                                <p class="mission-content">{{ item.saving_product_name }} 적금</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

import { computed, onMounted, onBeforeMount, ref, watch} from 'vue';
import { useStore } from 'vuex';
import Chart from 'chart.js/auto';
import { useRoute } from 'vue-router';

const store = useStore();
const route = useRoute();


const dateToday = ref(new Date());
const childHome = computed(() => store.state.childTransaction.childHome);

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
const savings = computed(() => store.state.childTransaction.savings);
const data = computed(() => store.state.childTransaction.data);

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

const childStats = computed(() => store.state.transaction.childStats);


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
// onMounted(async () => {
onBeforeMount(async () => {
    await store.dispatch('transaction/childStats', {date:dateToday.value});
    await store.dispatch("childTransaction/childHomeTransaction");
    
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
    border: 5px solid #5589e996;
    border-radius: 15px;
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
    font-size: 1.2rem;
}

.missions {
    width: 300px;
    height: 415px;
    margin-top: 25px;
    border: 5px solid #5589e996;
    text-align: center;
    border-radius: 15px;
}

.mission-title {
    padding: 25px;
    font-size: 1.5rem;
}
.mission-content {
    font-size: 1.2rem;
    margin-top: 15px;
}
</style>