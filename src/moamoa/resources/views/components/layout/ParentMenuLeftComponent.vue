<template>
<div class="menu-left">
    <!-- 반응형 버튼튼 -->
    <div class="menu-btn">
        <!-- 반응형 버튼(닫는거) -->
        <img src="/img/icon-angle-double-left.png" alt="" class="btn-left" @click="closeMenubtn">

        <!-- 반응형 버튼(여는거) -->
        <img src="/img/icon-angle-double-right.png" alt="" class="btn-right">
    </div>

    <div class="menu-container" v-show="slidMenu">

        <!-- 자녀가 있을 때  -->
        <div class="child-box" v-if="childNameList.length > 0" >
            <div class="child-profile">
                <img :src="childProfile.profile || '/profile/default5.webp'" alt="">    
            </div>
            <div class="child-info">
                <!-- todo nick name 나중에 수정 -->
                <div class="child-nickname">{{childProfile.nick_name}}</div>
                <div class="child-name">{{ childProfile.name }}</div>
            </div>

            <!-- 자녀 프로필 선택 메뉴 -->
            <select name="childName" id="child" v-if="childNameList.length > 0" v-model="selectedChildId">
                <option value="0" disabled>자녀 선택</option>
                <option v-for="child in childNameList" :key="child" :value="child">
                    {{ child.name }}
                </option>
            </select>
        </div>

        <!-- 자녀가 없을 때  -->
        <div class="child-box" v-else>
            <div class="child-profile">
                <img src="/profile/default5.webp" alt="">    
            </div>
            <div class="child-info">
                <div class="child-name">자녀를 등록하세요 </div>
            </div>

            <!-- 자녀 프로필 선택 메뉴 -->
            <select name="childName" id="child"> 
                <option  value="자녀없음" hidden>자녀 등록</option>
            </select>
        </div>

        <!-- 메뉴 -->
        <div class="menu-box">
            <router-link to="/parent/home" class="link-deco">
                <div class="menu-title">
                    <img src="/img/icon-home.png" alt="" class="menu-icon">
                    홈
                </div>
            </router-link>
            <div class="menu-title" @click="$store.dispatch('transaction/transactionList', selectedChildId.child_id)">
                <img src="/img/icon-coin.png" alt="" class="menu-icon">
                지출
            </div>
            <div class="menu-title" @click="$store.dispatch('mission/missionList', selectedChildId.child_id)">
                <img src="/img/icon-piggy-bank.png" alt="" class="menu-icon">
                미션
            </div>
            <div class="menu-title" @click="goParentCalendar(selectedChildId.child_id)">
                <img src="/img/icon-calendar.png" alt="" class="menu-icon">
                캘린더
            </div>
            <div class="menu-title" @click="goParentStatis(selectedChildId.child_id)">
                <img src="/img/icon-signal.png" alt="" class="menu-icon">
                통계
            </div>
            <div class="menu-title" @click="goBankbook">
                <img src="/img/icon-sack-dollar.png" alt="" class="menu-icon">
                모아통장
            </div>
            <!-- 부모는 안보임 -->
            <!-- <div class="menu-title">
                <img src="/img/icon-shopping-cart.png" alt="" class="menu-icon">
                상점
            </div> -->

            <button type="button" @click="$store.dispatch('auth/logout')" class="logout-btn">로그아웃</button>
        </div>
        <!-- 고객센터 -->
        <div class="cs">

        </div>
    </div>

</div>
</template>

<script setup>
import { computed, onBeforeMount, onMounted, ref, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';


const store = useStore();
const route = useRoute();
const router = useRouter();

// 좌측 메뉴
const  slidMenu = ref(true);
const closeMenubtn = () => {
    slidMenu.value = !slidMenu.value;
}

// 자녀 프로필 메뉴
const selectedChildId = ref(0); // 셀렉트 박스가 처음에 "0=자녀선택" 표시
const childNameList = computed(() => store.state.header.childNameList || []); //등록된 자녀 수 확인
const childProfile = ref({}); // 선택된 자녀 정보


watch(selectedChildId, (newId) => {
    console.log('선택된 자녀 정보', childProfile);
    if(selectedChildId) {
        childProfile.value = newId;
    }
})

onBeforeMount(async () => {
    await store.dispatch('header/childNameList');
    childProfile.value = store.state.header.childNameList[0];

    // TODO : 확인용 나중에 삭제 start ----------
    if(store.state.header.childNameList.length > 0) {
        console.log('등록된 자녀 확인하기');
        console.log(store.state.header.childNameList);
    }
    else {
        console.log('등록된 자녀 없음');
    }
    // TODO : 확인용 나중에 삭제 end ----------
})

//  부모 캘린더로 이동
const goParentCalendar = (child_id) => {
    store.dispatch('calendar/parentCalendar', child_id)
    router.push('/parent/calendar/'+ child_id);
}

// 부모 통계로 이동
const goParentStatis = (child_id) => {
    store.dispatch('transaction/parentStats', child_id)
    router.push('/parent/stats/' + child_id);
}
</script>

<style scoped>
* {
    font-family: 'MangoDdobak-R';
}

button {
    background-color: #FFFFFF;
}

.link-deco {
    text-decoration: none;
    color: #000000;
}

.menu-container {
    /* position : absolute;
    left: -220px; */

    -webkit-transition: left .3s;
    -moz-transition: left .3s;
    -ms-transition: left .3s;
    -o-transition: left .3s;
    transition: left .3s;
}

@font-face {
    font-family: 'MangoDdobak-R';
    src: url('https://fastly.jsdelivr.net/gh/projectnoonnu/2405-3@1.1/MangoDdobak-R.woff2') format('woff2');
    font-weight: 400;
    font-style: normal;
}

@font-face {
    font-family: 'MangoDdobak-B';
    src: url('https://fastly.jsdelivr.net/gh/projectnoonnu/2405-3@1.1/MangoDdobak-B.woff2') format('woff2');
    font-weight: 700;
    font-style: normal;
}

/* 가장 작은 화면에서 최대 800px까지 적용  */
@media (max-width:800px) {
    .menu-container {
        display: none;
    }
    .menu-box {
        display: none;
    }
    .btn-left {
        display: none;
    }
    .menu-btn {
        width: 60px;
        background-color: #ffffff;
    }
    
}

/* 801px 부터 적용*/
@media (min-width:801px) {
    .btn-right {
        display: none;
    }
}

/* 상단 메뉴 */
.header-container {
    height: 40px;
    width: 100vw;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    padding: 5px;
    gap: 5px;
    background-color: #e9e9e9;
}

.menu-left {
    background-color: #A2CAAC;

}

/* 좌측 메뉴 */
.menu-container {
    width: 250px;
    height: 51.55rem;
}

/* 반응형 버튼 */
.menu-btn {
    height: 60px;
    padding: 10px;
    display: flex;
    justify-content: flex-end;
}

.btn-left, .btn-right {
    width: 35px;
    height: 35px;
    padding: 5px;

}

/* 자녀 프로필 */
.child-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    border-bottom: 1px solid #000000;
    background-color: #d7e4da;
    padding: 10px;
}

.child-profile {
    width: 100px;
    height: 100px;
    margin-bottom: 10px;
}

.child-profile >img {
    width: 100px;
    height: 100px;
    padding: 3px;
    background-color: #FFFFFF;
    border-radius: 50px;
    margin: 5px;
}

.child-info {
    display: flex;
    text-align: center;
    padding-left: 10px;
    gap: 10px;
    align-items: center;
}
.child-name {
    font-size: 1.3rem;
}

.child-nickname {
    font-size: 1.1rem;
}

/* 자녀 선택 셀렉트 박스 */
#child {
    width: 120px;
    height: 30px;
    font-size: 1.2rem;
    border-radius: 4px;
    
}


/* 자녀 선택 버튼 */
.child-toggle-btn {
    /* width: 20px; */
    height: 30px;
    display: flex;
    align-items: center;
}

/* 메뉴  */
.menu-box {
    font-size: 1.6rem;
    font-family: 'MangoDdobak-B';
    font-weight: 700;
    cursor: pointer;
}

.menu-title {
    padding: 15px 20px 15px 20px;
    /* border: 1px solid; */
}

.menu-title > img {
    width: 15px;
}

.menu-title:hover {
    color: #ffffff;
    background-color: #6a8f73;
}

.menu-icon {
    width: 15px;
}

.test {
    display: flex;
    gap: 5px;
}

.test >img{
    width: 15px;
}

/* 서비스 문의 */
.cs {
    background-color: #e9e9e9;
}


/* 상단 메뉴(사용자매뉴얼, 알람, 부모 프로필) */
</style>