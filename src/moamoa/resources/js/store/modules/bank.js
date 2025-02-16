import axios from '../../axios';
import router from '../../router';

export default {
    namespaced: true,
    state: ()=> ({
        bankInterest : [] // 한국은행 금리
        ,savingList: [] // 적금 상품 리스트
        ,openModal: false // 더보기 버튼 누르긴 모달 닫겨 있게
        ,currentPage: 1 // 현재 페이지(1)
        ,productCount: 0 // 가입한 적금 상품 개수
        ,point: 0 // 보유중인 모아 포인트
        // 세션 관련 -------------------------------------------------------------
        ,childId: sessionStorage.getItem('child_id') ? sessionStorage.getItem('child_id') : null
        ,productInfo: {}
        ,singleList: [] //매일 적금
        ,weekList: [] // 매주 적금
        ,computedInterestRate: 0

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
        setProductCount(state, productCount) {
            state.productCount = productCount;
        },
        setChildId(state, childId) {
            state.childId = childId;
        },
        setPoint(state, point) {
            state.point = point;
        },
        setProductInfo(state, productInfo) {
            state.productInfo = productInfo;
        }
        ,setSingleSaving(state, singleList) { // 매일 적금 상품
            state.singleList = singleList;
        }
        ,setWeekSaving(state, weekList) { // 매주 적금 상품
            state.weekList = weekList;
        }
        ,setComputedInterestRate(state, computedInterestRate) { // 매주 적금 상품
            state.computedInterestRate = computedInterestRate;
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
            // const url = '/api/moabank/product?page=1';
            const url = '/api/moabank/product';
            console.log(url);
            axios.get(url)
            .then(response => {
                console.log('savingList', response.data.savingList.data);
                context.commit('setSavingList', response.data.savingList.data);
                context.commit('setSingleSaving', response.data.singleSavingList);
                context.commit('setWeekSaving', response.data.weekSavingList);
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

        // 가입한 적금 상품 개수와 포인트
        signProductCount(context, child_id) {
            const url = '/api/parent/moabank/'+ child_id;

            axios.get(url)
                .then(response => {
                    context.commit('setProductCount', response.data.productCount);
                    context.commit('setPoint', response.data.point);
                    console.log('상품 개수와 포인트 확인 : ', response.data);
                    console.log('자녀 : ', child_id);

                })
                .catch(error => {
                    console.log('가입한 적금 상품 개수 불러오기 오류', error);
                });
            
        },

        // id로 받아온 적금 상품
        selectedProduct(context, saving_product_id) {
            const url = '/api/moabank/product/detail/'+ saving_product_id;
            console.log(url);

            axios.get(url)
                .then(response => {
                    context.commit('setProductInfo', response.data.childProductInfo);
                    context.commit('setComputedInterestRate', response.data.computedInterestRate);
                    console.log('setComputedInterestRate 확인', response.data.computedInterestRate);
                    console.log('setProductInfo 확인', response.data.childProductInfo);
                    console.log(response.data);
                }) 
                .catch(error => {
                    console.log('해당 적금 상품을 불러올 수 없습니다', error);
                }) 
        },
        
        // id로 받아온 적금 상품 가입 페이지
        registrationProduct(context, saving_product_id) {
            const url = '/api/moabank/product/regist/'+ saving_product_id;
            console.log(url);

            axios.get(url)
                .then(response => {
                    context.commit('setProductInfo', response.data.childProductInfo);
                    console.log('setProductInfo 확인', response.data.childProductInfo);
                    console.log(response.data);
                }) 
                .catch(error => {
                    console.log('해당 적금 상품을 불러올 수 없습니다', error);
                }) 
        },

    },
    getters: {
        getNextPage(state) {
            return state.currentPage +1;
        },
    },
}