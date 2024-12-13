<template>
    <div class="d-flex">
        <div class="container">
            <div class="t-btn-div update">
                <button class="btn btn-approve">승인</button>
                <button class="btn btn-delete">삭제</button>
            </div>
            <div class="mission">
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
                        <p>Carl</p>
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
                        <p>Daisy</p>
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
                        <p>Liam</p>
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
                        <p>Amelia</p>
                        <div class="state state-cancel">
                            <p>취소</p>
                        </div>
                        <p>종류</p>
                        <p>미션 이름</p>
                        <p>금액(원)</p>
                        <p>미션 기한</p>
                    </div>
                    <hr class="list-hr-line">
                    <div class="mission-content">
                        <p>Isabella</p>
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
            <div class="b-btn-div">
                <button class="btn back">뒤로가기</button>
                <button class="btn add-mission">+ 미션 등록</button>
            </div>
        </div>    
    </div>
</template>
<script setup>


</script>
<style scoped>
body {
    background-image: url('/img/bck.jpg');
    background-repeat: no-repeat;
    background-size: cover;
}

.container {
    width: 1536px;
    height: 650px;
    background-color: white;
    margin: 25px;
}

/* 플랙스 적용 */
.d-flex {
    display: flex;
    justify-content: center;
    align-items: center;
}

/* 버튼 */
.t-btn-div {
    display: flex;
    justify-content: right;
    align-items: center;
    gap: 40px;
    margin: 2rem 67px 1rem 0;
}

.b-btn-div {
    display: flex;
    justify-content: right;
    align-items: center;
    gap: 40px;
    margin: 1rem 67px 1rem 0;
}

.btn {
    cursor: pointer;
}

.btn-approve {
    color: #A4D8E1;
    border: #A4D8E1 solid 3px;
    background-color: #FFFFFF;
    width: 120px;
    height: 50px;
    font-size: 20px;
}

.btn-delete {
    border: none;
    background-color: #A4D8E1;
    color: #FFFFFF;
    width: 120px;
    height: 50px;
    font-size: 20px;
}

.add-mission {
    border: none;
    background-color: #A4D8E1;
    color: #FFFFFF;
    width: 120px;
    height: 50px;
    font-size: 20px;
}

.back {
    color: #A4D8E1;
    border: #A4D8E1 solid 3px;
    background-color: #FFFFFF;
    width: 120px;
    height: 50px;
    font-size: 20px;
}

/* 미션 리스트 */
.mission {
    display: flex;
    justify-content: center;
    align-items: center;
}

.mission-list {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    width: 1400px;
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
    gap: 11rem;
    height: 60px;
    margin: 10px;
    font-size: 20px;
}

.list-hr-line {
    background-color: #f1f1f19c;
    width: 1400px;
    height: 3px;
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