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
  /* 중앙 정렬 */
  .bankbook-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 1000px;
  }
  
  /* 통장 스타일 */
  .bankbook {
    width: 1000px;
    height: 1000px;
    background: linear-gradient(180deg, #f3f4f6, #ffffff);
    border: 1px solid #aaa;
    border-radius: 30px 30px 0 0;
    box-shadow: 2px 4px 8px rgba(0, 0, 0, 0.2);
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
  }
  
  .bankbook::before {
    content: "";
    position: absolute;
    top: 66%;
    left: 0;
    width: 100%;
    height: 1px;
    background: linear-gradient(to right, rgba(0, 0, 0, 0.1), transparent 50%, rgba(0, 0, 0, 0.1));
    box-shadow: 0 -2px 2px rgba(0, 0, 0, 0.05), 0 2px 2px rgba(255, 255, 255, 0.3);
    transform: translateY(-45%);
  }
  
  /* 상단 헤더 */
  .bankbook-header {
    width: 100%;
    height: 60px;
    background-color: #e4eff4;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 2rem;
    font-weight: bold;
    color: #333;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  }
  
  /* 통장 정보 */
  .bankbook-info {
    width: 100%;
    height: 250px;
    display: flex;
    justify-content: center;
  }
  
  .info-detail {
    display: grid;
    grid-template-columns: repeat(2, 350px);
    gap: 25px;
    font-size: 1.5rem;
    text-align: center;
    padding: 25px 0;
    align-items: center;
  }
  
  /* 거래 내역 테이블 */
  .bankbook-table {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
  }
  
  .bankbook-title {
    width: 900px;
    height: 40px;
    display: grid;
    grid-template-columns: 184px 154px 154px 154px 254px;
    align-items: center;
    text-align: center;
    border: 1px solid #000;
    background-color: #c8e5f3;
    font-size: 1.3rem;
  }
  
  .bankbook-item {
    display: flex;
    width: 900px;
  }
  
  .bankbook-number {
    width: 30px;
    text-align: center;
    border-right: 1px solid black;
  }
  
  .num {
    padding: 5px;
  }
  
  .bankbook-number > p {
    border-bottom: 1px solid black;
  }
  
  .bankbook-transactions {
    width: 870px;
    display: grid;
    grid-template-columns: 154px 154px 154px 154px 254px;
    text-align: center;
  }
  
  .bankbook-transactions > p {
    border-right: 1px solid black;
    border-bottom: 1px solid black;
  }
  
  /* 정보 정렬 */
  .b-info {
    height: 50px;
    display: flex;
    align-items: center;
    border-radius: 8px;
    gap: 45px;
  }

  /* 자녀 프로필 사진 $이름 */
.bankbook-profile {
    width: 150px;
    height: 200px;
    border: 1px solid #000;
    background-color: #b3b3b3;
    margin: 25px 0 25px 25px;
}

.margin-left {
    margin-left: 40px;
}

</style>
  