import axios from '../../axios';
import router from '../../router';

export default {
    namespaced: true,
    state: ()=> ({
        bankInterest : [], // 한국은행 금리
        savingList: [], // 적금 상품 리스트
        openModal: false, // 더보기 버튼 누르긴 모달 닫겨 있게
        currentPage: 1, // 현재 페이지(1)

    }),
    mutations: {
        setKoreaBank(state, bank) {
            state.bankInterest = bank;
        },
        setSavingList(state, savingList) {
            state.savingList = savingList;
        },
        setCurrentPage(state, currentPage) {
            state.currentPage = currentPage;
        },
        addSavingPage(state, savingList) {
            state.savingList = state.savingList.concat(savingList);
        },
        setModalFlg(state, openModal) {
            state.openModal = openModal
        },
    },
    actions: {
        // 한국은행 기준금리 api
        koreaBank(context) {
            const url = '/api/koreabank';
            console.log(url);

            axios.get(url)
            .then(responseKoreaBank => {
                context.commit('setKoreaBank', responseKoreaBank.data);

            })
            .catch(error => {
                console.log('한국은행 이자 불러오기 실패', error);
            })
        },

        // 적금상품
        savingProductList(context) {
            const url = '/api/moabank/product?page=1';
            console.log(url);
            axios.get(url)
            .then(response => {
                console.log(response.data.savingList.data);
                context.commit('setSavingList', response.data.savingList.data);
            })
            .catch(error => {
                console.log('적금상품 불러오기 실패', error);
            })
        },
        // 적금상품 더보기
        addsavingList(context) {
            const url = '/api/moabank/product?page='+context.getters['getNextPage']
            console.log(url);
            axios.get(url)
            .then(response => {
                context.commit('addSavingPage', response.data.savingList.data);
                context.commit('setCurrentPage', getters.getNextPage);
            })
        },

        getSavingPage(context) {
            context.commit('setModalFlg', true);
            context.dispatch('savingProductList');
        },


    },
    getters: {
        getNextPage(state) {
            return state.currentPage +1;
        },
    },
}