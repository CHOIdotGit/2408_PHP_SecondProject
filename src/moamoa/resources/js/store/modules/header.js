import axios from '../../axios';
import router from '../../router';

export default {
    namespaced: true,

    state: () => ({
        childNameList: [], // 자녀 이름 목록
        bellContent: [],
        childInfo: [], // 로그인한 자녀 프로필 정보
        checkChildMissionAlarm: [],
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
        setCheckChildMissionAlarm(state, updatedMission) {
            // Vuex 상태에서 업데이트된 미션을 제거
            state.bellContent = state.bellContent.filter(item => item.mission_id !== updatedMission.mission_id);
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
                console.log('미션 데이터:', response.data.bellContent );
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
            console.log('알람 체크 버튼 활성화 확인', url);
            axios.patch(url)
            .then(response => {
                // context.commit('setBellContent', response.data.bellContent);
                console.log('미션 체크 완료');
                context.commit('setCheckChildMissionAlarm', response.data.bellCheck);
            })
            .catch(error => {
                console.log('미션 체크 실패', error);
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