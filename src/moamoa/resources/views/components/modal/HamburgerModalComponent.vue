<template>
<!-- ******************기각******************* -->
<!-- 이건 사용하지 않습니다 -->
<!-- 햄버거 드랍메뉴 -->
<div class="drop-bar">
    <div class="item">
        <div class="dropdown">
            <router-link v-if="$store.state.auth.parentFlg" to="/parent/family/info" class="link-deco">
                <p class="info-page">가족정보</p>
            </router-link>
            <router-link v-if="$store.state.auth.childFlg" to="/child/private/rematching" class="link-deco">
                <p class="info-page">부모 재매칭</p>
            </router-link>
            <router-link :to="$store.state.auth.parentFlg ? '/parent/private/edit' : '/child/private/edit'" class="link-deco">
                <p class="info-page">개인정보 수정</p>
            </router-link>
            <router-link :to="$store.state.auth.parentFlg ? '/parent/private/password' : '/child/private/password'" class="link-deco">
                <p class="info-page">비밀번호 변경</p>
            </router-link>
            <router-link :to="$store.state.auth.parentFlg ? '/parent/private/withdrawal' : '/child/private/withdrawal'" class="link-deco">
                <p class="info-page info-page-red">회원 탈퇴</p>
            </router-link>
            
        </div>
    </div>
</div> 
</template>

<script setup>
import { ref } from 'vue';

// *******햄버거 드랍 메뉴 *******
const dropDownMenu = ref(false);
const hamDropDown = () => {
    console.log('열려랴');
    dropDownMenu.value = !dropDownMenu.value; // 현재 상태를 반전시킴
    console.log('닫힘?');
}

</script>

<style scoped>
.drop-bar {
    position: fixed;
    width: 100%;
    height: 100%;
}

.item {
    text-align: center;
    border: 1px solid #000;
    width: 200px;
    height: 200px;
    top: 60px;
    right: 220px;
    position: relative;
}

/* 햄버거 드롭다운 */
.info-page  {
    text-decoration: none;
    color: #000;
    font-size: 1.2rem;
    padding: 15px;
}

.link-deco {
    text-decoration: none;
    color: #000;
}


</style>