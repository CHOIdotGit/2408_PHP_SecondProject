import axios from '../../axios';
import router from '../../router';

export default {
    namespaced:true,

    state: ()=> ({
        childSavingList: [] // 가입 통장 리스트
        ,savingDetail: [] // 자녀 통장 내역
        // 세션 관련 -------------------------------------------------------------
        ,childId: sessionStorage.getItem('child_id') ? sessionStorage.getItem('child_id') : null
        ,bankbookId: sessionStorage.getItem('bankbook_id') ? sessionStorage.getItem('bankbook_id') : null
        ,savingInfo:[] // 자녀 통장 정보
    }),

    mutations: {
        setChildSavingList(state, childSavingList) {
            state.childSavingList = childSavingList
        },
        setSavingDetail(state, savingDetail) {
            state.savingDetail = savingDetail
        },
        setBankBookId(state, bankbookId) {
            state.bankbookId = bankbookId
        },
        setSavingInfo(state, savingInfo) {
            state.savingInfo = savingInfo
        }
    },

    actions: {
        // 자녀가 가입한 적금 상품 리스트 받아오기
        childSaving(context) {
            const url = '/api/child/saving/list';
            console.log(url);
            axios.get(url)
                .then(response => {
                    context.commit('setChildSavingList', response.data.childSavingList);
                    console.log('자녀 적금 리스트 불러오기 :', response.data.childSavingList);
                })
                .catch(error => {
                    console.error('자녀 적금 리스트 불러오기 실패', error);
                })
        },

        // 자녀 통장 내역
        childSavingDetail(context, bankbook_id) {
            const url = '/api/child/moabank/bankbook/'+ bankbook_id;
            console.log(url);

            axios.get(url)
                .then(response => {
                    sessionStorage.setItem('bankbook_id', bankbook_id);
                    context.commit('setBankBookId', bankbook_id);
                    context.commit('setSavingDetail', response.data.bankBook);
                    context.commit('setSavingInfo', response.data.bankBookInfo);
                    console.log('자녀 통장 내역 불러오기 :', response.data.bankBook);
                })
                .catch(error => {
                    console.error('자녀 통장 내역 불러오기 실패', error);
                });
        }

    },

    getters: {

    }
}