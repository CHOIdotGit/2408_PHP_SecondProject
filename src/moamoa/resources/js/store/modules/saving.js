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
        ,savingInfo: [] // 자녀 통장 정보
        ,expiredSavings: [] // 자녀 만기 적금 리스트
        // 페이지네이션
        ,currentPage: 1
        ,lastPage: 1
        ,perPage: 10
        ,total: 0
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
        },
        setExpiredSavings(state, expiredSavings) {
            state.expiredSavings = expiredSavings
        },
        setPagination(state, { current_page, last_page, per_page, total }) {
            state.currentPage = current_page;
            state.lastPage = last_page;
            state.perPage = per_page;
            state.total = total;
        },
        setChildId(state, childId) {
            state.childId = childId;
        },

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
        //부모가 확인
        parentChildSaving(context, child_id) {
            const url = '/api/parent/saving/list/' + child_id;
            console.log(url);
            axios.get(url)
                .then(response => {
                    context.commit('setChildSavingList', response.data.savingList);
                    context.commit('setChildId', child_id);
                    console.log('자녀 적금 리스트 불러오기:', response.data.savingList);
                })
                .catch(error => {
                    console.error('자녀 적금 리스트 받아오기 실패', error);
                })
        },


        // 자녀 통장 내역
        childSavingDetail(context, bankbook_id) {
            const url = '/api/child/moabank/bankbook/'+ bankbook_id;

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
        },

        // 부모가 자녀 통장 확인
        parnetChildSavingDetail(context, bankbook_id) {
            const url = '/api/parent/bankbook/' + bankbook_id;
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
        },

        // 자녀 만기 적금 리스트
        // },
        async childExpiredSavings(context, searchData) {
            try {
                const response = await axios.get(`/api/child/expired/saving?page=${searchData.page}`);
                
                context.commit('setExpiredSavings', response.data.expiredSavings.data);
                context.commit('setPagination', {
                    current_page: response.data.expiredSavings.current_page,
                    last_page: response.data.expiredSavings.last_page,
                    per_page: response.data.expiredSavings.per_page,
                    total: response.data.expiredSavings.total,
                });
            } catch (error) {
                console.error('자녀 만기 적금 리스트 불러오기 실패', error);
            }
        },

        // 적금 가입하기
        createSaving(context, data) {
            const url = '/api/moabank/product/regist/' + data.product_id;
            console.log(url);

            const formData = new FormData();
            formData.append('child_id', data.child_id);
            formData.append('saving_product_id', data.saving_product_id);
            formData.append('saving_sign_up_deposit_at', data.saving_sign_up_deposit_at);
            formData.append('saving_sign_up_amount', data.saving_sign_up_amount);
            formData.append('saving_sign_up_start_at', data.saving_sign_up_end_at);
            formData.append('saving_sign_up_status', data.saving_sign_up_status);

            axios.post(url, formData)
                .then(response => {
                    const newSaving = response.data.savingDetail;
                    console.log('적금 가입', newSaving);

                    context.commit('setBankBookId', newSaving.bankbook_id);
                    console.log('새로운 통장 아이디 생성 : ', setBankBookId);

                    context.commit('setSavingDetail', newSaving);
                    alert('적금 가입이 완료 되었습니다.');
                    router.replace('/child/bankbook/' + newSaving.bankbook_id);
                })
                .catch(error => {
                    console.error('적금 가입 실패', error);
                })

        }

    },

    getters: {

    }
}