<template>
    <!-- 적금 가입하는 페이지 -->
    <div class="bank-product-register-container">
        <h1>적금 상품 가입</h1>
        <div class="headline">
            <p class="headline-1">개인 약관 동의</p>
            <p class="headline-2">2</p>
        </div>
        <!-- 약관 동의 -->
        <div class="bank-form-box">
            <div class="bank-form">
                <p class="bank-form-title">가입 대상</p>
                <div class="box-flex">
                    <p class="bank-form-content">모아에 가입한 자녀 사용자 (1인 최대 3 적금 통장)</p>

                </div>
            </div>
            <div class="bank-form">
                <p class="bank-form-title">가입 기간</p>
                <div class="box-flex">
                    <p class="bank-form-content">{{ productInfo.saving_product_period }} 일</p>

                </div>
            </div>
            <div class="bank-form">
                <p class="bank-form-title">최소 가입(납입) 금액</p>
                <div class="box-flex">
                    <p class="bank-form-content">100 모아</p>

                </div>
            </div>
            <div class="bank-form">
                <p class="bank-form-title">최대 가입(납입) 금액</p>
                <p class="bank-form-content">1,000 모아</p>
            </div>

            <div class="bank-form">
                <p class="bank-form-title">납입 방법</p>
                <div class="box-flex">
                    <p class="bank-form-content">모아에서 받은 포인트를 통해서만 납입 가능 </p>
                    <p class="bank-form-content">모아 은행 적금 상품에 가입 후 자동으로 납입</p>
                    <p class="bank-form-content">포인트의 잔액 부족 시 미납 처리, 추후 납입 불가능</p>

                </div>
            </div>
            <div class="bank-form">
                <p class="bank-form-title">금리</p>
                <div class="box-flex">
                    <p class="bank-form-content">한국은행 기준금리를 기준으로 모아은행에서 제시하는 변동이율 적용</p>
                    <p class="bank-form-content">모아 은행 적금 상품에 가입 후 자동으로 납입</p>
                    <p class="bank-form-content">포인트의 잔액 부족 시 미납 처리, 추후 납입 불가능</p>

                </div>
            </div>
            <div class="bank-form">
                <p class="bank-form-title">만기 시 자동해지</p>
                <div class="box-flex">
                    <p class="bank-form-content">만기일에 자동으로 해지되어 모아 은행 포인트 잔액에 자동으로 추가</p>

                </div>
            </div>
            <div class="bank-form">
                <p class="bank-form-title">이자 계산법</p>
                <div class="box-flex">
                    <p class="bank-form-content">모아 은행은 한국 시중 은행의 이율계산법을 기반으로 합니다</p>
                    <p class="bank-form-content">모아 은행 포인트 잔액에 자동으로 추가</p>

                </div>
            </div>

        </div>
        <!-- 적금 가입 동의하기 버튼 -->
        <div class="bank-box-bottom">
            <div class="bank-guide">※ 모아은행 적금 상품 가입에 동의하십니까</div>
            <div class="agreeBtn">
                <label for="agree-no" :class="{'checkRadio': agreement === 'no' }" class="radioStyle">
                    <input type="radio" id="agree-no" value="no" name="agree" v-model="agreement">
                    <p>아니요, 동의하지 않습니다.</p>
                </label>
                
                <label for="agree-yes" :class="{'checkRadio': agreement === 'yes' }" class="radioStyle">
                    <input type="radio" id="agree-yes" value="yes" name="agree" v-model="agreement">
                    <p>네, 동의합니다.</p>
                </label>
            </div>

            <div class="registBtn">
                <div class="box-btn cancel" @click="router.push('/moabank/product')">돌아가기</div>
                <div class="box-btn" @click="goResigt(agreement)">가입하기</div>
            </div>
        </div>
    <!-- ************************* -->
    <!-- ********모달********* -->
    <!-- ************************* -->
        <div class="base-modal-overlay" v-show="agreeModal">
            <div class="base-modal-box">
            <div class="base-modal-content">
                약관에 동의하시길 바랍니다
            </div>

            <div class="base-modal-btn">
                <button type="button" class="base-modal-submit" @click="closeModal">확인</button>
                <!-- <button type="button" class="base-modal-cancel" @click="closeModal">취소</button> -->
            </div>
            </div>
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

const agreement = ref("");

// 모달 처리
const agreeModal = ref(false);
const closeModal = () => {
    agreeModal.value = false;
}
const goResigt = () => {
    if(agreement.value === 'yes') {
        router.push('/moabank/product/regist/' + route.params.product_id);
        console.log('동의함', agreement.value);
    }

    else if(agreement.value === 'no') {
        agreeModal.value = true;
    }

    else {
        agreeModal.value = true;
    }
}


// 적금 상품 가져오기
const productInfo = computed(() => store.state.bank.productInfo);

// 적금에 가입할 자녀 정보
const childInfo = computed(()=> store.state.header.childInfo);
onBeforeMount(async () => {
    await store.dispatch('header/childInfo');
})

onMounted(() => {
    const id = route.params.product_id;
    console.log('상품 ID:', route.params);  // id가 제대로 전달되는지 확인
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
    margin-bottom: 30px;
    margin-left: 273px;
}

.headline {
    padding-bottom: 5px;
    display: flex;
    gap: 30px;
    font-size: 1.2rem;
    flex-direction: flex-start;
    margin-left: 273px;
    /* margin-bottom: 50px; */
}

.headline-1 {
    border: 2px solid #5589e996;
    border-radius: 30px;
    padding: 5px;
    color: #5589e996;
}

.headline-2 {
    color: #fff;
    background-color: #5589e996;
    border-radius: 50px;
    padding: 7px;
    width: 50px;
    text-align: center;
    
}

.bank-product-register-container {
    display: flex;
    flex-direction: column;
    /* margin: auto; */


}

/* *적금 상품 가입 입력 란 start************************ */
.bank-form-box {
    border-top: 3px solid #e0e7ee;
    border-bottom: 3px solid #e0e7ee;
    margin: auto;
    margin-top: 10px;
}

.bank-form {
    display: flex;
    align-items: center;
    border-bottom: 1px solid #e0e7ee;
    /* width: 700px;
    height: 80px; */
    /* padding: 10px; */
    /* gap: 50px; */
}

.bank-form-title {
    /* line-height: 77px; */
    width: 250px;
    height: 100px;
    font-size: 1.4rem;
    background: #f6f6f6;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bank-form-content {
    /* line-height: 77px; */
    font-size: 1.2rem;
    padding-left: 20px;
}

.box-flex {
    display: flex;
    flex-direction: column;
    width: 700px;
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
    flex-direction: column;
    margin-top: 20px;
    align-items: center;
    gap: 20px;
}

.agreeBtn {
    display: flex;
    gap: 55px;
}

/*  기본스타일 제거, 버튼 모양 재설정 */
input[type='radio'] {
  -webkit-appearance: none;  
  -moz-appearance: none; 
  appearance: none; 
  width: 13px;
  height: 13px;
  border: 1px solid #ccc; 
  border-radius: 50%;
  outline: none; 
  cursor: pointer;
}

/* 체크 시 버튼 모양 스타일 */
input[type='radio']:checked {
    border: 4px solid #ee8432;
    background-color: #fff5ee;
}

.radioStyle {
    display: flex;
    gap: 10px;
    /* box-shadow: 3px 3px 10px #dcdcdc; */
    border: 2px solid #ebebeb;
    border-radius: 15px;
    width: 240px;
    height: 46px;
    align-items: center;
    padding: 10px;
    cursor: pointer;
}

.checkRadio {
    border: 2px solid #ee8432;
    background-color: #fff5ee;
}

/* 가입멘트 */
.bank-guide {
    font-size: 1.8rem;
}

.registBtn {
    display: flex;
    gap: 100px;
}

.bank-box-bottom:first-child {
    font-size: 1.5rem;
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


/* ************* */
/*     모달      */
/* ************* */
/* 버튼 손모양 */
button {
cursor: pointer;
}

/* 뒷배경 */
.base-modal-overlay {
position: fixed;
top: 0;
left: 0;
width: 100%;
height: 100%;
background-color: rgba(0, 0, 0, 0.5);
display: flex;
justify-content: center;
align-items: center;
z-index: 1000;
}

/* 모달 박스 */
.base-modal-box {
background-color: #fff;
padding: 25px;
border-radius: 10px;
width: 430px;
height: 330px;
display: flex;
flex-direction: column;
justify-content: space-between;
align-items: center;
border: 3px solid #A2CAAC;
}

/* 각 넓이 설정 */
.base-modal-content, .base-modal-btn {
width: 100%;
font-size: 1.6rem;
text-align: center;
line-height: 227px;
}

/* 버튼 중앙 정렬 */
.base-modal-btn {
display: flex;
justify-content: center;
align-items: center;
column-gap: 75px;
}

/* 각 버튼 설정 */
.base-modal-btn > button {
padding: 12px 40px;
border: none;
border-radius: 5px;
font-size: 1.1rem;
}

/* 확인 버튼 */
.base-modal-submit {
background-color: #A2CAAC;
color: #fff;
}

/* 취소 버튼 */
.base-modal-cancel {
background-color: #F3F3F3;
}
  

</style>