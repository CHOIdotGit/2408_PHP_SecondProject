<template>
<div class="bankbook-container">
    <div class="bankbook">
        <div class="bankbook-hearder">
            <p class="moabank">모아은행</p>
            <div class="bankbook-info">
                <div class="bankbook-info-detail">
                    <p>적금 종류</p>
                    <p>7일 적금</p>
                    <p>납입 유형</p>
                    <p>매일</p>
                    <p>가입한 날</p>
                    <p>2025년 01월 01일</p>
                    <p>금리</p>
                    <p>3.0 %</p>
                </div>
                <div class="bankbook-profile">
                    <img src="" alt="사진">
                    <div>김주연</div>
                </div>

            </div>
        </div>
        <div class="bankbook-table">
            <div class="bankbook-title">
                <p class="title-payment-at">년 월 일</p>
                <p class="title-withdrawal">출금 금액</p>
                <p class="title-deposit">맡기신 금액</p>
                <p class="title-left-moa">거래 후 잔액</p>
                <p class="title-transaction-details">거래 내용</p>
            </div>
            <div class="bankbook-item">
                <div class="bankbook-number">
                    <p v-for="num in 23" class="num">{{ num }}</p>
                </div>
                <div class="bankbook-content">
                    <p class="payment-at content-p">2025-01-01</p>
                    <p class="withdrawal content-p">0</p>
                    <p class="deposit content-p">100</p>
                    <p class="left-moa content-p">100</p>
                    <p class="transaction-details">적금 납입</p>
                </div>

            </div>

        </div>
    </div>
</div>

</template>

<script setup>

</script>

<style scoped>

.bankbook-container {
    display: flex;
    justify-content: center;
}

/* 통장 전체 */
.bankbook {
    background-color: #ffffff;
    width: 900px;
    height: 1000px;
    border: 1px solid #000;
    border-radius: 30px 30px 0px 0px;

}

/*  */
.moabank {
    font-size: 1.8rem;
    text-align: center;
    background-color: #a2caac;
    border-radius: 30px 30px 0px 0px;
    border-bottom: 1px solid #000;
}

/* 통장 정보 영역 */
.bankbook-info {
    display: flex;
    height: 200px;
    justify-content: center;
    padding-top: 20px;
}

/* 통장 상세 정보 */
.bankbook-info-detail {
    display: grid;
    grid-template-columns: repeat(4, 100px);
    height: 100px;
}

/* 자녀 프로필 사진 $이름 */
.bankbook-profile {
    width: 100px;
    height: 100px;
    border: 1px solid #000;
    background-color: #b3b3b3;
}

.bankbook-table {
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* 통장 내역 */
.bankbook-title {
    display: grid;
    width: 830px;
    height: 40px;
    font-size: 1.3rem;
    padding-top: 7px;
    border-radius: 4px;
    /* grid-template-columns: 130px 100px 100px 100px 200px; */
    grid-template-columns: 170px 140px 140px 140px 240px;
    text-align: center;
    border: 1px solid #000;
    background-color: #a2caac;
}

.bankbook-title>p:not(5) {
    border-right: 1px solid #000;
}
.bankbook-item {
    display: flex;
    width: 830px;
}

/* 통장 내역 번호 */
.bankbook-number {
    width: 30px;
    text-align: center;
    border-right: 1px solid #000;
}

.num {
    padding: 5px;
}

.bankbook-number>p {
    border-bottom: 1px solid #000;

}

/* 통장 내역 상세 */
.bankbook-content {
    width: 600px;
    display: grid;
    grid-template-columns: 140px 140px 140px 140px 240px;
    text-align: center;
}

.bankbook-content>p {
    border-right: 1px solid #000;
    border-bottom: 1px solid #000;
    
}
.content-p{
    width: 140px;
    padding-top: 5px;
}

.payment-at {

}

.withdrawal {

}

.deposit {

}

.left-moa {

}

.transaction-details {
    padding-top: 5px;

}
</style>