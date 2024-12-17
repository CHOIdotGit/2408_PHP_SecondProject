import { createStore } from 'vuex';
import mission from './modules/mission';
import child from './modules/child';
import auth from './modules/auth';

export default createStore({
    modules: {
        mission, 
        child,
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