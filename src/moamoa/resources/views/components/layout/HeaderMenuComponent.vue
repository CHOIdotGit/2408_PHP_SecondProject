<template>
<div class="top-menu">
    <!-- <img src="/img/logo.png" alt="" class="logo"> -->

    <div class="top-right-menu" >
        <div class="parent-btn">
            <div @click="$store.dispatch('auth/logout')" class="menu-btn">로그아웃</div>
            <div class="menu-btn">이용자 매뉴얼</div>
            <!-- 알람 아이콘 -->
            <div  class="icon-btn" @click="openBellModal">
                <img src="/img/icon-bell-black.png" alt="" class="icon">
            </div>
            <!-- 알람 모달 메뉴  -->
            <div class="dropdown-bell" v-if="$store.state.auth.parentFlg" v-show="bellModal">
                <div class="bell-title">
                    <p>알림</p>
                    <img src="/img/icon-cross.png" alt="" class="cross" @click="closeBellModal">
                </div>
                <a href="#" class="alram" v-for="item in bellContent" :key="item">
                    <div  class="bell-content">
                        <p>{{ item.name }}(이)의 미션 등록되었어요!</p>
                        <p>{{ item.created_at }}</p>
                    </div>
                </a>
            </div>
            <!-- 햄버거 아이콘 -->
            <div  class="icon-btn" @click="openHamburgerModal" >
                <img src="/img/icon-hamburger-black.png" alt="" class="icon" >
            </div>
            <!-- 햄버거 모달 메뉴 -->
            <div class="dropdown" v-show="hamburgerModal" >
                <router-link v-if="$store.state.auth.parentFlg" to="/parent/family/info" class="link-deco">
                    <p class="info-page">가족정보</p>
                </router-link>
                <router-link v-if="$store.state.auth.childFlg" to="/child/private/rematching" class="link-deco">
                    <p class="info-page">부모 재매칭</p>
                </router-link>
                <router-link :to="$store.state.auth.parentFlg ? '/parent/private/edit' : '/child/private/edit'" class="link-deco">
                    <p class="info-page">개인정보 수정</p>
                </router-link>
                <router-link :to="$store.state.auth.parentFlg ? '/parent/private/password' : '/child/private/password'" class="link-deco">
                    <p class="info-page">비밀번호 변경</p>
                </router-link>
                <router-link :to="$store.state.auth.parentFlg ? '/parent/private/withdrawal' : '/child/private/withdrawal'" class="link-deco">
                    <p class="info-page info-page-red">회원 탈퇴</p>
                </router-link>
                        
            </div>
        </div>
    </div>

</div>

</template>

<script setup>
import { computed, ref } from 'vue';
import { useStore } from 'vuex';
const store = useStore();

/* 햄버거 모달 */
const hamburgerModal = ref(false);

// 모달 열기
const openHamburgerModal = () => {
    console.log('햄버거 모달 열기');
    hamburgerModal.value = true;
}

// 모달 닫기
const closeHamburgerModal = () => {
    hamburgerModal.value = false;
}

// *******벨 드랍 메뉴 *******
const bellModal = ref(false);
const bellContent = computed(() => store.state.header.bellContent);
const openBellModal = () => {
    console.log('열라라 참께');
    bellModal.value = !bellModal.value;
    store.dispatch('header/bellContent');
    console.log('이거 닫겼나?');
}

const closeBellModal = () => {
    bellModal.value = false;
}


</script>

<style scoped>

.top-menu {
    display: flex;
    justify-content: flex-end;
    /* background-color: #eeeeee; */
    height: 80px;
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

/* 햄버거 모달 */
.drop-bar {
    display: flex;
    gap: 30px;
}

.dropdown {
    width: 180px;
    background-color: #FFFFFF;
    position: absolute;
    top: 75px;
    right: 40px;
    display: flex;
    flex-direction: column;
    box-shadow: rgba(0, 0, 0, 0.05) 0px 6px 10px 0px, rgba(0, 0, 0, 0.1) 0px 0px 0px 1px;
}

.item {
    text-align: center;
    padding: 10px;
}

.info-page  {
    text-decoration: none;
    color: #000;
    font-size: 1.2rem;
    padding: 15px;
    text-align: center;
}

.info-page:hover {
    background-color: #f3f5f5;
}

.link-deco {
    text-decoration: none;
    color: #000;
}

/* 알람 모달 */
.dropdown-bell {
    width: 260px;
    background-color: #FFFFFF;
    /* position: relative; */
    position: absolute;
    top: 75px;
    right: 40px;
    display: flex;
    flex-direction: column;
    box-shadow: rgba(0, 0, 0, 0.05) 0px 6px 10px 0px, rgba(0, 0, 0, 0.1) 0px 0px 0px 1px;

}

.alram {
    display: flex;
    gap: 20px;
    text-decoration: none;
    color: #000;
    font-size: 1.0rem;
    padding: 10px;
}

.bell-content {
    display: flex;
    flex-direction: column;
    justify-content: center;
    font-size: 0.9rem;
}

.cross {
    width: 10px;
    height: 10px;
    cursor: pointer;
}

.bell-title {
    display: flex;
    justify-content: center;
}

</style>