<template>
    <div class="d-flex">
        <div class="container">
            <div class="h-690">
                <div class="w-750 gauge-Bar">

                </div>
                <div class="w-750">

                </div>
            </div>
            <div class="">
                <div class="info">
                    <div class="d-grid">
                        <div>
                            <p class="info-title">가장 큰 지출</p>
                            <!-- <p class="info-content">최근 소비한 내역이 없습니다.</p> -->
                            <p class="info-content">{{ mostSpendAmount?.amount && mostSpendAmount.amount !== 0 ? mostSpendAmount.amount + '원' : '최근 소비한 내역이 없습니다.' }}</p>
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
                        <p v-if="!(homeMission.status === '0')" class="mission-content">최근 등록된 미션이 없습니다</p>
                        <p v-else v-for="item in homeMission" :key="item" class="mission-content">{{ item.title }}</p>
                    </div>
                    <div class="Recently-completed-mission missions">
                        <p class="mission-title">최근 완료한 미션</p>
                        <p v-if="!(homeMission.status === '2')" class="mission-content">최근 완료한 미션이 없습니다</p>
                        <p v-else v-for="item in homeMission" :key="item" class="mission-content">{{ item.title }}</p>
                    </div>
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
const mostSpendAmount = computed(() => store.state.transaction.childHomeTransaction);
const mostUsedCategory = computed(() => store.state.transaction.mostUsedCategory);
const totalAmount = computed(() => store.state.transaction.totalAmount);
const totalExpenses = computed(() => store.state.transaction.totalExpenses);

const getCategoryText = (category) => {
    const categoryMapping = {
        0: '교통비',
        1: '취미',
        2: '쇼핑',
        3: '기타',
    };
    return categoryMapping[category]; // 기본값 없이 반환
};

// 마운트
onBeforeMount(() => {
    // store.commit('mission/resetState');
    store.dispatch('mission/childHome');
})
onMounted(() => {
    store.dispatch('transaction/childHomeTransaction');

})
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
    height: 740px;
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

.Recently-registered-mission {

}

.Recently-completed-mission {

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