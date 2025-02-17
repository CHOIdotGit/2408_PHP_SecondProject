<template>
    <div class="footerContainer">
        <div>
            <img src="/img/logo.png" alt="">
            <span>자녀 용돈 관리 사이트</span> <span>모아</span>
            <div class="company">
                <p>대구광역시 중구 중앙대로 364 제일빌딩 5F</p>
                <p>사업자등록번호 : 504-85-25999(대표자 : 박병주)</p>
                <p>통신판매업신고 : 2013-대구중구-3307호</p>
                <p>학원등록번호 : 제 3382호</p>
                <p>Copyright ⓒ MoA. All Rights Reserved.</p>
            </div>
        </div>
        <div class="as">
            <span>고객센터</span> <span>1588-9780</span>
            <p>월요일 ~ 금요일  9:00 ~ 17:00</p>
            <p>주말 및 공휴일 휴무</p>
            <p>juyoon57@naver.com</p>

        </div>

    </div>


</template>

<script setup>

</script>

<style scoped>
.footerContainer {
    display: flex;
    justify-content: space-between;
    margin-top: 54px;
    color: #575757;
}

.company {
    text-align: start;
    margin-left: 35px;
}

.as {
    text-align: end;
    margin-right: 35px;
}

p {
    font-size: 1.2rem;
}

span {
    font-size: 2rem;
}

</style>