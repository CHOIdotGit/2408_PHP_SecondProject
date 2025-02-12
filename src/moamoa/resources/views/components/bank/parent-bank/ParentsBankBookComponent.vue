<template>
    <div class="bankbook-wrapper">
        <div class="bankbook">
            <!-- 상단 헤더 (은행명) -->
            <div class="bankbook-header">
                <p class="bank-name">모아은행</p>
            </div>
            <!-- 통장 정보 -->
            <div class="bankbook-info">
                <div class="info-detail">
                    <div class="b-info">
                        <p>적금 종류</p>
                        <p>7일 적금</p>
                    </div>
                    <div class="b-info">
                        <p class="margin-left">납입 유형</p>
                        <p>매일</p>
                    </div>
                    <div class="b-info">
                        <p>가입한 날</p>
                        <p>2025년 01월 01일</p>
                    </div>
                        <div class="b-info">
                        <p class="margin-left">금리</p>
                        <p>3.0 %</p>
                    </div>
                </div>
                <div class="bankbook-profile">
                    <img src="" alt="사진">
                    <div>김주연</div>
                </div>
            </div>
            <!-- 거래 내역 -->
            <div class="bankbook-table">
                <div class="bankbook-title">
                    <p>년 월 일</p>
                    <p>출금 금액</p>
                    <p>맡기신 금액</p>
                    <p>거래 후 잔액</p>
                    <p>거래 내용</p>
                </div>
                <div class="bankbook-item">
                    <div class="bankbook-number">
                        <p v-for="num in 20" :key="num" class="num">{{ num }}</p>
                    </div>
                    <div class="bankbook-transactions">
                        <p>2025-01-01</p>
                        <p>0</p>
                        <p>100</p>
                        <p>100</p>
                        <p>적금 납입</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script setup>
</script>
  
<style scoped>
@import url('../../../../css/bankbook.css');

</style>
  