<template>
    <div class="bank-product-container">
        <div class="bank-product-box" >
            <p class="bank-product-title">한국은행 기준금리</p>
            <div class="bank-product-content">
                <p >한국은행 기준금리는 한국은행이 금융기관과 환매조건부증권(RP) 매매, 자금조정 예금 및 대출 등의 거래를 할 때 기준*이 되는 정책금리로서 간단히 ‘기준금리’(base rate)라고도 한다.</p>
                <p>한국은행은 기준금리를 7일물 RP매각시 고정입찰금리로, 7일물 RP매입시 최저입찰금리(minimum bid rate)로 사용한다. 자금조정예금 금리는 기준금리에서 50bp를 차감한 이율(최저이율은 0%)로, 자금조정대출 금리는 기준금리에서 50bp를 더한 이율(기준금리가 1% 미만일 경우 기준금리의 2배)로 운용한다.</p>
                <p>한국은행 금융통화위원회는 물가 동향, 국내외 경제 상황, 금융시장 여건 등을 종합적으로 고려하여 연 8회 기준금리를 결정하고 있다. 이렇게 결정된 기준금리는 초단기금리인 콜금리에 즉시 영향을 미치고, 장단기 시장금리, 예금 및 대출 금리 등의 변동으로 이어져 궁극적으로는 실물경제 활동에 영향을 미치게 된다.</p>
            </div>
        </div>
        <div class="bank-product-box" >
            <p class="bank-product-title">단리</p>
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