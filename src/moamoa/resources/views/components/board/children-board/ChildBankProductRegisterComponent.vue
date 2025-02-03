<template>
<div class="bank-product-register-container">
    <div>
        <h1>적금 상품 가입</h1>
        <div class="headline">

        </div>
        <p>정보 입력</p>
        <div class="bank-form-box">
            <div class="bank-form">
                <p class="bank-form-title">상품명</p>
                <p class="bank-form-content">7일 적금</p>
            </div>
            <div class="bank-form">
                <p class="bank-form-title">예금주</p>
                <p class="bank-form-content">배현진</p>
            </div>
            <div class="bank-form">
                <p class="bank-form-title">납입 기간</p>
                <p class="bank-form-content">7일</p>
            </div>
            <div class="bank-form">
                <p class="bank-form-title">납입 금액</p>
                <input type="num" maxlength="4" > 모아
            </div>
            <div class="bank-form">
                <p class="bank-form-title">납입 유형</p>
                <p class="bank-form-content">매일</p>
            </div>
            <div class="bank-form">
                <p class="bank-form-title">납입 날짜</p>
                <div class="selectDay" v-for="item in days" :key="item">
                    <input type="radio" name="day">
                    <label :for="'day' + item"></label>
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
import { ref } from 'vue';

const days = ref("월", "화")


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
    padding: 10px;
    /* gap: 50px; */
}

.bank-form-title {
    line-height: 60px;
    width: 180px;
    font-size: 1.8rem;

}

.bank-form-content {
    line-height: 60px;
    font-size: 1.4rem;
}

.input[type="num"] {
    width: 100px;
    outline: none;
    border: none;
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