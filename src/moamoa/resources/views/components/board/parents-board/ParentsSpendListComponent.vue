<template>
    <div class="container">
        <div class="list-container">
            <!-- <div class="for-buttons">
                <button class="btn-top mission-delete">삭제</button>
            </div> -->
            <div class="mission-title">
                <span class="mission-name">자녀이름</span>
                <span class="status">종류</span>
                <p class="mission-type">제목</p>
                <p class="charge">금액</p>
                <p class="due-date">소비일자</p>
            </div>
            <div class="mission-inserted-list">
                <div class="mission-content">
                    <span class="mission-name">박초롱초롱빛나</span>
                    <p class="state-waiting">쇼핑</p>
                    <p class="mission-type-selected">올리브영가서 세일이길래 쿠션샀음</p> 
                    <p class="charge">3,000</p>
                    <p class="due-date">2024.12.17</p>
                </div>
                <div class="mission-content">
                    <span class="mission-name">몽</span>
                    <p class="state-waiting">대기중</p>
                    <p class="mission-type-selected">집안일</p> 
                    <p class="charge">3,000</p>
                    <p class="due-date">2024.12.17</p>
                </div>
                <div class="mission-content">
                    <span class="mission-name">몽</span>
                    <p class="state-waiting">대기중</p>
                    <p class="mission-type-selected">집안일</p> 
                    <p class="charge">3,000</p>
                    <p class="due-date">2024.12.17</p>
                </div>
                <div class="mission-content">
                    <span class="mission-name">몽</span>
                    <p class="state-waiting">대기중</p>
                    <p class="mission-type-selected">집안일</p> 
                    <p class="charge">3,000</p>
                    <p class="due-date">2024.12.17</p>
                </div>
                <div class="mission-content">
                    <span class="mission-name">몽</span>
                    <p class="state-waiting">대기중</p>
                    <p class="mission-type-selected">집안일</p> 
                    <p class="charge">3,000</p>
                    <p class="due-date">2024.12.17</p>
                </div>
                <div class="mission-content">
                    <span class="mission-name">몽</span>
                    <p class="state-waiting">대기중</p>
                    <p class="mission-type-selected">집안일</p> 
                    <p class="charge">3,000</p>
                    <p class="due-date">2024.12.17</p>
                </div>
                <div class="mission-content">
                    <span class="mission-name">몽</span>
                    <p class="state-waiting">대기중</p>
                    <p class="mission-type-selected">집안일</p> 
                    <p class="charge">3,000</p>
                    <p class="due-date">2024.12.17</p>
                </div>
                <div class="mission-content">
                    <span class="mission-name">몽</span>
                    <p class="state-waiting">대기중</p>
                    <p class="mission-type-selected">집안일</p> 
                    <p class="charge">3,000</p>
                    <p class="due-date">2024.12.17</p>
                </div>
                <div class="mission-content">
                    <span class="mission-name">몽</span>
                    <p class="state-waiting">대기중</p>
                    <p class="mission-type-selected">집안일</p> 
                    <p class="charge">3,000</p>
                    <p class="due-date">2024.12.17</p>
                </div>
                <div class="mission-content">
                    <span class="mission-name">몽</span>
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
    grid-template-columns:250px 90px 400px 90px 300px;
    height: 60px;
    gap: 50px;
    background-color: #F5F5F5;
    font-size: 2rem;
    margin: 10px;
    align-items: center;
    width: 1400px;
    /* margin-top: 70px; */
    text-align: center;
}

.mission-content {
    display: grid;
    grid-template-columns: 250px 90px 400px 90px 300px;
    min-height: 80px;
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
}

</style>