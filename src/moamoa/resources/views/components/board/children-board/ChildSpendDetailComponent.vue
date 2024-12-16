<template>
    <div class="main-container">
        <div class="detail-container">
            <div class="content-list">
                <div class="content">
                    <p class="title">미션 제목</p>
                    <span class="ms-title"> 올리브영 </span>
                    <div class="date">
                        <span class="ms-date">2024-12-11</span>
                        <span>⁓</span>
                        <span class="ms-date">2024-12-11</span>
                    </div>
                </div>
                <div class="content">
                    <p class="title">미션 종류</p>
                    <div class="category-btn">
                        <input type="radio" name="category" id="meals" checked>
                            <img class="ms-category" src="img/icon-fastfood.png" alt=".">
                        </input>
                        <label for="study">식비</label>
                    </div>
                    <div class="category-btn">
                        <input type="radio" name="category" id="habit" >
                            <img class="ms-category" src="/img/icon-bus.png" alt=".">
                        </input>
                        <label for="habit">교통비</label>
                    </div>
                    <div class="category-btn">
                        <input type="radio" name="category" id="housework" >
                            <img class="ms-category" src="/img/icon-shoppingbag.png" alt=".">
                        </input>
                        <label for="housework">쇼핑</label>
                    </div>
                    <div class="category-btn">
                        <input type="radio" name="category" id="etc" >
                            <img class="ms-category" src="/img/icon-checklist7.png" alt=".">
                        </input>
                        <label for="lifestyle">기타</label>
                    </div>
                    aa
                </div>
                <div class="content">
                    <p class="title">미션 내용</p>
                    <div class="ms-content">올리브영 갔다가 주연언니랑 쇼핑함</div>
                </div>
                <div class="content">
                    <p class="title">금액(원)</p>
                    <p class="ms-amount">32,000</p>
                </div>
                <div class="bottom-btn">
                    <button class="ms-cancel">취소</button>
                    <button @click="delOpenModal" class="create-btn ms-del">삭제</button>
                    <button class="create-btn ms-up">완료</button>
                    <button class="create-btn ms-comfirm">수정</button>
                </div>
            </div>
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

<style scoped>

.main-container{
    margin-left: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.detail-container {
    background-color: #FFFFFF;
    width: 1500px;
    margin-top: 20px;
    height: 765px;
}
.content-list {
    display: grid;
    margin-top: 100px;
    margin-left: 20px;
    justify-content: center;
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
    font-size: 2rem;
    border-right: 2px solid #dfdfdf;
    padding: 10px;
    width: 150px;
    text-align: center;
}

/* 미션 제목 */
.ms-title {
    width: 300px;
    border: 3px solid #5589e996;
    outline: none;
    border-radius: 10px;
    font-size: 2rem;
    /* height: rem; */
    /* margin-top: 5px; */
    padding-left: 5px;
}

/* 미션 날짜 */
.date {
    border: 3px solid #5589e996;
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
    font-size: 1.5rem;
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
    color: #5589e996;
    /* border: 1px solid #5589e996; */
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
    background-color: #5589e996;
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
    border: 3px solid #5589e996;
    font-size: 1.3rem;
}

/* 미션 금액 */
.ms-amount {
    width: 300px;
    border: 3px solid #5589e996;
    border-radius: 10px;
    font-size: 1.3rem;
    padding-left: 5px;
    line-height: 45px;
}

/* 취소버튼 */
.ms-cancel {
    color: #ACACAC;
    background-color: #FFFFFF;
    font-size: 1.2rem;
    border: 1px solid #ACACAC;
    padding: 5px;
    width: 100px;
    height: 50px;
    border-radius: 0px;
    cursor: pointer;
    margin-left: 130px;
    margin-right: 350px;
}

/* 삭제버튼 */
.ms-del {
    /* margin-left: 250px; */
    color: #5589e996;
    background-color: #FFFF;
    font-size: 1.2rem;
    border: 1px solid #5589e996;
    padding: 5px;
    width: 100px;
    border-radius: 0px;
    cursor: pointer;
}

.bottom-btn{
    /* width: 60px; */
    gap: 30px;
    display: flex;
    margin-top: 50px;
}

/* 수정버튼 */
.ms-up {
    color: #FFFF;
    background-color: #5589e996;
    font-size: 1.2rem;
    border: 1px solid #5589e996;
    padding: 5px;
    width: 100px;
    border-radius: 0px;
    cursor: pointer;
}

/* 승인버튼 */
.ms-comfirm {
    color: #FFFF;
    background-color: #5589e996;
    font-size: 1.2rem;
    border: 1px solid #5589e996;
    padding: 5px;
    width: 100px;
    border-radius: 0px;
    cursor: pointer;
}

.btn-right {
    display: flex;
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
    align-items: center;
    /* margin-top: 500px; */
    justify-content: center;
}

.del-modal-white {
    width: 400px;
    height: 500px;
    background-color: #FFFFFF;
    border: 3px solid #5589e996;
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
    border: 3px solid #5589e996;
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
    background-color: #5589e996;
    font-size: 1.2rem;
    border: 1px solid #5589e996;
    padding: 5px;
    width: 100px;
    cursor: pointer;
    margin: 10px;
}

</style>