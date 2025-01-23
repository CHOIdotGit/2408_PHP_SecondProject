<template>
    <!-- 삭제 모달 -->
    <!-- 부모 mission detail 삭제 모달 -->
    <!-- 자녀 mission/spend detial 삭제 모달 -->
    <div class="del-modal-black">
        <div class="del-modal-white">
            <div class="modal-content">
                <img src="/img/icon-trash.png" class="modal-img">
                <!-- 지출 게시판에서 -->
                <div v-if="type === 'transaction'">
                    <p class="modal-ms-title" >지출 : {{ transactionDetail.title }}</p>
                    <p class="del-guide" >해당 지출이 삭제됩니다.</p>
                </div>

                <!-- 미션 게시판에서(부모) -->
                <div v-if="type === 'mission'">
                    <p class="modal-ms-title" >미션 : {{ missionDetail.title }}</p>
                    <div class="del-guide" >해당 미션이 삭제됩니다.</div>
                </div>

                <!-- 미션 게시판에서(자녀) -->
                <div v-if="type ==='mission'">
                    <p class="modal-ms-title" >미션 : {{ childmissionDetail.title }}</p>
                    <div class="del-guide" >해당 미션이 삭제됩니다.</div>
                </div>
            </div>
            <div class="del-btn">
                <button @click="delCloseModal" class="modal-cancel">취소</button>
                <button @click="" class="modal-del">삭제하기</button>
            </div>
        </div>
    </div>
</template>
    
<script setup>
import { computed, onMounted, reactive, ref, defineProps } from 'vue';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const router = useRouter();

// 미션 상세 정보 불러오기
const childmissionDetail = computed(() => store.state.childMission.showmissionDetail);

const missionDetail = computed(() => store.state.mission.missionDetail);
// defineProps({
//     type: { type: String, required: true },
// })

// 지출 상세 정보 불어오기
const transactionDetail = computed(() => store.state.childTransaction.transactionDetail);



</script>

<style scoped>
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
    width: 500px;
    height: 400px;
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
    padding: 3px;
}

/* 삭제 모달 버튼 */
.modal-cancel {
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


</style>