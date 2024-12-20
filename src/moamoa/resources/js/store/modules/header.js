import axios from '../../axios';
import router from '../../router';

export default {
    namespaced: true,

    state: () => ({
        childNameList: [], // 자녀 이름 목록
        
    }),
    mutations: {
        setChildNameList(state, childNameList) {
            state.childNameList = childNameList;
        }
    },
    actions: {
        // ***************************
        // 부모 미션 등록 알람
        // ***************************
        //->(다음에)


        // ***************************
        // 헤더 자녀 이름 출력
        // ***************************
        childNameList(context) {
            const url = '/api/parent/header';
            axios.get(url)
            .then(response => {

                console.log(response.data.childNameList);
                context.commit('setChildNameList', response.data.childNameList);
            })
            .catch(error => {
                console.error('자녀 이름 불러오기 실패', error);
            })
        }


    },
    getters: {

    },
}