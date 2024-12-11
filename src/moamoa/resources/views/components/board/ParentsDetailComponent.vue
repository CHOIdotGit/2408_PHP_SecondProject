<template>
    <div class="detail-container">
        <div class="content">
            <p class="title">미션 제목</p>
            <p class="ms-title">점심 설거지하기</p>
            <div class="date">
                <p class="ms-date">2024-12-11</p>
                <span>⁓</span>
                <p class="ms-date">2024-12-11</p>
            </div>
        </div>
        <div class="content">
            <p class="title">미션 종류</p>
            <div class="category-btn">
                <input type="radio" name="category" id="study" checked>
                    <img class="ms-category" src="img/icon-pencil.png" alt=".">
                </input>
                <label for="study">학습</label>
            </div>
            <div class="category-btn">
                <input type="radio" name="category" id="habit" >
                    <img class="ms-category" src="/img/icon-bicycle.png" alt=".">
                </input>
                <label for="habit">취미</label>
            </div>
            <div class="category-btn">
                <input type="radio" name="category" id="housework" >
                    <img class="ms-category" src="/img/icon-cleaner.png" alt=".">
                </input>
                <label for="housework">집안일</label>
            </div>
            <div class="category-btn">
                <input type="radio" name="category" id="lifestyle" >
                    <img class="ms-category" src="/img/icon-clock.png" alt=".">
                </input>
                <label for="lifestyle">생활습관</label>
            </div>
            <div class="category-btn">
                <input type="radio" name="category" id="etc" >
                    <img class="ms-category" src="/img/icon-checklist7.png" alt="">
                </input>
                <label for="etc">기타</label>
            </div>
        </div>
        <div class="content">
            <p class="title">미션 내용</p>
            <div class="ms-content">점심먹고 설거지 해놔라. 집에 엄마 없다.</div>
        </div>
        <div class="content">
            <p class="title">금액(원)</p>
            <p class="ms-amount">2,000원</p>
        </div>
        <div class="create-btn">
            <button class="ms-cancel">취소</button>
            <button class="ms-sub">+ 미션등록</button>
            <button class="ms-del">삭제</button>
        </div>
    </div>
</template>

<script setup>

</script>

<style>
.detail-container {
    background-color: #FFFFFF;
    width: 1550px;
    margin: 30px auto 50px;
    height: 700px;

}

.content {
    display: flex;
    padding: 20px;
    margin: 0 300px;
    gap: 10px;
    border-bottom: 2px solid #dfdfdf;
    width: 1000px;

}


.title {
    font-size: 1.5rem;
    border-right: 2px solid #dfdfdf;
    padding: 10px;
    width: 150px;
    text-align: center;

}

/* 미션 제목 */
.ms-title {
    width: 300px;
    border: 3px solid #A4D8E1;
    border-radius: 10px;
    font-size: 1.3rem;
    padding-left: 5px;
}

/* 미션 날짜 */
.date {
    border: 3px solid #A4D8E1;
    border-radius: 10px;
    padding: 10px;
    margin-left: 30px;
}

span {
    padding: 5px;
}

.ms-date {
    border: none;
    outline: none;
    /* width: 200px; */
    font-size: 1.3rem;
    text-align: center;
}

/* 미션 종류 카테고리 이미지 */
.category-btn {
    display: flex;
    flex-direction: column;
    text-align: center;
    padding-right: 30px;
}

.category-btn > label {
    color: #A4D8E1;
    /* border: 1px solid #A4D8E1; */
    font-size: 0.9rem;
    padding-top: 5px;

}

.category-btn > input {
    display: none;
}

.ms-category {
    width: 60px;
    height: 60px;
    background-size: cover;
    background-repeat: no-repeat;
    border: none;
    background-color: #A4D8E1;
    cursor: pointer;
    border-radius: 50px;
    padding: 5px;
}

/* 미션 내용 */
.ms-content {
    width: 500px;
    height: 150px;
    padding: 10px;
    border-radius: 10px;
    border: 3px solid #A4D8E1;
    font-size: 1.3rem;
}

/* 미션 금액 */
.ms-amount {
    width: 300px;
    border: 3px solid #A4D8E1;
    border-radius: 10px;
    font-size: 1.3rem;
    padding-left: 5px;
}



/* 취소,미션등록, 삭제 버튼 */
.create-btn {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-right: 250px;
    margin-top: 20px;
}

.ms-cancel {
    color: #A4D8E1;
    background-color: #FFFFFF;
    font-size: 1.2rem;
    border: 1px solid #A4D8E1;
    padding: 5px;
    width: 100px;
    border-radius: 0px;
    cursor: pointer;
}

.ms-sub {
    color: #FFFF;
    background-color: #A4D8E1;
    font-size: 1.2rem;
    border: 1px solid #A4D8E1;
    padding: 5px;
    width: 100px;
    border-radius: 0px;
    cursor: pointer;
}

.ms-del {
    color: #FFFF;
    background-color: #A4D8E1;
    font-size: 1.2rem;
    border: 1px solid #A4D8E1;
    padding: 5px;
    width: 100px;
    border-radius: 0px;
    cursor: pointer;
}

</style>