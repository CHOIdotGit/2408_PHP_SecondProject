import { createStore } from 'vuex';
import mission from './modules/mission';

export default createStore({
    modules: {
        mission,
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