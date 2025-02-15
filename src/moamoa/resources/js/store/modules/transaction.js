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
        // 지출 관련 -------------------------------------------------------------
        ,childId: sessionStorage.getItem('child_id') ? sessionStorage.getItem('child_id') : null
        ,transactionId: sessionStorage.getItem('transactionId') ? sessionStorage.getItem('transactionId') :null
        ,transactionDetail: []
        ,transactionId: sessionStorage.getItem('transactionId') ? sessionStorage.getItem('transactionId') :null
        ,mostSpendAmount: 0
        ,transactions_max_category: ''
        ,totalAmount: 0
        ,parentStatsInfo: {}
        ,totalExpenses: 0
        ,categoryData: []
        ,parentStats:[]
        ,doughnutData: [0]
        ,weeklyOutgoData: [0]
        ,filter: [] // 필터검색
        ,currentPage: 1
        ,lastPage: 1
        ,perPage: 10
        ,total: 0

    }),
    mutations: {
        setTransactionList(state, transactionList) {
            state.transactionList = transactionList;
        },
        setControlFlg(state, flg) {
            state.controlFlg = flg;
        },
        resetState(state) {
            state.transactionList = []; // 지출 리스트 초기화
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
        setChildStats(state, data) {
            state.childstats = data;
        },
        setParentStatsInfo(state, objDate) {
            state.parentStatsInfo = data;
        }
        ,setDoughnutData(state, data) {
            state.doughnutData = data;
        },
        setWeeklyOutgoData(state, data) {
            state.weeklyOutgoData = data;
        },
        setFilterTransactionList(state, transantionList) {
            state.filter = transantionList;
        },
        setPagination(state, { current_page, last_page, per_page, total }) {
            state.currentPage = current_page;
            state.lastPage = last_page;
            state.perPage = per_page;
            state.total = total;
        },
    },
    actions: {
        /**
         * 지출 리스트 획득
         * 
         * @param {*} context commit, state 포함되어있음
         */
        // transactionList(context, child_id) {
        //     // context.commit('setControlFlg', false);
            
        //     const url = '/api/parent/spend/list/' + child_id;
            
        //     axios.get(url)
        //     .then(response => {
        //         context.commit('setTransactionList', response.data.transactionList.data);
        //         // 세션 스토리지에 자녀ID 세팅
        //         sessionStorage.setItem('child_id', child_id);
        //         context.commit('setChildId', child_id);
        //         router.push('/parent/spend/list/' + child_id);
        //         // console.log(response.data.transactionList.data);
        //     })
        //     .catch(error => {
        //         console.error('지출 리스트 불러오기 오류', error);
        //     });    
        // },

        // 부모 지출 리스트 페이지(페이지네이션 작업 by 최상민)
        async transactionList(context, searchData) {
            try {
                const response = await axios.get(`/api/parent/spend/list/${searchData.child_id}?page=${searchData.page}`);
                
                // transactionList 데이터를 commit
                context.commit('setTransactionList', response.data.transactionList.data);

                // 세션 스토리지에 자녀ID 세팅
                sessionStorage.setItem('child_id', searchData.child_id);
                context.commit('setChildId', searchData.child_id);
        
                // pagination 정보를 개별적으로 commit
                context.commit('setPagination', {
                    current_page: response.data.transactionList.current_page,
                    last_page: response.data.transactionList.last_page,
                    per_page: response.data.transactionList.per_page,
                    total: response.data.transactionList.total,
                });
            } catch (error) {
              console.error('지출 정보 불러오기 오류', error);
            }
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
        parentStats(context, objDate) {
            return new Promise((resolve, reject) => {
                const url = '/api/parent/stats/' + objDate.child_id + '?year=' + objDate.date.getFullYear() + '&month=' + (objDate.date.getMonth() + 1);
                axios.get(url)
                    .then((response) => {
                        context.commit('setChildId', objDate.child_id);
                        context.commit('setParentStats', response.data.data);
                        // const eachCategoryTransaction = response.data.eachCategoryTransaction.map(item => item.total_amount); // v3 del
                        const weeklyExpenseAmount = response.data.weeklyOutgoData.map(item => item.total);
                        // context.commit('setDoughnutData', eachCategoryTransaction); // v3 del
                        context.commit('setDoughnutData', response.data.eachCategoryTransaction); // v3 add
                        context.commit('setWeeklyOutgoData', weeklyExpenseAmount);
                        return resolve();
                    })
                    .catch((error) => {
                        console.error('부모 통계 데이터 불러오기 실패:', error.response?.data?.message || error.message);
                        return reject();
                    });

            });
        },

        childStats(context){
            return new Promise((resolve, reject) => {
                const url = '/api/child/home';
                axios.get(url)
                    .then((response) => {
                        
                        return resolve();
                    })
                    .catch((error) => {
                        console.error('자녀 통계 데이터 불러오기 실패:', error.response?.data?.message || error.message);
                        return reject();
                    });

            });
        },
        //************************************** */
        // // 필터&검색기능
        // transactionSearch(context, searchData) {
        //     const url = '/api/transaction/search';
        // 필터&검색기능
        // ***************************************
        transactionSearch(context, searchData) {
            const url = '/api/transaction/search';

            axios.get(url, {
                params:  searchData 
            })
            .then(response => {
                context.commit('setTransactionList', response.data.filters.data);
                context.commit('setFilterTransactionList', searchData);
            })
            .catch(error => {
                console.log('검색안됨', error);
            })
        },
            // axios.get(url, {
            //     params:  searchData 
            // })
            // .then(response => {
            //     context.commit('setTransactionList', response.data.filters.data);
            //     context.commit('setFilterTransactionList', searchData);
            // })
            // .catch(error => {
            //     console.log('검색안됨', error);
            // })
        // },
    },

    getters: {
        getDoughnutDataTotalAmount(state) {
            return state.doughnutData.map(item => item.total_amount);
        },
        getDoughnutDataLabels(state) {
            return state.doughnutData.map(item => item.category);
        },
    },
}
