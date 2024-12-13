<template>
<div :class="{'bg-childs': isChilds, 'bg-parents': isParents}">
    <header>
        <HeaderComponent/>
    </header>

    <!-- main -->
    <main>
        <router-view></router-view>

    </main>
    
    <!-- footer -->
    <footer>
        <p>© 2024. Moa Co. all rights reserved.</p>
    </footer>
</div>
</template>



<script setup>
import HeaderComponent from './layout/HeaderComponent.vue';
import { ref } from 'vue';

const isChilds = ref(false);
const isParents = ref(true);






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