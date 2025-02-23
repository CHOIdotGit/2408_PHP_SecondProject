
import { set } from 'lodash';
import axios from '../../axios';
import router from '../../router';

export default {
    namespaced:true,
    state: ()=> ({
        childTransactionList: []
        ,childHome: null
        ,transactions: []
        ,lastPageFlg: false
        ,controlFlg: true
        // 세션 관련 -------------------------------------------------------------
        ,childId: sessionStorage.getItem('child_id') ? sessionStorage.getItem('child_id') : null
        ,transactionId: sessionStorage.getItem('transactionId') ? sessionStorage.getItem('transactionId') :null
        ,transactionDetail: {}
        ,transactionAmount: 0
        ,mostUsedCategory: ''
        ,totalAmount: 0
        ,totalExpenses: 0
        ,currentPage: 1
        ,lastPage: 1
        ,perPage: 10
        ,total: 0
        ,filter: [] // 필터 검색
        ,savings: []
        ,data: []
    }),
    mutations: {
        setChildTransactionList(state, childTransactionList) {
            state.childTransactionList = childTransactionList;
        },
        setControlFlg(state, flg) {
            state.controlFlg = flg;
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
        deleteTransaction(state, transactionId) {
            state.childTransactionList = state.childTransactionList.filter(transaction => transaction.transaction_id !== transactionId ); 
        },
        setCreateTransaction(state, transactionDetail) {
            state.transactionDetail = transactionDetail;
        },
        setUpdateTransaction(state, transactionDetail) {
            state.transactionDetail = transactionDetail;
        },
        setTransactionAmount(state, transactionAmount) {
            state.transactionAmount = transactionAmount;
        },
        setMostUsedCategory(state, mostUsedCategory) {
            state.mostUsedCategory = mostUsedCategory;
        },
        setTotalAmount(state, totalAmount) {
            state.totalAmount = totalAmount;
        },
        setTotalExpenses(state, totalExpenses) {
            state.totalExpenses = totalExpenses;
        },
        setPagination(state, { current_page, last_page, per_page, total }) {
            state.currentPage = current_page;
            state.lastPage = last_page;
            state.perPage = per_page;
            state.total = total;
        },
        setFilterTransactionList(state, childTransactionList) {
            state.filter = childTransactionList;
        },
        setChildHome(state, childHome) {
            state.childHome = childHome;
        },
        setSavings(state, savings) {
            state.savings = savings;
        },
        setData(state, data) {
            state.data = data;
        },
    },
    actions: {
        // 자녀 홈페이지 지출 관련
        childHomeTransaction(context) {
            const url = '/api/child/home';
            axios.get(url)
            .then(response => {
                context.commit('setChildHome', response.data.childHome);
                context.commit('setSavings', response.data.savings);
                context.commit('setData', response.data.data);
            })
            .catch(error => {
                console.error('지출 금액 불러오기 실패', error);
            })
        },

        async transactionList(context, searchData) {
            try {
                const response = await axios.get(`/api/child/spend/list?page=${searchData.page}`);
                
                // transactionList 데이터를 commit
                context.commit('setChildTransactionList', response.data.childTransactionList.data);

                // 세션 스토리지에 자녀ID 세팅
                // sessionStorage.setItem('child_id', searchData.child_id);
                // context.commit('setChildId', searchData.child_id);
        
                // pagination 정보를 개별적으로 commit
                context.commit('setPagination', {
                    current_page: response.data.childTransactionList.current_page,
                    last_page: response.data.childTransactionList.last_page,
                    per_page: response.data.childTransactionList.per_page,
                    total: response.data.childTransactionList.total,
                });
            } catch (error) {
              console.error('지출 정보 불러오기 오류', error);
            }
        },


        // 자녀 지출 상세 페이지
        showTransactionDetail(context, transaction_id) {
            const url = '/api/child/spend/detail/' + transaction_id;
            axios.get(url, transaction_id)
            .then(response => {
                console.log(response.data.transactionDetail);
                sessionStorage.setItem('transactionId', transaction_id);
                context.commit('setTransactionDetail', response.data.transactionDetail);
                context.commit('setTransactionId', transaction_id);
                router.push('/child/spend/detail/' + transaction_id);
            })
            .catch(error => {
                console.log('지출 상세 페이지 불러오기 오류', error);
            }) 
        },


        // ***************************
        //  자녀 지출 작성 페이지로 이동
        // ***************************
        goSpendCreate(context, child_id) {
            const url = '/api/child/spend/create';
            axios.get(url)
            .then(response => {
                    context.commit('setCreateTransaction', response.data.transactionDetail);
                    sessionStorage.setItem('child_id', child_id);
                    context.commit('setChildId', child_id);
                    router.push('/child/spend/create');
                })
                .catch(error => {
                    console.log('지출 작성 페이지로 이동 못함', error);
                })
        },

        // ***************************
        // 자녀 지출 작성 페이지
        // ***************************
        createTransaction(context, data) {
            console.log(data);
            const url  = '/api/child/spend/create';
            
            const formData = new FormData();
            formData.append('title', data.title);
            formData.append('transaction_date', data.transaction_date);
            formData.append('category', data.category);
            formData.append('memo', data.memo);
            formData.append('amount', data.amount);
            formData.append('transaction_code', data.transaction_code);
            formData.append('parent_id', data.parent_id);
            formData.append('child_id', data.child_id);
            
            axios.post(url, formData)
            .then(response => {

                const newTransaction = response.data.transactionDetail;
                console.log('새로운 지출 생성', newTransaction);

                context.commit('setTransactionId', newTransaction.transaction_id)
                console.log('새로운 지출 아이디:', newTransaction.transaction_id)
                context.commit('setTransactionDetail', newTransaction);
                //지출 작성 알람
                alert('지출 작성되었습니다.');

                router.replace('/child/spend/detail/' + newTransaction.transaction_id);
                })
                .catch(error => {
                    console.log('지출 등록 실패', error);
                })
                .finally(() => {
                    context.commit('setControlFlg', true);
                })
        },

        // ***************************
        // 자녀 지출 삭제 페이지
        // ***************************
        deleteTransaction(context, transaction_id) { //2개만
            const url = '/api/child/spend/delete/' + transaction_id; 

            axios.delete(url)
                .then(response => {
                    context.commit('deleteTransaction', response.data.deleteTransaction);
                    
                    alert('지출 삭제되었습니다.'); //지출 삭제 알람

                    router.replace('/child/spend/list');
                    console.log('자녀아이디 확인', '/child/spend/list' );
                })
                .catch(error => {
                    console.log('지출 삭제 안 됨', error);
                })
                },

        // ***************************
        // 선택된 자녀 미션 삭제
        // ***************************
        deletcheckedTransaction(context, spendIds) {
            const url = '/api/child/spend/list/checked/delete';
            
            axios.delete(url, {
                data: { spendIds } // 선택된 지출 ID 배열 전달
            })
            .then(response => {
                // console.log(response.data.checkedMissionId);
                context.commit('deleteTransaction', response.data.checkedMissionId);
                //**context.commit**은 Mutation을 호출하여 Vuex 상태를 변경할 때 사용됩니다.
                //context.commit('mutationName', 전달할 데이터(payload));
                
                alert('지출이 삭제되었습니다.'); //지출 삭제 알람

            })
            .catch(error => {
                console.log('지출 삭제 안됨', error);
            })
        },

        // ***************************
        // 자녀 지출 수정 페이지로 이동
        // ***************************    
        goUpdateTransaction(context, transaction_id) {
            const url = '/api/child/spend/detail/'+ transaction_id;

            axios.get(url)
                .then(response => {
                    context.commit('setTransactionDetail', response.data.transactionDetail);
                    context.commit('setTransactionId', transaction_id);
                })
                .catch(error => {
                    console.log('지출 수정 페이지로 이동 못함', error);
                })
        },

        // 자녀 지출 수정 페이지
        UpdateTransaction(context, updateInfo) {

            const data = JSON.stringify(updateInfo);
            const url = '/api/child/spend/update/' + updateInfo.transaction_id;

            axios.patch(url, data)
            .then(response => {
                console.log('지출 수정 성공 :', response.data);
                context.commit('setTransactionDetail', response.data.updateTransaction);
                
                router.push('/child/spend/detail/' + data.transaction_id);

            })
            .catch(error => {
                console.log('지출 수정 에러', error);
            })
        },
        // ***********************
        // 필터&검색기능
        // ***********************
        transactionSearch(context, searchData) {
        const url = '/api/child/transaction/search';

        axios.get(url, {
            params:  searchData 
        })
        .then(response => {
            context.commit('setChildTransactionList', response.data.filters.data);
            context.commit('setFilterTransactionList', searchData);
            // console.log('setFilterTransactionList', searchData);
            // console.log('검색된 내용', response.data.filters);
        })
        .catch(error => {
            console.log('검색안됨', error);
        })
    },
    },
    getters: {

    },
}
