
import axios from '../../axios';
import router from '../../router';

export default {
    namespaced:true,
    state: ()=> ({
        transactionList: []
        ,lastPageFlg: false
        ,controlFlg: true
        // 미션 관련 -------------------------------------------------------------
        ,childId: sessionStorage.getItem('child_id') ? sessionStorage.getItem('child_id') : null
        ,transactionId: sessionStorage.getItem('transactionId') ? sessionStorage.getItem('transactionId') :null
        ,transactionDetail: []
        ,transactionId: sessionStorage.getItem('transactionId') ? sessionStorage.getItem('transactionId') :null
    }),
    mutations: {
        setTransactionList(state, transactionList) {
            state.transactionList = state.transactionList.concat(transactionList);
        },
        setControlFlg(state, flg) {
            state.controlFlg = flg;
        },
        resetState(state) {
            state.transactionList = []; // 미션 리스트 초기화
            state.childId = null; // 자녀 ID 초기화
        },
        setChildId(state, childId) {
            state.childId = childId;
        },
        setTransactionId(state, transactionId) {
            state.transactionId = transactionId;
        },
        setTransactionDetail(state, transactionDetail) {
            state.transactionDetail = transactionDetail;
        },
    },
    actions: {
        /**
         * 미션 리스트 획득
         * 
         * @param {*} context commit, state 포함되어있음
         */
        transactionList(context, child_id) {
            context.commit('setControlFlg', false);
            
            const url = '/api/parent/spend/list/' + child_id;
            
            axios.get(url)
            .then(response => {
                context.commit('setTransactionList', response.data.transactionList.data);
                // 세션 스토리지에 자녀ID 세팅
                sessionStorage.setItem('child_id', child_id);
                context.commit('setChildId', child_id);
                router.push('/parent/spend/list/' + child_id);
                // console.log(response.data.transactionList.data);
            })
            .catch(error => {
                console.error('미션 리스트 불러오기 오류', error);
            });    
        },

        // 지출 상세 페이지
        showTransactionDetail(context, transaction_id) {
            const url = '/api/parent/spend/detail/' + transaction_id;
            axios.get(url, transaction_id)
            .then(response => {
                sessionStorage.setItem('transactionId', transaction_id);
                context.commit('setTransactionDetail', response.data.transactionDetail);
                context.commit('setTransactionId', transaction_id);
                router.push('/parent/spend/detail/' + transaction_id);
            })
            .catch(error => {
                console.log('지출 상세 페이지 불러오기 오류', error);
            }) 
        }
       
    },

    getters: {

    },
}
