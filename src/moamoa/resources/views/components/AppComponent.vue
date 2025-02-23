<template>
    <!-- :class="{'bg-childs': (isChilds === true || isChilds === 'true'), 'bg-parents': (isParents === true || isParents === 'true'), 'bg-auth': !isAuth }" -->

    <!-- 웹 디자인 -->
    <div class="container" v-if="!isMobile" :class="{ 'container-custom': !isAuth }">
        <!-- 상단 메뉴 -->
        <div class="containver-view">
            <header>
                <!-- 좌측 고정 메뉴 -->
                    <MenuLeftComponent v-if="isAuth"/>
            </header>
            
            <main class="layout" :class="{ 'layout-margin': isAuth }">
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
        <footer class="web-footer" :class="{ 'footer-custom': !isAuth }">
            <FooderComponent />
        </footer>
    </div>

    <!-- 모바일 디자인 -->
    <div v-else>
        <div>
            <header v-if="isAuth && (isParents && urlType === 'private')">
                <MobileHeaderComponent />
            </header>
            
            <main>
                <!-- 화면 -->
                <div :class="{ 'm-expense-list': !isAuth || (isAuth && (isParents && urlType === 'private')) }">
                    <router-view></router-view>
                </div>
            </main>

            <footer v-if="isAuth && (isParents && urlType === 'private')">
                <MobileFooterComponent />
            </footer>
        </div>
    </div>

    
</template>



<script setup>
import { computed } from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';
import MenuLeftComponent from './layout-project-3/MenuLeftComponent.vue';
import HeaderMenuComponent from './layout-project-3/HeaderMenuComponent.vue';
import FooderComponent from './layout-project-3/FooderComponent.vue';

import MobileHeaderComponent from './layout/MobileHeaderComponent.vue';
import MobileFooterComponent from './layout/MobileFooterComponent.vue';


const store = useStore();
const route = useRoute();

// 로그인 상태 체크
const isAuth = computed(() => store.state.auth.authFlg);
const isParents = computed(() => store.state.auth.parentFlg);
const isChilds = computed(() => store.state.auth.childFlg);
const isMobile = store.state.mobile.isMobile;
const urlType = computed(() => route.path.split('/')[2]);

</script>



<style scoped>
@import url("../../css/common.css");
@import url("../../css/swiper.css");

/* footer */
.web-footer {
    height: 32vh;
    background: #fafafa;
}
.footer-custom {
    width: 100vw;
}

.container {
    /* width: 100vw; */
    /* min-height: 100%; */
    /* height: 100vh; */
    display: flex;
    flex-direction: column;
}
.container-custom {
    overflow-x: hidden;
}

.containver-view {
    display: flex;
}

.layout {
    /* width: 100%; */
    display: flex;
    flex-direction: column;
}
.layout-margin {
    margin-bottom: 200px; 
}

.top-header-menu {
    width: 1500px;
}

.m-expense-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    overflow: scroll;
    white-space: nowrap;
    min-height: 640px;
} 

</style>