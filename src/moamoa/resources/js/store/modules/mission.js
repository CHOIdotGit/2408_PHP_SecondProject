
import axios from 'axios';

export default {
    namespaced:true,
    state: ()=> ({
        missionList: []
        ,missionDetail: null
        ,lastPageFlg: false
        ,controlFlg: true

    }),
    mutations: {
        setMissionList(state, missionList) {
            state.missionList = state.missionList.concat(missionList);
        },
        setControlFlg(state, flg) {
            state.controlFlg = flg;
        },
        resetMissionList(state) {
            state.missionList = [];
        }
    },
    actions: {
        /**
         * 미션 리스트 획득
         * 
         * @param {*} context commit, state 포함되어있음
         */
        missionListPagination(context) {
            context.commit('setControlFlg', false);
            
            const url = '/api/parents/home';
            

            
            axios.get(url)
            .then(response => {
                context.commit('setMissionList', response.data.missionList);
                // console.log(response.data.missionList);
            })
            .catch(error => {
                console.error('미션 리스트 불러오기 오류', error);
            });    
        }
    },
    getters: {
        getMissionTitle(state) {
            return state.missionList;
        },
        getPendingMissions(state) { // 대기 중인 미션 가져오는 getter 추가
            return state.pendingMissions;
        },
    },
}