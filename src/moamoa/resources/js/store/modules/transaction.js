
import axios from '../../axios';
import router from '../../router';

export default {
    namespaced:true,
    state: ()=> ({
        transactionList: []
        ,lastPageFlg: false
        ,controlFlg: true

    }),
    mutations: {
        setTransactionList(state, transactionList) {
            state.transactionList = state.transactionList.concat(transactionList);
        },
        setControlFlg(state, flg) {
            state.controlFlg = flg;
        },
    },
    actions: {
        /**
         * 미션 리스트 획득
         * 
         * @param {*} context commit, state 포함되어있음
         */
        transactionListPagination(context) {
            context.commit('setControlFlg', false);
            
            const url = '/api/parent/spend/list';
            
        
            axios.get(url)
            .then(response => {
                context.commit('setTransactionList', response.data.transactionList.data);
                // console.log(response.data.transactionList.data);
            })
            .catch(error => {
                console.error('미션 리스트 불러오기 오류', error);
            });    
        },

       
    },

    getters: {

    },
}
