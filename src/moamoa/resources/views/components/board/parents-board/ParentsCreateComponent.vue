<template>
    <div class="main-container">
        <div class="create-container">
            <div class="content-list">
                <div class="content">
                    <p class="title">미션 제목</p>
                    <input v-model="missionCreate.title" type="text" class="ms-title" id="ms-title" maxlength="10" autofocus placeholder="미션 제목을 입력하세요">
                    <div class="date">
                        <input v-model="missionCreate.start_at" type="date" class="ms-date" id="ms-date" min="2000-01-01"  >
                        <span>⁓</span>
                        <input v-model="missionCreate.end_at" type="date" class="ms-date" id="ms-date" min="2000-01-01" >
                        <!-- value="today" -->
                    </div>
                </div>
                <div class="content" >
                    <p class="title">미션 종류</p>
                    <!-- 카테로리 v-for -->
                    <div class="category-btn" v-for="item in categories" :key="item">
                        <input type="radio" name="category" :value="item.index" :id="'category-' + item.index" v-model="radioCategories"></input>
                        <label :for="'category-' + item.index" :class="[{'checked-category-btn': item.index === radioCategories}, 'ms-category-btn']">
                            <img class="ms-category-img" :src="item.img" >
                            <p>{{ item.name }}</p>
                        </label>
                    </div>
                </div>
                

                </div>
                <div class="content">
                    <p class="title">미션 내용</p>
                    <textarea v-model="missionCreate.content" class="ms-content" id="ms-content" placeholder="미션 내용을 입력하세요"></textarea>
                </div>
                <div class="content">
                    <p class="title">금액(원)</p>
                    <input v-model="missionCreate.amount" type="nume" class="ms-amount" id="ms-amount" min="0" maxlength="7" placeholder="금액을 입력하세요">
                </div>
                <div class="bottom-btn">
                    <button @click="$router.replace('/parent/mission/list/${child_id}')" class="create-btn">취소</button>
                    <button @click="$store.dispatch('mission/createMission', missionCreate)" class="create-btn">등록</button>
                </div>
            </div>
        </div>
    
</template>


<script setup>
import { reactive, ref } from 'vue';
import { useRoute } from 'vue-router';

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
    height: 765px;
}

.content-list {
    /* display: grid; */
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

.ms-category-img {
    margin-top: 13px;
    width: 50px;
    height: 50px;
    background-size: contain;
    background-repeat: no-repeat;
    border: none;


}

/* 선택된 카테고리 */
/* .category-btn > input[type=radio]:checked + label {
    background-color: #A2CAAC;
    border-radius: 50px;
} */

.checked-category-btn {
    background-color: #A2CAAC;
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
    font-size: 2rem;
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

}


/* 취소/미션등록 버튼 */
.create-btn {
    width: 100px;
    height: 50px;
    font-size: 1.5rem;
    border: none;
    background-color: #A2CAAC ;
    margin-bottom: 30px;
    cursor: pointer;
}

</style>