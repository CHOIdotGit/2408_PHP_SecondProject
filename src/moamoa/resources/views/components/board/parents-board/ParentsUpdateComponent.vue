<template>
    <div  class="main-container">
        <div class="board-container">
            <div class="content-list">
                <div class="content" >
                    <p class="title">제목</p>
                    <input type="text" v-model="missionDetail.title" value="" class="ms-title deco" id="ms-title" maxlength="10" autofocus>
                    
                    <div class="date deco">
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
                    <textarea v-model="missionDetail.content" class="ms-content deco" id="ms-content"></textarea>
                    <!-- {{ missionDetail.content}} -->
                </div>
                <div class="content">
                    <p class="title">금액</p>
                    <input v-model="missionDetail.amount" type="number" class="ms-amount deco" id="ms-amount" required>
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
@import url('../../../../css/boardCommon.css');
@import url('../../../../css/category.css');

/* 선택된 카테고리 색깔 */
.checked-category-btn {
    background-color: #A2CAAC;
}

/* 입력란 테두리 */
.deco {
    border: 3px solid #A2CAAC;
}

/* 하단 버튼 영역 */
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