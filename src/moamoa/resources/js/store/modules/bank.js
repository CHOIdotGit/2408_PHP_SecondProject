import axios from '../../axios';
import router from '../../router';

export default {
    namespaced: true,
    state: ()=> ({
        bankInterest : null
    }),
    mutations: {
        setKoreaBank(state, bank) {
            state.bankInterest = bank;
        }
    },
    actions: {
        koreaBank(context) {
            const url = '/api/bank/koreaBank';

            axios.get(url)
            .then(responseKoreaBank => {
                context.commit('setKoreaBank', responseKoreaBank.data);

            })
            .catch(error => {
                console.log('한국은행 이자 불러오기 실패', error);
            })
        }
    }
}