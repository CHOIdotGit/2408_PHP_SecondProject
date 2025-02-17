
import axios from '../../axios';

export default {
    namespaced:true,
    state: ()=> ({
        pointList: []
        ,totalPoint: 0
        ,lastPageFlg: false
        ,controlFlg: true
        // 세션 관련 -------------------------------------------------------------
        ,childId: sessionStorage.getItem('child_id') ? sessionStorage.getItem('child_id') : null
        ,pointId: sessionStorage.getItem('pointId') ? sessionStorage.getItem('pointId') :null
        ,currentPage: 1
        ,lastPage: 1
        ,perPage: 10
        ,total: 0
        ,childPoint: 0
        
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
        setPointId(state, pointId) {
            state.pointId = pointId;
        },
        setPagination(state, { current_page, last_page, per_page, total }) {
            state.currentPage = current_page;
            state.lastPage = last_page;
            state.perPage = per_page;
            state.total = total;
        },
        setChildPoint(state, childPoint) {
            state.childPoint = childPoint;
        },
        
    },
    actions: {
        // 부모의 자녀 포인트 페이지
        async printPointList(context, searchData) {
            try {
                const response = await axios.get(`/api/parent/point/${searchData.child_id}?page=${searchData.page}`);
                
                // childPointList 데이터를 commit
                context.commit('setPointList', response.data.childPointList.data);

                // 세션 스토리지에 자녀ID 세팅
                sessionStorage.setItem('child_id', searchData.child_id);
                context.commit('setChildId', searchData.child_id);
        
                context.commit('setTotalPoint', response.data.totalPoint);
                // pagination 정보를 개별적으로 commit
                context.commit('setPagination', {
                    current_page: response.data.childPointList.current_page,
                    last_page: response.data.childPointList.last_page,
                    per_page: response.data.childPointList.per_page,
                    total: response.data.childPointList.total,
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
                context.commit('setPointList', response.data.pointList.data);
        
                context.commit('setTotalPoint', response.data.childTotalPoint);
                // pagination 정보를 개별적으로 commit
                context.commit('setPagination', {
                    current_page: response.data.pointList.current_page,
                    last_page: response.data.pointList.last_page,
                    per_page: response.data.pointList.per_page,
                    total: response.data.pointList.total,
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
