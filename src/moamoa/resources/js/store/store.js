import { createStore } from 'vuex';

const store = createStore({
    state: {
        currentPath:'/'
    },
    mutations: {
        setCurrentPath(state, path) {
            state.currentPath = path;
        }
    },
    actions: {
        updateCurrentPath({ commit }, path) {
            commit('setCurrentPath', path);
        }
    }
});


export default store;