import axios from '../../axios';
import router from '../../router';

export default {
    namespaced: true,

    state: () => ({
        childNameList: [], // 자녀 이름 목록
        bellContent: [],
        childInfo: [], // 로그인한 자녀 프로필 정보
    }),
    mutations: {
        setChildNameList(state, childNameList) {
            state.childNameList = childNameList;
        },
        setBellContent(state, bellContent) {
            state.bellContent = bellContent
        },
        setChildInfo(state, childInfo) {
            state.childInfo = childInfo
        },
    },
    actions: {
        // ***************************
        // 부모 미션 등록 알람
        // ***************************
        bellContent(context) {
            const url = '/api/parent/header/bell';
            console.log(url);

            axios.get(url)
            .then(response => {
                context.commit('setBellContent', response.data.bellContent);
                console.log('a미션 데이터:', response.data.bellContent );
            })
            .catch(error => {
                console.log('알람 불러오기 실패', error);
            })
        },


        // ***************************
        // 헤더 자녀 이름 출력
        // ***************************
        childNameList(context) {
            return new Promise((resolve, reject) => {
                const url = '/api/parent/header';
                axios.get(url)
                .then(response => {
    
                    console.log(response.data.childNameList);
                    context.commit('setChildNameList', response.data.childNameList);
                    
                    return resolve();
                })
                .catch(error => {
                    console.error('자녀 이름 불러오기 실패', error);
                    return resolve();
                });
            });
        },
        // ***************************
        // 로그인한 자녀 프로필 정보
        // ***************************
        childInfo(context) {
            return new Promise((resolve, reject) => {
                const url = '/api/child/info';
                axios.get(url)
                .then(response => {
                    console.log('로그인한 자녀 프로필 확인', response.data.childInfo[0]);
                    context.commit('setChildInfo', response.data.childInfo[0]);
                    return resolve();
                })
                .catch(error => {
                    console.log('로그인한 자녀 프로필 불러오기 실패 ', error);
                    return resolve();
                });
            });
        },
    },
    getters: {

    },
}