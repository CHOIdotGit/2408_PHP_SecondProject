<template>
    <div  class="main-container">
        <div class="create-container">
            <div class="content-list">
                <div class="content" >
                    <p class="title">제목</p>
                    <input type="text" v-model="missionDetail.title" value="" class="ms-title" id="ms-title" maxlength="10" autofocus>
                    
                    <div class="date">
                        <input type="date" v-model="missionDetail.start_at" class="ms-date" id="ms-date" min="2000-01-01"  >
                        <span>⁓</span>
                        <input type="date" v-model="missionDetail.end_at" class="ms-date" id="ms-date" min="2000-01-01" >
                        <!-- value="today" -->
                    </div>
                </div>
                <div class="content">
                    <p class="title">종류</p>
                    <div class="category-btn" v-for="item in categories" :key="item">
                        <input type="radio" name="category" :value="item.index" :id="'category-' + item.index" v-model="missionDetail.category"></input>
                        <label :for="'category-' + item.index" :class="[{'checked-category-btn': item.index === missionDetail.category}, 'ms-category-btn']">
                            <img class="ms-category-img" :src="item.img" >
                            <p class="category-name">{{ item.name }}</p>
                        </label>
                    </div>

                </div>
                <div class="content">
                    <p class="title">내용</p>
                    <textarea v-model="missionDetail.content" class="ms-content" id="ms-content"></textarea>
                    <!-- {{ missionDetail.content}} -->
                </div>
                <div class="content">
                    <p class="title">금액</p>
                    <input v-model="missionDetail.amount" type="number" class="ms-amount" id="ms-amount" required>
                    <!-- {{ missionDetail.amount}} -->
                </div>
                <div class="bottom-btn">
                    <button @click="$router.push(`/parent/mission/detail/${mission_id}`)" class="create-btn cancel">수정 취소</button>
                    <button @click="getUpdateMission" class="create-btn">수정 완료</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useStore } from 'vuex';
import { computed, onBeforeMount, reactive  } from 'vue';
import { useRoute } from 'vue-router';
// import mission from '../../../../js/store/modules/mission';

const route = useRoute();
const store = useStore();

onBeforeMount(() => {
    const mission_id = route.params.mission_id;
    console.log('수정할 mission id 획득 : ', mission_id);
    store.dispatch('mission/goUpdateMission', mission_id); //mission_id 불러오기
});

// *****미션 상세 정보******
const missionDetail = store.state.mission.missionDetail;


// 미션 내용 수정
// const upDateMission = reactive({
//     title: ''
//     ,content: ''
//     ,category: ''
//     ,amount: 0
//     ,start_at: ''
//     ,end_at: ''
//     ,mission_id: route.params.mission_id
// });

const categories = reactive([
    {name: '학습' , img:'/img/icon-pencil.png', index: "0"}
    ,{name: '취미' , img:'/img/icon-bicycle.png', index: "1"}
    ,{name: '집안일' , img:'/img/icon-cleaner.png', index: "2"}
    ,{name: '생활습관' , img:'/img/icon-clock.png', index: "3"}
    ,{name: '기타' , img:'/img/icon-checklist7.png', index: "4"}
]);

const getUpdateMission = () => {
    store.dispatch('mission/UpdateMission', missionDetail);
};




</script>

<style scoped>
.main-container{
    margin-left: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.create-container {
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
    display: flex;
    justify-content: center;
    align-items: center;
}

/* 미션 제목 */
.ms-title {
    width: 300px;
    border: 3px solid #A2CAAC;
    outline: none;
    border-radius: 10px;
    font-size: 1.8rem;
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
    text-align: center;
    padding-right: 30px;
}

.category-btn > input {
    display: none;
}

.ms-category-btn {
    width: 80px;
    height: 80px;
    border-radius: 50px;
    background-color: #c9cfca;
    text-align: center;
    cursor: pointer;
}

.checked-category-btn {
    background-color: #A2CAAC;
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
    margin-top: 10px;
}

/* 미션 내용 */
.ms-content {
    width: 500px;
    height: 150px;
    resize: none;
    padding: 10px;
    outline: none;
    border-radius: 10px;
    border: 3px solid #A2CAAC;
    font-size: 1.5rem;
}

/* 미션 금액 */
.ms-amount {
    width: 300px;
    border: 3px solid #A2CAAC;
    outline: none;
    border-radius: 10px;
    font-size: 1.8rem;
    padding-left: 5px;
}

.ms-amount::-webkit-outer-spin-button,
.ms-amount::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

.bottom-btn{
    display: flex;
    justify-content: right;
    gap: 30px;
    margin: auto;
    margin-top: 50px;
    cursor: pointer;
    margin-right: 200px;
    color: #FFFFFF;
}


/* 취소/미션등록 버튼 */
.create-btn {
    width: 120px;
    height: 50px;
    font-size: 1.5rem;
    border: none;
    background-color: #A2CAAC ;
    margin-bottom: 30px;
    cursor: pointer;
    color: #FFFFFF;
}
/* 취소버튼 */
.cancel {
    color: #ACACAC;
    background-color: #FFFFFF;
    border: 1px solid #ACACAC;

}
</style>