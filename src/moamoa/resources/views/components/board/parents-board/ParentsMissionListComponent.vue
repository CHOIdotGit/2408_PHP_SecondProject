<template>
    <div class="container">
        <div class="list-container">
            <div class="for-buttons">
                <button class="btn-top mission-delete">삭제</button>
                <button class="btn-top mission-confirm">승인</button>
            </div>
            <div class="mission-title">
                <div class="chk-div">
                    <input type="checkbox" id="checkbox9">
                </div>
                <span class="kids-name">자녀이름</span>
                <span class="status">상태</span>
                <p class="mission-type">종류</p>
                <p class="mission-name">미션이름</p>
                <p class="charge">금액</p>
                <p class="due-date">기한</p>
            </div>
            <div class="mission-inserted-list">
                <div class="mission-content">
                    <div class="chk-div">
                        <input type="checkbox" id="checkbox9">
                    </div>
                    <span class="kids-name">김</span>
                    <p class="state-in-progress">진행중</p>
                    <p class="mission-type-selected">집안일</p>
                    <p class="mission-name-">고양이돌보기</p>
                    <p class="charge">3,000</p>
                    <p class="due-date">2024.12.17</p>
                </div>
                <div class="mission-content">
                    <div class="chk-div">
                        <input type="checkbox" id="checkbox9">
                    </div>
                    <span class="kids-name">김율</span>
                    <p class="state-complete">완료</p>
                    <p class="mission-type-selected">생활습관</p>
                    <p class="mission-name-">매일 10시에 잠들기</p>
                    <p class="charge">3,000</p>
                    <p class="due-date">2024.12.17 ~ 24.12.25</p>
                </div>
                <div class="mission-content">
                    <div class="chk-div">
                        <input type="checkbox" id="checkbox9">
                    </div>
                    <span class="kids-name">배짜장</span>
                    <p class="state-cancel">취소</p>
                    <p class="mission-type-selected">집안일</p>
                    <p class="mission-name-">열여섯글자까지가능하다는데확인용</p>
                    <p class="charge">35,000</p>
                    <p class="due-date">2024.12.17</p>
                </div>
                <div class="mission-content">
                    <div class="chk-div">
                        <input type="checkbox" id="checkbox9">
                    </div>
                    <span class="kids-name">유노윤호</span>
                    <p class="state-waiting">대기중</p>
                    <p class="mission-type-selected">집안일</p>
                    <p class="mission-name-">고양이돌보기</p>
                    <p class="charge">3,000</p>
                    <p class="due-date">2024.12.17</p>
                </div>
                <div class="mission-content">
                    <div class="chk-div">
                        <input type="checkbox" id="checkbox9">
                    </div>
                    <span class="kids-name">다아섯글자</span>
                    <p class="state-waiting">대기중</p>
                    <p class="mission-type-selected">집안일</p>
                    <p class="mission-name-">고양이돌보기</p>
                    <p class="charge">3,000</p>
                    <p class="due-date">2024.12.17</p>
                </div>
                
            </div>
            <div class="for-buttons">
                <button class="btn-bottom mission-goback">뒤로가기</button>
                <button class="btn-bottom mission-insert">+ 등록</button>
            </div>
        </div>
    </div>
</template>

<script setup>


</script>

<style scoped>
.container {
    margin-left: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    /* padding-bottom: 40px; */
}

.list-container {
    margin-top: 20px;
    width: 1500px;
    height: 765px;
    background-color: white;
    display: flex;
    flex-direction: column;
    gap: 20px;
    justify-content: center;
    align-items: center;   
}

.mission-title {
    display: grid;
    grid-template-columns: 40px 190px 90px 90px 320px 90px 300px;
    height: 60px;
    gap: 50px;
    background-color: #F5F5F5;
    font-size: 2rem;
    margin: 10px;
    align-items: center;
    width: 1400px;
    text-align: center;
}

.mission-content {
    display: grid;
    grid-template-columns: 40px 190px 90px 90px 320px 90px 300px;
    height: 40px;
    gap: 50px;
    /* background-color: #F5F5F5; */
    font-size: 1.3rem;
    margin: 10px;
    align-items: center;
    width: 1400px;
    text-align: center;
    /* border-bottom: 2px solid black; */
}

.for-buttons{
    display: flex;
    justify-content: right;
    gap: 30px;
    margin-left: 1170px;
}

.btn-top {
    width: 100px;
    height: 50px;
    font-size: 1.5rem;
    border: none;
    background-color:#A2CAAC ;
    margin-top: 30px;
}

.btn-bottom {
    width: 100px;
    height: 50px;
    font-size: 1.5rem;
    border: none;
    background-color:#A2CAAC ;
    margin-bottom: 30px;
}

#checkbox9 {
    margin: 15px;
}

.state-in-progress {
    background-color: rgba(138, 160, 246, 0.5);
    color: #0010BE;
    height: 40px;
    line-height: 40px;
}

.state-waiting {
    background-color: rgba(22, 200, 150, 0.5);
    color: #165442;
    height: 40px;
    line-height: 40px;
}

.state-complete {
    background-color: rgba(255, 222, 92, 0.5);
    color: #2F8A1D;
    height: 40px;
    line-height: 40px;
}

.state-cancel {
    background-color: rgba(254, 135, 105, 0.5);
    color: #FF3300;
    height: 40px;
    line-height: 40px;
}

.mission-inserted-list {
    height: 800px;
    display: grid;
}
</style>