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
        missionList: [],
        income: [],
        expense: [],
        childId: sessionStorage.getItem('child_id') ? sessionStorage.getItem('child_id') : null,
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
        setMissionList(state, missionList) {
            state.missionList = missionList;
        },
        setIncome(state, income) {
            console.log("Income 업데이트됨:", income); // 확인용
            state.income = income;
        },
        setExpense(state, expense) {
            console.log("Expense 업데이트됨:", expense); // 확인용
            state.expense = expense;
        },
        setChildId(state, childId) {
            state.childId = childId;
        },
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

        // 자녀 달력 모달
        childCalendarModal(context, strDate) {
            if (!strDate) {
                // year, month가 있는지 확인
                console.error('date 값이 필요합니다.');
                return;
            }
            
            const url = `/api/child/calendar/modal?date=${strDate}`;
            axios.get(url)
                .then(response => {
                    // sessionStorage.setItem('child_id', child_id);
                    // context.commit('setChildId', child_id);
                    context.commit('setIncome', response.data.income);
                    context.commit('setExpense', response.data.expense);
                })
                .catch(error => {
                    console.error('캘린더 데이터를 불러오는 도중 오류 발생', error);
                });
        },

        // 부모 달력 모달
        parentCalendarModal(context, payload) {
            if (!payload.strDate) {
                // year, month가 있는지 확인
                console.error('date 값이 필요합니다.');
                return;
            }

            const url = `/api/parent/calendar/modal/${payload.child_id}?date=${payload.strDate}`;
            axios.get(url)
                .then(response => {
                    context.commit('setIncome', response.data.income);
                    context.commit('setExpense', response.data.expense);
                })
                .catch(error => {
                    console.error('부모 캘린더 모달 데이터 불러오지 못 함', error);
                });
        },
        
    },
    getters: {
        sidebarMission: state => state.sidebarMission
    },
};
