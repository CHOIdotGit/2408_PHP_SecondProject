
import axios from '../../axios';

export default {
    namespaced:true,
    state: ()=> ({
        pointList: []
        ,childPointList: []
        ,totalPoint: 0
        ,currentPage: 1
        ,lastPage: 1
        ,lastPageFlg: false
        ,controlFlg: true
        ,savingList: []
        // 세션 관련 -------------------------------------------------------------
        ,childId: sessionStorage.getItem('child_id') ? sessionStorage.getItem('child_id') : null
        // ,pointId: sessionStorage.getItem('pointId') ? sessionStorage.getItem('pointId') :null
        // ,perPage: 10
    }),
    mutations: {
        setPointList(state, pointList) {
            state.pointList = pointList;
        },
        setTotalPoint(state, totalPoint) {
            state.totalPoint = totalPoint;
        },
        setControlFlg(state, flg) {
            state.controlFlg = flg;
        },
        setChildId(state, childId) {
            state.childId = childId;
        },
        // setPointId(state, pointId) {
        //     state.pointId = pointId;
        // },
        setPagination(state, { current_page, last_page }) {
            state.currentPage = current_page;
            state.lastPage = last_page;
        },
        setChildPointList(state, childPointList) {
            state.childPointList = childPointList;
        },
        setSavingList(state, savingList) {
            state.savingList = savingList;
        },
    },
    actions: {
        // 부모의 자녀 포인트 페이지
        async printPointList(context, searchData) {
            try {
                const response = await axios.get(`/api/parent/point/${searchData.child_id}?page=${searchData.page}`);
                
                // childPointList 데이터를 commit
                context.commit('setChildPointList', response.data.childPointList);
                // 세션 스토리지에 자녀ID 세팅
                sessionStorage.setItem('child_id', searchData.child_id);
                context.commit('setChildId', searchData.child_id);
                context.commit('setTotalPoint', response.data.totalPoint);
                // pagination 정보를 개별적으로 commit
                context.commit('setPagination', {
                    current_page: response.data.currentPage,
                    last_page: response.data.lastPage,
                });
            } catch (error) {
              console.error('포인트 정보 불러오기 오류', error);
            }
        },

        // 자녀의 포인트 페이지
        async childPrintPointList(context, searchData) {
            try {
                const response = await axios.get(`/api/child/point/?page=${searchData.page}`);
                
                // pointList 데이터를 commit
                context.commit('setPointList', response.data.pointList);
                context.commit('setTotalPoint', response.data.totalPoint);
                // pagination 정보를 개별적으로 commit
                context.commit('setPagination', {
                    current_page: response.data.currentPage,
                    last_page: response.data.lastPage,
                });
            } catch (error) {
              console.error('포인트 정보 불러오기 오류', error);
            }
        },

        // 자녀 모아 은행 페이지
        childPoint(context) {
            const url = '/api/child/moabank';
            axios.get(url)
            .then(response => {
                context.commit('setTotalPoint', response.data.totalPoint);
                context.commit('setSavingList', response.data.savingList);
            })
            .catch(error => {
                console.error('자녀 포인트 합계 불러오기 실패', error);
            })
        },
    },
    getters: {
        totalPoints: state => state.totalPoints,
    },
}
