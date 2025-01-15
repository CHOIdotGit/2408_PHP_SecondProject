<template>
<div :class="{'bg-childs': (isChilds === true || isChilds === 'true'), 'bg-parents': (isParents === true || isParents === 'true'), 'bg-auth': !isAuth }">

<div class="layout">
    <header v-if="isAuth">
        <!-- 좌측 메뉴 -->
        <MenuLeftComponent/>
    </header>

    <main>
        <div v-if="isAuth" class="top-menu">
            <!-- 상단 메뉴 -->

        </div>
        <div>
            <router-view></router-view>
        </div>
    </main>
</div>
    <!-- footer -->
    <!-- <footer v-if="isAuth">
        <div>

        </div>
    </footer> -->
</div>
</template>



<script setup>
import HeaderComponent from './layout/HeaderComponent.vue';
import { computed } from 'vue';
import { useStore } from 'vuex';
import MenuLeftComponent from './layout/MenuLeftComponent.vue';


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

.layout {
    display: flex;
}

main {
    display: flex;
    flex-direction: column;
}

.top-menu {
    display: flex;
}

</style>