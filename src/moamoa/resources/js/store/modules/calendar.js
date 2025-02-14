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
        dailyOutgoData: [],
        transactionsOnDay: [],
        missionList: [],
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
        setTransactionsOnDay(state, transactions) {
            state.transactionsOnDay = transactions;
        },
        setMissionList(state, missionList) {
            state.missionList = missionList;
        }
    },
    actions: {
        // 자녀 캘린더 불러오기
        childCalendarInfo(context, objDate) {
            return new Promise((resolve, reject) => {
                const url = '/api/child/calendar?year=' + objDate.getFullYear() + '&month=' + (objDate.getMonth() + 1);
                console.log('childCalendarInfo',url)
                axios.get(url)
                .then(response => {
                    // console.log(response.data);
                    context.commit('setCalendarInfo', response.data);
                    resolve();
                })
                .catch(error => {
                    console.log(' 달력 이름X', error);
                    reject();
                });
            });
        },
        // 부모 캘린더 불러오기
        parentCalendarInfo(context, objDate) {
            return new Promise((resolve, reject) => {
                const url = '/api/parent/calendar/' + objDate.child_id + '?year=' + objDate.date.getFullYear() + '&month=' + (objDate.date.getMonth() + 1);
                console.log('parentCalendarInfo', url)
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

                // commit("setSidebarData",  JSON.stringify(response.data.sidebarData));
                commit("setSidebarData",  response.data.sidebarData);
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
        fetchDailyOutgo({ commit }, { year, month }) {
            axios
                .get("/api/child/calendar", {
                    params: { year, month },
                })
                .then((response) => {
                    // 서버에서 받은 dailyIncomeData를 Mutation에 전달
                    commit("SET_DAILY_OUTGO", response.data.dailyOutgoData);
                })
                .catch((error) => {
                    console.error("일별 지출 데이터를 불러오지 못했습니다:", error);
                });
        },

        // 일별 지출 합계 - 모달
        transactionsOnDay(context, strDate) {
            if (!strDate) {
                // year, month가 있는지 확인
                console.log('날짜 확인', strDate);
                console.error('date 값이 필요합니다.');
                return;
            }
            
            const url = `/api/child/calendar/modal?date=${strDate}`;
            axios.get(url)
                .then(response => {
                    // 서버 응답에서 미션 데이터를 Vuex 상태에 저장
                    context.commit('setTransactionsOnDay', response.data.transactions);
                    // context.commit('setMissionList', response.data.missionList);
                    console.log('transactions 확인', response.data.transactions);
                    // console.log('missionList 확인', response.data.missions);
                    // commit('setSelectedDate', response.data.date); 
                })
                .catch(error => {
                    console.error('캘린더 데이터를 불러오는 도중 오류 발생', error);
                });
        }
        
    },
    getters: {
        sidebarMission: state => state.sidebarMission
    },
};
