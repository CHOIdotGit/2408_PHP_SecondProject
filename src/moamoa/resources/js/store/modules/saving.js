import axios from '../../axios';
import router from '../../router';

export default {
    namespaced:true,

    state: ()=> ({
        childSavingList: [] // 가입 통장 리스트
        ,savingDetail: [] // 자녀 통장 내역
        // 세션 관련 -------------------------------------------------------------
        ,childId: sessionStorage.getItem('child_id') ? sessionStorage.getItem('child_id') : null
        ,savingSignUpId: sessionStorage.getItem('saving_sign_up_id') ? sessionStorage.getItem('saving_sign_up_id') : null
        // -----------------------------------------------------------------
        ,savingInfo: [] // 자녀 통장 정보
        ,expiredSavings: [] // 자녀 만기 적금 리스트
        // 페이지네이션
        ,currentPage: 1
        ,lastPage: 1
        // 에러 메세지 관련 ----------------------------------------------
        ,errorMsg: ''
    }),

    mutations: {
        setChildSavingList(state, childSavingList) {
            state.childSavingList = childSavingList
        },
        setSavingDetail(state, savingDetail) {
            state.savingDetail = savingDetail
        },
        setSavingSignUpId(state, savingSignUpId) {
            state.savingSignUpId = savingSignUpId
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
        // 에러 메세지 관련 -----------------------------
        setErrorMsg(state, errorMsg) {
            state.errorMsg = errorMsg;
        },

        // 에러 메세지 초기화 -----------------------------
        resetError(state) {
            state.errorMsg = '';
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
        //부모가 확인
        parentChildSaving(context, child_id) {
            const url = '/api/parent/saving/list/' + child_id;
            console.log(url);
            axios.get(url)
                .then(response => {
                    sessionStorage.setItem('child_id', child_id);
                    context.commit('setChildId', child_id);
                    
                    context.commit('setChildSavingList', response.data.savingList);
                    console.log('자녀 적금 리스트 id:', child_id);

                })
                .catch(error => {
                    console.error('자녀 적금 리스트 받아오기 실패', error);
                })
        },


        // 자녀 통장 내역
        childSavingDetail(context, saving_sign_up_id) {
            const url = '/api/child/moabank/bankbook/'+ saving_sign_up_id;

            axios.get(url)
                .then(response => {
                    sessionStorage.setItem('saving_sign_up_id', saving_sign_up_id);
                    context.commit('setSavingSignUpId', saving_sign_up_id);
                    context.commit('setSavingDetail', response.data.bankBook);
                    context.commit('setSavingInfo', response.data.bankBookInfo);
                    
                })
                .catch(error => {
                    console.error('자녀 통장 내역 불러오기 실패', error);
                });
        },

        // 부모가 자녀 통장 확인
        parnetChildSavingDetail(context, saving_sign_up_id) {
            const url = '/api/parent/bankbook/' + saving_sign_up_id;
            console.log(url);
            axios.get(url) 
                .then(response => {
                    sessionStorage.setItem('saving_sign_up_id', saving_sign_up_id);
                    context.commit('setSavingSignUpId', saving_sign_up_id);
                    context.commit('setSavingDetail', response.data.bankBook);
                    context.commit('setSavingInfo', response.data.bankBookInfo);
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
                
                context.commit('setExpiredSavings', response.data.expiredSavings);
                context.commit('setPagination', {
                    current_page: response.data.expiredSavings.currentPage,
                    last_page: response.data.expiredSavings.lastPage,
                });
            } catch (error) {
                console.error('자녀 만기 적금 리스트 불러오기 실패', error);
            }
        },

        // 적금 가입하기
        createSaving(context, data) {
            const url = '/api/child/moabank/saving/create/' + data.saving_product_id;
            console.log(data);

            // const formData = new FormData();
            // formData.append('saving_product_id', data.saving_product_id);
            // formData.append('saving_sign_up_deposit_at', data.saving_sign_up_deposit_at);
            // formData.append('saving_sign_up_amount', data.saving_sign_up_amount);
            // // formData.append('saving_sign_up_start_at', data.saving_sign_up_start_at);
            // // formData.append('saving_sign_up_end_at', data.saving_sign_up_end_at);
            // formData.append('saving_product_period', data.saving_product_period);

            axios.post(url, data)
            .then(response => {
                console.log(response.data);
                const newSaving = response.data.regist;
                console.log('적금 가입', newSaving);

                context.commit('setSavingSignUpId', newSaving.saving_sign_up_id);
                sessionStorage.setItem('savingSignUpId', newSaving.saving_sign_up_id);
                console.log('새로운 통장 아이디 생성 : ', newSaving.saving_sign_up_id);

                context.commit('setSavingDetail', newSaving);
                alert('적금 가입이 완료 되었습니다.');
                router.replace('/child/bankbook/' + newSaving.saving_sign_up_id);
            })
            .catch(error => {
                context.commit('resetError');

                const errorMsg = error.response.data.errormsg;
                // console.log(error.response.data.errormsg.point);

                if(error.response.status === 400) {
                    context.commit('setErrorMsg', errorMsg);
                }
            });

        }

    },

    getters: {

    }
}