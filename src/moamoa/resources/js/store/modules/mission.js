
import axios from 'axios';

export default {
    namespaced:true,
    state: ()=> ({
        missionList: []
        ,pendingMissions: []
        ,page: 0
        ,missionDetail: null
        ,lastPageFlg: false
        ,controlFlg: true

    }),
    mutations: {
        setMissionList(state, missionList) {
            state.missionList = state.missionList.concat(missionList);
        },
        setPendingMissions(state, pendingMissions) { // 대기 중인 미션 변이 추가
            state.pendingMissions = pendingMissions;
        },
        setPage(state, page) {
            state.page = page;
        },
        setLastPageFlg(state, flg) {
            state.lastPageFlg = flg;
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
        missionListPagination(context) {
            context.commit('setControlFlg', false);
            
            const url = '/api/parents/home';
            

            
        axios.get(url)
        .then(response => {
            context.commit('setMissionList', response.data.missionList.data);
            context.commit('setPendingMissions', response.data.pendingMissions || []); // 대기 중인 미션 설정
            // console.log(response.data.missionList.data);
        })
        .catch(error => {
            console.error('미션 리스트 불러오기 오류', error);
        }); 
        // .finally(() => {
        //     context.commit('setControlFlg', true);
        // });    
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