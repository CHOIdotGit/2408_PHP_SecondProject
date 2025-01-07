<template>
    <div class="main-container">
        <div class="detail-container">
            <div class="content-list">
                <div class="content" v-if="missionDetail">
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
                    <div class="categorybtn"  v-for="item in categories" :key="item" :class="{'categorybtn-green' : item.index === Number(category) }">
                        <img class="ms-category-img" :src="item.img" >
                        <p class="category-name">{{ item.name }}</p>
                    </div>
                </div>
                <div class="content">
                    <p class="title">미션 내용</p>
                    <div class="ms-content">{{ missionDetail.content}}</div>
                </div>
                <div class="content">
                    <p class="title">금액(원)</p>
                    <p class="ms-amount">{{ missionDetail.amount}}</p>
                </div>
                <div class="bottom-btn">
                    <div class="bottom-btn right">
                        <button @click="goBack(missionDetail.child_id)" class="ms-cancel">뒤로가기</button>
                    </div>
                    <div class="bottom-btn right">
                        <button @click="delOpenModal" class="create-btn ms-del">삭제</button>
                        <button class="create-btn ms-up">완료</button>
                        <button @click="goUpdate(missionDetail.mission_id)" class="create-btn ms-comfirm">수정</button>
                    </div>
                </div>
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
                <p class="modal-name"></p>
                <p class="modal-ms-title">미션 : {{missionDetail.title}}</p>
                <div class="del-guide">해당 미션이 삭제됩니다.</div>
            </div>
            <div class="del-btn">
                <button @click="delCloseModal" class="modal-cancel">취소</button>
                <button @click="deleteMission(missionDetail.mission_id)" class="modal-del">삭제</button>
            </div>
        </div>
    </div>

</template>

<script setup>
import { ref, computed, onMounted, reactive } from 'vue';
import { useStore } from 'vuex';
import { useRouter } from 'vue-router';

const store = useStore();
const router = useRouter();
const missionDetail = computed(() => store.state.childMission.missionDetail);
onMounted(() => {
    store.dispatch('childMission/showMissionDetail', store.state.childMission.missionId);
});

//****미션 카테고리 정보 출력****
const category = computed(() => store.state.childMission.missionDetail.category);


const categories = reactive([
    {name: '학습' , img:'/img/icon-pencil.png', index : 0}
    ,{name: '취미' , img:'/img/icon-bicycle.png', index : 1}
    ,{name: '집안일' , img:'/img/icon-cleaner.png', index : 2}
    ,{name: '생활습관' , img:'/img/icon-clock.png', index : 3}
    ,{name: '기타' , img:'/img/icon-checklist7.png', index : 4}
]);

// 뒤로가기
const goBack = (child_id) => {
    store.dispatch('childMission/setChildMissionList', child_id);
    router.push('/child/mission/list');

}
// *****미션 삭제*******
const deleteMission = (mission_id) => {
    console.log('삭제 요청 mission_id : ', mission_id)
    store.dispatch('childMission/deleteMission', mission_id);
}

// *****미션 수정*******
const goUpdate = (mission_id) => {
    console.log('수정 요청 mission_id : ', mission_id);
    store.dispatch('childMission/goUpdateMission', mission_id);
    router.push('/child/mission/update/'+ mission_id);
}

// 삭제 모달
const delModal = ref(false);

const delOpenModal = () => {
    delModal.value = true;
}

const delCloseModal = () => {
    delModal.value = false;
}


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
    height: 720px;
}
.content-list {
    display: grid;
    margin-top: 50px;
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
    width: 450px;
    /* border: 3px solid #5589e996; */
    outline: none;
    border-radius: 10px;
    font-size: 1.8rem;
    padding-left: 5px;
    display: flex;
    align-items: center;
}

/* 미션 날짜 */
.date {
    /* border: 3px solid #5589e996; */
    border-radius: 10px;
    padding: 10px;
    /* margin-left: 30px; */
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
.categorybtn {
    display: flex;
    flex-direction: column;
    width: 80px;
    height: 80px;
    background-color: #c9cfca;
    border-radius: 50px;
    align-items: center;
    margin-right: 20px;
    
}

/* db에 저장된 카테고리 표시 */
.categorybtn-green {
    background-color: #5589e996;
}

.ms-category-img {
    margin-top: 13px;
    width: 50px;
    height: 50px;
    background-size: contain;
    background-repeat: no-repeat;
    border: none;

}
.category-name {
    margin-top: 15px;
}

/* 미션 내용 */
.ms-content {
    width: 500px;
    height: 150px;
    padding: 10px;
    border-radius: 10px;
    /* border: 3px solid #5589e996; */
    font-size: 1.3rem;
}

/* 미션 금액 */
.ms-amount {
    width: 300px;
    /* border: 3px solid #5589e996; */
    border-radius: 10px;
    font-size: 1.8rem;
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
    color: #5589e996;
    background-color: #FFFF;
    font-size: 1.2rem;
    border: 1px solid #5589e996;
    padding: 5px;
    width: 100px;
    border-radius: 0px;
    cursor: pointer;
}

.bottom-btn{
    /* width: 1400px; */
    gap: 30px;
    display: flex;
    margin-top: 10px;
    justify-content: space-between;
}

.left {
    margin-left: 450px;
}

.right {
    margin-right: 350px;
}


/* 수정버튼 */
.ms-up {
    color: #FFFF;
    background-color: #5589e996;
    font-size: 1.2rem;
    border: 1px solid #5589e996;
    padding: 5px;
    width: 100px;
    border-radius: 0px;
    cursor: pointer;
}

/* 승인버튼 */
.ms-comfirm {
    color: #FFFF;
    background-color: #5589e996;
    font-size: 1.2rem;
    border: 1px solid #5589e996;
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
    border: 3px solid #5589e996;
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
    background-color: #5589e996;
    font-size: 1.2rem;
    border: 1px solid #5589e996;
    padding: 5px;
    width: 100px;
    cursor: pointer;
    margin: 10px;
}

</style>