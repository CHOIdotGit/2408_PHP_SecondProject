<template>
    <div class="detail-container">
        <div class="content">
            <p class="title">미션 제목</p>
            <p class="ms-title">점심 설거지하기</p>
            <div class="date">
                <span class="ms-date">2024-12-11</span>
                <span>⁓</span>
                <span class="ms-date">2024-12-11</span>
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
            <p class="ms-amount">2,000</p>
        </div>
        <div class="create-btn">
            <button class="ms-cancel">취소</button>
            <button @click="delOpenModal" class="ms-del">삭제</button>
            <button class="ms-up">수정</button>
            <button class="ms-comfirm">승인</button>
        </div>
    </div>

    <!-- ************************* -->
    <!-- ********삭제 모달********* -->
    <!-- ************************* -->
    <div class="del-modal-black" v-show="delModal">
        <div class="del-modal-white">
            <div class="modal-content">
                <img src="/img/icon-boy-2.png" class="modal-img" alt=".">
                <p class="modal-name">최상민</p>
                <p class="modal-ms-title">미션 : 설거지 하기</p>
                <div class="del-guide">해당 미션이 삭제됩니다.</div>
            </div>
            <div class="del-btn">
                <button @click="delCloseModal" class="modal-cancel">취소</button>
                <button @click="" class="modal-del">삭제</button>
            </div>
        </div>
    </div>

</template>

<script setup>
import { ref } from 'vue';

const delModal = ref(false);

const delOpenModal = () => {
    delModal.value = true;
}

const delCloseModal = () => {
    delModal.value = false;
}


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
    line-height: 45px;
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
    line-height: 45px;
}



/* 취소,미션등록, 삭제 버튼 */
.create-btn {
    margin-top: 20px;
    gap: 10px;
    margin-right: 250px;
    display: flex;
    justify-content: flex-end;
}


/* 취소버튼 */
.ms-cancel {
    color: #ACACAC;
    background-color: #FFFFFF;
    font-size: 1.2rem;
    border: 1px solid #ACACAC;
    padding: 5px;
    width: 100px;
    border-radius: 0px;
    cursor: pointer;
    margin-right: 570px;
}

/* 삭제버튼 */
.ms-del {
    color: #A4D8E1;
    background-color: #FFFF;
    font-size: 1.2rem;
    border: 1px solid #A4D8E1;
    padding: 5px;
    width: 100px;
    border-radius: 0px;
    cursor: pointer;
}

/* 수정버튼 */
.ms-up {
    color: #FFFF;
    background-color: #A4D8E1;
    font-size: 1.2rem;
    border: 1px solid #A4D8E1;
    padding: 5px;
    width: 100px;
    border-radius: 0px;
    cursor: pointer;
}

/* 승인버튼 */
.ms-comfirm {
    color: #FFFF;
    background-color: #A4D8E1;
    font-size: 1.2rem;
    border: 1px solid #A4D8E1;
    padding: 5px;
    width: 100px;
    border-radius: 0px;
    cursor: pointer;
}

/* ********************* */
/* *******삭제 모달****** */
/* ********************* */

.del-modal-black {
    background-color: rgba(0, 0, 0, 0.5);
    position: fixed;
    /* top: 182px;
    left: 177px; */
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
}

.del-modal-white {
    width: 400px;
    height: 500px;
    background-color: #FFFFFF;
    border: 3px solid #A4D8E1;
    /* margin: 170px; */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

}

.modal-content {
    text-align: center;
    margin: 60px;
}

.modal-name {
    font-size: 1.3rem;
    padding: 10px;
}

.modal-ms-title {
    font-size: 1.3rem;
    padding: 10px;
}

.del-guide {
    font-size: 1.4rem;
    padding: 15px;
}


.modal-img{
    width: 100px;
    height: 100px;
    background-color: #FFFFFF;
    border: 3px solid #FFBDD0;
    border-radius: 50px;
    padding: 3px;
}



/* 버튼 */
.modal-cancel {
    color: #ACACAC;
    background-color: #FFFFFF;
    font-size: 1.2rem;
    border: 1px solid #ACACAC;
    padding: 5px;
    width: 100px;
    cursor: pointer;
    margin: 10px;
}

.modal-del {
    color: #FFFF;
    background-color: #A4D8E1;
    font-size: 1.2rem;
    border: 1px solid #A4D8E1;
    padding: 5px;
    width: 100px;
    cursor: pointer;
    margin: 10px;
}

</style>