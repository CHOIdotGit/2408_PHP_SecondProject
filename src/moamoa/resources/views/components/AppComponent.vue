<template>
<div :class="{'bg-childs': (isChilds === true || isChilds === 'true'), 'bg-parents': (isParents === true || isParents === 'true'), 'bg-auth': !isAuth }">

<!-- 상단 메뉴 -->
    <header>
        <HeaderMenuComponent/>
    </header>
    

    <main class="layout">
        <!-- 좌측 고정 메뉴 -->
        <div class="menu-left" v-if="isAuth">
            <MenuLeftComponent/>
        </div>
        <!-- 화면 -->
        <div>
            <router-view></router-view>
        </div>
    </main>

    <!-- footer -->
    <!-- <footer v-if="isAuth">
        <div>

        </div>
    </footer> -->
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



<style>
@import url("../../css/common.css");

/* footer */
footer > p {
    text-align: center;
    color: white;
}

.layout {
    display: flex;
}

</style>