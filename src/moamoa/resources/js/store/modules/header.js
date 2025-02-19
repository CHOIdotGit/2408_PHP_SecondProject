import axios from '../../axios';
import router from '../../router';

export default {
    namespaced: true,

    state: () => ({
        childNameList: [], // 자녀 이름 목록
        bellContent: [],
        childBell: [], // 자녀 로그인 시 자녀 미션 완료 출력
        childInfo: [], // 로그인한 자녀 프로필 정보
        checkChildMissionAlarm: [],
        myName: {},
        selectChild: sessionStorage.getItem('child_id') ? sessionStorage.getItem('child_id') : null,
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
        setCheckChildMissionAlarm(state, checkChildMissionAlarm) {
            // Vuex 상태에서 업데이트된 미션을 제거
            state.bellContent = state.bellContent.filter(mission => mission.mission_id !== checkChildMissionAlarm.mission_id);
        },
        setChildBell(state, childBell) {
            state.childBell = childBell
        },
        setMyName(state, myName) {
            state.myName = myName
        },
        setSelectChild(state, childNameList) {
            state.childNameList = childNameList
            sessionStorage.setItem('child_id', childNameList);
        }
    },
    actions: {
        // ***************************
        // 부모 미션 등록 알람
        // ***************************
        bellContent(context) {
            const url = '/api/parent/header/bell';

            axios.get(url)
            .then(response => {
                context.commit('setBellContent', response.data.bellContent);

            })
            .catch(error => {
                console.log('알람 불러오기 실패', error);
            })
        },

        // ***************************
        // 자녀 미션 완료 알람
        // ***************************
        childContent(context) {
            const url = '/api/child/header/bell';

            axios.get(url)
            .then(response => {
                context.commit('setChildBell', response.data.childBellContent);
            })
            .catch(error => {
                console.log('알람 불러오기 실패', error);
            })
        },

        // ***************************
        // 알람 메뉴 체크시 확인된 상태로 만들어서 안보이게 출력
        // ***************************
        bellMenuCheck(context, mission_id) {
            const url = '/api/parent/header/bell/check/' + mission_id;
            axios.patch(url)
            .then(response => {
                // context.commit('setBellContent', response.data.bellContent);
                context.commit('setCheckChildMissionAlarm', response.data.bellCheck);
            })
            .catch(error => {
                console.log('미션 체크 실패', error);
            })

        },

        // ***************************
        // 헤더 자녀 이름과 본인 이름름 출력
        // ***************************
        childNameList(context) {
            return new Promise((resolve, reject) => {
                const url = '/api/parent/header';
                axios.get(url)
                .then(response => {
    
                    context.commit('setChildNameList', response.data.childNameList);
                    console.log('메뉴에서 자녀 리스트 보이기',response.data.childNameList);
                    context.commit('setMyName', response.data.myName);
                    
                    return resolve();
                })
                .catch(error => {
                    console.error('자녀 이름 불러오기 실패', error);
                    reject(error); // 여기서 반드시 reject 호출 - 최상민
                });
            });
        },
        // ****************************************
        // 로그인한 자녀 프로필 정보(자녀에서만 작동)
        // ****************************************
        childInfo(context) {
            return new Promise((resolve, reject) => {
                const url = '/api/child/info';
                axios.get(url)
                .then(response => {
                    console.log('로그인한 자녀 프로필 확인', response.data.childInfo);
                    context.commit('setChildInfo', response.data.childInfo);
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