<template>
    <div class="bank-product-container">
        <div class="savings-product">
            <!-- <div class="route"> 홈  > 모아은행 > 적금상품 </div> -->
            <div class="bank-product-head">
                <h1>모아은행 적금 상품</h1>
                <p>어릴때 부터 가진 투자와 저축에 대한 관심이 성인이 되어 수입을 만들고 소비하는데 도움을 줍니다 </p>
            </div>
            <div class="bankBar">
                <!-- <div :class="[{ everyDay : 'false'}, 'bankBarBtn' ]" @click="openEveryDay" >매일 적금</div> -->
                <div :class="everyDay ? 'bankBarBtn' : 'false'" @click="openEveryDay" >매일 적금</div>
                <div :class="everyWeek ? 'bankBarBtn' : 'false'"  @click="opneEveryWeek">매주 적금</div>
            </div>
            <div class="outline" v-show="everyDay">
                <!-- 매일 적금 -->
                <div class="saving-product-name">매일 포인트를 저축해요</div>
                <div class="div-products">
                    <div class="products" v-for="item in single" :key="item" @click="router.push('/moabank/product/detail/'+ item.saving_product_id)">
                        <p class="product-title">⭐{{ item.saving_product_name }} 적금</p>
                        <p class="rate-percent">이자율 : {{ item.saving_product_interest_rate}} %</p>
                        <p class="rate-percent">최소 납입 포인트 : {{ item.saving_product_amount }} moa</p>
                    </div>
                </div>

            </div>
            <div class="outline" v-show="everyWeek">
                <!-- 매주 적금 -->
                <div  class="saving-product-name"> 일주일에 한번 포인트를 저축해요</div>
                <div class="div-products">
                    <div class="products" v-for="item in week" :key="item" @click="router.push('/moabank/product/detail/'+ item.saving_product_id)">
                        <p class="product-title">⭐{{ item.saving_product_name }} 적금</p>
                        <p class="rate-percent">이자율 : {{ item.saving_product_interest_rate}} %</p>
                        <p class="rate-percent">최소 납입 금액 : {{ item.saving_product_amount }} moa</p>
                    </div>
                </div>
            </div>    
            
        </div>
    </div>

</template>

<script setup>

import { computed, onBeforeMount, onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const router = useRouter();

// 적금 상품 가져오기
const savingProduct = computed(()=> store.state.bank.savingList);

// 매일 적금
const single =  computed(()=> store.state.bank.singleList);

// 매주 적금
const week = computed(()=> store.state.bank.weekList);
onBeforeMount(() => {
    // store.dispatch('bank/koreaBank');
    store.dispatch('bank/savingProductList');
    
});

// const addSavingPage = () => {
//     console.log('적금상품 더보기');
//     store.dispatch('bank/addsavingList');
// }


const everyDay = ref(true);
const everyWeek = ref(false); 

const openEveryDay = () => {
    everyDay.value = true;
    everyWeek.value = false;
}

const opneEveryWeek = () => {
    everyDay.value = false;
    everyWeek.value = true;
}

</script>


<style scoped>
/* @media (max-width: 1600px) {
    .bank-product-container {
        width: 100vw;
    }
} */

h1 {
    font-size: 2.5rem;
    margin-bottom: 20px;
}

h2 {
    font-size: 2rem;
    margin-bottom: 5px;
}

.headline {
    padding-bottom: 5px;
}

hr {
    border: 2px solid #e0e7ee;
}

.bank-product-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 100vh;
}

img {
    width: 60px;
    
}

.bank-product-box {
    width: 700px;
    height: 350px;
    border: 2px solid #e0e7ee;
    padding: 30px;
}

.bank-box {
    display: flex;
    gap: 20px;
    margin-top: 40px;
    margin-bottom: 80px;
    justify-content: space-between;
}

.box-item {
    display: flex;
    gap: 10px;
}

.box-item > div {
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 5px;
}

.box-item-content {
    font-size: 1.4rem;
}

.bank-box-bottom {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 5px;
}

.box-btn {
    background-color: #ffcf0f;
    cursor: pointer;
    width: 150px;
    height: 40px;
    text-align: center;
    line-height: 40px;
}

.tap-menu {
    display: flex;
    width: 700px;
    height: 20px;
    margin: 40px 0 20px
}

.tap-item {
    background-color: #f2f2f2;
    border-bottom: 1px solid #d9d9d9;
    text-align: center;
    width: 100%;
    height: 50px;
    cursor: pointer;
    line-height: 47px;
    
}

/* 적금 상품들 */
.bank-product-head {
    background-image: linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%);
    width: 100%;
    height: 150px;
    color: #fff;
    padding-top: 30px;
    padding-left: 10px;
    margin-bottom: 50px;
}

.bankBar {
    display: flex;
}

/* 선택된 버튼 */
.bankBarBtn {
    width: 600px;
    height: 73px;
    border-top: 5px solid #ffdd38;
    border-left: 1px solid #d9d9d9;
    border-bottom-color: #fff;
    background: #fff;
    cursor: pointer;
    font-size: 2rem;
    line-height: 74px;
    text-align: center;
}

/* 선택 안된 버튼 */
.false {
    background-color: #f0f0f0;
    width: 600px;
    height: 73px;
    border-bottom: 1px solid #d9d9d9;
    cursor: pointer;
    font-size: 2rem;
    line-height: 74px;
    text-align: center;
}




.outline {
    width: 1200px;
    /* 부모 색깔 */
    /* background-color: #e8ecdc;  */

    /* 자녀 색깔 */
    /* background-color: #e4eff4; */
    /* border-radius: 10px; */
    border: 1px solid #d9d9d9;
    border-top: 1px solid #fff;
    height: 500px;
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 50px;
    background-image: url("/img/icon-bank-account.png");
    background-repeat: no-repeat;

}

/* 매일 적금/매주 적금 타이틀 크기 */
.saving-product-name {
    font-size: 2.1rem;
    padding-bottom: 14px;
}

/* 적금 상품 */
.div-products {
    display: flex;
    gap: 20px;
    margin-top: 60px;
}

.products {
    min-width: 200px;
    height: 200px;
    padding: 20px;
    border: 1px solid #ddd;
    background-color: #fff;
    cursor: pointer;
}

.products:hover {
    box-shadow: 0 1px 10px rgba(0,0,0,0.16), 0 1px 10px rgba(0,0,0,0.23)
}

.product-title {
    font-size: 1.5rem;
}


.rate-percent {
    margin-top: 30px;
}


</style>