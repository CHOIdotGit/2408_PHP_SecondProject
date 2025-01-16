<template>
<div class="top-menu">
    <img src="/img/logo.png" alt="" class="logo">

    <!-- 부모가 로그인 했을떄 우측 메뉴-->
    <div class="top-right-menu" >
        <div class="parent-btn" v-if="$store.state.auth.parentFlg">
            <div v-if="$store.state.auth.authFlg">로그아웃</div>
            <div>이용자 매뉴얼</div>
            <div v-if="$store.state.auth.authFlg">알람</div>
            <div v-if="$store.state.auth.authFlg">햄버거</div>
            <!-- 로그인 전 표시되는 메뉴 -->
            <div v-if="!$store.state.auth.authFlg">로그인</div>
            <div v-if="!$store.state.auth.authFlg">회원가입</div>
        </div>
        <!-- 자녀가 로그인 했을때 우측 메뉴 -->
        <div class="child-btn" v-else>
            <div v-if="$store.state.auth.authFlg">로그아웃</div>
            <div>이용자 매뉴얼</div>
            <div v-if="$store.state.auth.authFlg">알람</div>
            <div v-if="$store.state.auth.authFlg">햄버거</div>
            <!-- 로그인 전 표시되는 메뉴 -->
            <div v-if="!$store.state.auth.authFlg">로그인</div>
            <div v-if="!$store.state.auth.authFlg">회원가입</div>
        </div>
    </div>
</div>

</template>

<script setup>

</script>

<style scoped>
.top-menu {
    display: flex;
    justify-content: space-between;
    background-color: #eeeeee;
    height: 50px;
    padding-left: 50px;
    padding-right: 50px;
}

.parent-btn, .child-btn {
    display: flex;
    gap: 20px;
}

.top-right-menu {
    display: flex;
    align-items: center;
}



.logo {
    width: 80px;
}

</style>