<template>
<div class="top-menu">
    <!-- <img src="/img/logo.png" alt="" class="logo"> -->

    <!-- 부모가 로그인 했을떄 우측 메뉴-->
    <div class="top-right-menu" >
        <div class="parent-btn" v-if="$store.state.auth.parentFlg">
            <div  class="menu-btn">로그아웃</div>
            <div class="menu-btn">이용자 매뉴얼</div>
            <!-- 벨 아이콘 -->
            <div  class="icon-btn">
                <img src="/img/icon-bell-black.png" alt="" class="icon">
            </div>
            <!-- 햄버거 아이콘 -->
            <div >
                <img src="/img/icon-hamburgur-black.png" alt="" class="icon">
            </div>

        </div>
        <!-- 자녀가 로그인 했을때 우측 메뉴 -->
        <div class="child-btn" v-else>
            <div  class="menu-btn">로그아웃</div>
            <div class="menu-btn">이용자 매뉴얼</div>
            <!-- 벨 아이콘 -->
            <div  class="icon-btn">
                <img src="/img/icon-bell-black.png" alt="" class="icon">
            </div>
            <!-- 햄버거 아이콘 -->
            <div  class="icon-btn">
                <img src="/img/icon-hamburger-black.png" alt="" class="icon">
            </div>
        </div>
    </div>
</div>

</template>

<script setup>

</script>

<style scoped>
.top-menu {
    display: flex;
    justify-content: flex-end;
    /* background-color: #eeeeee; */
    height: 60px;
    padding-left: 50px;
    padding-right: 50px;
    /* background: linear-gradient(180deg, #ffff, #fafafa, #f8f8f8); */
}

.parent-btn, .child-btn {
    display: flex;
    gap: 20px;
}

.top-right-menu {
    display: flex;
    align-items: center;
}

/* 가장 작은 화면에서 최대 800px까지 적용  */
@media(max-width:800px) {
    .menu-btn {
        display: none;
    }
}

/* 로그아웃, 이용자 매뉴얼 버튼 디자인 */
.menu-btn {
    border-radius: 50px;
    border: 1px solid #ddddddee;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    padding: 5px;
}

.menu-btn:hover{
    background-color: #eeee;
} 


/* 벨, 햄버거 버튼 디자인 */
.icon-btn {
    width: 45px;
    height: 45px;
    border-radius: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
}

.icon-btn:hover {
    background-color: #eeee;

}

.icon {
    width: 30px;
    height: 30px;
}

.logo {
    width: 80px;
}

</style>