import axios from 'axios';

export default {
    namespaced: true,
    state: () => ({
        page: 0,
        controlFlg: true,
        calendarInfo: {},
        calendarList: [],
        calendarDetail: [],
        calendarMeal : 0,
        calendarTraffic: 0,
        calendarShopping: 0,
        calendarEtc:0,
        calendarMission:0,
    }),
    mutations: {
        setCalendarInfo(state, data) {
          state.calendarInfo = data;
        },
        setCalendarList(state, calendarList) {
            state.calendarList = state.calendarList.concat(calendarList);
        },
        setControlFlg(state, flg) {
            state.controlFlg = flg;
        },
        setCalendarDetail(state, board) {
            state.calendarDetail = state.calendar
        },
        setCalendarMeal(state, data) {
            state.calendarMeal = data;
        },
        setCalendarTraffic(state, data) {
            state.calendarTraffic = data;
        },
        setCalendarShopping(state, data) {
            state.calendarShopping = data;
        },
        setCalendarEtc(state, data) {
            state.calendarEtc = data;
        },
        setCalendarMission(state, data) {
            state.calendarMission = data;
        },
        
    },
    actions: {
        // 캘린더에서 이름불러오기
        calendarInfo(context) {
            const url = '/api/child/calendar';
            // console.log(url)
            axios.get(url)
            .then(response => {
                // console.log(response.data.calendarData);
                context.commit('setCalendarInfo', response.data.calendarData);
            })
            .catch(error => {
                console.log(' 달력 이름X', error);
            })
        },


        // ----내역에서 비용-----
        // 식비
        calendarMeal(context) {
            const url = '/api/child/calendar';
            axios.get(url)
            .then(response => {
                const sidebarMeal = Number(response.data.sidebarMeal); // 숫자로 변환
                console.log(sidebarMeal);
                context.commit('setCalendarMeal', sidebarMeal);
            })
            .catch(error => {
                console.log('식비 합계X', error);
            });
        },

        // 교통비
        calendarTraffic(context) {
            const url = '/api/child/calendar';
            axios.get(url)
            .then(response => {
                const sidebarTraffic = Number(response.data.sidebarTraffic); // 숫자로 변환
                console.log(sidebarTraffic);
                context.commit('setCalendarTraffic', sidebarTraffic);
            })
            .catch(error => {
                console.log('교통비 합계X', error);
            });
        },

        // 쇼핑
        calendarShopping(context) {
            const url = '/api/child/calendar';
            axios.get(url)
            .then(response => {
                const sidebarShopping = Number(response.data.sidebarShopping); // 숫자로 변환
                console.log(sidebarShopping);
                context.commit('setCalendarShopping', sidebarShopping);
            })
            .catch(error => {
                console.log('쇼핑 합계X', error);
            });
        },

        // 기타
        calendarEtc(context) {
            const url = '/api/child/calendar';
            axios.get(url)
            .then(response => {
                const sidebarEtc = Number(response.data.sidebarEtc); // 숫자로 변환
                console.log(sidebarEtc);
                context.commit('setCalendarEtc', sidebarEtc);
            })
            .catch(error => {
                console.log('쇼핑 합계X', error);
            });
        },
        
        // 미션총합
        calendarMission(context) {
            const url = '/api/child/calendar';
            axios.get(url)
            .then(response => {
                const sidebarMission = Number(response.data.sidebarMission); // 숫자로 변환
                console.log(sidebarMission);
                context.commit('setCalendarMission', sidebarMission);
            })
            .catch(error => {
                console.log('미션 합계X', error);
            });
        }  

        
    },
    getters: {
       
    },
};
