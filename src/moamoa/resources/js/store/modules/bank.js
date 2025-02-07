import axios from '../../axios';
import router from '../../router';

export default {
    namespaced: true,
    state: ()=> ({
        bankInterest : [],
        savingList: [],
    }),
    mutations: {
        setKoreaBank(state, bank) {
            state.bankInterest = bank;
        },
        setSavingList(state, savingList) {
            state.savingList = savingList;
        }
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
            const url = '/api/moabank/product';
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
    },
    getters: {

    },
}