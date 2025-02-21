<template>
<div class="menu-left" v-if="!isMobile">
    <div class="menu-container" v-show="slidMenu">
        <img src="/img/logo4.png" class="logo" width="250px"  height="100px">
        <!-- 자녀가 있을 때 - 최상민 수정함함 -->
        <!-- <div class="child-box" v-if="childNameList.length > 0" >
            <div class="child-profile">
                <img :src="childProfile.profile || '/profile/default5.webp'" alt="">    
            </div>
            <div class="child-info"> -->
                <!-- <div class="child-name">{{ childProfile.name }}</div>
            </div> -->

            <!-- 자녀 프로필 선택 메뉴 -->
            <!-- <select name="childName" id="child" v-if="childNameList.length > 0" v-model="selectedChildId">
                <option value="0" disabled>자녀 선택</option>
                <option v-for="child in childNameList" :key="child" :value="child">
                    {{ child.name }}
                </option>
            </select>
        </div> -->
        <div class="child-box" v-if="childNameList.length > 0">
            <!-- 메뉴 :  프로필 보이는 곳 -->
            <div class="child-profile">
                <img :src="displayProfile.profile">
            </div>
            <div class="child-info">
                <div class="child-name">{{ displayProfile.name }}</div>
            </div>
            <!-- 자녀 선택 없을 시 경고 알람 : "자녀를 선택하세요" -->
            <img src="/img/icon-select-alarm.png" alt="" class="select-alarm" v-show="showAlarm">

            <!-- 자녀 프로필 선택 메뉴 -->
            <select name="childName" id="child" v-model="selectedChild">
                <!-- 기본값: 부모 정보 -->
                <option :value="null">(나)</option>
                <option v-for="child in childNameList" :key="child" :value="child">
                    {{ child.name }}
                </option>
            </select>
        </div>

        <!-- 자녀가 없을 때  -->
        <div class="child-box" v-else>
            <div class="child-profile">
                <img :src="displayProfile.profile || '/profile/default5.webp'">    
            </div>
            <div class="child-info">
                <div class="child-name">{{ displayProfile.name }}</div>
                <div class="child-name">자녀를 등록하세요</div>
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
            <!-- <div class="menu-title" @click="$store.dispatch('transaction/transactionList', selectedChildId.child_id)"> -->
            <!-- 최상민 : 거래 모듈 변경에 따른 지출리스트 이동 방법 변경 -->
            <div class="menu-title" @click="goSpendList"> 
                <img src="/img/icon-coin.png" alt="" class="menu-icon">
                지출
            </div>
            <!-- <div class="menu-title" @click="$store.dispatch('mission/missionList', selectedChildId.child_id)"> -->
            <!-- 최상민 : 미션 모듈 변경에 따른 미션리스트 이동 방법 변경 -->
            <div class="menu-title" @click="goMissionList"> 
                <img src="/img/icon-piggy-bank.png" alt="" class="menu-icon">
                미션
            </div>
            <div class="menu-title" @click="goParentCalendar">
                <img src="/img/icon-calendar.png" alt="" class="menu-icon">
                캘린더
            </div>
            <div class="menu-title" @click="goParentStatis">
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

// +==========================+
// +    모바일 화면 전환       +
// +==========================+
// v-if="ismobile"적으면 모바일 화면으로 이동
const isMobile = store.state.mobile.isMobile;


// 좌측 메뉴
const  slidMenu = ref(true);

// 자녀 프로필 메뉴
const selectedChild = ref(null); // 로그인시 부모가 표시
const childNameList = computed(() => store.state.header.childNameList || []); //등록된 자녀 수 확인
const childProfile = ref({}); // 선택된 자녀 정보

// to do : 이거 나중에 childNameList로 합칠 수 있을 듯
// 로그인한 부모 정보
const myName = computed(() => store.state.header.myName);

// const displayProfile = computed(() => {
//     return selectedChild.value ? selectedChild.value : myName.value;
// });
const displayProfile = computed(()=> selectedChild.value ? selectedChild.value : myName.value);

watch(() => selectedChild.value, newInfo => {
    if(newInfo) {
        store.commit('header/setChildId', newInfo.child_id);
    }
    else {
        store.commit('header/setChildId', null);
    }
    router.replace('/parent/home');
});

onBeforeMount(async () => {
    await store.dispatch('header/childNameList');
    childProfile.value = store.state.header.childNameList[0];
    
    // TODO : 확인용 나중에 삭제 start ----------
    if(store.state.header.childNameList.length > 0) {
        console.log('등록된 자녀 확인하기:', store.state.header.childNameList);
    }
    else {
        console.log('등록된 자녀 없음');
    }
    // TODO : 확인용 나중에 삭제 end ----------
});

// 자녀 선택 없을 시 띄우는 경고 알람 
const showAlarm = ref(false);

// 게시판 이동----------------------------------------------------------------------
// 부모 지출 리스트 페이지로 이동
const goSpendList = () => {
    console.log('현재 선택된 자녀', selectedChild.value);

    if(!selectedChild.value) {
        showAlarm.value = true;
        setTimeout(()=> showAlarm.value = false, 2000); // 2초 후 닫기기
        return;    
    } 
    
    else {
        const child_id = selectedChild.value.child_id;
        // 거래 정보를 가져오는 액션 호출
        store.dispatch('transaction/transactionList', {child_id: route.params.id, page: 1});
        router.push('/parent/spend/list/' + child_id);

    }
};

// 부모 미션 리스트 페이지로 이동
const goMissionList = () => {
    if(!selectedChild.value) {
        showAlarm.value = true;
        setTimeout(()=> showAlarm.value = false, 2000); // 2초 후 닫기기
        return;    
    } 
    const child_id = selectedChild.value.child_id;
    store.dispatch('mission/missionList', {child_id: route.params.id, page: 1});
    router.push('/parent/mission/list/' + child_id);
};

//  부모 캘린더로 이동
const dateToday = ref(new Date());
const goParentCalendar = () => {
    if(!selectedChild.value) {
        showAlarm.value = true;
        setTimeout(()=> showAlarm.value = false, 2000); // 2초 후 닫기기
        return;    
    }
    const child_id = selectedChild.value.child_id;
    store.dispatch('calendar/parentCalendarInfo', { date: dateToday.value, child_id })
    router.push('/parent/calendar/'+ child_id);
}

// 부모 통계로 이동
const goParentStatis = () => {
    if(!selectedChild.value) {
        showAlarm.value = true;
        setTimeout(()=> showAlarm.value = false, 2000); // 2초 후 닫기기
        return;    
    }
    const child_id = selectedChild.value.child_id;
    store.dispatch('transaction/parentStats', child_id)
    router.push('/parent/stats/' + child_id);
}

// 부모 통장 페이지
const goBankbook = () => {
    if(!selectedChild.value) {
        showAlarm.value = true;
        setTimeout(()=> showAlarm.value = false, 2000); // 2초 후 닫기기
        return;    
    }
    const child_id = selectedChild.value.child_id;
    router.push('/parent/moabank/' + child_id);
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
/* @media (max-width:800px) {
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
    
} */

/* 801px 부터 적용*/
/* @media (min-width:801px) {
    .btn-right {
        display: none;
    }
} */

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
    height: 100%;
    background-color: #A2CAAC;
    box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);
}

/* 좌측 메뉴 */
.menu-container {
    /* width: 250px;
    height: 93vh; */

    width: 300px;
    height: 100%;
    min-height: 100vh;
    -webkit-transition: left .3s;
    -moz-transition: left .3s;
    -ms-transition: left .3s;
    -o-transition: left .3s;
    transition: left .3s;
}

.select-alarm {
    width: 11%;
    position: absolute;
    top: 240px;
    left: 216px;
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
    object-fit: cover;
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
    position: relative;
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

.logo {
    margin-left: 30px;
}

/* 상단 메뉴(사용자매뉴얼, 알람, 부모 프로필) */
</style>