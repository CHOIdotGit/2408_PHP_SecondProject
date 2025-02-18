<template>
    <div class="del-modal-black" v-show="props.isOpen">
        <div class="del-modal-white">
            <div class="modal-list-container">
                <div class="modal-mission-title">
                    <p class="mission-name">제목</p>
                    <p class="expense-type">종류</p>
                    <p class="inout-come">분류</p>
                    <p class="charge">금액</p>
                    <p class="due-date">작성일자</p>
                </div>
                <div class="mission-inserted-list">
                    <div class="modal-mission-content" v-for="item in income" :key="item">
                        <p class="mission-name">{{ item.title }}</p>
                        <p class="expense-type">{{ item.category }}</p>
                        <p class="inout-come income">수입</p>
                        <p class="charge">{{ Number(item.amount).toLocaleString() }}</p>
                        <p class="due-date">{{ formatDate(item.updated_at) }}</p>
                    </div>
                    <div class="mission-content">
                        <div class="modal-mission-content" v-for="item in expense" :key="item">
                            <p class="mission-name">{{ item.title }}</p>
                            <p class="expense-type">{{ item.category }}</p>
                            <span class="inout-come outgo">지출</span>
                            <p class="charge">{{ Number(item.amount).toLocaleString() }}</p>
                            <p class="due-date">{{ formatDate(item.transaction_date) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="del-btn">
                <button @click="$emit('close')" class="modal-cancel">취소</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, watch } from "vue";
import { useStore } from "vuex";
import { useRoute, useRouter } from "vue-router";
import { defineProps } from "vue";

const store = useStore();
const router = useRouter();
const route = useRoute();

const income = computed(() => store.state.calendar.income);
const expense = computed(() => store.state.calendar.expense);

const props = defineProps({
    isOpen: {
        type: Boolean,
        default: false
    },
    selectedDate: {  // 부모에서 선택한 날짜를 전달받음
        type: Date,
        required: true  // 부모가 반드시 값을 전달해야 함
    }
});

// 날짜 형식을 "YYYY-MM-DD"로 변환하는 유틸리티 함수 (예시)
function formatDate(dateString) {
    // 실제 변환 로직은 필요에 따라 작성
    return dateString;
}

watch(
    () => props.selectedDate,
    (newDate) => {
        if (newDate) {
        const formattedDate = newDate.toISOString().split('T')[0];
        console.log("모달에 전달된 선택한 날짜:", formattedDate);
        store.dispatch('calendar/ParentCalendarModal', {
            date: formattedDate,
            child_id: route.params.child_id
        });
        } else {
        console.error("선택한 날짜가 전달되지 않았습니다.");
        }
    }
);
</script>

<style scoped >
@import url('../../../css/calendarModal.css');
</style>