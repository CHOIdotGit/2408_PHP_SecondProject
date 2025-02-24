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
            <!-- 한 게시물  -->
            <div class="m-explanation">
                <div class="m-kr-bank">
                    <div class="m-kr-bank-headline">이달의 한국은행 기준 금리</div>
                        <!-- ### 한국은행 기준금리 api ### -->
                        <p class="m-red" v-if="koreaBankInterest">{{ Number(koreaBankInterest.interest).toFixed(1) }} %</p>
                    </div>
                    <p class="m-p-explanation m-m-t">* 기준금리는 한국은행이 발표하는 정책금리로, 금융기관 간 거래의 기준이 되는 금리입니다.</p>
                    <p class="m-p-explanation">* 기준 금리는 30일 적금 상품에 적용되는 금리입니다.</p>
                    <p class="m-p-explanation">* 적금은 은행 예금 상품의 하나로, 정기적 또는 비정기적으로 돈을 불입*하여 계약 기간이 만료된 후 이자와 함께 돌려받는 것을 의미합니다.</p>
                    <p class="m-p-explanation">* 최대 3개의 적금 상품을 가입하실 수 있습니다.</p>
                </div>
                <div class="m-moa-each-box">
                    <div @click="goPointPage" class="m-moa-box go-point">
                    <p class="m-have-point">보유중인 모아 포인트</p>
                    <!-- 온클릭으로 링크는 부모 포인트 페이지로 설정하기 -->
                    <p class="m-have-moa">{{ Number(point).toLocaleString() }} moa</p>
                    <!-- totalPoint 가져오면 -->
                    <p class="m-subscribe">현재 가입한 적금 상품 : {{ productCount }} 개</p>
                    <!-- 가입한 적금 상품 개수 쿼리문으로 가져오기 -->
                </div>
            </div>
            <div class="m-moa-box" v-for="item in savingList" :key="item">
                <div @click="goSavingDetail(item.saving_sign_up_id)">
                    <p class="m-have-point">모아 적금통장</p>
                    <p class="m-have-moa" >⭐ {{ item.saving_product_name }} 적금 ⭐</p>
                    <div class="m-div-box-item">
                        <p >잔액</p>
                        <div>{{ item.total }}moa</div>
                    </div>
                    <div class="m-div-box-item">
                        <p>이자율 : </p>
                        <div>{{ item.saving_product_interest_rate }} %</div>
                    </div>
                </div>
            </div>
        </div>

        <footer>
            <div class="m-footer-menu">
                <div class="m-menu">
                    <div class="m-menu-section">
                        <img src="/img/icon-home.png" alt="" class="m-home menu-sec-first">
                        <p class="m-menu-title   menu-sec-first"> 홈 </p>
                    </div>
                    <div class="m-menu-section">
                        <img src="/img/icon-piggy-bank.png" alt="" class="m-mission">
                        <p class="m-menu-title"> 미션 </p>
                    </div>
                    <div class="m-menu-section">
                        <img src="/img/icon-coin.png" alt="" class="m-expense">
                        <p class="m-menu-title"> 지출 </p>
                    </div>
                    <div class="m-menu-section">
                        <img src="/img/icon-calendar.png" alt="" class="m-calendar">
                        <p class="m-menu-title"> 달력 </p>
                    </div>
                    <div class="m-menu-section">
                        <img src="/img/icon-sack-dollar.png" alt="" class="m-bank">
                        <p class="m-menu-title"> 모아통장 </p>
                    </div>
                    <div class="m-menu-section">
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

    <div v-else class="bankbook-wrapper">
        <div class="bankbook">
            <!-- 상단 헤더 (은행명) -->
            <div class="bankbook-header">
                <p class="bank-name">모아은행</p>
            </div>
            <!-- 통장 정보 -->
            <div class="bankbook-info">
                <div class="info-detail">
                    <div class="b-info">
                        <p class="p-first">통장 종류</p>
                        <p>{{ savingDetail[0]?.saving_product_name }} 적금</p>
                    </div>
                    <div class="b-info">
                        <p class="margin-left p-first">납입 유형</p>
                        <p>{{ getTypeText(savingDetail[0]?.saving_product_type) }}</p>
                    </div>
                    <div class="b-info">
                        <p class="p-first">가입 날짜</p>
                        <p>{{ savingInfo.saving_sign_up_start_at }}</p>
                    </div>
                        <div class="b-info">
                            <p class="margin-left p-first">금   리</p>
                            <p class="p-rate">{{ Number(savingDetail[0]?.saving_product_interest_rate).toFixed(1) }} %</p>
                    </div>
                </div>
                <div class="bankbook-profile">
                    <img :src="childInfo.profile" class="img-size">
                    <!-- 이름 영역 -->
                    <!-- <div class="div-child-name">
                        <p>{{ childInfo.name }}</p>
                    </div> -->
                </div>
            </div>
            <!-- 거래 내역 -->
            <div class="bankbook-table">
                <div class="bankbook-title">
                    <p>년 월 일</p>
                    <p>출금 금액</p>
                    <p>맡기신 금액</p>
                    <p>거래 후 잔액</p>
                    <p>거래 내용</p>
                </div>
                <div class="bankbook-item">
                    <div class="main-content" >
                        <div v-for="item in savingDetail" :key="item" class="bankbook-transactions">
                            <p>{{ item.saving_detail_created_at }}</p>
                            <p class="bankbook-amount">{{ Number(item.saving_detail_outcome).toLocaleString() }}</p>
                            <p class="bankbook-amount">{{ Number(item.saving_detail_income).toLocaleString() }}</p>
                            <p class="bankbook-amount">{{ Number(item.saving_detail_left).toLocaleString() }}</p>
                            <p>{{ getCategoryText(item.saving_detail_category) }}</p>
                        </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script setup>
import { computed, onBeforeMount, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const route = useRoute();

// +==========================+
// +    모바일 화면 전환       +
// +==========================+
// v-if="ismobile"적으면 모바일 화면으로 이동
const isMobile = store.state.mobile.isMobile;

// 자녀 통장 내역 불러오기
const savingDetail = computed(()=>store.state.saving.savingDetail);

// 통장 정보
const savingInfo = computed(()=> store.state.saving.savingInfo);

onMounted(()=> {
    store.dispatch('saving/parnetChildSavingDetail', route.params.saving_sign_up_id);
    store.dispatch('header/childNameList');
    // store.dispatch('header/chooseChild', store.state.header.childId);
})

// 자녀 정보
const childInfo = computed(() => store.getters['header/getChildInfo'](store.state.header.childId));

// 카테고리 문자로 변환
const getCategoryText = (saving_detail_category) => {
    const categoryMapping = {
        "0": '입금',
        "1": '이자',
    };
    return categoryMapping[saving_detail_category]; // 기본값 없이 반환
};

// 적금 유형 문자로 변환
const getTypeText = (saving_product_type) => {
    const typeMapping = {
        "0": '매일 납입',
        "1": '매주 납입',
    };
    return typeMapping[saving_product_type]; // 기본값 없이 반환
};



</script>
  
<style scoped>
@import url('../../../../css/bankbook.css');

</style>
  