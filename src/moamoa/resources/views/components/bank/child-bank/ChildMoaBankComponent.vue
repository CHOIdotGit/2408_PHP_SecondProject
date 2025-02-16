<template>
    <div class="bank">
        <div class="explanation">
            <div class="kr-bank">
                <div class="kr-bank-headline">이달의 한국은행 기준 금리</div>
                <!-- ### 한국은행 기준금리 api ### -->
                <p class="red" v-if="koreaBankInterest">{{ Number(koreaBankInterest.interest).toFixed(1) }} %</p>
            </div>
            <p class="p-explanation m-t">기준금리는 한국은행이 발표하는 정책금리로, 금융기관 간 거래의 기준이 되는 금리입니다.</p>
            <p class="p-explanation">기준 금리는 30일 적금 상품에 적용되는 금리입니다.</p>
            <p class="p-explanation">적금은 은행 예금 상품의 하나로, 정기적 또는 비정기적으로 돈을 불입*하여 계약 기간이 만료된 후 이자와 함께 돌려받는 것을 의미합니다.</p>
            <p class="p-explanation">최대 3개의 적금 상품을 가입하실 수 있습니다.</p>
        </div>
        <div class="account">
            <!-- 가입 날짜로 정렬할 예정 -->
            <div class="div-box" @click="goPointList()">
                <p class="have-point">보유중인 모아 포인트</p>
                <p class="have-moa">{{ Number(totalPoints).toLocaleString() }} moa</p>
                <p class="subscribe">현재 가입한 적금 상품 : 2개</p>
            </div>
            <div class="div-box" v-for="item in savingList" :key="item"  @click="goSavingDetail(item.saving_sign_up_id)">
                <p class="have-point">모아 적금통장</p>
                {{ savingList.length }}
                <p class="have-moa" >{{ item.saving_product_name }} 적금</p>
                <div class="div-box-item">
                    <p >잔액</p>
                    <div>{{ item.total }}moa</div>
                </div>
                <div class="div-box-item">
                    <p>이자율 : </p>
                    <div>{{ item.saving_product_interest_rate.toFixed(1) }} %</div>
                </div>
            </div>
                <!-- 빈 통장 슬롯 -->
                <!-- <div class="div-box" @click="goSavingProduct" v-for="item in empty" :key="item" >
                    <p class="have-point">모아 적금통장</p>
                    <p class="non-product p-t">가입한 적금 상품이 없습니다.</p>
                    <p class="non-product">새로운 적금 상품을 가입하시겠습니까?</p>
                </div> -->
                <div class="div-box" @click="goSavingProduct"  >
                    <p class="have-point">모아 적금통장</p>
                    <p class="non-product p-t">가입한 적금 상품이 없습니다.</p>
                    <p class="non-product">새로운 적금 상품을 가입하시겠습니까?</p>
                </div>
                <div class="div-box" @click="goSavingProduct"  >
                    <p class="have-point">모아 적금통장</p>
                    <p class="non-product p-t">가입한 적금 상품이 없습니다.</p>
                    <p class="non-product">새로운 적금 상품을 가입하시겠습니까?</p>
                </div>

            </div>
        </div>
    
    
</template>


<script setup>
import { computed, ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const router = useRouter();
// const childPoint = computed(() => store.state.childPoint.childPoint);
const totalPoints = computed(() => store.state.childPoint.totalPoints);
console.log('totalPoints 확인 : ', totalPoints)
// 자녀 적금 상품 가져 오기
const savingList = computed(()=> store.state.saving.childSavingList);

// 자녀 포인트 받아오기
// 한국은행 기준금리 api 가져오기
const koreaBankInterest = computed(()=> store.state.bank.bankInterest);
onMounted(() => {
    store.dispatch('childPoint/childPoint');
    store.dispatch('bank/koreaBank');
    store.dispatch('saving/childSaving');
});

// 자녀 통장 페이지로 이동
const goSavingDetail = (saving_sign_up_id) => {
    const bankbook_id = saving_sign_up_id;
    store.dispatch('saving/childSavingDetail', bankbook_id);
    router.push('/child/bankbook/' + bankbook_id);
    console.log('자녀 적금 통장 페이지로 이동', bankbook_id);
}

// 자녀 포인트 페이지로 이동
const goPointList = (saving_sign_up_id) => {
    // const bankbook_id = saving_sign_up_id;
    // store.dispatch('saving/childSavingDetail', bankbook_id);
    // router.push('/child/bankbook/' + bankbook_id);
    // console.log('자녀 적금 통장 페이지로 이동', bankbook_id);
}

// 적금 상품 페이지로 이동
const goSavingProduct = () => {
    router.push('/moabank/product');
}

// 적금 통장 빈 공간
const empty = computed(()=> {
    const total = 3;
    const aa = total - savingList.legnth;
})



</script>


<style scoped>

.bank {
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
    border-radius: 10px;
    width: 60%;
}

.kr-bank-headline {
    font-size: 2.3rem;
    padding: 5px;
    margin-right: 20px;
    font-weight: 700
}

.red {
    color: red;
    font-family: 'LAB디지털';
    font-size: 2rem;
    width: 150px;
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
    cursor: pointer;
}

.div-box:hover {
    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
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

.div-box-item {
    display: flex;
    justify-content: end;
    align-items: center;
    gap: 10px;
    padding-right: 50px;
    font-size: 1.3rem;
}

/* 적금 상품들 */
.savings-product {
    margin: 50px 0 50px 75px;

}

.outline {
    width: 1470px;
    text-align: center;
    background-color: #e8ecdc;
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
    /* background-color: antiquewhite; */

}

.product-title {
    font-size: 1.5rem;
}

.rate-percent {
    margin-top: 30px;
}

</style>