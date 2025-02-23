<template>
<div class="first-guide-container" v-show="loginModal">
    <img src="/img/icon-delete-black.png" alt="" class="close-login-modal" @click="closeLoginModal">
    <div class="guide-box" v-show="firstGuide">
        <div>
            <img src="/img/img-guide1.png" alt="" class="login-guide-img">
        </div>
        <div class="guide-box-content">
            <p>모아는 보호자와 자녀 사용자로 나뉘어집니다.</p>
            <p>보호자 자녀 따로 회원가입을 해주세요.</p>
        </div>

    </div>
    <div class="guide-box" v-show="secondGuide">
        <div>
            <img src="/img/img-guide2.png" alt="" class="login-guide-img">
            <!-- <img src="icon-delete-black.png" alt="" class="close-login-modal"> -->
        </div>
        <div class="guide-box-content">
            <p>가족코드로 보호자와 자녀를 연결하고 있습니다.</p>
            <p>반드시 보호자 먼저 회원가입을 부탁드립니다.</p>
        </div>

    </div>
    <div class="guide-box" alt="" v-show="thirdGuide">
        <div>
            <img src="/img/img-guide3.png" class="login-guide-img" >
            <!-- <img src="icon-delete-black.png" alt="" class="close-login-modal"> -->
        </div>
        <div class="guide-box-content">
            <p>자녀는 보호자의 승인을 받은 후 활동할 수 있습니다. </p>
            <p>자녀의 회원가입 후 가족정보에서 자녀의 승인을 부탁드립니다.</p>
        </div>

    </div>
    <div class="guide-box-btn">
        <button class="guide-box-btn-style" @click="preBtn" v-show="preModalBtn">이전</button>
        <button class="guide-box-btn-style" style="visibility: hidden;">이전</button>

        <button class="guide-box-btn-style" @click="nextBtn" v-show="nextModalBtn">다음</button>
    </div>

</div>

</template>

<script setup>
import { ref } from 'vue';
const loginModal = ref(true);

const firstGuide = ref(true);
const secondGuide = ref(false);
const thirdGuide = ref(false);

const nextModalBtn = ref(true);
const preModalBtn = ref(false);

const nextBtn = () => {
    if( firstGuide.value === true) { 
        firstGuide.value = false;
        secondGuide.value = true;
        // -------------------------
        preModalBtn.value = true;
    }

    else if(secondGuide.value === true) {
        secondGuide.value = false;
        thirdGuide.value = true;
        // -------------------------
        nextModalBtn.value = false;
        preModalBtn.value = true;
    }

}

const preBtn = () => {
     if(secondGuide.value === true) {
        secondGuide.value = false;
        firstGuide.value = true;
        // ---------------------------
        preModalBtn.value = false;
        nextModalBtn.value = true;
    }

    else if( thirdGuide.value === true) { 
        secondGuide.value = true;
        thirdGuide.value = false;
        // ---------------------------
        preModalBtn.value = true;
        nextModalBtn.value = true;
    }
}

const closeLoginModal = () => {
    loginModal.value = false;
}

</script>

<style scoped>
.first-guide-container {
    width: 700px;
    height: 578px;
    border: 1px solid;
    border-radius: 20px;
    background-color: #fff;
}

.login-guide-img {
    width: 698px;
    height: 402px;
    border-radius: 20px 20px 0px 0px;
    object-fit: cover;
}

.guide-box-content {
    font-size: 1.3rem;
    text-align: center;
    padding: 20px;
    line-height: 28px;
}

.close-login-modal {
    width: 30px;
    position: absolute;
    top: 15px;
    right: 15px;
    background-color: #fff;
    border-radius: 50px;
    cursor: pointer;
}

/* 버튼 영억 */
.guide-box-btn {
    
    display: flex;
    justify-content: space-between;
    margin-right: 30px;
    margin-left: 30px;
}

/* 버튼 스타일 */
.guide-box-btn-style {
    width: 90px;
    font-size: 1.2rem;
    height: 50px;
    background-color: #fff;
    border: 3px solid #a5d8e1;
    border-radius: 25px;
    font-weight: 600;
    cursor: pointer;
}

.guide-box-btn-style:hover {
    background-color: #a5d8e1;
    color: #fff;
}


</style>