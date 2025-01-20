<template>

<div class="login-container" v-if="!mobileFlg">
    <div class="login-box">
        <!-- 아이디 비밀 번호 입력 -->
        <div class="data-input">
            <div>
                <!-- 공용 에러 메세지 -->
                <div v-if="errMsg.common" class="err-msg">
                    <span> {{ errMsg.common }} </span>
                </div>
                    
                <label for="account">
                    <span v-if="errMsg.account" class="err-msg">
                    {{ errMsg.account }}
                    </span>
                </label>
                    <!-- <br> -->
                    <input v-model="userInfo.account" :class="{ 'err-border' : errMsg.account }" type="text" name="account" placeholder="아이디를 입력하세요">
            </div>
            <div>
                <label for="password">
                    <span v-if="errMsg.password" class="err-msg">
                    {{ errMsg.password }}
                    </span>
                </label> 
                <!-- <br> -->
                <input v-model="userInfo.password" :class="{ 'err-border' : errMsg.password }" type="password" name="password" placeholder="비밀번호를 입력하세요">
            </div>
            
        </div>
        <!-- 로그인 버튼 -->
        <button class="login-btn" type="button" @click="$store.dispatch('auth/login', userInfo)">로그인</button>

        <!-- 아이디/비밀번호 찾기, 회원가입 -->
        <div class="data-btn">
            <button>아이디 찾기</button>
            <button>비밀번호 찾기</button>
            <router-link to="/regist/main">
                <button class="regist-btn" type="button">회원가입</button>
            </router-link>
        </div>
    </div>
</div>
<div v-else>ㅅㅅㅅㅅㅅㅅㅅㅅㅅㅅ</div>
</template>

<script setup>
import { computed, reactive, onMounted, onBeforeUnmount } from 'vue';
import { onBeforeRouteLeave } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const mobileFlg = /Mobi/i.test(window.navigator.userAgent);
// ******** 모바일/ 웹 이동 *******




// 에러 정보 ---------------------------------------------------------------------------------------------
const errMsg = computed(() => store.state.auth.errMsg);

// 회원 정보 ---------------------------------------------------------------------------------------------
const userInfo = reactive({
    account: '',
    password: '',
});

// 이동 반응 이벤트 ---------------------------------------------------------------------------------------------

// 예외 정보 리셋
const clearState = () => {
    // 들어간 값이 하나라도 있으면 리셋
    if(Object.values(errMsg).some(value => value !== '' || value !== null || value !== undefined)) {
        store.commit('auth/resetErrMsg');
    }
};

// 페이지가 로드되면 DOM에 이벤트 추가
onMounted(() => {
    window.addEventListener('popstate', clearState());
    window.addEventListener('beforeunload', clearState);
});

// 다음 컴포넌트로 넘어갈 때 이벤트를 제거
onBeforeUnmount(() => {
    window.removeEventListener('popstate', clearState());
    window.removeEventListener('beforeunload', clearState);
});

// 다른 페이지로 이동 시 작동함
onBeforeRouteLeave((to, from, next) => {
    clearState();
    next();
});



</script>

<style scoped>
/* 폼 기본 설정 */
input {
    outline: none;
    border: 1px solid #dddddd;
    border-radius: 5px;
    background-color: #ffffff;
    width: 300px;
    height: 50px;
    font-size: 1.3rem;
    padding: 5px;
}

input:focus {
    border: 3px solid #A4D8E1;
    outline: none;

}

/* 커서 손모양으로 변경 */
button {
    cursor: pointer;
}

@media (max-width:800px) {
    .login-box {
        width: 400px;
    }
    
}

.login-container {
    background-image: url('/img/background.png');
    background-size: cover;
    background-repeat: no-repeat;
    width: 100vw;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.login-box {
    background-color: #ffffff;
    width: 500px;
    height: 250px;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-top: 20px;
}

.data-input {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.login-btn {
    width: 300px;
    height: 50px;
    font-size: 1.3rem;
    margin: 10px;
    border: none;
    border-radius: 5px;
}

.login-btn:hover {
    background-color: #A4D8E1;
}


/* 아이디/비밀번호 찾기, 회원가입 버튼 */
.data-btn > button {
    border: none;
    background-color: #ffffff;
    margin: 10px;
    font-size: 1rem;
}

.regist-btn {
    border: none;
    background-color: #ffffff;
    margin: 10px;
    font-size: 1rem;
}

.data-btn > button:hover {
    text-decoration: underline;

}

.regist-btn:hover {
    text-decoration: underline;  
}

</style>