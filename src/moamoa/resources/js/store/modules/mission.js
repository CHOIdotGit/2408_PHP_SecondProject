
import { set } from 'lodash';
import axios from '../../axios';
import router from '../../router';

export default {
    namespaced:true,
    state: ()=> ({
        missionList: []
        ,parentHome: []
        ,lastPageFlg: false
        ,controlFlg: true
        ,bellContent: []
        // 미션 관련 -------------------------------------------------------------
        ,childId: sessionStorage.getItem('child_id') ? sessionStorage.getItem('child_id') : null
        // ,missionDetail: sessionStorage.getItem('missionDetail') ? JSON.parse(sessionStorage.getItem('missionDetail')) : []
        ,missionDetail: {}
        ,missionId: sessionStorage.getItem('missionId') ? sessionStorage.getItem('missionId') :null
        ,childHome: []
        
    }),
    mutations: {
        setParentHome(state, parentHome) {
            state.parentHome = parentHome;
        },
        setChildHome(state, childHome) {
            state.childHome = childHome;
        },
        setMissionList(state, missionList) {
            state.missionList = missionList;
        },
        setControlFlg(state, flg) {
            state.controlFlg = flg;
        },
        // Vuex 상태 초기화 하는 함수
        resetState(state) {
            state.missionList = null; // 미션 리스트 초기화
            state.childId = null; // 자녀 ID 초기화
            state.controlFlg = true; // 제어 플래그 초기화
            state.parentHome = [];
            state.childHome = [];
            // 필요한 상태 변수 추가로 초기화
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
        },
        // 삭제된 mission_id 를 제거한 missionList 받아오기
        deleteMission(state, missionId) {
            state.missionList = state.missionList.filter(mission => mission.mission_id !== missionId ); 
        },
        setCreateMission(state, missionDetail) {
            state.missionDetail = missionDetail;
        },
        setUpdateMission(state, missionDetail) {
            state.missionDetail = missionDetail;
        },
        

    },
    actions: {
        /**
         * 부모 홈 페이지
         * 
         * 미션 리스트 획득
         * 
         * @param {*} context commit, state 포함되어있음
         */
        parentHome(context) {
            context.commit('setControlFlg', false);
            
            const url = '/api/parent/home';
            
        
            axios.get(url)
            .then(response => {
                context.commit('setParentHome', response.data.parentHome);
                // console.log(response.data.parentHome);
            })
            .catch(error => {
                console.error('부모 미션 리스트 불러오기 오류', error);
            });    
        },

        /**
         * 자녀 홈 페이지
         * 
         * @param {*} context 
         */
        childHome(context) {
            context.commit('setControlFlg', false);
            
            const url = '/api/child/home';
            
            axios.get(url)
            .then(response => {
                context.commit('setChildHome', response.data.childHome.missions);
                // console.log(response.data);
            })
            .catch(error => {
                console.error('자녀 미션 리스트 불러오기 오류', error);
            });    
        },

        /**
         * 부모 미션 리스트 페이지
         * 
         * 미션 정보 획득
         * 
         * @param {*} context commit, state 포함되어있음
         */
        missionList(context, child_id) {
            context.commit('setControlFlg', false);
            
            const url = '/api/parent/mission/list/' + child_id;
                        
            axios.get(url)
                .then(response => {
                    console.log(response.data.missionList.data);
                    context.commit('setMissionList', response.data.missionList.data);
                    // console.log('응답 데이터 확인', response.data.missionList.data);
                    
                    // 세션 스토리지에 자녀ID 세팅
                    sessionStorage.setItem('child_id', child_id);
                    context.commit('setChildId', child_id);
                    router.push('/parent/mission/list/' + child_id);
                    
                    // console.log('자녀 확인', context.state.childId);
                })
                .catch(error => {
                    console.error('미션 정보 불러오기 오류', error);
                });    
        },
        // ***************************
        // 자녀 미션 리스트 페이지
        // ***************************
        childMissionList(context, child_id) {
            context.commit('setControlFlg', false);
            const url = '/api/child/mission/list/' + child_id;
            console.log(url);
            axios.get(url)
                .then(response => {
                    context.commit('setMissionList', response.data.missionList.data);
                    console.log(response.data.missionList.data);
                })
                .catch(error => {
                    console.error('미션 정보 불러오기 오류', error);
                }); 
        },


        // ***************************
        // 부모 미션 상세 페이지
        // ***************************
        showMissionDetail(context, mission_id) {
            const url = '/api/parent/mission/detail/'+ mission_id;
            // console.log(url);

            axios.get(url, mission_id)
                .then(response => {
                    console.log(response.data.missionDetail);
                    // sessionStorage.setItem('missionDetail', JSON.stringify(response.data.missionDetail));
                    sessionStorage.setItem('missionId', mission_id);
                    context.commit('setMissionDetail', response.data.missionDetail);
                    context.commit('setMissionId', mission_id);

                    router.push('/parent/mission/detail/'+ mission_id);

                    // sessionStorage.removeItem('missionDetail'); // 다른페이지로 이동시 디테일 정보 제거
                    })
                .catch(error => {
                    console.log('미션 상세 페이지 불러오기 오류', error);
                    
                });
        },
        // ***************************
        // 부모 미션 작성 페이지로 이동
        // ***************************
        goCreateMission(context, child_id) {
            const url = '/api/parent/mission/create/'+ child_id;
            console.log(url);
            axios.get(url)
            .then(response => {
                    context.commit('setCreateMission', response.data.missionDetail);
                    sessionStorage.setItem('child_id', child_id);
                    context.commit('setChildId', child_id);
                    router.push('/parent/mission/create/' + child_id);
                })
                .catch(error => {
                    console.log('미션 작성 페이지로 이동 못함', error);
                })
        },

        // ***************************
        // 부모 미션 작성 페이지
        // ***************************
        createMission(context, data) {
            console.log(data);
            const url  = '/api/parent/mission/create/'+ data.child_id;
            console.log(url);
            
            const formData = new FormData();
            formData.append('title', data.title);
            formData.append('start_at', data.start_at);
            formData.append('end_at', data.end_at);
            formData.append('category', data.category);
            formData.append('content', data.content);
            formData.append('amount', data.amount);
            formData.append('parent_id', data.parent_id);
            formData.append('child_id', data.child_id);
            
            axios.post(url, formData)
            .then(response => {

                const newMission = response.data.missionDetail;
                console.log('새로운 미션 생성', newMission);

                context.commit('setMissionId', newMission.mission_id)
                console.log('새로운 미션 아이디:', newMission.mission_id)
                // context.commit('setMissonListUnshift', response.data.missionList);
                context.commit('setMissionDetail', newMission);
                //미션 등록 알람
                alert('미션이 등록되었습니다.');
                router.replace('/parent/mission/detail/' + newMission.mission_id);
                })
                .catch(error => {
                    console.log('미션 등록 실패 ㅠㅠ', error);
                })
                .finally(() => {
                    context.commit('setControlFlg', true);
                })
        },

        // ***************************
        // 부모 미션 삭제 페이지
        // ***************************
        deleteMission(context, mission_id, child_id) { //2개만
        
            const url = '/api/parent/mission/delete/' + mission_id;
            console.log(url);
            axios.delete(url)
                .then(response => {
                    context.commit('deleteMission', response.data.deleteMission);
                    context.commit('setChildId', child_id)
                    router.replace('/parent/mission/list/' + sessionStorage.child_id);
                    console.log('자녀아이디 확인', '/parent/mission/list/' + sessionStorage.child_id );
                })
                .catch(error => {
                    console.log('미션 삭제 안됨', error);
                })
                },

        // ***************************
        // 선택된 부모 미션 삭제
        // ***************************
        deletcheckedMission(context, missionIds) {
            const url = '/api/parent/mission/list/checked/delete';
            
            axios.delete(url, {
                data: { missionIds } // 선택된 미션 ID 배열 전달
            })
            .then(response => {
                // console.log(response.data.checkedMissionId);
                context.commit('deleteMission', response.data.checkedMissionId);

                
                alert('미션이 삭제되었습니다.'); //미션 삭제 알람


            })
            .catch(error => {
                console.log('미션 삭제 안됨', error);
            })
        },

        // ***************************
        // 부모 미션 수정 페이지로 이동
        // ***************************    
        goUpdateMission(context, mission_id) {
            const url = '/api/parent/mission/detail/'+ mission_id;
            // console.log(url);

            
            axios.get(url)
                .then(response => {
                    context.commit('setMissionDetail', response.data.missionDetail);
                    // sessionStorage.setItem('missionId', 'mission_id');
                    context.commit('setMissionId', mission_id);
                })
                .catch(error => {
                    console.log('미션 수정 페이지로 이동 못함', error);
                })
        },



        // ***************************
        // 부모 미션 수정 페이지
        // ***************************
        UpdateMission(context, updateInfo) {
            const data = JSON.stringify(updateInfo);
            // console.log(updateInfo);
            const url = '/api/parent/mission/update/' + updateInfo.mission_id;
            // console.log(url);
            // const updateData = new FormData();
            // updateData.append('title', updateInfo.title);
            // updateData.append('start_at', updateInfo.start_at);
            // updateData.append('end_at', updateInfo.end_at);
            // updateData.append('category', updateInfo.category);
            // updateData.append('content', updateInfo.content);
            // updateData.append('amount', updateInfo.amount);

            // console.log(updateData);
            //patch 는 FormData 사용안함. header에서 첨부터 json을 기본으로 하는데
            //json 배열로 데이터를 보내줘야함. 
            axios.patch(url, data)
            .then(response => {
                console.log('미션 수정 성공 :', response.data);
                context.commit('setMissionDetail', response.data.updateMission);
                
                alert("미션이 수정되었습니다.");
                router.push('/parent/mission/detail/' + data.mission_id);

            })
            .catch(error => {
                console.log('미션 수정 에러', error);
            })
        },

        /**
         * 미션 승인
         * 
         * @param {*} context 
         * @param {*} ids 
         */
        approvalMission(context, ids) {
            const url = '/api/parent/mission/approval';
            const data = JSON.stringify({ mission_ids: ids });

            axios.post(url, data)
            .then(res => {
                // console.log(res.data.data);
            });
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
