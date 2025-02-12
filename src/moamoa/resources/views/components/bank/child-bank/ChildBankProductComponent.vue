<template>
    <div class="bank-product-container">
        <div class="savings-product">
            <div class="outline">
                <h1>모아은행 적금 상품</h1>
                <div class="div-products">
                    <!-- <div v-if="" class="every-day-product"> -->
                        <!-- <p>매일 적금</p> -->
                        <div class="products" v-for="item in savingProduct" :key="item">
                            <p @click="router.push('/moabank/product/detail/'+ item.saving_product_id)" class="product-title">⭐{{ item.saving_product_name }} 적금</p>
                            <p class="rate-percent">이자율 : {{ item.saving_product_interest_rate}} %</p>
                            <p class="rate-percent">최소 납입 포인트 : {{ item.saving_product_amount }} moa</p>
                        </div>
                    <!-- </div> -->
                    <!-- <div v-else class="every-weekend-product">
                        <p>매주 적금</p>
                        <div class="products" v-for="item in savingProduct" :key="item">
                            <p class="product-title">⭐{{ item.saving_product_name }} 적금</p>
                            <p class="rate-percent">이자율 : {{ item.saving_product_interest_rate}} %</p>
                            <p class="rate-percent">최소 납입 포인트 : {{ item.saving_product_amount }} moa</p>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>

</template>

<script setup>

import { computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const route = useRoute();
const router = useRouter();

// 적금 상품 가져오기
const savingProduct = computed(()=> store.state.bank.savingList);
onMounted(() => {
    store.dispatch('bank/koreaBank');
    store.dispatch('bank/savingProductList');
});

// const addSavingPage = () => {
//     console.log('적금상품 더보기');
//     store.dispatch('bank/addsavingList');
// }

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
.savings-product {
    

}

.outline {
    width: 1470px;
    text-align: center;
    /* 부모 색깔 */
    /* background-color: #e8ecdc;  */

    /* 자녀 색깔 */
    background-color: #e4eff4;
    border-radius: 10px;
    height: 750px;
    padding: 20px;

}


.div-products {
    display: flex;
}

.products {
    min-width: 200px;
    height: 200px;
    padding-top: 20px;
    border: 1px solid #ddd;
    background-color: #fff;
    
}

.product-title {
    font-size: 1.5rem;
    cursor: pointer;
}

.rate-percent {
    margin-top: 30px;
}


</style>