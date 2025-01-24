
import { resolve } from 'chart.js/helpers';
import axios from '../../axios';
import router from '../../router';
import { reject } from 'lodash';

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
        ,mostSpendAmount: 0
        ,transactions_max_category: ''
        ,totalAmount: 0
        ,totalExpenses: 0
        ,categoryData: []
        ,parentStats:[]
        ,doughnutData: [0]
        ,weeklyOutgoData: [0]

    }),
    mutations: {
        setTransactionList(state, transactionList) {
            state.transactionList = transactionList;
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
        setMostSpendAmount(state, mostSpendAmount) {
            state.mostSpendAmount = mostSpendAmount;
        },
        setMostUsedCategory(state, transactions_max_category) {
            state.transactions_max_category = transactions_max_category;
        },
        setTotalAmount(state, totalAmount) {
            state.totalAmount = totalAmount;
        },
        setTotalExpenses(state, totalExpenses) {
            state.totalExpenses = totalExpenses;
        },
        setCategoryData(state, data) {
            state.categoryData = data;
        },
        setParentStats(state, data) {
            state.parentStats = data;
        },
        setDoughnutData(state, data) {
            state.doughnutData = data;
        },
        setWeeklyOutgoData(state, data) {
            state.weeklyOutgoData = data;
        },
    },
    actions: {
        /**
         * 미션 리스트 획득
         * 
         * @param {*} context commit, state 포함되어있음
         */
        transactionList(context, child_id) {
            // context.commit('setControlFlg', false);
            
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
        },

        // 부모 통계 불러오기
        parentStats(context, child_id) {
            return new Promise((resolve, reject) => {
                const url = '/api/parent/stats/' + child_id ;
                axios
                    .get(url)
                    .then((response) => {
                        context.commit('setChildId', child_id);
                        context.commit('setParentStats', response.data.data);
                        const eachCategoryTransaction = response.data.eachCategoryTransaction.map(item => item.total_amount);
                        const weeklyExpenseAmount = response.data.weeklyOutgoData.map(item => item.total);
                        context.commit('setDoughnutData', eachCategoryTransaction);
                        context.commit('setWeeklyOutgoData', weeklyExpenseAmount);
                        return resolve();
                    })
                    .catch((error) => {
                        console.error('부모 통계 데이터 불러오기 실패:', error.response?.data?.message || error.message);
                        return reject();
                    });

            });
        },
    },

    getters: {
        
    },
}
