<template>
    <div class="bankbook">
        <div class="explanation">
            <div class="kr-bank">
                <h1>이달의 한국은행 기준 금리</h1> <h1 class="red">3%</h1>
            </div>
            <p class="p-explanation m-t">기준금리는 한국은행이 발표하는 정책금리로, 금융기관 간 거래의 기준이 되는 금리입니다.</p>
            <p class="p-explanation">기준 금리는 30일 적금 상품에 적용되는 금리입니다.</p>
            <p class="p-explanation">적금은 은행 예금 상품의 하나로, 정기적 또는 비정기적으로 돈을 불입*하여 계약 기간이 만료된 후 이자와 함께 돌려받는 것을 의미합니다.</p>
            <p class="p-explanation">최대 3개의 적금 상품을 가입하실 수 있습니다.</p>
        </div>
        <div class="account">
            <!-- 가입 날짜로 정렬할 예정 -->
            <div class="div-box">
                <p class="have-point">보유중인 모아 포인트</p>
                <p class="have-moa">{{ Number(totalPoints).toLocaleString() }} moa</p>
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
                <div class="div-products">
                    <div class="products">
                        <p class="product-title">14일 적금</p>
                        <p class="rate-percent">이자율 : 2.1%</p>
                        <p class="rate-percent">최소 납입 포인트 : 100moa</p>
                    </div>
                    <div class="products">
                        <p class="product-title">21일 적금</p>
                        <p class="rate-percent">이자율 : 3.4%</p>
                        <p class="rate-percent">최소 납입 포인트 : 100moa</p>
                    </div>
                    <div class="products">
                        <p class="product-title">35일 적금</p>
                        <p class="rate-percent">이자율 : 4.8%</p>
                        <p class="rate-percent">최소 납입 포인트 : 100moa</p>
                    </div>
                    <div class="products">
                        <p class="product-title">49일 적금</p>
                        <p class="rate-percent">이자율 : 6.7%</p>
                        <p class="rate-percent">최소 납입 포인트 : 100moa</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup>
import { computed, ref, onMounted } from 'vue';
import { useStore } from 'vuex';
const store = useStore();

// const childPoint = computed(() => store.state.childPoint.childPoint);
const totalPoints = computed(() => store.state.childPoint.totalPoints);
console.log('totalPoints 확인 : ', totalPoints)

// onMount
onMounted(() => {
    store.dispatch('childPoint/childPoint');
});
</script>


<style scoped>

.bankbook {
    width: 1620px;
}

/* 설명 적는 div */
.explanation {
    width: 100%;
    height: 350px;
    padding: 50px;
    padding-left: 75px;
    background-color: lightgray;
}

.kr-bank {
    display: flex;
    flex-wrap: wrap;
    gap: 100px;
}

.red {
    color: red;
}

.p-explanation {
    margin-top: 20px;
    font-size: 1.3rem;
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
    background: wheat;
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
    border: 5px #a2caac solid;
    border-radius: 30px;
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
    background-color: antiquewhite;

}

.product-title {
    font-size: 1.5rem;
}

.rate-percent {
    margin-top: 30px;
}

</style>