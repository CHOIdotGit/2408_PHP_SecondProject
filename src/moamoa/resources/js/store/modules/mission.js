
import axios from 'axios';

export default {
    namespaced:true,
    state: ()=> ({
        missionList: []
        ,missionDetail: null
        ,lastPageFlg: false
        ,controlFlg: true
        ,missionId: null

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
        },    
        setMissionDetail(state, missionDetail) {
            state.missionDetail = missionDetail;
        }
        ,setMissionId(state, missionId) {
            state.missionId = missionId;
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
                context.commit('setMissionList', response.data.missionList);
                // console.log(response.data.missionList);
            })
            .catch(error => {
                console.error('미션 리스트 불러오기 오류', error);
            });    
        },


        // ***************************
        // 부모 미션 상세 페이지
        // ***************************
        showMissionDetail(context, id) {
            console.log(id);
            const url = '/api/parents/detail/'+ id ;
            axios.get(url, id)
            .then(response => {
                console.log(response.data.missionDetail);
                context.commit('setMissionDetail', response.data.missionDetail);
                context.commit('setMissionId', id);
                })
            .catch(error => {
                console.log('미션 상세 페이지 불러오기 오류', error);
            });
        },

        // ***************************
        // 부모 미션 작성 페이지
        // ***************************
        createMission(context, data) {
            context.commit('setControlFlg', false)
            const url  = '/api/parents/mission/create';
            const formData = new FormData();
            formData.append('title', data.title);
            formData.append('start_at', data.start_at);
            formData.append('end_at', data.end_at);
            formData.append('category', data.category);
            formData.append('content', data.content);

            axios.post(url, formData)
            .then(response => {
                router.replace('/api/parents/mission/list');
            })
            .catch(error => {
                console.log('미션 등록 실패 ㅠㅠ', error);
            })
            .finally(() => {
                context.commit('setControlFlg', true);
            })
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
