<template>
    <div class="bankbook">
        <div class="explanation">
            <div class="kr-bank">
                <h1>이달의 한국은행 기준 금리</h1>
                <!-- ### 한국은행 기준금리 api ### -->
                <p class="red" v-if="koreaBankInterest">{{ Number(koreaBankInterest.interest).toFixed(1) }} %</p>
            </div>
            <p class="p-explanation m-t">* 기준금리는 한국은행이 발표하는 정책금리로, 금융기관 간 거래의 기준이 되는 금리입니다.</p>
            <p class="p-explanation">* 기준 금리는 30일 적금 상품에 적용되는 금리입니다.</p>
            <p class="p-explanation">* 적금은 은행 예금 상품의 하나로, 정기적 또는 비정기적으로 돈을 불입*하여 계약 기간이 만료된 후 이자와 함께 돌려받는 것을 의미합니다.</p>
            <p class="p-explanation">* 최대 3개의 적금 상품을 가입하실 수 있습니다.</p>
        </div>
        <div class="account">
            <!-- 가입 날짜로 정렬할 예정 -->
            <div class="div-box">
                <p class="have-point">보유중인 모아 포인트</p>
                <p class="have-moa">5,000 moa</p>
                <p class="subscribe">현재 가입한 적금 상품 : 2개</p>
            </div>
            <div class="div-box">
                <p class="have-point">모아 적금통장</p>
                <p class="have-moa">14일 적금</p>
                <div class="interest-rate">
                    <p>이자율 : </p>
                    <div>2.1%</div>
                </div>
            </div>
            <div class="div-box">
                <p class="have-point">모아 적금통장</p>
                <p class="have-moa">35일 적금</p>
                <div class="interest-rate">
                    <p>이자율 : </p>
                    <div>4.8%</div>
                </div>
            </div>
            <div class="div-box">
                <p class="have-point">모아 적금통장</p>
                <p class="non-product p-t">가입한 적금 상품이 없습니다.</p>
                <p class="non-product">새로운 적금 상품을 가입하시겠습니까?</p>
            </div>
        </div>
        <div class="savings-product">
            <div class="outline">
                <h1>모아은행 적금 상품</h1>
                <div class="div-products" >
                    <div class="products" v-for="item in savingProduct" :key="item">
                        <p class="product-title">⭐{{ item.saving_product_name }} 적금</p>
                        <p class="rate-percent">이자율 : {{ item.saving_product_interest_rate}} %</p>
                        <p class="rate-percent">최소 납입 포인트 : {{ item.saving_product_amount }} moa</p>
                    </div>
                    <!-- 더보기 누르면 적금 상품 더보이게(페이지네이션 처리) -->
                    <div>
                        <p @click="addSavingPage" class="more">더보기</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>


<script setup>
import { computed, onMounted } from 'vue';
import { useStore } from 'vuex';


const store = useStore();

// 한국은행 기준금리 api 가져오기
const koreaBankInterest = computed(()=> store.state.bank.bankInterest);
onMounted(() => {
    store.dispatch('bank/koreaBank');
});

// 적금 상품 가져오기
const savingProduct = computed(()=> store.state.bank.savingList);
onMounted(()=> {
    store.dispatch('bank/savingProductList');
})



const addSavingPage = () => {
    console.log('적금상품 더보기');
    store.dispatch('bank/addsavingList');

}


</script>


<style scoped>

.bankbook {
    width: 1600px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* 설명 적는 div */
.explanation {
    width: 85%;
    height: 350px;
    padding: 50px;
    padding-left: 75px;
    background-color: #f8f8f8;
}

.kr-bank {
    display: flex;
    border: 1px solid #e0e7ee;
    border-radius: 10px;
    width: 60%;
}

.red {
    color: red;
    font-family: 'LAB디지털';
    background-color: #fff;
    font-size: 2rem;
    width: 150px;
    padding: 5px;
}

.p-explanation {
    margin-top: 20px;
    font-size: 1rem;
}

.m-t{
    margin-top: 30px;
}

.p-t {
    padding-top: 50px;
}

/* 통장 관리 부분 */
.account {
    width: 100%;
    margin: 20px 0 20px 0;
    padding: 50px;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    gap: 50px
}

.div-box {
    width: 500px;
    height: 250px;
    margin-right: 50px;
    text-align: center;
    background: #c9e6d7;
    border-radius: 30px;
}

.have-point {
    margin-top: 20px;
    font-size: 2rem;
}

.have-moa {
    margin-top: 60px;
    font-size: 1.5rem;
}

.subscribe {
    margin-top: 50px;
    font-size: 1.2rem;

}

.non-product {
    margin-top: 10px;
    font-size: 1.3rem;
    font-weight: 600;
}

.interest-rate {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    gap: 10px;
    margin-top: 50px;
    padding-left: 230px;
    font-size: 1.2rem;
}

/* 적금 상품들 */
.savings-product {
    margin: 50px 0 50px 75px;

}

.outline {
    width: 1470px;
    text-align: center;
    /* 부모 색깔 */
    background-color: #e8ecdc; 

    /* 자녀 색깔 */
    /* background-color: #e4eff4; */
    border-radius: 10px;
    height: 350px;
    padding: 20px;

}

.div-products {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    margin-top: 50px;
    gap: 75px;

}

.products {
    width: 200px;
    height: 200px;
    padding-top: 20px;
    border: 1px solid #ddd;
    background-color: #fff;

}



.product-title {
    font-size: 1.5rem;
}

.rate-percent {
    margin-top: 30px;
}

.more {
    cursor: pointer;
}

.more:hover {
    text-decoration: underline;
}

</style>