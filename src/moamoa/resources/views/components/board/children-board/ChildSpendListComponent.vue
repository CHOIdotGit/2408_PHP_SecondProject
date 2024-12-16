<template>
    <div class="container">
        <div class="list-container">
            <!-- <div class="for-buttons">
                <button class="btn-top mission-delete">삭제</button>
            </div> -->
            <div class="mission-title">
                <div class="chk-div">
                    <input type="checkbox" id="checkbox9">
                </div>
                <span class="status">종류</span>
                <p class="mission-type">제목</p>
                <p class="charge">금액</p>
                <p class="due-date">소비일자</p>
            </div>
            <div class="mission-inserted-list">
                <div class="mission-content">
                    <div class="chk-div">
                        <input type="checkbox" id="checkbox9">
                    </div>
                    <p class="state-waiting">쇼핑</p>
                    <p class="mission-type-selected">올리브영가서 세일이길래 쿠션샀음</p> 
                    <p class="charge">32,000</p>
                    <p class="due-date">2024.12.17</p>
                </div>
                <div class="mission-content">
                    <div class="chk-div">
                        <input type="checkbox" id="checkbox9">
                    </div>
                    <p class="state-waiting">식비</p>
                    <p class="mission-type-selected">학원끝나고 떡볶이</p> 
                    <p class="charge">8,000</p>
                    <p class="due-date">2024.12.17</p>
                </div>
                <div class="mission-content">
                    <div class="chk-div">
                        <input type="checkbox" id="checkbox9">
                    </div>
                    <p class="state-waiting">교통비 </p>
                    <p class="mission-type-selected">학교 왕복</p> 
                    <p class="charge">2,500</p>
                    <p class="due-date">2024.12.17</p>
                </div>
                <div class="mission-content">
                    <div class="chk-div">
                        <input type="checkbox" id="checkbox9">
                    </div>
                    <p class="state-waiting">대기중</p>
                    <p class="mission-type-selected">집안일</p> 
                    <p class="charge">3,000</p>
                    <p class="due-date">2024.12.17</p>
                </div>
                <div class="mission-content">
                    <div class="chk-div">
                        <input type="checkbox" id="checkbox9">
                    </div>
                    <p class="state-waiting">대기중</p>
                    <p class="mission-type-selected">집안일</p> 
                    <p class="charge">3,000</p>
                    <p class="due-date">2024.12.17</p>
                </div>
                <div class="mission-content">
                    <div class="chk-div">
                        <input type="checkbox" id="checkbox9">
                    </div>
                    <p class="state-waiting">대기중</p>
                    <p class="mission-type-selected">집안일</p> 
                    <p class="charge">3,000</p>
                    <p class="due-date">2024.12.17</p>
                </div>
                <div class="mission-content">
                    <div class="chk-div">
                        <input type="checkbox" id="checkbox9">
                    </div>
                    <p class="state-waiting">대기중</p>
                    <p class="mission-type-selected">집안일</p> 
                    <p class="charge">3,000</p>
                    <p class="due-date">2024.12.17</p>
                </div>
                <div class="mission-content">
                    <div class="chk-div">
                        <input type="checkbox" id="checkbox9">
                    </div>
                    <p class="state-waiting">대기중</p>
                    <p class="mission-type-selected">집안일</p> 
                    <p class="charge">3,000</p>
                    <p class="due-date">2024.12.17</p>
                </div>
                <div class="mission-content">
                    <div class="chk-div">
                        <input type="checkbox" id="checkbox9">
                    </div>
                    <p class="state-waiting">대기중</p>
                    <p class="mission-type-selected">집안일</p> 
                    <p class="charge">3,000</p>
                    <p class="due-date">2024.12.17</p>
                </div>
                <div class="mission-content">
                    <div class="chk-div">
                        <input type="checkbox" id="checkbox9">
                    </div>
                    <p class="state-waiting">대기중</p>
                    <p class="mission-type-selected">집안일</p> 
                    <p class="charge">3,000</p>
                    <p class="due-date">2024.12.17</p>
                </div>
            </div>
            <!-- <div class="for-buttons">
                <button class="btn-bottom mission-goback">뒤로가기</button>
                <button class="btn-bottom mission-insert">+ 등록</button>
            </div> -->
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
    grid-template-columns: 70px 150px 400px 150px 300px;  
    height: 60px;
    gap: 50px;
    background-color: #F5F5F5;
    font-size: 2rem;
    margin: 10px;
    align-items: center;
    width: 1420px;
    /* margin-top: 70px; */
    text-align: center;
}

.mission-content {
    display: grid;
    grid-template-columns: 70px 150px 400px 150px 300px;  
    min-height: 70px;
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
    background-color:#5589e996 ;
    margin-top: 30px;
}

.btn-bottom {
    width: 100px;
    height: 50px;
    font-size: 1.5rem;
    border: none;
    background-color:#5589e996 ;
    margin-bottom: 30px;
}

#checkbox9 {
    margin: 15px;
}


.mission-inserted-list {
    height: 550px;
    display: grid;
    overflow-y: auto;
    /* -ms-overflow-style: none;  IE, Edge */
    /* scrollbar-width: none; Firefox */
    /* ::-webkit-scrollbar {
    width: 3px; }*/
}

</style>