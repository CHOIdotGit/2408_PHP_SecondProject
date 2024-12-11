<template>
    <div class="container">
        <div class="btn-border">
            <button class="btn-page">
                <div class="child-list-triangle"></div>
            </button>
        </div>

        <div class="child-box">
            <div class="d-flex">
                <div class="profile-img">
                    <img src="/img/icon-boy-2.png">
                </div>
            </div>
            <div class="first-child child">
                <h3 class="name">이름</h3>
                <p class="nickname">별명</p>
                <div class="cild-profile">
                    <p class="mission">승인 대기 중인 미션</p>
                    <p class="pre-mission">대기중인 미션</p>
                    <p class="pre-mission">대기중인 미션</p>
                    <p class="pre-mission">대기중인 미션</p>
                    <p class="pre-mission">대기중인 미션</p>
                    <p class="pre-mission">대기중인 미션</p>
                </div>
                <p class="allowance">오늘 받은 용돈</p>
                <p class="amount">6,000</p>
            </div>
            <div class="btn-box">
                <button class="btn bonus">+ 보너스 지급</button>
                <button class="btn malus">- 용돈 차감</button>
            </div>
        </div>
        <div class="child-box">
            <div class="d-flex">
                <div class="profile-img">
                    <img src="/img/icon-boy-2.png">
                </div>
            </div>
            <div class="first-child child">
                <h3 class="name">이름</h3>
                <p class="nickname">별명</p>
                <div class="cild-profile">
                    <p class="mission">승인 대기 중인 미션</p>
                    <p class="pre-mission">대기중인 미션</p>
                    <p class="pre-mission">대기중인 미션</p>
                    <p class="pre-mission">대기중인 미션</p>
                    <p class="pre-mission">대기중인 미션</p>
                    <p class="pre-mission">대기중인 미션</p>
                </div>
                <p class="allowance">오늘 받은 용돈</p>
                <p class="amount">6,000</p>
            </div>
            <div class="btn-box">
                <button class="btn bonus">+ 보너스 지급</button>
                <button class="btn malus">- 용돈 차감</button>
            </div>
        </div>
        <div class="child-box">
            <div class="d-flex">
                <div class="profile-img">
                    <img src="/img/icon-boy-2.png">
                </div>
            </div>
            <div class="first-child child">
                <h3 class="name">이름</h3>
                <p class="nickname">별명</p>
                <div class="cild-profile">
                    <p class="mission">승인 대기 중인 미션</p>
                    <p class="pre-mission">대기중인 미션</p>
                    <p class="pre-mission">대기중인 미션</p>
                    <p class="pre-mission">대기중인 미션</p>
                    <p class="pre-mission">대기중인 미션</p>
                    <p class="pre-mission">대기중인 미션</p>
                </div>
                <p class="allowance">오늘 받은 용돈</p>
                <p class="amount">6,000</p>
            </div>
            <div class="btn-box">
                <button class="btn bonus">+ 보너스 지급</button>
                <button class="btn malus">- 용돈 차감</button>
            </div>
        </div>
        
        <div class="btn-border">
            
            <button class="btn-page child-list-btn-right">
                <div class="child-list-triangle"></div>
            </button>
        </div>
    </div>
</template>
<script setup>

</script>
<style>
    /* 메인 화면 */
.container {
    width: 90%;
    height: 600px;
    background-color: white;
    margin: 50px;
    display: grid;
    grid-template-columns: 1fr 480px 480px 480px 1fr;

    gap: 10px;
}

/* 플랙스 적용 */
.d-flex {
    display: flex;
    justify-content: center;
    align-items: center;
}

/* 자녀 프로필 관련 */
.child-box {
    margin: 25px 0;
}

.profile-img {
    display: flex;
    justify-content: center;
    width: 120px;
    height: 120px;
    border: #FFBDD0 solid 5px;
    border-radius: 50%;
}

.profile-img > img {
    width: 100px;
    height: 100px;
}

.child {
    background-color: white;
    border: solid #A4D8E1 5px;
    width: 480px;
    height: 500px;
    margin-top: 15px;
}

/* 프로필 텍스트 */
.name {
    font-size: 48px;
    margin-top: 50px;
    text-align: center;
    color: #A4D8E1;
}

.nickname {
    font-size: 30px;
    margin-top: 5px;
    text-align: center;
    color: #A4D8E1;
}

.mission {
    font-size: 25px;
    margin-top: 20px;
    text-align: center;
}

.pre-mission {
    font-size: 20px;
    margin-top: 10px;
    text-align: center;
}

.allowance {
    font-size: 25px;
    margin-top: 10px;
    text-align: center;
}

.amount {
    font-size: 20px;
    margin-top: 20px;
    text-align: center;
}

/* 버튼 설정 */
.btn-border{
    display: flex;
    justify-content: center;
    align-items: center;
}

.btn-box{
    display: flex;
    justify-content: space-evenly;
    align-items: center;
    margin-top: 25px;
    vertical-align: middle;
}

.btn {
    width: 180px;
    height: 50px;
    cursor: pointer;
}

.bonus {
    border: none;
    background-color: #A4D8E1;
    color: #FFFFFF;
    font-size: 30px;
}

.malus {
    border: #A4D8E1 solid 5px;
    background-color: #FFFFFF;
    color: #A4D8E1;
    font-size: 30px;
}

/* 페이지네이션 버튼 */
.btn-page {
    background: #A4D8E1;
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    width: 60px;
    height: 60px;
    margin: 10px;
    padding: 5px;
}

.child-list-triangle {
    border-top: 25px solid transparent; /* 위 경계 투명 */
    border-bottom: 25px solid transparent; /* 아래 경계 투명 */
    border-right: 40px solid #ffffff; /* 오른쪽 경계 색상 */
}

.child-list-btn-right {
    transform: rotate(180deg);
}
</style>