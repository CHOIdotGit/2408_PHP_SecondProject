<template>
    <div v-if="missionDetail"  class="detail-container">
        <div class="content" >
            <p class="title">미션 제목</p>
            <p class="ms-title">{{ missionDetail.title }}</p>
            <div class="date">
                <span class="ms-date">{{ missionDetail.start_at }}</span>
                <span>⁓</span>
                <span class="ms-date">{{ missionDetail.end_at }}</span>
            </div>
        </div>
        <div class="content">
            <p class="title">미션 종류</p>
            <div :class="category-btn"  v-for="index in categories" :key="index">
                <img class="ms-category-img" :src="index.img" alt="." v-if="categoryImg">
                <p>{{ index.name }}</p>
            </div>
        </div>
        <div class="content">
            <p class="title">미션 내용</p>
            <div class="ms-content">{{ missionDetail.content}}</div>
        </div>
        <div class="content">
            <p class="title">금액(원)</p>
            <!-- <p class="ms-amount">{{ missionDetail.amount.toLocaleString()}}</p> -->
        </div>
        <div class="create-btn">
            <button @click="$router.push('/parent/mission/list')" class="ms-cancel">뒤로가기</button>
            <button @click="delOpenModal" class="ms-del">삭제</button>
            <button class="ms-up">수정</button>
            <button class="ms-comfirm">승인</button>
        </div>
    </div>
    <!-- ************************* -->
    <!-- ********삭제 모달********* -->
    <!-- ************************* -->
    <div class="del-modal-black" v-show="delModal">
        <div class="del-modal-white">
            <div class="modal-content">
                <img src="/img/icon-boy-2.png" class="modal-img" alt=".">
                <p class="modal-name"></p>
                <p class="modal-ms-title">미션 : </p>
                <div class="del-guide">해당 미션이 삭제됩니다.</div>
            </div>
            <div class="del-btn">
                <button @click="delCloseModal" class="modal-cancel">취소</button>
                <button @click="" class="modal-del">삭제</button>
            </div>
        </div>
    </div>

    
</template>

<script setup>
import { computed, ref, onMounted, reactive  } from 'vue';
import { useStore } from 'vuex';

const categories = reactive({
    name: '학습' , img:'/img/icon-pencil.png'
    ,name: '취미' , img:'/img/icon-bicycle.png'
    ,name: '집안일' , img:'/img/icon-cleaner.png'
    ,name: '생활습관' , img:'/img/icon-clock.png'
    ,name: '기타' , img:'/img/icon-checklist7.png'
})




// { name: '학습' , img:'/img/icon-pencil.png' },
// { name: '취미' , img:'/img/icon-bicycle.png' },
// { name: '집안일' , img:'/img/icon-cleaner.png' },
// { name: '생활습관' , img:'/img/icon-clock.png' },
// { name: '기타' , img:'/img/icon-checklist7.png' },


// *****삭제 모달창********** 
const delModal = ref(false);

const delOpenModal = () => {
    delModal.value = true;
}

const delCloseModal = () => {
    delModal.value = false;
}

// *****미션 상세 정보******
const store = useStore();
const missionDetail = computed(() => store.state.mission.missionDetail);
onMounted(() => {
    store.dispatch('mission/showMissionDetail', store.state.mission.missionId);
});



</script>

<style scoped>

.main-container{
    margin-left: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.detail-container {
    background-color: #FFFFFF;
    width: 1500px;
    margin-top: 20px;
    height: 765px;
}
.content-list {
    display: grid;
    margin-top: 100px;
    margin-left: 20px;
    justify-content: center;
}


.content {
    display: flex;
    padding: 20px;
    margin: 0 300px;
    gap: 10px;
    border-bottom: 2px solid #dfdfdf;
    width: 1000px;
}


.title {
    font-size: 2rem;
    border-right: 2px solid #dfdfdf;
    padding: 10px;
    width: 150px;
    text-align: center;
}

/* 미션 제목 */
.ms-title {
    width: 300px;
    border: 3px solid #A2CAAC;
    outline: none;
    border-radius: 10px;
    font-size: 2rem;
    padding-left: 5px;
}

/* 미션 날짜 */
.date {
    border: 3px solid #A2CAAC;
    border-radius: 10px;
    padding: 10px;
    margin-left: 30px;
}

span {
    padding: 5px;
}

.ms-date {
    border: none;
    outline: none;
    /* width: 200px; */
    font-size: 1.5rem;
    text-align: center;
}

/* 미션 종류 카테고리 이미지 */
.category-btn {
    display: flex;
    flex-direction: column;
    width: 80px;
    height: 80px;
    background-color: #c9cfca;
    border-radius: 50px;
    align-items: center;
    margin-right: 20px;
    
}


.ms-category-img {
    margin-top: 13px;
    width: 50px;
    height: 50px;
    background-size: contain;
    background-repeat: no-repeat;
    border: none;

}

/* 미션 내용 */
.ms-content {
    width: 500px;
    height: 150px;
    padding: 10px;
    border-radius: 10px;
    border: 3px solid #A2CAAC;
    font-size: 1.3rem;
}

/* 미션 금액 */
.ms-amount {
    width: 300px;
    border: 3px solid #A2CAAC;
    border-radius: 10px;
    font-size: 1.3rem;
    padding-left: 5px;
    line-height: 45px;
}

/* 취소버튼 */
.ms-cancel {
    color: #ACACAC;
    background-color: #FFFFFF;
    font-size: 1.2rem;
    border: 1px solid #ACACAC;
    padding: 5px;
    width: 100px;
    height: 50px;
    border-radius: 0px;
    cursor: pointer;
    margin-left: 130px;
    margin-right: 350px;
}

/* 삭제버튼 */
.ms-del {
    /* margin-left: 250px; */
    color: #A2CAAC;
    background-color: #FFFF;
    font-size: 1.2rem;
    border: 1px solid #A2CAAC;
    padding: 5px;
    width: 100px;
    border-radius: 0px;
    cursor: pointer;
}

.bottom-btn{
    /* width: 60px; */
    gap: 30px;
    display: flex;
    margin-top: 50px;
}

/* 수정버튼 */
.ms-up {
    color: #FFFF;
    background-color: #A2CAAC;
    font-size: 1.2rem;
    border: 1px solid #A2CAAC;
    padding: 5px;
    width: 100px;
    border-radius: 0px;
    cursor: pointer;
}

/* 승인버튼 */
.ms-comfirm {
    color: #FFFF;
    background-color: #A2CAAC;
    font-size: 1.2rem;
    border: 1px solid #A2CAAC;
    padding: 5px;
    width: 100px;
    border-radius: 0px;
    cursor: pointer;
}

.btn-right {
    display: flex;
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
    background-color: #FFFFFF;
    border: 3px solid #5589e996;
    border-radius: 50px;
    padding: 3px;
}



/* 버튼 */
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