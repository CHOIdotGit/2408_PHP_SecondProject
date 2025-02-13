<template>
<!-- 적금 가입하는 페이지 -->
<div class="bank-product-register-container">
    <div>
        <h1>적금 상품 가입</h1>
        <div class="headline">

        </div>
        <p>정보 입력</p>
        <div class="bank-form-box">
            <div class="bank-form">
                <p class="bank-form-title">상품명</p>
                <p class="bank-form-content">{{ productInfo.saving_product_name }} 적금</p>
            </div>
            <div class="bank-form">
                <p class="bank-form-title">예금주</p>
                <p class="bank-form-content">{{ childInfo.name}}</p>
            </div>
            <div class="bank-form">
                <p class="bank-form-title">납입 기간</p>
                <p class="bank-form-content">{{ productInfo.saving_product_period }} 일</p>
            </div>
            <div class="bank-form">
                <p class="bank-form-title">납입 금액</p>
                <input type="num" maxlength="4" > 모아
            </div>
            <div class="bank-form">
                <p class="bank-form-title">납입 유형</p>
                <p class="bank-form-content">{{ getTypeText(productInfo.saving_product_type) }}</p>
            </div>
            <div class="bank-form">
                <p class="bank-form-title">납입 날짜</p>
                <!-- <div class="selectDay" v-for="item in 7" :key="item">
                    <input type="radio" name="day">
                    <label :for="'day' + item.days">
                        <p>{{ days }}</p>
                    </label>
                </div> -->
                <!-- <div class="selectDay" v-for="item in days" :key="item">
                    <input type="radio" name="days" :value="item.days" :id="'day-' + item.days" ></input>
                    <label :for="'day-' + item.days" >
                        <p class="day-name">{{ item.days }}</p>
                    </label>
                </div> -->
                <div class="selectdays" v-for="item in days" :key="item" v-if="productInfo.saving_product_type === '0'">
                    <input type="radio" :value="item.days"  name="days" :id="'day-'+item.days" class="days-btn" disabled >
                    <label :for="'day-' + item" >
                        <p>{{ item }}</p>
                    </label>
                </div>
                <div class="selectdays" v-for="item in days" :key="item" v-if="productInfo.saving_product_type === '1'">
                    <input type="radio" :value="item.days"  name="days" :id="'day-'+item.days" class="days-btn" >
                    <label :for="'day-' + item" >
                        <p>{{ item }}</p>
                    </label>
                </div>

            </div>
        </div>

    </div>
    <div class="bank-box-bottom">
        <div class="box-btn cancel">돌아가기</div>
        <div class="box-btn">가입하기</div>
    </div>


</div>

</template>

<script setup>
import { computed, onBeforeMount, onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const route = useRoute();
const router = useRouter();
const days = ref(["일","월", "화", "수", "목", "금", "토"]);

// 적금 상품 가져오기
const productInfo = computed(() => store.state.bank.productInfo);

// 적금에 가입할 자녀 정보
const childInfo = computed(()=> store.state.header.childInfo);
onBeforeMount(async () => {
    await store.dispatch('header/childInfo');
})

onMounted(() => {
    const id = route.params.id;
    console.log('상품 ID:', id);  // id가 제대로 전달되는지 확인
    store.dispatch('bank/registrationProduct', id);
});

// 적금 유형 문자로 변환
const getTypeText = (saving_product_type) => {
    const typeMapping = {
        "0": '매일 납입',
        "1": '매주 납입',
    };
    return typeMapping[saving_product_type]; // 기본값 없이 반환
};

</script>

<style scoped>
h1 {
    font-size: 2.5rem;
    margin-bottom: 20px;
}

.headline {
    padding-bottom: 5px;
}

.bank-product-register-container {
    display: flex;
    flex-direction: column;
    /* margin: auto; */
    align-items: center;

}

.bank-form-box {
    border-top: 3px solid #e0e7ee;
    border-bottom: 3px solid #e0e7ee;
}

.bank-form {
    display: flex;
    
    border-bottom: 1px solid #e0e7ee;
    width: 700px;
    height: 80px;
    /* padding: 10px; */
    /* gap: 50px; */
}

.bank-form-title {
    line-height: 77px;
    width: 180px;
    font-size: 1.8rem;
    background: #f6f6f6;
    text-align: center;
}

.bank-form-content {
    line-height: 77px;
    font-size: 1.4rem;
    padding-left: 20px;
}

.input[type="num"] {
    width: 100px;
    outline: none;
    border: none;
}

/* 날짜 선택 버튼 */
.days-btn {
    font-size: 1.2rem;
    background-color: #c9c9c9;
}

.days-btn>input {
    display: hidden;
}


/* 가입,취소 버튼 */
.bank-box-bottom {
    display: flex;
    margin-top: 20px;
    /* align-items: flex-end; */
    gap: 20px;
}

.box-btn {
    background-color: #ffcf0f;
    cursor: pointer;
    width: 150px;
    height: 40px;
    text-align: center;
    line-height: 40px;
}

.cancel {
    background-color: #fff;
    border: 1px solid #c9c9c9;
    color: #c9c9c9;
}



</style>