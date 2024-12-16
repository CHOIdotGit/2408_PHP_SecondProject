<template>
<div :class="{'bg-childs': isChilds, 'bg-parents': isParents, 'bg-auth': isAuth}">
    <header v-if="!isAuth">
        <HeaderComponent/>
    </header>

    <!-- main -->
    <main>
        <router-view></router-view>

    </main>
    
    <!-- footer -->
    <footer v-if="!isAuth">
        <p>© 2024. Moa Co. all rights reserved.</p>
    </footer>
</div>
</template>



<script setup>
import HeaderComponent from './layout/HeaderComponent.vue';
import { ref } from 'vue';

const isChilds = ref(true);
const isParents = ref(false);
const isAuth = ref(false);






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