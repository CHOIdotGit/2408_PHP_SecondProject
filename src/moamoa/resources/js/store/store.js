import { createStore } from 'vuex';
import mission from './modules/mission';
import calendar from './modules/calendar';

import auth from './modules/auth';

export default createStore({
    modules: {
        mission,
        calendar,
        auth,
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
