<template>
<div class="top-menu" v-if="!isMobile">
    <!-- <img src="/img/logo.png" alt="" class="logo"> -->

    <div class="top-right-menu" >
        <div class="parent-btn">
            <!-- <div>
                <img src="/img/logo.png" class="logo">
            </div> -->
            <div @click="$store.dispatch('auth/logout')" class="menu-btn">로그아웃</div>
            <div class="menu-btn">이용자 매뉴얼</div>
            <!-- 알람 아이콘 -->
            <div  class="icon-btn" @click="openBellModal" v-if="$store.state.auth.parentFlg">
                <img src="/img/icon-bell-black.png" alt="" class="icon">
                <!-- 알람 내용이 있을 경우 나타나는 빨간색 동그라미 -->
                <img src="/img/icon-circle.png" alt="" class="icon-circle" v-if="bellContent.length > 0">
            </div>
            <!-- 부모 알람 모달 메뉴  -->
            <div class="dropdown-bell" v-if="$store.state.auth.parentFlg" v-show="bellModal">
                <img src="/img/alram-deco.png" alt="" class="alram-deco">
                <div class="bell-title">
                    <!-- 알람 닫기 X 아이콘 -->
                    <img src="/img/icon-cross.png" alt="" class="cross" @click="closeBellModal">
                    <div>알림함</div>
                </div>
                <div class="bell-list">
                    <div class="alram" v-for="item in bellContent" :key="item">
                        <img :src="item.child.profile" alt="" class="alram-profile">
                        <div  class="bell-content">
                            <div @click="goMission(item.mission_id)">{{ item.child.name }} 님의 {{ item.title }} 미션이 등록되었어요!</div>
                            <p>{{ item.created_at }}</p>
                        </div>
                        <!-- 알람 체크 버튼 -->
                        <img src="/img/icon-delete-black.png" alt="" class="alram-check" @click="checkMission(item.mission_id)">
                    </div>
                    <div v-if="bellContent.length === 0" class="not">등록된 미션이 없습니다</div>
                </div>
            </div>
            <!-- 자녀 알람 모달 메뉴  -->
            <!-- 알람 아이콘 -->
            <div  class="icon-btn" @click="openChildBellModal" v-if="$store.state.auth.childFlg">
                <img src="/img/icon-bell-black.png" alt="" class="icon">
                <!-- 알람 내용이 있을 경우 나타나는 빨간색 동그라미 -->
                <!-- <img src="/img/icon-circle.png" alt="" class="icon-circle"  v-if="childBellContent.length > 0"> -->
            </div>
            <!-- 자녀 알람 모달 내용 -->
            <div class="dropdown-bell" v-if="$store.state.auth.childFlg" v-show="childBellModal">
                <img src="/img/alram-deco.png" alt="" class="alram-deco">
                <div class="bell-title">
                    <!-- 알람 닫기 X 아이콘 -->
                    <img src="/img/icon-cross.png" alt="" class="cross" @click="closeChildBellModal">
                    <div>알림함</div>
                </div>
                <div class="bell-list">
                    <div class="alram" v-for="child in childBellContent" :key="child">
                        <div  class="bell-content">
                            <div @click="goMission(child.mission_id)">{{ child.title }} 미션이 승인 되었어요!</div>
                            <p>{{ child.created_at }}</p>
                        </div>
                        <!-- 알람 체크 버튼 -->
                        <!-- <img src="/img/icon-delete-black.png" alt="" class="alram-check" @click="checkMission(item.mission_id)"> -->
                    </div>
                </div>
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
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';
const store = useStore();
const route = useRoute();
const router = useRouter();
// +==========================+
// +    모바일 화면 전환       +
// +==========================+
// v-if="ismobile"적으면 모바일 화면으로 이동
const isMobile = store.state.mobile.isMobile;


/* 햄버거 모달 */
const hamburgerModal = ref(false);

// 모달 열기
const openHamburgerModal = () => {
    console.log('햄버거 모달 열기');
    hamburgerModal.value = !hamburgerModal.value;
    bellModal.value = false;
}

// 모달 닫기
const closeHamburgerModal = () => {
    hamburgerModal.value = false;
}

// *******벨 드랍 메뉴 *******
const bellModal = ref(false);

// ******부모가 로그인했을 때 표시되는 자녀 미션*******
const bellContent = computed(() => store.state.header.bellContent); // 새로 등록될때마다 미션 보여줌
const openBellModal = () => {
    console.log('열라라 참께');
    bellModal.value = !bellModal.value;
    hamburgerModal.value = false;
    store.dispatch('header/bellContent');
    console.log('이거 닫겼나?');
}

const closeBellModal = () => {
    bellModal.value = false;
}

// ******자녀가 로그인했을 때 표시되는 자녀 미션*******
const childBellModal = ref(false); // 모달 닫기(기본)

const childBellContent = computed(() => store.state.header.childBell);

const openChildBellModal = () => {
    console.log('자녀 알람 모달 열기');
    childBellModal.value = !childBellModal.value;
    hamburgerModal.value = false;
    store.dispatch('header/childContent');
    
}

const closeChildBellModal = () => {
    childBellModal.value = false;
}



const goMission = (mission_id) => {
    console.log('goMission mission_id : ', mission_id);
    store.dispatch('mission/showMissionDetail', mission_id);
    closeBellModal();
}

// 알람 확인 체크
// const mission_id = computed(()=> store.state.header.bellCheckMenu);
const checkMission = (mission_id) => {
    console.log('알람 확인 체크 mission_id : ');
    store.dispatch('header/bellMenuCheck', mission_id);
    store.dispatch('header/bellContent');
}


</script>

<style scoped>

.top-menu {
    display: flex;
    justify-content: flex-end;
    /* background-color: #eeeeee; */
    height: 80px;
    /* padding-left: 50px;
    padding-right: 50px; */
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
    position: absolute;
    width: 300px;
    left: 350px;
    top: -30px;
}

.icon-circle {
    position: absolute;
    top: 23px;
    right: 177px;
    width: 10px;
}

.alram-deco {
    width: 30px;
    position: absolute;
    top: -30px;
    right: 160px;
}

/* **************햄버거 모달************* */
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

/* **********알람 모달창*********** */
.dropdown-bell {
    width: 300px;
    /* height: 600px; */
    background-color: #FFFFFF;
    position: absolute;
    z-index: 1000;
    top: 91px;
    right: 15px;
    display: flex;
    flex-direction: column;
    border-radius: 10px 10px 0px 0px;
    box-shadow: rgba(0, 0, 0, 0.05) 0px 6px 10px 0px, rgba(0, 0, 0, 0.1) 0px 0px 0px 1px;
}

.bell-title {
    display: flex;
    justify-content: center;
    flex-direction: column;
    font-size: 1rem;
    height: 80px;
    border-bottom: 1px solid #ddddddee;
}

.bell-title > div {
    text-align: center;
}

/* 알람 닫기 버튼 */
.bell-title > img {
    width: 10px;
    height: 10px;
    cursor: pointer;
    text-align: end;
    position: absolute;
    top: 10px;
    right: 10px;
}

/* 알람 내역 리스트 */
.bell-list {
    overflow: hidden;
    overflow-y: auto;
    min-height: 180px;
    max-height: 449px;

}


.alram {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: #000;
    font-size: 1.0rem;
    padding: 10px;
    gap: 5px;
    height: 90px;
    border-bottom: 1px solid #ddddddee;
}

.alram:hover {
    background-color: #f3f5f5;
}

.alram-profile {
    width: 40px;
    height: 40px;
    border-radius: 50px;
    border: 2px solid #A2CAAC;
    margin-right: 8px;
}


.bell-content {
    display: flex;
    justify-content: center;
    font-size: 0.9rem;
    width: 185px;
}

.bell-content >p {
    color: #969696;
    font-size: 0.8rem;
}

.bell-content:not(img) {
    display: flex;
    flex-direction: column;

}


.alram-check {
    width: 20px;
    height: 20px;
    cursor: pointer;
    background-image: url('/img/icon-delete-black.png');
}

.alram-check:hover {
    background-image: url('/img/icon-delete-red.png');
}

/* 등록된 미션이 없을때 */
.not {
    text-align: center;
    margin-top: 78px;
}





</style>