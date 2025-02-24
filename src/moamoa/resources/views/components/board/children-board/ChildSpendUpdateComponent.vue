<template>
    <div class="main-container">
        <div class="board-container">
            <div class="c-route"> 홈  > 지출 > 수정</div>
            <div class="content-list">
                <div class="c-content">
                    <p class="c-list-title">제목</p>
                    <input type="text" class="ms-title deco" id="ms-title" maxlength="10" v-model="transactionDetail.title" autofocus>
                </div>
                <div class="c-content">
                    <p class="c-list-title">날짜</p>
                    <div class="date">
                        <div class="end-date">
                            <input type="date" class="ms-date" id="ms-date" min="2000-01-01" v-model="transactionDetail.transaction_date">
                        </div>
                    </div>
                </div>
                <div class="c-content-cate">
                    <p class="c-list-title">종류</p>
                    <div class="category-btn" v-for="item in categories" :key="item">
                        <input type="radio" name="category" :value="item.index" :id="'category-' + item.index" v-model="transactionDetail.category"></input>
                        <label :for="'category-' + item.index" :class="[{'checked-category-btn': item.index === transactionDetail.category}, 'ms-category-btn']">
                            <img class="ms-category-img" :src="item.img" >
                            <p class="category-name">{{ item.name }}</p>
                        </label>
                    </div>
                </div>
                <div class="c-content">
                    <p class="c-list-title">사용 내역</p>
                    <textarea v-model="transactionDetail.memo" class="ms-content deco" id="ms-content" placeholder="사용 내역을 입력하세요"></textarea>
                </div>
                <div class="c-content">
                    <p class="c-list-title">금액(원)</p>
                    <input v-model="transactionDetail.amount" type="number" class="ms-amount deco" id="ms-amount" required>
                </div>
                <div class="bottom-btn">
                    <button @click="$router.push('/child/spend/detail/${transaction_id}')" class="create-btn">수정 취소</button>
                    <button @click="updateTransaction" class="create-btn">수정 완료</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onBeforeMount, reactive } from 'vue';
import { useRoute } from 'vue-router';
import { useStore } from 'vuex';

const route = useRoute();
const store = useStore();

// 해당 날짜 가져오기
const today = new Date().toISOString().split('T')[0];

// 마운트
onBeforeMount(() => {
    const transaction_id = route.params.transaction_id;
    console.log('수정할 transaction id 획득 : ', transaction_id);
    store.dispatch('childTransaction/goUpdateTransaction', transaction_id); //mission_id 불러오기
});

// *****미션 상세 정보******
const transactionDetail = store.state.childTransaction.transactionDetail;

// 카테고리 변환
const categories = reactive([
    {name: '교통비' , img:'/img/icon-bus.png', index : 0}
    ,{name: '식비' , img:'/img/icon-fastfood.png', index : 1}
    ,{name: '쇼핑' , img:'/img/icon-shoppingbag.png', index : 2}
    ,{name: '기타' , img:'/img/Pngtreestationery_icon_3728043.png', index : 3}
]);

const updateTransaction = () => {
    store.dispatch('childTransaction/UpdateTransaction', transactionDetail);
};

</script>

<style scoped>
@import url('../../../../css/childboardCommon.css');
@import url('../../../../css/category.css');

/* 입력란 테두리 */
.deco {
    border: 3px solid #5589e996;
}

/* db에 저장된 카테고리 표시 */
.checked-category-btn {
    background-color: #5589e996;
}


.bottom-btn{
    display: flex;
    justify-content: right;
    gap: 30px;
    margin-right: 300px;
    margin-top: 30px;
}


/* 취소/미션등록 버튼 */
.create-btn {
    width: 120px;
    height: 50px;
    font-size: 1.5rem;
    border: none;
    color: #FFFFFF;
    background-color: #5589e996 ;
    margin-bottom: 30px;
    cursor: pointer;
}


.category-name {
    margin-top: 10px;
    font-size: 1.3rem;
}

</style>