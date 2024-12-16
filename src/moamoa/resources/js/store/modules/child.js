import axios from "axios";

export default {
    namespaced:true,
    state: ()=> ({
        childInfo: []
        ,controlFlg: true
    }),
    mutations: {
        setChildInfo(state, childInfo) {
            state.childInfo = state.childInfo.concat(childInfo);
        },
        setControlFlg(state, flg) {
            state.controlFlg = flg;
        },
    },
    actions: {
        /**
         * 미션 리스트 획득
         * 
         * @param {*} context commit, state 포함되어있음
         */
        childInfo(context) {
            context.commit('setControlFlg', false);
            
            const url = '/api/parents/mission/list';
            

            
            axios.get(url)
            .then(response => {
                context.commit('setChildInfo', response.data.childInfo.data);
                console.log(response.data.childInfo.data);
            })
            .catch(error => {
                console.error('자녀 정보 불러오기 오류', error);
            });    
        }
    },
    getters: {
        
    },
}