// 사용자가 접속한 기기가 모바일일때
// 모바일 화면으로 송출
// isMobile = false 웹(크롬 기준)
// isMobile = true 앱(모바일)
import axios from '../../axios';

export default {
    namespaced: true,
    state: ()=> ({
        isMobile : /Mobi/i.test(window.navigator.userAgent),
    }),
    mutations: {
        setMobile(state, mobile) {
            state.isMobile = mobile;
        }
    },
    actions: {
        screenMobile(context) {
            context.commit('setMobile', true); // 
        }
    },
    getters: {

    },
}

