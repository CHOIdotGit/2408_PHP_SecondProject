<template>
<div class="userManual-container">
    <div class="userManual-head">
        <h1>이용자 매뉴얼</h1>
        <div>모아의 사용자 일반 매뉴얼 입니다</div>
    </div>
    <div class="tap-menu">
        <div class="tap-item" :class="{'active' : activeTab === 0}" @click=" activeTab = 0">회원가입</div>
        <div class="tap-item" :class="{'active' : activeTab === 1}" @click=" activeTab = 1">지출</div>
        <div class="tap-item" :class="{'active' : activeTab === 2}" @click=" activeTab = 2">미션</div>
        <div class="tap-item" :class="{'active' : activeTab === 3}" @click=" activeTab = 3">캘린더</div>
        <div class="tap-item" :class="{'active' : activeTab === 4}" @click=" activeTab = 4">통계</div>
        <div class="tap-item" :class="{'active' : activeTab === 5}" @click=" activeTab = 5">은행</div>
    </div>
    <div class="userManual-body">

        <!-- 회원가입 매뉴얼 -->
        <UserManualRegistComponent v-show="activeTab === 0" />

        <!-- 지출 메뉴얼 -->
        <UserManualSpendComponent v-show="activeTab === 1"/>

        <!-- 미션 메뉴얼 -->
        <UserManualMissionComponent v-show="activeTab === 2"/>

        <!-- 캘린더 메뉴얼 -->
        <UserManualCalendarComponent v-show="activeTab === 3"/>

        <!-- 통계 메뉴얼 -->


        <!-- 은행 메뉴얼 -->
        <UserManualBankComponent v-show="activeTab === 5"/>

    </div>
</div>

</template>

<script setup>
import { ref } from 'vue';
import UserManualBankComponent from './UserManualBankComponent.vue';
import UserManualCalendarComponent from './UserManualCalendarComponent.vue';
import UserManualMissionComponent from './UserManualMissionComponent.vue';
import UserManualRegistComponent from './UserManualRegistComponent.vue';
import UserManualSpendComponent from './UserManualSpendComponent.vue';


const activeTab = ref(0);



</script>

<style scoped>
.userManual-container {
    display: flex;
    flex-direction: column;
    margin: auto;
} 

.userManual-head {
    background-color: #0AB48C;
    width: 85%;
    height: 150px;
    margin: auto;
    color: #fff;
    padding-top: 30px;
    padding-left: 10px;
}

/* 탭 메뉴 */
.tap-menu {
    display: flex;
    width: 85%;
    /* height: 20px; */
    margin: auto;
    margin-top: 40px;
}

.tap-item {
    background-color: #f2f2f2;
    border-bottom: 1px solid #d9d9d9;
    text-align: center;
    width: 100%;
    height: 50px;
    cursor: pointer;
    line-height: 47px;
    
}

.active {
    border-top: 5px solid #ffdd38;
    border-bottom: 1px solid #fff;
    background: #fff;
    text-align: center;
    cursor: pointer;
    line-height: 47px;
    width: 100%;
    height: 50px;
}

/* 컨텐츠 영역 */
.userManual-body {
    text-align: center;
    margin-top: 70px;
}
</style>