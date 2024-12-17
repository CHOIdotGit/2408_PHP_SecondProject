<template>
<div :class="{'bg-childs': isChilds, 'bg-parents': isParents, 'bg-auth': !isAuth}">
    <header v-if="isAuth">
        <HeaderComponent/>
    </header>

    <!-- main -->
    <main>
        <router-view></router-view>

    </main>
    
    <!-- footer -->
    <footer v-if="isAuth">
        <p>© 2024. Moa Co. all rights reserved.</p>
    </footer>
</div>
</template>



<script setup>
import HeaderComponent from './layout/HeaderComponent.vue';
import { computed } from 'vue';
import { useStore } from 'vuex';

const store = useStore();

// 로그인 상태 체크
const isAuth = computed(() => store.state.auth.authFlg);
const isParents = computed(() => store.state.auth.parentFlg);
const isChilds = computed(() => store.state.auth.childFlg);





</script>
<style>
    @import url("../../css/common.css");
    @import url("../../css/header.css");
    /* footer */
    footer > p {
        text-align: center;
        color: white;
    }



</style>