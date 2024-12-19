import { createStore } from 'vuex';
import mission from './modules/mission';
import calendar from './modules/calendar';
import auth from './modules/auth';
import header from './modules/header';
import transaction from './modules/transaction';

export default createStore({
    modules: {
        mission,
        transaction,
        calendar,
        auth,
        header,
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
