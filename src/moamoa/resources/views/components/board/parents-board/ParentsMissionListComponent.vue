<template>
<div v-if="isMobile">
    <div class="m-container">
        <div class="m-header">
            <img src="/img/icon-girl-4.png" alt="" class="m-user-image">
            <p class="m-user-profile">김주연</p>
            <p class="go-update"> > </p>
        </div>
        <div class="m-child-select-section">
            <div class="m-search-section">
                <!-- 자식 선택은 스와이퍼추가 / 자식 수가 늘어나면 옆으로 넘길수 있도록
                    선택한 자식은 클래스 추가로 백그라운드 컬러만 m-main-content 에 추가해주시면됩니다 -->
                <p class="m-child-select menu-sec-first"> 배현진 </p>
                <p class="m-child-select"> 김현석 </p>
                <p class="m-child-select"> 최상민 </p>
            </div>
            <img src="/img/m-search.png" alt="" class="m-search-button" @click="searchOpenModal">
        </div>
        <div class="m-expense-list">
            <img src="/img/m-create-btn.png" alt="" class="m-create-btn" @click="getChildId(childId)">
            <!-- 한 게시물  -->
            <div class="m-expense-section" v-if="missionList && missionList.length" v-for="item in missionList" :key="item">
                <div class="m-expense-first">
                    <p @click="getMissionId(item.mission_id)" class="m-expense-title">{{ item.title }}</p>
                </div>
                <div class="m-expense-second">
                    <div class="m-sec-expense">
                        <p :class="mGetStatusClass(item.status)">{{ getStatusText(item.status) }}</p>
                        <p class="m-expense-cate">{{ getCategoryText(item.category) }}</p>
                    </div>
                    <p class="m-expense-amount">{{ item.amount.toLocaleString() }}원</p>
                </div>
                <div class="m-expense-thrid">
                    <p class="m-expense-date">{{ item.start_at }}</p>
                    <p class="m-expense-date">~</p>
                    <p class="m-expense-date">{{ item.end_at }}</p>
                </div>
            </div>
            <!-- 여기까지 한게시물 -->
        </div>
        <footer>
            <div class="m-footer-menu">
                <div class="m-menu">
                    <div class="m-menu-section" @click="router.push('/parent/home')">
                        <img src="/img/icon-home.png" alt="" class="m-home menu-sec-first">
                        <p class="m-menu-title   menu-sec-first"> 홈 </p>
                    </div>
                    <div class="m-menu-section" @click="router.push('/parent/mission/list/1')">
                        <img src="/img/icon-piggy-bank.png" alt="" class="m-mission">
                        <p class="m-menu-title"> 미션 </p>
                    </div>
                    <div class="m-menu-section" @click="router.push('/parent/spend/list/1')">
                        <img src="/img/icon-coin.png" alt="" class="m-expense">
                        <p class="m-menu-title"> 지출 </p>
                    </div>
                    <div class="m-menu-section" @click="goParentCalendar">
                        <img src="/img/icon-calendar.png" alt="" class="m-calendar">
                        <p class="m-menu-title"> 달력 </p>
                    </div>
                    <div class="m-menu-section" @click="router.push('/parent/moabank/1')">
                        <img src="/img/icon-sack-dollar.png" alt="" class="m-bank">
                        <p class="m-menu-title"> 모아통장 </p>
                    </div>
                    <div class="m-menu-section" >
                        <img src="/img/mobile-etc.png" alt="" class="m-etc">
                    </div>
                </div>
            </div>
        </footer>
    </div>

<!-- 검색 모달 -->
        <div class="del-modal-back" v-show="serachModal.isOpen">
            <div class="m-modal-list-container">
                <div class="m-search-filter">
                    <div class="m-search-category">
                        <p>지출 종류 </p> 
                        <select name="spend-type">
                            <option value="">전체</option>
                            <option value="0">교통비</option>
                            <option value="1">식비</option>
                            <option value="2" >쇼핑</option>
                            <option value="3">기타</option>
                        </select>
                    </div>
                    <div class="m-search-date">
                        <p>지출 일자</p> 
                        <input type="date" min="2000-01-01" >
                    </div>
                    <div class="m-modal-search">
                        <input type="search" placeholder="검색어를 입력해주세요">
                    </div>
                </div>
            </div>
            <div class="m-modal-btn">
                <button @click="searchCloseModal" class="modal-cancel">취소</button>
                <button> 검색</button>
            </div>
        </div>    
</div>



<!--  웹 -->
    <div class="container" v-else>
        <div class="list-container">
            <div class="route"> 홈  > 미션 </div>
            <div class="top-btns">
                <button @click="approvalMission" class="btn-top mission-confirm">미션 승인</button>
                <button @click="getChildId(childId)" class="btn-top mission-regist">미션 등록</button>
                <button @click="delOpenModal" class="btn-top mission-delete">미션 삭제</button>
            </div>
            <div class="search-menu">
                <div class="search-option">
                    <div class="search-date">
                        <p>⦁ 미션 일자</p> 
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
                            <option value="3">미션 취소</option>
                        </select>
                    </div>
                    <div class="search">
                        <input type="search" v-model="filters.keyword" placeholder="검색어를 입력해주세요">
                    </div>
                </div>
                <div class="search-btn">
                    <button @click="missionSearch(childId)">검색</button>
                </div>
            </div>
            <div class="mission-title-bar">
                <div class="chk-div">
                    <input type="checkbox" class="checkbox" name="checkAll" @change="checkAll" :checked="isAllChecked">
                </div>
                <p class="mission-name">제목</p>
                <span class="status">상태</span>
                <p class="mission-type">종류</p>
                <p class="charge">금액</p>
                <p class="due-date">기한</p>
            </div>
            <div>
                <div v-if="missionList && missionList.length" v-for="item in missionList" :key="item" class="mission-inserted-list">
                    <div class="mission-content">
                        <div class="chk-div">
                            <input v-model="checkboxItem" class="checkbox" type="checkbox" :value="item.mission_id" name="checkbox">
                        </div>
                        <p @click="getMissionId(item.mission_id)" class="mission-title">{{ item.title }}</p>
                        <p :class="getStatusClass(item.status)">{{ getStatusText(item.status) }}</p>
                        <p class="mission-type-selected">{{ getCategoryText(item.category) }}</p>
                        <p class="mission-amount">{{ item.amount.toLocaleString() }}원</p>
                        <p class="mission-due-date">{{ item.start_at }} ~ {{ item.end_at }}</p>
                    </div>
                </div>
                <!-- <div v-else>검색 결과가 없습니다</div> -->
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
                    <!-- '...'인 경우 span 태그 사용 -->
                    <!-- <button class="paginate-btn" @click="goToPage(page)" :disabled="page === currentPage || page === '...'">{{ page }}</button> -->
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
        </div>
    </div>

    <!-- ************************* -->
    <!-- ********삭제 모달********* -->
    <!-- ************************* -->
    <div class="del-modal-black" v-show="delModal">
        <div class="del-modal-white">
            <div class="modal-content">
                <img src="/img/icon-trash.png" class="modal-img" alt=".">
                <div class="del-guide">선택한 {{(checkboxItem).length}} 개의 미션이 삭제됩니다.</div>
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
        <div >미션을 선택하세요</div>
      </div>

      <div class="base-modal-btn">
        <button type="button" class="base-modal-submit" @click="delCloseModal">확인</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onBeforeMount, onMounted, reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';

// 스토어 라우터 라우트
const store = useStore();
const router = useRouter();
const route = useRoute();

// 라우터에서 쿼리 파라미터 받기
// const childId = router.query.child_id;

// 자녀 id 파라미터 세팅
const childId = computed(() => store.state.mission.childId);

// *******체크박스 선택하기**********
// 선택된 체크박스 데이터
const checkboxItem = ref([]); // 모두 선택되면 전체 체크박스에도 선택 표시하기 위해서
console.log('체크박스 선택된 데이터 : ', checkboxItem.value);

// 모든 체크박스가 선택되었는지 확인 (computed : 반응형 데이터로 다루기 위해)
const isAllChecked = computed(() => {
    return checkboxItem.value.length > 0 && missionList.value.every((item) => checkboxItem.value.includes(item.mission_id));
});

// 체크된 미션만 삭제 처리 하기
const deleteCheckedMission = () => {
    if(checkboxItem.value.length === 0) {
        // alert("삭제할 미션을 선택하세요");
        infoModal.value = true;
        return;
    }
    store.dispatch('mission/deletcheckedMission', checkboxItem.value);
    delModal.value = false;
    
    store.dispatch('mission/missionList', { child_id: route.params.id, page: currentPage.value }); //삭제후 미션 리스트 새로 불러오기
    checkboxItem.value = [];
}

// +==========================+
// +    모바일 화면 전환       +
// +==========================+
// v-if="ismobile"적으면 모바일 화면으로 이동
const isMobile = store.state.mobile.isMobile;

// --------------- 모바일 모달 -----------------

const serachModal = reactive({ isOpen: false });

const searchOpenModal = () => {
    serachModal.isOpen = true;
};

const searchCloseModal = () => {
    serachModal.isOpen = false;
};

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

// ********** 페이지네이션 **********
const currentPage = computed(() => store.state.mission.currentPage);
const lastPage = computed(() => store.state.mission.lastPage);

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
        store.dispatch('mission/missionList', { child_id: route.params.id, page });
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

// 체크박스 모두 선택
// const checkboxItem = ref(false);
// const checkAll = () => {
//     checkboxItem.value = !checkboxItem.value;
//     console.log('체크박스 선택');
// }



// 첫 번째 자녀의 name을 가져오는 computed
const childName = computed(() => {
    const child = missionList.value?.[0]?.child;
    return child ? child.name : 'Loading...';
});

// 상태를 문자열로 변환하는 함수
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
        "0": '학습',
        "1": '취미',
        "2": '집안일',
        "3": '생활습관',
        "4": '기타',
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

const mGetStatusClass = (status) => {
    switch (status) {
        case '0': // 진행중
            return 'm-state-in-progress';
        case '1': // 대기중
            return 'm-state-waiting';
        case '2': // 완료
            return 'm-state-complete';
        case '3': // 취소
            return 'm-state-cancel';
    }
};


// 미션 리스트 받아오기
const missionList = computed(() => store.state.mission.missionList);


// onMount(메뉴에서 선택된 자녀 id 받아와서 list 출력)
onMounted(() => {
    store.dispatch('mission/missionList', {child_id: route.params.id, page: 1});
});

// 미션아이디 확인해서 상세 페이지 이동하기 위해서
const getMissionId = (mission_id) => {
    console.log('미션 아이디 획득', mission_id);
    store.dispatch('mission/showMissionDetail', mission_id);
}

// 자녀 아이디 확인해서 작성 페이지로 이동하기 위해서
const getChildId = (child_id) => {
    console.log('자녀 아이디 확인', child_id)
    store.dispatch('mission/goCreateMission', child_id);
}

// 미션 승인
const approvalMission = () => {
    if(checkboxItem.value.length > 0) {
        store.dispatch('mission/approvalMission', checkboxItem.value);
        store.dispatch('mission/missionList', { child_id: route.params.id, page: currentPage.value });
        checkboxItem.value = [];
        // alert('진행중인 미션의 승인이 완료되었습니다.');
    }else {
        // alert('선택되어있는 미션이 없습니다.');
        infoModal.value = true;
    }
};

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
    child_id: ""
});


const missionSearch = (childId) => {
    filters.value.child_id = childId;
    store.dispatch('mission/missionSearch', filters.value);
};

// 모바일 메뉴--------------------------------------------------
//  부모 캘린더로 이동
const dateToday = ref(new Date());
const goParentCalendar = () => {
    // const child_id = selectedChild.value.child_id;
    store.dispatch('calendar/parentCalendarInfo', { date: dateToday.value, child_id: 1 })
    router.push('/parent/calendar/'+ 1);
}

</script>

<style scoped>
.container {
    /* margin-left: 50px; */
    display: flex;
    justify-content: center;
    align-items: center;
}

.list-container {
    background-color: white;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.search {
    font-size: 1.2rem;
    display: flex;
    align-items: center; /* 수직 정렬 */
    gap: 10px;
}

.search input {
    width: 1000px;
    padding: 5px; /* 적절한 여백 추가 */
    font-size: 1.2rem;
    margin-bottom: 20px;
    margin-left: 20px;
    margin-top: 20px;
}
.route {
    margin-top: 20px;
    font-size: 1.7rem;
}

.search-date {
    margin-top: 20px;
    margin-left: 20px;
    font-size: 1.5rem;
    display: flex;
    align-items: center; /* 수직 정렬 */
    gap: 10px;
    p {
        margin: 0; /* 기본 여백 제거 */
    white-space: nowrap; /* 텍스트 줄바꿈 방지 */
    }
}

.search-date input[type="date"] {
    padding: 5px; /* 적절한 여백 추가 */
    font-size: 1.2rem;
}

.search-filter {
    margin-top: 20px;
    margin-left: 20px;
    font-size: 1.5rem;
    display: flex;
    align-items: center; /* 수직 정렬 */
    gap: 30px;
    p {
        margin: 0; /* 기본 여백 제거 */
    white-space: nowrap; /* 텍스트 줄바꿈 방지 */
    }
}

.search-filter select {
    padding: 5px; /* 적절한 여백 추가 */
    font-size: 1.2rem;
}

.mission-title-bar {
    display: grid;
    grid-template-columns: 40px 210px 100px 100px 150px 340px;
    height: 60px;
    gap: 75px;
    background-color: #d1d1d1;
    font-size: 2rem;
    align-items: center;
    text-align: center;
}

.mission-title {
    cursor: pointer;
}

.search-btn button{
    width: 120px;
    height: 150px;
    margin-top: 20px;
    font-weight: 800;
    align-content: space-between;
    margin-left: 680px;
    background-color: #A2CAAC;
    font-size: 1.5rem;
    border: none;
    cursor: pointer;
  box-shadow: 0 3px 6px rgba(0,0,0,0.16), 0 3px 6px rgba(0,0,0,0.23);

}

.search-btn button:hover {
    color: #ffffff;
    background-color: #6a8f73;
}

.mission-confirm {
    margin-left: 1000px;
}

.mission-delete {
    margin-left: 20px;
}

.mission-regist {
    margin-left: 20px;
}
.mission-title:hover {
    color: #A2CAAC;
}

.mission-content {
    display: grid;
    grid-template-columns: 40px 210px 100px 100px 150px 340px;
    gap: 75px;
    font-size: 1.3rem;
    align-items: center;
    text-align: center;
}

/* 금액 : 오른쪽 정렬 */
.mission-amount {
    text-align: end;
    padding-right: 30px;
}


.search-menu{
    display: flex;
    flex-direction: row;
    background-color: #d3e2d7;
    height: 200px;
    gap: 30px;
}

.btn-top {
    width: 120px;
    height: 50px;
    font-size: 1.2rem;
    border: none;
    background-color:#A2CAAC ;
    margin-top: 30px;
    cursor: pointer;
    color: #FFFFFF;
}

.btn-bottom {
    width: 120px;
    height: 50px;
    font-size: 1.3rem;
    border: none;
    background-color:#A2CAAC ;
    margin-bottom: 30px;
    cursor: pointer;
    color: #FFFFFF;
}

.checkbox {
    margin: 15px;
    width: 16px;
    height: 16px;
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
    display: grid;
    margin-top: 15px ;
}

.margin-top {
    margin-top: 20px;
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
    border: 3px solid #A2CAAC;
    /* margin: 170px; */
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

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
    background-color: #A2CAAC;
    font-size: 1.2rem;
    border: 1px solid #A2CAAC;
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
    color: #A2CAAC;
}

.no-pointer {
    cursor: default;
    background-color: #a2caac;
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

/* ==========모바일 ================ */
/* ------------- 모바일 버전 css ------------ */

.m-container {
    width: 100vw;
    height: 100vh;
}

.m-header {
    display: flex;
    height: 70px;
    background-color: #A2CAAC;
    width: 100vw;
    align-items: center;
    gap: 10px;
}

.m-user-image {
    height: 50px;
    width: 50px;
    border-radius: 50px;
    border: 2px white solid;
    background-color: white;
    margin-left: 20px;
}

.m-user-profile {
    font-size: 1.7rem;
    width: 100px;
}

.go-update {
    font-size: 2rem;
    margin-left: 180px;
}

.m-child-profile {
    width: 100px;
    height: 100px;
    border-radius: 50px;
    border: 3px solid #A2CAAC;
    background-color: white;
}

.m-child {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-top: 20px;
}   

.m-child-name {
    margin-top: 20px;
    font-size: 1.5rem;
    text-align: center;
    font-weight: 600;
}

.m-home-title-e{
    font-size: 1.3rem;
    margin-left: 70px;
    background-color: #A2CAAC;
    color: white;
    border-radius: 15px;
    font-weight: 600;
    width: 100px;
    text-align: center;
    margin-top: 20px;
    height: 50px;
    line-height: 50px;
}

.m-home-title-c{
    font-size: 1.3rem;
    margin-left: 35px;
    background-color: #A2CAAC;
    color: white;
    border-radius: 15px;
    font-weight: 600;
    width: 180px;
    text-align: center;
    margin-top: 20px;
    height: 50px;
    line-height: 50px;
}

.m-home-content {
    text-align: center;
    font-size: 1.2rem;
    height: 30px;
    line-height: 30px;
}

.m-first {
    margin-top: 20px;
}

.m-main-content {
    margin-top: 20px;
    width: 250px;
    border: 3px solid #A2CAAC;
    border-radius: 20px;
    height: 420px;
}

.m-menu {
    display: flex;
    gap: 15px;
    border-top: 2px solid black;
    img {
        filter: contrast(0.5);
        margin-top: 10px;
        width: 25px;
        height: 25px;
    }
}

.m-state-in-progress {
    background-color: rgba(138, 160, 246, 0.5);
    color: #0010BE;
    line-height: 23px;
}

.m-state-complete {
    background-color: rgba(255, 222, 92, 0.5);
    color: #2F8A1D;
    line-height: 23px;
}
.m-state-waiting {
    background-color: rgba(22, 200, 150, 0.5);
    color: #165442;
    line-height: 23px;
}

.m-state-cancel {
    background-color: rgba(254, 135, 105, 0.5);
    color: #FF3300;
    line-height: 23px;
}

.m-sec-expense {
    display: flex;
    gap: 20px;
}
.menu-sec-first {
    margin-left: 15px;
}
.m-menu-section {
    display: flex;
    flex-direction: column;
    width: 60px;
    justify-content: center;
    align-items: center;
    gap: 3px;
}
.m-menu-title {
    font-size: 0.8rem;
}

.m-home {
    margin-left: 15px;
}

.m-child-select-section {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}
.m-search-button {
    width: 30px;
    height: 30px;
    margin-right: 10px;
}

.m-search-section {
    max-width: 360px;
    height: 50px;
    width: 100vw;
    display: flex;
    gap: 15px;
    align-items: center;
    scrollbar-width: 10px;
    overflow: scroll;
    white-space: nowrap;
}

.m-child-select {
    min-width: 70px;
    height: 30px;
    border-radius: 15px;
    border: 3px solid #A2CAAC;
    text-align: center;
    line-height: 25px;
}

.m-search-btn{
    background-color: transparent;
    border: none;

}

.m-create-btn {
    width: 50px;
    height: 50px;
    position: sticky;
    border: none;
    background-color: transparent;
}

.m-search-opt {
    margin-left: 20px;
    font-size: 1.2rem;
    height: 35px;
}

.m-expense-section {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-left: 20px;
    margin-bottom: 20px;
}

.m-expense-amount {
    margin-right: 20px;
    font-size: 1.3rem;
    
}

.m-expense-first {
    display: flex;
    font-size: 1.3rem;
}

.m-expense-second {
    display: flex;
    gap: 15px;
    justify-content: space-between;
    font-size: 1.1rem;
}

.m-expense-thrid {
    display: flex;
    gap: 15px;
    font-size: 1.1rem;
}

.m-expense-date {
    font-size: 0.9rem;
}
.m-expense-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
    overflow: scroll;
    white-space: nowrap;
    height: 640px;
}

.m-expense-status{
    min-width: 50px;
}

.m-create-btn {
    position: fixed;
    width: 50px;
    height: 50px;
    left: 325px;
    bottom: 80px;
}

/* --- 모바일 모달 ---- */

.del-modal-back {
    background-color: rgba(88, 88, 88, 0.637);
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    flex-direction: column;
    justify-content: center;
    gap: 30px;
}

.m-search-filter {
    display: flex;
    flex-direction: column;
    font-size: 1.5rem; 
    gap: 20px;
}

.spend-type {
    height: 30px;
}

.m-search-category {
    display: flex;
}

.m-search-date {
    display: flex;
}

.m-modal-list-container {
    background-color: white;
    display: flex;
}

.m-modal-btn{
    display: flex;
    gap: 30px;
    button {
        width: 80px;
        height: 40px;
        font-size: 1.2rem;
    }
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