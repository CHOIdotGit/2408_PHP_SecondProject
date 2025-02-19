<template>
    <div class="main-container">
        <div class="board-container">
        <div class="route"> 홈   > 미션  >  작성 </div>
            <div class="content-list">
                <div class="content">
                    <p class="title">제목</p>
                    <input v-model="missionCreate.title" type="text" class="ms-title deco" id="ms-title" maxlength="10" autofocus placeholder="미션 제목을 입력하세요">
                </div>
                <div class="content">
                    <p class="title">날짜</p>
                    <div class="date-detail">
                        <div class="start-date">
                            <p class="date-title">시작일</p>
                            <input v-model="missionCreate.start_at" type="date" class="ms-date" id="ms-date" min="2000-01-01">
                        </div>
                        <div class="end-date">
                            <p class="date-title">종료일</p>
                            <input v-model="missionCreate.end_at" type="date" class="ms-date" id="ms-date" min="2000-01-01" >
                        </div>
                    </div>
                </div>
                <div class="content-cate" >
                    <p class="title">종류</p>
                    <!-- 카테로리 v-for -->
                    <div class="category-btn" v-for="item in categories" :key="item">
                        <input type="radio" name="category" :value="item.index" :id="'category-' + item.index" v-model="radioCategories"></input>
                        <label :for="'category-' + item.index" :class="[{'checked-category-btn': item.index === radioCategories}, 'ms-category-btn']">
                            <img class="ms-category-img" :src="item.img" >
                            <p class="category-name">{{ item.name }}</p>
                        </label>
                    </div>
                </div>
                <div class="content">
                    <p class="title">내용</p>
                    <textarea v-model="missionCreate.content" class="ms-content deco" id="ms-content" placeholder="미션 내용을 입력하세요"></textarea>
                </div>
                <div class="content">
                    <p class="title">금액</p>
                    <input v-model="missionCreate.amount" type="nume" class="ms-amount deco" id="ms-amount" min="0" maxlength="7" placeholder="금액을 입력하세요">
                    <span>원</span>
                </div>
                <div class="bottom-btn">
                    <button @click="getChildId(childId)" class="create-btn cancel">등록 취소</button>
                    <button @click="$store.dispatch('mission/createMission', missionCreate)" class="parent create-btn">미션 등록</button>
                </div>
                
            </div>
        </div>
    </div>
    
</template>


<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const router = useRouter();
const route = useRoute();

const categories = reactive([
    {name: '학습' , img:'/img/icon-pencil.png', index : "0"}
    ,{name: '취미' , img:'/img/icon-bicycle.png', index : "1"}
    ,{name: '집안일' , img:'/img/icon-cleaner.png', index : "2"}
    ,{name: '생활습관' , img:'/img/icon-clock.png', index : "3"}
    ,{name: '기타' , img:'/img/icon-checklist7.png', index : "4"}
]);

const radioCategories = ref('');

const clickCategory = (e, index) => {
    // const btnLabel = document.querySelectorAll('.ms-category-btn');
    // if(btnLabel.classList.contains('category-btn-chk')) {
    //     btnLabel.classList.remove('category-btn-chk');
    // }
    // document.querySelector('#study-'+index).checked = true;
    // e.target.classList.add('category-btn-chk');
    
}

const checkedcategory = ref(true);

// const checkedcategoryIndex = ref(null);

// function checkedcategory(index) {
//     checkedcategoryIndex.value = index;
// }
        
const today = ref(new Date().toISOString().slice(0, 10));

const nextDay = ref(new Date().toISOString().slice(0, 10)); 

const missionCreate = reactive({
    title: '',
    start_at: today, // 오늘 날짜
    end_at: nextDay,
    category: radioCategories,
    content: '',
    amount: '',
    child_id: route.params.child_id,
});
// 자녀 id 파라미터 세팅
const childId = computed(() => store.state.mission.childId);

// 미션 리스트의 데이터 사용
// const missionList = computed(() => store.state.mission.missionList);

// 자녀 미션 리스트 페이지로 이동
const getChildId = (child_id) => {
    store.dispatch('mission/missionList', {child_id: route.params.id, page: 1});
    router.push('/parent/mission/list/' + child_id);
}

// 자녀 미션 등록 후 미션 상세 페에지로 이동
// const createMission = (mission_id) => {

//     router.replace('/parent/mission/detail/' + newMission.mission_id);
// }

// 데이터를 가져오기 위한 마운트
// onMounted(() => {
//     store.dispatch('mission/createMission', missionCreate);
//     store.dispatch('mission/missionList', {child_id: route.params.id, page: 1});
// });


</script>


<style scoped>
@import url('../../../../css/boardCommon.css');
@import url('../../../../css/category.css');

/* 선택된 카테고리 색깔 */
.checked-category-btn {
    background-color: #A2CAAC;
}

#ms-title {
    width: 500px;
}

.deco{
    border-color: #A2CAAC;
    border-width: 3px;
    border-style: solid;
}


/* 하단 버튼 영역 */
.bottom-btn{
    display: flex;
    gap: 760px;
    margin-top: 20px;
    margin-right: 150px;
    margin-left: 500px;

}

.category-name {
    margin-top: 10px;
    font-size: 1.3rem;
}

/* 취소/미션등록 버튼 */
.create-btn {
    width: 120px;
    height: 50px;
    font-size: 1.5rem;
    border: none;
    margin-bottom: 30px;
    cursor: pointer;
    color: #FFFFFF;
}

.cancel {
    color: #ACACAC;
    background-color: #FFFFFF;
    border: 1px solid #ACACAC;
}



/* 부모쪽 버튼 색깔 */
.parent {
    background-color: #A2CAAC ;
}



</style>