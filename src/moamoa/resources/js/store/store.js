import { createStore } from 'vuex';
import mission from './modules/mission';
import calendar from './modules/calendar';
import auth from './modules/auth';
import header from './modules/header';
import transaction from './modules/transaction';
import childMission from './modules/childMission';
import childTransaction from './modules/childTransaction';
import mobile from './modules/mobile';
import bank from './modules/bank';
import point from './modules/point';
import saving from './modules/saving';

export default createStore({
    modules: {
        mission,
        transaction,
        calendar,
        auth,
        header,
        childMission,
        childTransaction,
        mobile,
        point,
        bank,
        saving,
    },
});

// const store = createStore({
//     state: {
//         currentPath:'/'
//     },
//     mutations: {
//         setCurrentPath(state, path) {
//             state.currentPath = path;
//         }
//     },
//     actions: {
//         updateCurrentPath({ commit }, path) {
//             commit('setCurrentPath', path);
//         }
//     }
// });
// export default store;
