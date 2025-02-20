<template>
    <div class="container">
        <div class="list-container">
            <div class="route"> 홈  > 미션 </div>
            <div class="for-buttons">
                <button @click="getChildId" class="btn btn-bottom mission-insert">미션 등록</button>
                <button @click="delOpenModal" class="btn btn-top mission-delete">미션 삭제</button>
            </div>
            <div class="search-menu">
                <div class="search-option">
                    <div class="search-date">
                        <p>⦁ 미션 일자 </p> 
                        <input type="date" min="2000-01-01" v-model="filters.startDate">
                        <p>~</p>
                        <input type="date" min="2000-01-01" v-model="filters.endDate">
                    </div>
                    <div class="search-filter">
                        <p>⦁ 미션 종류 </p> 
                        <select name="mission-type" v-model="filters.category" >
                            <option value="" >전체</option>
                            <option value="0">학습</option>
                            <option value="1">취미</option>
                            <option value="2" >집안일</option>
                            <option value="3">생활습관</option>
                            <option value="4">기타</option>
                        </select>
                        <p>⦁ 진행 상태 </p> 
                        <select name="status" v-model="filters.status">
                            <option value="">전체</option>
                            <option value="0">미션 진행</option>
                            <option value="1">미션 대기</option>
                            <option value="2">미션 완료</option>
                            <option value="3">취소</option>
                        </select>
                    </div>
                    <div class="search">
                        <input type="search" v-model="filters.keyword" placeholder="검색어를 입력해주세요">
                    </div>
                </div>
                <div class="search-btn">
                    <button @click="missionSearch">검색</button>
                </div>
            </div>
            <div class="mission-title-bar">
                <div class="chk-div">
                    <input type="checkbox" id="checkbox" class="checkbox" name="checkAll" @change="checkAll" :checked="isAllChecked">
                </div>
                <span class="mission-name">제목</span>
                <span class="status">상태</span>
                <p class="mission-type">종류</p>
                <p class="charge">금액</p>
                <p class="due-date">기한</p>
            </div>
            <div class="scroll">
                <div v-for="item in missionList" :key="item" class="mission-inserted-list">
                    <div class="mission-content">
                        <div class="chk-div">
                            <input v-model="checkboxItem" type="checkbox" id="checkbox" class="checkbox" :value="item.mission_id" name="checkbox">
                        </div>
                        <span @click="getMissionId(item.mission_id)" class="mission-title">{{ getTruncatedTitle(item.title) }}</span>
                        <p class="state" :class="getStatusClass(item.status)">{{ getStatusText(item.status) }}</p>
                        <p class="mission-type-selected">{{ getCategoryText(item.category) }}</p> 
                        <p class="charge">{{ item.amount.toLocaleString() }}원</p>
                        <p class="due-date">{{ item.start_at }} ~ {{ item.end_at }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 페이지네이션 UI by 최상민 -->
    <div class="pagination">
        <!-- 이전 버튼 -->
        <button v-if="currentPage > 1"
            class="paginate-btn" 
            @click="goToPrevious" 
            :disabled="currentPage === 1">
            < 이전
        </button>
        <!-- 페이지 번호 출력 4가 현재 페이지일때 (예: 1 ... 3 4 5 6) -->
        <span v-for="page in pageNumbers" :key="page" class="paginate-span">
            <!-- 페이지 번호 버튼 -->
            <button 
                v-if="page !== '...'" 
                class="paginate-btn" 
                @click="goToPage(page)" 
                :disabled="page === currentPage"
                :class="{'no-pointer': page === currentPage}"
            >
                {{ page }}
            </button>

            <!-- '...' 버튼 스타일을 위해 별도의 클래스 적용 -->
            <button 
                v-else 
                class="dots" 
                disabled
            >
                {{ page }}
            </button>
        </span>
        <!-- 다음 버튼 -->
        <button v-if="currentPage < lastPage"
            class="paginate-btn" 
            @click="goToNext" 
            :disabled="currentPage === lastPage">
            다음 >
        </button>
    </div>
    <!-- ************************* -->
    <!-- ********삭제 모달********* -->
    <!-- ************************* -->
    <div class="del-modal-black" v-show="delModal">
        <div class="del-modal-white">
            <div class="modal-content">
                <img src="/img/icon-trash.png" class="modal-img" alt=".">
                <div class="del-guide">선택한 {{ (checkboxItem).length }} 개의 미션이 삭제됩니다.</div>
            </div>
            <div class="del-btn">
                <button @click="delCloseModal" class="modal-close">취소</button>
                <button @click="deleteCheckedMission()" class="modal-del">삭제</button>
            </div>
        </div>
    </div>

    <!-- ************************* -->
    <!-- ********안내 모달********* -->
    <!-- ************************* -->
    <div class="base-modal-overlay" v-show="infoModal">
    <div class="base-modal-box">
      <div class="base-modal-content">
        <div>삭제할 미션을 선택하세요</div>
      </div>

      <div class="base-modal-btn">
        <button type="button" class="base-modal-submit" @click="delCloseModal">확인</button>
      </div>
    </div>
  </div>

</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';
// import router from '../../../../js/router';
const route = useRoute();
const store = useStore();

// onMount
// onMounted(() => {
//     store.dispatch('childMission/setChildMissionList', route.params.child_id);
// });

onMounted(() => {
    // store.dispatch('childMission/setChildMissionList');
    store.dispatch('childMission/setChildMissionList', {child_id: route.params.id, page: 1});
});

// 미션 리스트 가져오기
const missionList = computed(() => store.state.childMission.childMissionList);

// 12글자 이후 '...'으로 표기
const maxLength = 12;

const getStatusText = (status) => {
    const statusMapping = {
        0: '미션 진행',
        1: '미션 대기',
        2: '미션 완료',
        3: '미션 취소',
    };
    return statusMapping[status]; // 기본값 없이 반환
};

// 카테고리를 문자열로 변환하는 함수
const getCategoryText = (category) => {
    const categoryMapping = {
        0: '학습',
        1: '취미',
        2: '집안일',
        3: '생활습관',
        4: '기타',
    };
    return categoryMapping[category]; // 기본값 없이 반환
};

// status 값에 따라 class 변화
const getStatusClass = (status) => {
    switch (status) {
        case '0': // 진행중
            return 'state-in-progress';
        case '1': // 대기중
            return 'state-waiting';
        case '2': // 완료
            return 'state-complete';
        case '3': // 취소
            return 'state-cancel';
    }
};

const getTruncatedTitle =(title) => {
  return title.length > maxLength 
    ? title.substring(0, maxLength) + '...' 
    : title;
};

// ********** 페이지네이션 **********
const currentPage = computed(() => store.state.childMission.currentPage);
const lastPage = computed(() => store.state.childMission.lastPage);

// 페이지네이션을 위한 페이지 번호 배열 생성
const pageNumbers = computed(() => {
    const numbers = [];
    const range = 1; // 현재 페이지 앞뒤로 표시할 페이지 수

    // 첫 번째 페이지 추가
    if (currentPage.value > range + 1) {
        numbers.push(1);
        numbers.push('...');  // 첫 번째 페이지 앞에 '...'
    }

    // 페이지 번호를 배열에 추가 (예: 1 ... 3 4 5 6)
    for (let i = Math.max(currentPage.value - range, 1); i <= Math.min(currentPage.value + range, lastPage.value); i++) {
        numbers.push(i);
    }

    // 마지막 페이지가 포함되지 않으면 추가
    if (numbers[numbers.length - 1] !== lastPage.value) {
        if (currentPage.value < lastPage.value - range - 1) {
            numbers.push('...');  // 마지막 페이지 뒤에 '...'
        }
        numbers.push(lastPage.value);
    }

    // 마지막 페이지가 1이면 추가하지 않도록 설정
    if (lastPage.value === 1) {
        numbers.pop();
    }
    
    return numbers;
});

// 페이지 이동 함수
const goToPage = (page) => {
    if (page >= 1 && page <= lastPage.value) {
        store.dispatch('childMission/childMissionList', { child_id: route.params.id, page });
    }
};

// 이전 페이지로 이동하는 함수
const goToPrevious = () => {
    if (currentPage.value > 1) {
        goToPage(currentPage.value - 1);
    }
};

// 다음 페이지로 이동하는 함수
const goToNext = () => {
    if (currentPage.value < lastPage.value) {
        goToPage(currentPage.value + 1);
    }
};

// ************** 체크 박스 ******************
// 모든 체크박스가 선택되었는지 확인 (computed : 반응형 데이터로 다루기 위해)
const isAllChecked = computed(() => {
    return checkboxItem.value.length > 0 && missionList.value.every((item) => checkboxItem.value.includes(item.mission_id));
});

// 체크박스 전체선택/ 전체해제
const checkAll = (e) => {
    if(e.target.checked) {
        checkboxItem.value = missionList.value.map(item => item.mission_id);
        console.log('체크박스 모두 선택');
        console.log('체크박스 선택된 데이터 : ', missionList.value.length);
    } else {
        checkboxItem.value = []; //전체 해제
        console.log('체크박스 모두 해제');
    }
}

const checkboxItem = ref([]);
console.log('체크박스 선택된 데이터 : ', checkboxItem.value);

// const checkAll = (e) => {
//     if(e.target.checkAll) {
//         checkboxItem 
//     }
// }

// 체크된 미션만 삭제 처리 하기
const deleteCheckedMission = () => {
    if(checkboxItem.value.length === 0) {
        // alert("삭제할 미션을 선택하세요");
        infoModal.value = true;
        return;
    }
    store.dispatch('childMission/deletcheckedMission', checkboxItem.value);
    delModal.value = false;
    
    store.dispatch('childMission/setChildMissionList'); //삭제후 미션 리스트 새로 불러오기
    checkboxItem.value = [];

}

// ****************************
// *******모달창 설정***********
// ****************************
// 안내 모달창
const infoModal = ref(false); 

// *****삭제 모달창********** 
const delModal = ref(false);

const delOpenModal = () => { //모달창 열기
    if(checkboxItem.value.length === 0) {
        infoModal.value = true;
        return;
    }
    else {
        delModal.value = true;
    }
}

const delCloseModal = () => { //모달창 닫기
    delModal.value = false;
    infoModal.value = false;
}


// 미션아이디 확인해서 상세 페이지 이동하기 위해서
const getMissionId = (mission_id) => {
    store.dispatch('childMission/showMissionDetail', mission_id);
}

// 자녀 아이디 확인해서 작성 페이지로 이동하기 위해서
const getChildId = () => {
    store.dispatch('childMission/goCreateMission');
}


// +=================+
// +    검색 필터     +
// +=================+
// 기본값
const today = ref(new Date().toISOString().slice(0, 10));
const nextDay = ref(new Date().toISOString().slice(0, 10));

const filters = ref({
    category: "",
    status: "",
    startDate: today,
    endDate: nextDay,
    keyword: "",
});


const missionSearch = () => {
    store.dispatch('childMission/missionSearch', filters.value);
};


</script>

<style scoped>
@import url('../../../../css/list.css');
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    /* padding-bottom: 40px; */
}

.mission-title-bar {
    width: 1400px;
    display: grid;
    grid-template-columns: 40px 210px 100px 100px 150px 350px;
    height: 60px;
    gap: 75px;
    background-color: #d1d1d1;
    font-size: 2rem;
    align-items: center;
    text-align: center;
}

.checkbox {
    margin: 15px;
    width: 16px;
    height: 16px;
}

.mission-content {
    display: grid;
    grid-template-columns: 40px 210px 100px 100px 150px 340px;
    width: 1400px;
    min-height: 60px;
    gap: 75px;
    font-size: 1.3rem;
    align-items: center;
    text-align: center;
}

/* 금액 : 오른쪽 정렬 */
.mission-content>.charge {
    text-align: end;
    padding-right: 30px;
}

.mission-title {
    cursor: pointer;
}

.mission-title:hover {
    color: #5589e996;
}

.btn {
    width: 120px;
    height: 50px;
    font-size: 1.2rem;
    border: none;
    background-color:#5589e996 ;
    margin-top: 30px;
    cursor: pointer;
    color: #FFFFFF;
}

.btn-top {
    margin-left: 20px;
}

.btn-bottom {
    margin-left: 1140px;
}

#checkbox9 {
    margin: 15px;
}

.state {
    width: 90px;
}

.state-in-progress {
    background-color: rgba(138, 160, 246, 0.5);
    color: #0010BE;
    height: 40px;
    line-height: 40px;
}

.state-waiting {
    background-color: rgba(22, 200, 150, 0.5);
    color: #165442;
    height: 40px;
    line-height: 40px;
}

.state-complete {
    background-color: rgba(255, 222, 92, 0.5);
    color: #2F8A1D;
    height: 40px;
    line-height: 40px;
}

.state-cancel {
    background-color: rgba(254, 135, 105, 0.5);
    color: #FF3300;
    height: 40px;
    line-height: 40px;
}

.mission-inserted-list {
    /* height: 800px; */
    height: 60px;
    display: grid;
    /* width: 1400px; */
}

.scroll {
    /* display: flex;
    flex-direction: column;
    gap: 20px;
    height: 400px; */
}

/* 스크롤바 커스텀 */
/*.scroll::-webkit-scrollbar-thumb {
    background: #1d54bb96;  스크롤바 막대 색상 
    border-radius: 12px 12px 12px 12px;
}

.scroll::-webkit-scrollbar-button {
    display: none;
} */

.c-title:hover {
    color: #5589e996;
}

/* ********************* */
/* *******삭제 모달****** */
/* ********************* */

.del-modal-black {
    background-color: rgba(0, 0, 0, 0.5);
    position: fixed;
    /* top: 182px;
    left: 177px; */
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    /* margin-top: 500px; */
    justify-content: center;
}

.del-modal-white {
    width: 400px;
    height: 500px;
    background-color: #FFFFFF;
    border: 3px solid #5589e996;
    /* margin: 170px; */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

}

.search-btn button{
    width: 120px;
    height: 150px;
    margin-top: 20px;
    font-weight: 800;
    align-content: space-between;
    margin-left: 210px;
    background-color: #5589e996;
    font-size: 1.5rem;
    border: none;
  box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);

}

.search-btn button:hover {
    color: #ffffff;
    background-color: #214c9c96;
}

.modal-content {
    text-align: center;
    margin: 60px;
}

.modal-name {
    font-size: 1.3rem;
    padding: 10px;
}

.modal-ms-title {
    font-size: 1.3rem;
    padding: 10px;
}

.del-guide {
    font-size: 1.4rem;
    padding: 15px;
}


.modal-img{
    width: 100px;
    height: 100px;
    border-radius: 50px;
}

/* 삭제 모달 버튼 */
.modal-close {
    color: #ACACAC;
    background-color: #FFFFFF;
    font-size: 1.2rem;
    border: 1px solid #ACACAC;
    padding: 5px;
    width: 100px;
    cursor: pointer;
    margin: 10px;
}

.modal-del {
    color: #FFFF;
    background-color: #5589e996;
    font-size: 1.2rem;
    border: 1px solid #5589e996;
    padding: 5px;
    width: 100px;
    cursor: pointer;
    margin: 10px;
}

/* 페이지네이션 css */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
}

.paginate-span {
    font-size: 1.3rem;
    font-weight: 600;
}

.paginate-btn {
    all: unset;
    cursor: pointer;
    font-size: 1.3rem;
}

.paginate-btn:hover {
    color: #5589e996;
}

.no-pointer {
    cursor: default;
    background-color: #5589e996;
    border-radius: 50%;
    text-align: center;
    width: 30px;
    height: 30px;
}

.no-pointer:hover {
    color: #000000;
}

.dots {
    /* cursor: default; */
    all: unset;
}

/* ********** */
/* 안내 모달 */
/* ********** */
/* 버튼 손모양 */
button {
cursor: pointer;
}

/* 뒷배경 */
.base-modal-overlay {
position: fixed;
top: 0;
left: 0;
width: 100%;
height: 100%;
background-color: rgba(0, 0, 0, 0.5);
display: flex;
justify-content: center;
align-items: center;
z-index: 1000;
}

/* 모달 박스 */
.base-modal-box {
background-color: #fff;
padding: 25px;
border-radius: 10px;
width: 430px;
height: 330px;
display: flex;
flex-direction: column;
justify-content: space-between;
align-items: center;
border: 3px solid #A2CAAC;
}

/* 각 넓이 설정 */
.base-modal-content, .base-modal-btn {
width: 100%;
font-size: 1.6rem;
text-align: center;
line-height: 227px;
}

/* 버튼 중앙 정렬 */
.base-modal-btn {
display: flex;
justify-content: center;
align-items: center;
column-gap: 75px;
}

/* 각 버튼 설정 */
.base-modal-btn > button {
padding: 12px 40px;
border: none;
border-radius: 5px;
font-size: 1.1rem;
}

/* 확인 버튼 */
.base-modal-submit {
background-color: #A2CAAC;
color: #fff;
}

/* 취소 버튼 */
.base-modal-cancel {
background-color: #F3F3F3;
}
</style>