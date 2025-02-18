<template>
    <!-- :class="{'bg-childs': (isChilds === true || isChilds === 'true'), 'bg-parents': (isParents === true || isParents === 'true'), 'bg-auth': !isAuth }" -->

    <div v-if="!isMobile" class="container">
    <!-- 상단 메뉴 -->
     <div class="containver-view">
         <header>
             <!-- 좌측 고정 메뉴 -->
                 <MenuLeftComponent v-if="isAuth"/>
         </header>
         
         <main class="layout">
             <!-- 상단 메뉴 버튼 -->
             <div v-if="isAuth" class="top-header-menu">
                 <HeaderMenuComponent/>
             </div>
             <!-- 화면 -->
             <div class="app-div">
                 <router-view></router-view>
             </div>
         </main>

     </div>

        <!-- 하단 메뉴 -->
        <footer>
            <FooderComponent />
        </footer>
    </div>

    <div v-else class="app-container">
        
        <main class="app-layout">
            <!-- 화면 -->
            <router-view></router-view>
        </main>


    </div>
</template>



<script setup>
import { computed } from 'vue';
import { useStore } from 'vuex';
import MenuLeftComponent from './layout-project-3/MenuLeftComponent.vue';
import HeaderMenuComponent from './layout-project-3/HeaderMenuComponent.vue';
import FooderComponent from './layout-project-3/FooderComponent.vue';


const store = useStore();

// 로그인 상태 체크
const isAuth = computed(() => store.state.auth.authFlg);
const isParents = computed(() => store.state.auth.parentFlg);
const isChilds = computed(() => store.state.auth.childFlg);
const isMobile = store.state.mobile.isMobile;

</script>



<style scoped>
@import url("../../css/common.css");
@import url("../../css/swiper.css");

/* footer */
footer {
    height: 32vh;
    background: #fafafa;
}

.container {
    /* width: 100vw; */
    /* min-height: 100%; */
    /* height: 100vh; */
    display: flex;
    flex-direction: column;

}

.containver-view {
    display: flex;
}

.layout {
    /* width: 100%; */
    display: flex;
    flex-direction: column;
    margin-bottom: 200px;
}

.top-header-menu {
    width: 1500px;
}

/* 앱 설정 ----------------------------------------------------------------------------------------------- */

.app-container {
    width: 100vw;
    height: 100vh;
    display: flex;
    justify-content: center;
}

.app-layout {
    width: 100%;
}

</style>