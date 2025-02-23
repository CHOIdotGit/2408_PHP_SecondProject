<template>
<div class="userManual-container">
    <div class="userManual-head">
        <h1>이용자 매뉴얼</h1>
        <div>모아의 사용자 일반 매뉴얼 입니다</div>
    </div>
    <div class="tap-menu">
        <!-- <div :class="openBankEconomic ? 'active' : 'tap-item'" @click="economicEdu">회원가입</div>
        <div :class="openBankGuide ? 'active' : 'tap-item'" @click="bankGuide">상품 안내</div> -->
        <div class="tap-item" @click="userRegist">회원가입</div>
        <div class="tap-item" @click="spend">지출</div>
        <div class="tap-item" @click="mission">미션</div>
        <div class="tap-item" @click="calendar">캘린더</div>
        <div class="tap-item" @click="statis">통계</div>
        <div class="tap-item" @click="bank">은행</div>
    </div>
    <div class="userManual-body">
        <div>
        </div>
        <div>

        </div>
        <div class="userManual-content">
            <h2>보호자</h2>
            <div class="userManual-box">
                <div>
                    <img src="" alt="">
                </div>
                
            </div>
            <h2>자녀</h2>
            <div class="userManual-box"></div>
        </div>

    </div>
</div>

</template>

<script setup>

</script>

<style scoped>
.userManual-container {
    display: flex;
    flex-direction: column;
    margin: auto;
} 

.userManual-head {
    background-color: #0AB48C;
    width: 85%;
    height: 150px;
    margin: auto;
    color: #fff;
    padding-top: 30px;
    padding-left: 10px;
}

/* 탭 메뉴 */
.tap-menu {
    display: flex;
    width: 85%;
    /* height: 20px; */
    margin: auto;
    margin-top: 40px;
}

.tap-item {
    background-color: #f2f2f2;
    border-bottom: 1px solid #d9d9d9;
    text-align: center;
    width: 100%;
    height: 50px;
    cursor: pointer;
    line-height: 47px;
    
}

.active {
    border-top: 5px solid #ffdd38;
    background: #fff;
    text-align: center;
    cursor: pointer;
    line-height: 47px;
    width: 100%;
    height: 50px;
}
</style>