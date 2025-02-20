<template>
    <div class="main-container">
        <div class="board-container">
            <div class="c-route"> 홈  > 미션 > 작성</div>
            <div class="content-list">
                <div class="c-content">
                    <p class="c-list-title">제목</p>
                    <input v-model="missionCreate.title" type="text" class="ms-title deco" id="ms-title" maxlength="10" autofocus placeholder="미션 제목을 입력하세요">
                </div>
                    <div class="c-content">
                        <p class="c-list-title">날짜</p>
                        <div class="date-detail">
                            <div class="start-date">
                                <p class="date-title">시작일</p>
                                <input v-model="missionCreate.start_at" type="date" class="c-ms-date" id="ms-date" min="2000-01-01">
                            </div>
                            <div class="end-date">
                                <p class="date-title">종료일</p>
                                <input v-model="missionCreate.end_at" type="date" class="c-ms-date" id="ms-date" min="2000-01-01">
                            </div>
                        </div>
                </div>
                <div class="c-content-cate">
                    <p class="c-list-title">종류</p>
                    <div class="category-btn" v-for="item in categories" :key="item">
                        <input type="radio" name="category" :value="item.index" :id="'category-' + item.index" v-model="radioCategories"></input>
                        <label :for="'category-' + item.index" :class="[{'checked-category-btn': item.index === radioCategories}, 'ms-category-btn']">
                            <img class="ms-category-img" :src="item.img" >
                            <p class="category-name">{{ item.name }}</p>
                        </label>
                    </div>
                </div>
                <div class="c-content">
                    <p class="c-list-title">내용</p>
                    <textarea v-model="missionCreate.content" class="ms-content deco" id="ms-content" placeholder="미션 내용을 입력하세요"></textarea>
                </div>
                <div class="c-content">
                    <p class="c-list-title">금액</p>
                    <input v-model="missionCreate.amount" type="num" class="ms-amount deco" id="ms-amount" rmin="0" maxlength="7" placeholder="금액을 입력하세요">
                    <span class="span-div">원</span>
                </div>
                <div class="c-bottom-btn">
                    <button @click="goBack" class="create-btn cancel">등록 취소</button>
                    <button @click="$store.dispatch('childMission/createMission', missionCreate)" class="create-btn">미션 등록</button>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup>
import { reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useStore } from 'vuex';

const store = useStore();
const router = useRouter();
const route = useRoute();

const categories = reactive([
    {name: '학습' , img:'/img/icon-pencil.png', index : "0"}
    ,{name: '식비' , img:'/img/icon-bicycle.png', index : "1"}
    ,{name: '집안일' , img:'/img/icon-cleaner.png', index : "2"}
    ,{name: '생활습관' , img:'/img/icon-clock.png', index : "3"}
    ,{name: '기타' , img:'/img/icon-checklist7.png', index : "4"}
]);
const radioCategories = ref('');

const today = ref(new Date().toISOString().slice(0, 10));
const nextDay = ref(new Date().toISOString().slice(0, 10));

const missionCreate = reactive({
    title: '',
    start_at: today, // 오늘 날짜
    end_at: nextDay,
    category: radioCategories,
    content: '',
    amount: '',
    // child_id: route.params.child_id,
});

// 뒤로가기
const goBack = () => {
    store.dispatch('childMission/setChildMissionList', {child_id: route.params.id, page: 1});
    router.replace('/child/mission/list');

}

</script>


<style scoped>
@import url('../../../../css/boardCommon.css');
/* 입력란 테두리 */
.deco {
    border: 3px solid #5589e996;
}


/* 미션 종류 카테고리 이미지 */
.category-btn {
    display: flex;
    flex-direction: column;
    text-align: center;
    padding-right: 30px;
    padding-bottom: 10px;
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

.ms-category-img {
    margin-top: 13px;
    width: 50px;
    height: 50px;
    background-size: contain;
    background-repeat: no-repeat;
    border: none;
}

/* 선택된 카테고리 색깔 */
.checked-category-btn {
    background-color: #5589e996;
}

/* ******************************** */


/* 취소/미션등록 버튼 */
.create-btn {
    width: 120px;
    height: 50px;
    font-size: 1.5rem;
    border: none;
    background-color: #5589e996 ;
    margin-bottom: 30px;
    cursor: pointer;
    color: #FFFFFF;
}

.c-bottom-btn{
    gap: 30px;
    margin-top: 20px;
    display: flex;
}

.category-name {
    margin-top: 10px;
    font-size: 1.3rem;
}

.cancel {
    margin-left: 1030px;
}

.c-ms-date {
    margin-left: 15px;
}

.ms-category-btn {
    margin-left: 27px;
}
</style>