<template>
    <div class="container">
        <div class="select-table">
            <div class="child-area">
                <div class="profile-child selected">
                    <img src="/img/icon-boy-2.png">
                </div>
                <div class="profile-child">
                    <img src="/img/icon-boy-2.png">
                </div>
                <div class="profile-child">
                    <img src="/img/icon-boy-2.png">
                </div>
                <div class="profile-child">
                    <img src="/img/icon-boy-2.png">
                </div>
            </div>
            <div class="btn update">
                <button class="btn-approve">승인</button>
                <button class="btn-delete">삭제</button>
            </div>
        </div>
        <div class="mission-list">
            <div class="mission-title">
                <p>자녀이름</p>
                <div class="state">
                    <p class="status">상태</p>
                </div>
                <p>종류</p>
                <p>미션 이름</p>
                <p>금액(원)</p>
                <p>미션 기한</p>
            </div>
            <div class="mission-content">
                <p>자녀이름</p>
                <div class="state state-underway">
                    <p>진행중</p>
                </div>
                <p>종류</p>
                <p>미션 이름</p>
                <p>금액(원)</p>
                <p>미션 기한</p>
            </div>
            <hr class="list-hr-line">
            <div class="mission-content">
                <p>자녀이름</p>
                <div class="state state-waiting">
                    <p>대기중</p>
                </div>
                <p>종류</p>
                <p>미션 이름</p>
                <p>금액(원)</p>
                <p>미션 기한</p>
            </div>
            <hr class="list-hr-line">
            <div class="mission-content">
                <p>자녀이름</p>
                <div class="state state-complete">
                    <p>완료</p>
                </div>
                <p>종류</p>
                <p>미션 이름</p>
                <p>금액(원)</p>
                <p>미션 기한</p>
            </div>
            <hr class="list-hr-line">
            <div class="mission-content">
                <p>자녀이름</p>
                <div class="state state-cancel">
                    <p>취소</p>
                </div>
                <p>종류</p>
                <p>미션 이름</p>
                <p>금액(원)</p>
                <p>미션 기한</p>
            </div>
            <hr class="list-hr-line">
        </div>
    </div>    
</template>
<script setup>


</script>
<style>
.container {
    width: 90%;
    height: 600px;
    background-color: white;
    margin: 50px;
}

.select-table {
    display: flex;
    justify-content: space-around;
    align-items: center;
    width: 1000px;
    height: 100px;
    margin-bottom: 10px;
    gap: 45rem;
    font-size: 30px;
    
    /* padding: 10px 10px 0 10px; */
}

.child-area {
    display: flex;
    margin-top: 1rem;
    gap: 2.5rem;
}

/* 자녀 프로필 */
.profile-child {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 5px;
    width: 80px;
    height: 80px;
    border: #D9D9D9 solid 5px;
    border-radius: 50%;
}

.profile-child > img {
    width: 60px;
    height: 60px;
}

.selected {
    border: #FFBDD0 solid 5px;
}

/* 미션 리스트 */
.mission-list {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}

.mission-title {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10rem;
    height: 40px;
    background-color: #F5F5F5;
    font-size: 25px;
    margin: 10px;
    width: 1400px;
}

.mission-content {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 12rem;
    height: 80px;
    margin: 10px;
    font-size: 30px;
}

.list-hr-line {
    background-color: #f1f1f19c;
    width: 1700px;
    height: 3px;
}

/* 버튼 */
.btn {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 40px;
}

.update {
    margin-top: 1rem;
}

.btn-approve {
    color: #A4D8E1;
    border: #A4D8E1 solid 3px;
    background-color: #FFFFFF;
    width: 120px;
    height: 50px;
    font-size: 30px;
}

.btn-delete {
    border: none;
    background-color: #A4D8E1;
    color: #FFFFFF;
    width: 120px;
    height: 50px;
    font-size: 30px;
}


/* 상태 */
.state {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 90px;
    height: 50px;
}

.state-underway {
    background-color: rgba(138, 160, 246, 0.5);
    color: #0010BE;
}

.state-waiting {
    background-color: rgba(22, 200, 150, 0.5);
    color: #165442;
}

.state-complete {
    background-color: rgba(255, 222, 92, 0.5);
    color: #2F8A1D;
}

.state-cancel {
    background-color: rgba(254, 135, 105, 0.5);
    color: #FF3300;
}
</style>