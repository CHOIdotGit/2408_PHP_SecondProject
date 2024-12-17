<template>
    <div class="main-container">
        <div class="create-container">
            <div class="content-list">
                <div class="content">
                    <p class="title">미션 제목</p>
                    <input v-model="missionCreate.title" type="text" class="ms-title" id="ms-title" maxlength="10" required autofocus>
                    <div class="date">
                        <input v-model="missionCreate.start_at" type="date" class="ms-date" id="ms-date" min="2000-01-01"  required>
                        <span>⁓</span>
                        <input v-model="missionCreate.end_at" type="date" class="ms-date" id="ms-date" min="2000-01-01"  required>
                        <!-- value="today" -->
                    </div>
                </div>
                <div class="content" >
                    <p class="title">미션 종류</p>
                    <!-- 실패... -->
                    <!-- 학습 (category = 0)-->
                    <!-- <div class="category-btn" v-for="(category, index) in categories" :key="index" >
                        <input type="radio" :id="'category-' + index" :value="index" v-model="checkedcategory" class="category-input"></input>
                        <label :for="'category-' + ind class="ms-category-btn"ex" @click="checkedcategory(index)" :class="{ active: checkedcategoryIndex === index}" class="category-label">
                            <img class="ms-category-img" :src="category.image" alt="." />
                        </label>
                    </div> -->

                    <!-- 학습(category = 0) -->
                    <div class="category-btn">
                        <input type="radio" name="category" id="study" v-bind:value="0" @click="checked"></input>
                        <label for="study" class="ms-category-btn">
                        <img class="ms-category-img" src="/img/icon-pencil.png" alt=".">
                        <p>학습</p>
                        </label>
                    </div>
                    <!-- 취미 (category = 1) -->
                    <div class="category-btn">
                        <input type="radio" name="category" id="habit" v-bind:value="1" @click="checked"></input>
                        <label for="habit" class="ms-category-btn">
                            <img class="ms-category-img" src="/img/icon-bicycle.png" alt=".">
                            <p>취미</p>
                        </label>
                    </div>
                    <!-- 집안일(category = 2) -->
                    <div class="category-btn">
                        <input type="radio" name="category" id="housework" v-bind:value="2" ></input>
                        <label for="housework" class="ms-category-btn">
                            <img class="ms-category-img" src="/img/icon-cleaner.png" alt=".">
                            <p>집안일</p>  
                        </label>
                    </div>
                    <!-- 생활습관(category = 3) -->
                    <div class="category-btn">
                        <input type="radio" name="category" id="lifestyle" v-bind:value="3" ></input>
                        <label for="lifestyle" class="ms-category-btn">
                            <img class="ms-category-img" src="/img/icon-clock.png" alt=".">
                            <p>생활습관</p>
                        </label>
                    </div>
                    <!-- 기타(category = 4) -->
                    <div class="category-btn">
                        <input type="radio" name="category" id="etc" v-bind:value="4" ></input>
                        <label for="etc" class="ms-category-btn">
                            <img class="ms-category-img" src="/img/icon-checklist7.png" alt=".">
                            <p>기타</p>
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
                    <input v-model="missionCreate.amount" type="num" class="ms-amount" id="ms-amount" min="0" maxlength="7" required>
                </div>
                <div class="bottom-btn">
                    <button @click="$router.push('/parents/mission/list')" class="create-btn">취소</button>
                    <button @click="$store.dispatch('/parents/mission/list', missionCreate)" class="create-btn">등록</button>
                </div>
            </div>
        </div>
    
</template>


<script setup>
import { reactive, ref } from 'vue';


// const categories =  [
//     { name: '학습', image: '/img/icon-pencil.png' },
//     { name: '취미', image: '/img/icon-bicycle.png' },
//     { name: '집안일', image: '/img/icon-cleaner.png' },
//     { name: '생활습관', image: '/img/icon-clock.png' },
//     { name: '기타', image: '/img/icon-checklist7.png' },
// ],

// const checkedcategoryIndex = ref(null);

// function checkedcategory(index) {
//     checkedcategoryIndex.value = index;
// }
        
const today = ref(new Date().toISOString().slice(0, 10));

const missionCreate = reactive({
    title: '',
    start_at: today, // 오늘 날짜
    end_at: '',
    category: '',
    content: '',
    amount: ''
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
.category-btn > input[type=radio]:checked + label {
    background-color: #A2CAAC;
    border-radius: 50px;
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