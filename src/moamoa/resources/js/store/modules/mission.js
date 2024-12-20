
import { set } from 'lodash';
import axios from '../../axios';
import router from '../../router';

export default {
    namespaced:true,
    state: ()=> ({
        missionList: []
        ,missionInfo: null
        ,missionDetail: null
        ,lastPageFlg: false
        ,controlFlg: true
        ,missionId: null
        ,bellContent: []
        ,childId: null

    }),
    mutations: {
        setMissionList(state, missionList) {
            state.missionList = state.missionList.concat(missionList);
        },
        setMissionInfo(state, missionInfo) {
            state.missionInfo = missionInfo;
            console.log('mutation setMissionInfo called with:', missionInfo);
        },
        setControlFlg(state, flg) {
            state.controlFlg = flg;
        },
        resetMissionList(state) {
            state.missionList = [];
        },    
        setMissionDetail(state, missionDetail) {
            state.missionDetail = missionDetail;
        },
        setMissionId(state, missionId) {
            state.missionId = missionId;
        },
        setbellContent(state, bellContent) { // 헤더 알림창
            state.bellContent = bellContent;
        },
        setMissonListUnshift(state, missionList) { //미션 리스트 항목 추가
            state.missionList.unshift(missionList);
        },
        setChildId(state, childId) {
            state.childId = childId;
            console.log('mutation setChildId called with:', childId);
        }
        

    },
    actions: {
        /**
         * 부모 홈 페이지
         * 
         * 미션 리스트 획득
         * 
         * @param {*} context commit, state 포함되어있음
         */
        missionListPagination(context) {
            context.commit('setControlFlg', false);
            
            const url = '/api/parent/home';
            
        
            axios.get(url)
            .then(response => {
                context.commit('setMissionList', response.data.missionList);
                // console.log(response.data.missionList);
            })
            .catch(error => {
                console.error('미션 리스트 불러오기 오류', error);
            });    
        },

        /**
         * 미션 리스트 페이지
         * 
         * 미션 정보 획득
         * 
         * @param {*} context commit, state 포함되어있음
         */
        missionInfo(context, id) {
            context.commit('setControlFlg', false);
            
            const url = '/api/parent/mission/list/' + id;
            
            axios.get(url, { params: { id } })
            .then(response => {
                console.log('Fetched missions for child ID:', id);

                const missionInfo = response.data.missionInfo.data;
                const childId = missionInfo.child_id || id;
    
                context.commit('setMissionInfo', missionInfo);
                context.commit('setChildId', childId);
    
                console.log('Updated child ID in state:', context.state.mission.childId);
            })
            .catch(error => {
                console.error('미션 정보 불러오기 오류', error);
            });    
        },

        // ***************************
        // 부모 미션 상세 페이지
        // ***************************
        showMissionDetail(context, id) {
            console.log(id);
            const url = '/api/parent/mission/detail/'+ id ;
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
            const url  = '/api/parent/mission/create';
            console.log(data, url);
            const formData = new FormData();
            formData.append('title', data.title);
            formData.append('start_at', data.start_at);
            formData.append('end_at', data.end_at);
            formData.append('category', data.category);
            formData.append('content', data.content);
            formData.append('content', data.amount);

            axios.post(url, formData)
            .then(response => {
                console.log('미션 등록 ^^');
                context.commit('setMissonListUnshift', response.data.missionList);
                router.replace('/api/parents/detail');
            })
            .catch(error => {
                console.log('미션 등록 실패 ㅠㅠ', error);
            })
            .finally(() => {
                context.commit('setControlFlg', true);
            })
        },

        
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
