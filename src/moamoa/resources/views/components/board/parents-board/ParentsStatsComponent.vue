<template>
    <div class="stat-container"> 
        <div class="graph-btn">
            <button @click="setChartType('week')">주간</button>
            <button @click="setChartType('month')">월간</button>  
        </div>
        <div class="stat-section">  
            <div class="each-part">
                <div class="graph-section">
                     <!-- 그래프를 표시할 canvas 요소 -->
                    <canvas ref="chartCanvas"></canvas>
                </div>
                <div class="notice-section">
                    <p>가장 큰 지출 : {{ mostSpendAmount.amount && mostSpendAmount.amount !== 0 
                            ? Number(mostSpendAmount.amount).toLocaleString() + '원' 
                            : '최근 소비한 내역이 없습니다.' }}   </p>
                    <p>가장 많이 쓴 카테고리 : {{ mostUsedCategory.category 
                            ? getCategoryText(mostUsedCategory.category) 
                            : '최근 사용한 카테고리가 없습니다.' }} </p>
                    <p>지출 총합 : {{ totalAmount ? Number(totalAmount).toLocaleString() + '원' : '최근 지출 내역이 없습니다.' }} </p>
                    <p>용돈 총합 : {{ totalExpenses ? Number(totalExpenses).toLocaleString() + '원' : '최근 받은 용돈이 없습니다.' }} </p>
                </div>
            </div>
            <div class="each-part">
                <div class="graph-section">
                    그래프들어갈자리
                </div>
                <div class="notice-section">
                    <p>가장 큰 지출 : </p>
                    <p>가장 많이 쓴 카테고리 : 쇼핑</p>
                    <p>지출 총합 : 270,000</p>
                    <p>용돈 총합 : 300,00</p>
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
import { computed, onBeforeMount, onMounted } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

// 미션 들고오기
const homeMission = computed(() => store.state.mission.childHome)
// console.log('자녀 홈 미션', homeMission.value);

// 가장 큰 지출과 가장 많이 사용한 카테고리
const mostSpendAmount = computed(() => store.state.transaction.mostSpendAmount);
const mostUsedCategory = computed(() => store.state.transaction.mostUsedCategory);
const totalAmount = computed(() => store.state.transaction.totalAmount);
const totalExpenses = computed(() => store.state.transaction.totalExpenses);

const getCategoryText = (category) => {
    const categoryMapping = {
        "0": '교통비',
        "1": '취미',
        "2": '쇼핑',
        "3": '기타',
    };
    return categoryMapping[category]; // 기본값 없이 반환
};

// 마운트
onBeforeMount(() => {
    // store.commit('mission/resetState');
    // store.dispatch('mission/childHome');
    store.dispatch('transaction/parentStats');
})


import { Chart, registerables } from 'chart.js';

// Chart.js 모듈 등록
Chart.register(...registerables);



// export default {
//   name: 'ParentStatsComponent',
//   data() {
//     return {
//       chart: null, // 차트 객체 저장
//       chartType: 'week', // 기본 차트 타입
//       chartData: {
//         week: [12, 19, 3, 5, 2, 3],
//         month: [45, 67, 32, 87, 21, 44]
//       }
//     };
//   },
//   mounted() {
//     this.renderChart(); // 컴포넌트가 마운트되면 차트 렌더링
//   },
//   methods: {
//     renderChart() {
//       if (this.chart) {
//         this.chart.destroy(); // 기존 차트 파괴
//       }
//       const ctx = this.$refs.chartCanvas.getContext('2d');
//       this.chart = new Chart(ctx, {
//         type: 'bar',
//         data: {
//           labels: ['1주차', '2주차', '3주차', '4주차', '5주차'],
//           datasets: [{
//             label: '지출 금액',
//             data: this.chartData[this.chartType],
//             borderWidth: 5,
//             backgroundColor: 'rgba(75, 192, 192, 0.2)',
//             borderColor: 'rgba(75, 192, 192, 1)'
//           }]
//         },
//         options: {
//           scales: {
//             y: {
//               beginAtZero: true
//             }
//           }
//         }
//       });
//     },
//     setChartType(type) {
//       this.chartType = type;
//       this.renderChart(); // 차트 새로 렌더링
//     }
//   }
// };

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
        font-size: 1rem;
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

</style>