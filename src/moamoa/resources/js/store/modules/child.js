import axios from "axios";

export default {
    namespaced:true,
    state: ()=> ({
        missionInfo: []
        ,controlFlg: true
    }),
    mutations: {
        setMissionInfo(state, missionInfo) {
            state.missionInfo = state.missionInfo.concat(missionInfo);
        },
        setControlFlg(state, flg) {
            state.controlFlg = flg;
        },
    },
    actions: {
        /**
         * 자녀 정보 획득
         * 
         * @param {*} context commit, state 포함되어있음
         */
        missionInfo(context) {
            context.commit('setControlFlg', false);
            
            const url = '/api/parents/mission/list';
            

            
            axios.get(url)
            .then(response => {
                console.log(response.data.missionInfo.data);
                context.commit('setMissionInfo', response.data.missionInfo.data);
            })
            .catch(error => {
                console.error('미션 정보 불러오기 오류', error);
            });    
        }
    },
    getters: {
        
    },
}