<template>
<div class="bank-product-container">
    <div>
        <!-- 상품 상세 정보 추가 -->
        <h1>적금 상품 가입</h1>
        <div class="bank-product-box">
            <div class="headline">
                <h2>{{ productInfo.saving_product_name }} 적금</h2>
                <p>{{ productInfo.saving_product_name }} 동안 매일 적금해보아요</p>
            </div>
            <hr>
            <div class="bank-box">
                <div class="box-item">
                    <img src="/img/icon-moabank-graph.png" alt="">
                    <div>
                        <p class="box-item-title">기간</p>
                        <p class="box-item-content">{{ productInfo.saving_product_period }}일</p>
                    </div>
                </div>
                <div class="box-item">
                    <img src="/img/icon-moabank-amount.png" alt="">
                    <div>
                        <p class="box-item-title">금액</p>
                        <p class="box-item-content">100 ~ 1000모아</p>
                    </div>
                </div>
                <div class="box-item">
                    <img src="/img/icon-moabank-analytics.png" alt="">
                    <div>
                        <p class="box-item-title">금리</p>
                        <p class="box-item-content">{{ interestRate }}%</p>
                    </div>
                </div>
            </div>
            <div class="bank-box-bottom">
                <!-- <div @click="router.push('/moabank/product/regist/'+ saving_product_id)" class="box-btn">가입하기</div> -->
                <div @click="router.push('/moabank/product/regist/'+ productInfo.saving_product_id)" class="box-btn">가입하기</div>
                <p>※ 자세한 내용은 아래 상품안내를 참조하시기 바랍니다.</p>

            </div>
        </div>
    </div>

    <div class="tap-menu">
        <div class="tap-item">상품안내</div>
        <div class="tap-item">금리 및 이율</div>
        <div class="tap-item">유의사항</div>
    </div>

    <div>
        <div></div>
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
const productInfo = computed(() => store.state.bank.productInfo);
const interestRate = computed(() => store.state.bank.computedInterestRate)

onMounted(() => {
    const id = route.params.id;
    console.log('상품 ID:', id);  // id가 제대로 전달되는지 확인
    store.dispatch('bank/selectedProduct', id);
});
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

.box-item-title {
    font-size: 1.3rem;
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
    font-size: 1.2rem;
}

.tap-menu {
    display: flex;
    width: 700px;
    /* height: 20px; */
    margin: 40px 0 20px
}

.tap-item {
    background-color: #f2f2f2;
    border-bottom: 1px solid #d9d9d9;
    text-align: center;
    width: 100%;
    height: 50px;
    /* cursor: pointer; */
    line-height: 47px;
    
}

</style>