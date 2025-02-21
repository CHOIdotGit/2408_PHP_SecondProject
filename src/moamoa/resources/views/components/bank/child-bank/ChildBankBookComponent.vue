<template>
    <div class="bankbook-wrapper">
        <div class="bankbook">
            <!-- 상단 헤더(은행명) -->
            <div class="bankbook-header">
                <p class="bank-name">모아은행</p>
            </div>
            <!-- 통장정보 -->
            <div class="bankbook-info">
                <div class="info-detail">
                    <div class="b-info">
                        <p class="p-first">통장 종류</p>
                        <p>{{ savingDetail[0]?.saving_product_name }} 적금</p>
                    </div>
                    <div class="b-info">
                        <p class="margin-left p-first">납입 유형</p>
                        <p>{{ getTypeText(savingInfo.saving_sign_up_status) }}</p>
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
                </div>
            </div>
            <!-- 중도 해지 버튼 -->
            <div class="early-termination">
                <button @click="openModal" class="btn-early-termination">중도 해지</button>
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
                            <p class="bankbook-date">{{ item.saving_detail_created_at }}</p>
                            <p class="bankbook-amount">{{ item.saving_detail_outcome === 0 ? '' : Number(item.saving_detail_outcome).toLocaleString() }}</p>
                            <p class="bankbook-amount">{{ item.saving_detail_income === 0 ? '' : Number(item.saving_detail_income).toLocaleString() }}</p>
                            <p class="bankbook-amount">{{ Number(item.saving_detail_left).toLocaleString() }}</p>
                            <p>{{ getCategoryText(item.saving_detail_category) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 모달: 중도 해지 안내 -->
    <div v-if="showModal" class="base-modal-overlay">
      <div class="base-modal-box">
        <div class="base-modal-content">
          <h3>중도 해지 안내</h3>
          <p>약관에 따라 이자는 지급되지 않습니다.</p>
          <p>최종 지급 금액은 {{ Number(finalTotal).toLocaleString() }} 포인트 입니다.</p>
        </div>
        <div class="base-modal-btn">
          <button @click="confirmModal" class="base-modal-submit">해지하기</button>
          <button @click="close" class="base-modal-cancel">취소</button>
        </div>
      </div>
    </div>
</template>

<script setup>
import { computed, onBeforeMount, onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const route = useRoute();

// 자녀 통장 내역 불러오기
const savingDetail = computed(()=>store.state.saving.savingDetail);
// 통장 정보
const savingInfo = computed(()=> store.state.saving.savingInfo);
// 자녀 정보
const childInfo = computed(() => store.state.header.childInfo);

// 모달 관련 상태와 값
const showModal = ref(false);
const finalTotal = computed(() => store.state.saving.finalTotal);
const savingSignUpId = ref(null);

// onBeforeMount(() => {
onMounted(() => {
    store.dispatch('saving/childSavingDetail', route.params.saving_sign_up_id);
    store.dispatch('header/childInfo');
});

const openModal = () => {
    store.dispatch('saving/earlyTermination', { 
            savingSignUpId: savingSignUpId.value, 
            confirmed: false 
    });
};



const confirmModal = async () => {
    try {
            await store.dispatch('saving/earlyTermination', { 
            savingSignUpId: savingSignUpId.value, 
            confirmed: true 
        });
        store.commit('saving/setFinalTotal', response.data.final_total);
        showModal.value = false;
    } catch (error) {
        console.error("중도 해지 처리 실패:", error);
    }
};

const close = () => {
    showModal.value = false;
};

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