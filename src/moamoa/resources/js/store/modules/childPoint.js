
import { set } from 'lodash';
import axios from '../../axios';
import router from '../../router';

export default {
    namespaced:true,
    state: ()=> ({
        childPoint: 0
        ,totalPoints: 0
        ,lastPageFlg: false
        ,controlFlg: true
        // 세션 관련 -------------------------------------------------------------
        ,childId: sessionStorage.getItem('child_id') ? sessionStorage.getItem('child_id') : null
        ,pointId: sessionStorage.getItem('pointId') ? sessionStorage.getItem('pointId') :null
        
    }),
    mutations: {
        setChildPoint(state, childPoint) {
            state.childPoint = childPoint;
        },
        setTotalPoints(state, totalPoints) {
            state.totalPoints = totalPoints;
        },
        setControlFlg(state, flg) {
            state.controlFlg = flg;
        },
        setChildId(state, childId) {
            state.childId = childId;
        },
        setPointId(state, pointId) {
            state.pointId = pointId;
        },
        
    },
    actions: {

        // 자녀 모아 은행 페이지
        childPoint(context) {
            const url = '/api/child/moabank';
            axios.get(url)
            .then(response => {
                context.commit('setChildPoint', response.data.childPoint.data);
                context.commit('setTotalPoints', response.data.totalPoints);
            })
            .catch(error => {
                console.error('지출 금액 불러오기 실패', error);
            })
        },

        /**
         * 자녀 지출 리스트 페이지
         * 
         * @param {*} context commit, state 포함되어있음
         */
        transactionList(context, child_id) {            
            const url = '/api/child/spend/list';
            
            axios.get(url)
            .then(response => {
                context.commit('setChildTransactionList', response.data.childTransactionList.data);
                // 세션 스토리지에 자녀ID 세팅
                sessionStorage.setItem('child_id', child_id);
                context.commit('setChildId', child_id);
                router.push('/child/spend/list');
            })
            .catch(error => {
                console.error('지출 리스트 불러오기 오류', error);
            });    
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
    },
    getters: {
        totalPoints: state => state.totalPoints,
    },
}
