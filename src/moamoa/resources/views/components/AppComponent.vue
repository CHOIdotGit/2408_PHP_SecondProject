<template>
<div :class="{'bg-childs': (isChilds === true || isChilds === 'true'), 'bg-parents': (isParents === true || isParents === 'true'), 'bg-auth': !isAuth }">
    <div class="container">
    <!-- 상단 메뉴 -->
        <header>
            <!-- 좌측 고정 메뉴 -->
            <div class="menu-left" v-if="isAuth">
                <MenuLeftComponent/>
            </div>
        </header>
        
        
        <main class="layout">
            <!-- 상단 메뉴 버튼 -->
            <div v-if="isAuth">
                <HeaderMenuComponent/>
            </div>
            <!-- 화면 -->
            <div class="app-div">
                <router-view></router-view>
            </div>
        </main>

        <!-- footer -->
        <!-- <footer v-if="isAuth">
            <div>

            </div>
        </footer> -->
    </div>
</div>

</template>



<script setup>
import { computed } from 'vue';
import { useStore } from 'vuex';
import MenuLeftComponent from './layout/MenuLeftComponent.vue';
import HeaderMenuComponent from './layout/HeaderMenuComponent.vue';


const store = useStore();

// 로그인 상태 체크
const isAuth = computed(() => store.state.auth.authFlg);
const isParents = computed(() => store.state.auth.parentFlg);
const isChilds = computed(() => store.state.auth.childFlg);

</script>



<style scoped>
@import url("../../css/common.css");
@import url("../../css/swiper.css");

/* footer */
footer > p {
    text-align: center;
    color: white;
}

.container {
    display: flex;
}



.layout {
    display: flex;
    flex-direction: column;
}

.app-div {
    width: 1670px;
    height: 800px;
    display: flex;
    justify-content: center;
    align-items: center;
}

</style>