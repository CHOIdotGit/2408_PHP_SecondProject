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
                <div class="bank-form-content">
                    <input type="num" class="amountInput" maxlength="4" v-model="regist.amount"> 모아
                </div>
                <p class="guide">*최소 100 ~ 최대 1000 금액을 입력할 수 있습니다</p>
            </div>
            <div class="bank-form">
                <p class="bank-form-title">납입 유형</p>
                <p class="bank-form-content">{{ getTypeText(productInfo.saving_product_type) }}</p>
            </div>
            <div class="bank-form">
                <p class="bank-form-title">납입 날짜</p>

                <!-- 매일 적금일때 ---- 비활성화 -->
                <!-- 매주 적금일때 ---- 활성화 -->
                <div class="selectdays">
                    <div class="dayOption" v-for="item in days" :key="item" >
                        <input type="radio" :value="item"  name="days" :id="'day-'+item" class="days-btn"  :disabled="isDisabled(item)" v-model="selectdays" >
                        <label :for="'day-' + item" >
                            <p>{{ dayMatching(item) }}</p>
                        </label>
                    </div>
                </div>
                

            </div>
        </div>

    </div>
    <div class="bank-box-bottom">
        <div class="box-btn cancel">돌아가기</div>
        <div class="box-btn" @click="goBank">가입하기</div>
    </div>


</div>

</template>

<script setup>
import { computed, onBeforeMount, onMounted, reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const route = useRoute();
const router = useRouter();


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

// 요일 선택하기
const days = ref(["0", "1", "2", "3", "4", "5", "6", "7"]);

//요일 매칭
const dayMatching = (item) => {
    const dayLabel = {
        "0": "일",
        "1": "월",
        "2": "화",
        "3": "수",
        "4": "목",
        "5": "금",
        "6": "토",
        "7": "매일"
    }
    return dayLabel[item];
}

const selectdays = ref("7"); // 기본값 : 매일 선택
console.log('선택된 날짜 :', selectdays.value);

const isDisabled = (item) => {
    if(productInfo.value.saving_product_type === "0") {
        return item.days !== "7";
    }
    else {
        return item.days === "7";

    }
};

// 적금 가입하기
const today = ref(new Date().toISOString().slice(0, 10));
const endDay = ref(new Date().toISOString().slice(0, 10)); 
const regist = reactive({
    amount: ''
    ,child_id: route.params.child_id
    ,saving_product_id: route.params.product_id
    ,saving_sign_up_status: productInfo.value.saving_product_type
    ,saving_sign_up_start_day: today
    ,saving_sign_up_end_day: endDay
    ,saving_sign_up_deposit_at: selectdays.value
})

const goBank = () =>{
    store.dispatch('saving/createSaving', regist);
}

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

/* *적금 상품 가입 입력 란 start************************ */
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

.amountInput {
    height: 50px;
    border: #dbdbdb 1px solid;
    width: 180px;
    text-align: end;
}

.amountInput:focus {
    outline: none;
    border: #5589e996 2px solid;
}

.guide {
    font-size: 0.8rem;
    color: #7d7d7d;
    line-height: 125px;
}

/* 날짜 선택 버튼 */
.selectdays {
    display: flex;
    align-items: center;
    gap: 20px;
    padding-left: 20px;
    text-align: center;
}

.days-btn {
    font-size: 1.2rem;
    background-color: #c9c9c9;
}

.days-btn>input {
    display: hidden;
}
/* ************************************end */

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