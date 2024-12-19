import axios from 'axios';

export default {
    namespaced: true,
    state: () => ({
        page: 0,
        controlFlg: true,
        calendarInfo: {},
        sidebarMission: 0,
        sidebarData: {
            traffic: 0,
            meal: 0,
            shopping: 0,
            etc: 0,
          },
        mission: 0,
        dailyIncomeData: [],
    
    }),
    mutations: {
        setCalendarInfo(state, data) {
          state.calendarInfo = data;
        },
        setCalendarMission(state, data) {
            state.calendarMission = data;
        },
        setSidebarData(state, payload) {
            state.sidebarData = payload;
        },
        SET_SIDEBAR_MISSION(state, mission) {
            state.sidebarMission = mission;
        },
        SET_CALENDAR_MONTH(state, data) {
            state.calendarInfo.sidebarData = data.sidebarData;
            state.calendarInfo.sidebarMission = data.sidebarMission;
        },
        SET_DAILY_INCOME(state, data) {
            state.dailyIncomeData = data;
        },
    },
    actions: {
        // 캘린더에서 이름불러오기
        calendarInfo(context, objDate) {
            // const url = '/api/child/calendar?year=' + objDate.getFullYear() + '&month=' + (objDate.getMonth() + 1);
            // // console.log(url)
            // axios.get(url)
            // .then(response => {
            //     console.log(response.data);
            //     context.commit('setCalendarInfo', response.data);
            // })
            // .catch(error => {
            //     console.log(' 달력 이름X', error);
            // })

            return new Promise((resolve, reject) => {
                const url = '/api/child/calendar?year=' + objDate.getFullYear() + '&month=' + (objDate.getMonth() + 1);
                // console.log(url)
                axios.get(url)
                .then(response => {
                    console.log(response.data);
                    context.commit('setCalendarInfo', response.data);
                    resolve();
                })
                .catch(error => {
                    console.log(' 달력 이름X', error);
                    reject();
                });
            });
        },
        
        // 교통비 식비 쇼핑 기타 각각내역
        fetchSidebarData({ commit }, {year, month}) {
            axios
            .get("/api/child/calendar", {
                params: {year, month},
            })
            .then((response) => {
                // console.log(response.data.sidebarData);

                commit("setSidebarData",  JSON.stringify(response.data.sidebarData));
            })
            .catch((error) => {
                console.error("데이터 못불러옴:", error);
            });
        },

        // 미션으로 받은 용돈
        fetchSidebarMission({ commit }, { year, month }) {
            axios
              .get(`/api/child/mission?year=${year}&month=${month}`)
              .then(response => {
                commit('SET_SIDEBAR_MISSION', response.data.missionTotal);
              })
              .catch(error => {
                console.error("미션값 못불러옴", error);
              });
        },
        
        // 일별 수입합계
        fetchDailyIncome({ commit }, { year, month }) {
            axios
                .get("/api/child/calendar", {
                    params: { year, month },
                })
                .then((response) => {
                    // 서버에서 받은 dailyIncomeData를 Mutation에 전달
                    commit("SET_DAILY_INCOME", response.data.dailyIncomeData);
                })
                .catch((error) => {
                    console.error("일별 수입 데이터를 불러오지 못했습니다:", error);
                });
        },

        
    },
    getters: {
        sidebarMission: state => state.sidebarMission
    },
};
