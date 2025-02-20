<template>
    <div class="bank-product-container">
        <div class="bank-product-box" >
            <p class="bank-product-title">상품 특징</p>
            <div class="bank-product-content">
                <p >모아에서 특별히 자녀 경제 교육을 위한 적금 상품으로, 현금이 아닌 포인트로 이용 가능합니다.</p>
                <p>가입과 동시에 자동 이체 처리, 자동 만기 관리부터 가능합니다.</p>
            </div>
        </div>
        <div class="bank-product-box" >
            <p class="bank-product-title">가입 대상</p>
            <div class="bank-product-content">
                <p >모아를 사용하는 모든 자녀 사용자</p>
            </div>
        </div>
        <div class="bank-product-box" >
            <p class="bank-product-title">가입 금액</p>
            <div class="bank-product-content">
                <p >최소 100 최대 1000 (추가입금 불가)</p>
            </div>
        </div>
        <div class="bank-product-box" >
            <p class="bank-product-title">이자 지급 시기</p>
            <div class="bank-product-content">
                <p >만기 후 이자 지급식</p>
            </div>
        </div>
        <div class="bank-product-box" >
            <p class="bank-product-title">세제 해택</p>
            <div class="bank-product-content">
                <p>우리나라는 이자소득 원천징수세 15.4%(소득세 14%, 지방소득세 1.4%)를 부과하나</p>
                <p>모아 은행은 자녀의 경제 교육을 위한 포인트 통장으로 발생한 이자에 대한 소득제를 부과하지 않습니다</p>
            </div>
        </div>
        <div class="bank-product-box" >
            <p class="bank-product-title">예금자 보호 제도</p>
            <div class="bank-product-content">
                <img src="/img/kdic_logo_new.png" alt="">
                <p >예금자보호제도는 다수의 소액예금자를 우선 보호하고</p>
                <p>부실 금융회사를 선택한 예금자도 일정부분 책임을 분담한다는 차원에서 예금의 전액을 보호하지 않고 일정액만을 보호하고 있습니다</p>
                <p>원금과 소정이자를 합하여 1인당 5천만원까지만 보호되며 초과금액은 보호되지 않습니다</p>
            </div>
        </div>
    </div>

</template>

<script setup>

</script>

<style scoped>
.bank-product-container {
    margin-top: 30px;
    
    font-size: 1.2rem;
}

.bank-product-box {
    border-bottom: 1px solid #f1f1f1;
    display: flex;
    padding: 20px;
    width: 700px;
}

.bank-product-title {
    width: 150px;
    margin-right: 10px;
    color: #bd842e;
    font-weight: 600;
}

.bank-product-content {
    width: 500px;
    line-height: 34px;
}

</style>