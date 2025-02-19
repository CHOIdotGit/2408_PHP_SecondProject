<template>
    <!-- <div class="del-modal-black" v-show="props.isOpen"> -->
    <div class="del-modal-black">
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
                    <div class="modal-mission-content" v-for="item in $store.state.calendar.income" :key="item">
                        <p class="mission-name">{{ item.title }}</p>
                        <p class="expense-type">{{ item.category }}</p>
                        <p class="inout-come income">수입</p>
                        <p class="charge">{{ Number(item.amount).toLocaleString() }}</p>
                        <p class="due-date">{{ item.updated_at.split(" ")[0] }}</p>
                    </div>
                    <!-- 데이터 없을 때 처리 필요 -->
                    <div class="mission-content">
                        <div class="modal-mission-content" v-for="item in $store.state.calendar.expense" :key="item">
                            <p class="mission-name">{{ item.title }}</p>
                            <p class="expense-type">{{ item.category }}</p>
                            <span class="inout-come outgo">지출</span>
                            <p class="charge">{{ Number(item.amount).toLocaleString() }}</p>
                            <p class="due-date">{{ item.transaction_date }}</p>
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
import { defineProps } from 'vue'

const props = defineProps({
    selectedDate: String,
})


</script>

<style scoped >
@import url('../../../css/calendarModal.css');
</style>