<template>
    <div class="main-container">
        <div class="board-container">
            <div class="c-route"> 홈  > 미션 > 상세</div>
            <div class="c-content-list">
                <div class="c-content" v-if="missionDetail">
                    <p class="c-list-title">제목</p>
                    <p class="ms-title">{{ missionDetail.title }}</p>
                </div>
                <div class="c-content">
                    <p class="c-list-title">날짜</p>
                    <div class="c-date-detail">
                        <div class="c-date">
                            <div class="c-start-date">
                                <p class="c-date-title">시작일</p>
                                <span class="c-ms-date span-div">{{ missionDetail.start_at }}</span>
                            </div>
                            <div class="c-end-date">
                                <p class="c-date-title">종료일</p>
                                <span class="c-ms-date span-div">{{ missionDetail.end_at }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="c-content-cate">
                    <p class="c-list-title">종류</p>
                    <div class="category-btn"  v-for="item in categories" :key="item" :class="{'categorybtn-green' : item.index === Number(category) }">
                        <img class="ms-category-img" :src="item.img" >
                        <p class="category-name">{{ item.name }}</p>
                    </div>
                </div>
                <div class="c-content">
                    <p class="c-list-title">내용</p>
                    <div class="ms-content">{{ missionDetail.content}}</div>
                </div>
                <div class="c-content">
                    <p class="c-list-title">금액</p>
                    <p class="c-ms-amount">{{ Number(missionDetail.amount).toLocaleString()}}원</p>
                </div>
                <div class="c-bottom-btn">
                    <div class="c-left">
                        <button @click="goBack" class="c-back">뒤로가기</button>
                    </div>
                    <div class="c-right">
                        <button 
                            v-if="missionDetail.status === '0'" 
                            @click="completeBtn" 
                            class="c-ms-complete">
                            미션 완료
                        </button>
                        <button 
                            @click="delOpenModal('mission', mission_id)" 
                            :class="['c-ms-del', { 'c-ms-del-no-complete': missionDetail.status !== '0' }]"
                        >
                            미션 삭제
                        </button>
                        <button 
                            @click="goUpdate(missionDetail.mission_id)" 
                            class="c-ms-up">
                            미션 수정
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- ********삭제 모달********* -->
<!-- 삭제 모달 모듈 작업 중 -->
<!-- <div class="delModal child-theme" v-if="delModal">
    <ModalComponent :type="modalType" @click="delCloseModal" />
</div> -->

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
            <button @click="delCloseModal" class="modal-close">취소</button>
            <button @click="deleteMission(missionDetail.mission_id)" class="modal-del">삭제</button>
        </div>
    </div>
</div>

    <!-- 완료 모달 -->
    <div v-show="completeModalFlg" class="base-modal-overlay">
        <div class="base-modal-box">
            <div class="base-modal-content">
                해당 미션을 완료 처리 하였습니다
            </div>

            <div class="base-modal-btn">
                <button @click="closeCompleteModal" type="button" class="base-modal-cancel">닫기</button>
            </div>
        </div>
    </div>


</template>

<script setup>
import { ref, computed, onMounted, reactive } from 'vue';
import { useStore } from 'vuex';
import { useRoute, useRouter } from 'vue-router';
import ModalComponent from '../../modal/ModalComponent.vue';

const store = useStore();
const router = useRouter();
const route = useRoute();

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
const goBack = () => {
    store.dispatch('childMission/setChildMissionList', {child_id: route.params.id, page: 1});
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

// ********삭제 모달창********** 
const delModal = ref(false); // 처음 모달창 닫겨 있는 상태
const modalType = ref(''); // 모달 타입 : transaction 인지 mission 인지
// 모달창 열기
const delOpenModal = (type, mission_id) => {
    modalType.value = type;
    console.log('모달창에서 자녀 mission id 불러오기 ', modalType);
    if(type === 'mission') {
        store.dispatch('childMission/showMissionDetail', mission_id);
    }
    delModal.value = true;
}

const delCloseModal = () => {
    delModal.value = false;
}


    // 완료용 모달 스위칭
    const completeModalFlg = ref(false);
    
    // 완료 모달 열기 닫기
    const openCompleteModal = () => {
        completeModalFlg.value = true;
    };
    const closeCompleteModal = () => {
        completeModalFlg.value = false;
    };

    // 미션 완료
    const completeBtn = async () => {
        // 완료 처리 우선 수행
        await store.dispatch('childMission/completeChildMission', missionDetail.value);

        // 모달 띄우고
        openCompleteModal();
        
        setTimeout(() => {
            // 리스트 업데이트후
            store.dispatch('childMission/setChildMissionList', {child_id: missionDetail.value.child_id, page: 1});
            
            // 리스트로 이동
            router.replace('/child/mission/list');
        }, 1000);
    };



</script>

<style scoped>
@import url('../../../../css/childboardCommon.css');

/* 미션(지출) 제목 입력란 *

/* 미션(지출) 종류 카테고리 */
.category-btn {
    width: 80px;
    height: 80px;   
}

/* db에 저장된 카테고리 표시 */
.categorybtn-green {
    background-color: #5589e996;
}

.category-name {
    margin-top: 20px;
    font-size: 1.3rem;
}


.c-ms-date{
    margin-left: 25px;
}


.c-bottom-btn{
    display: flex;
    margin-top: 20px;
    button {
        height: 50px;
        width: 120px;
    }
}

.c-back {
    margin-left: 300px;
    color: #ACACAC;
    background-color: #FFFFFF;
    font-size: 1.3rem;
    border: 1px solid #ACACAC;
    padding: 5px;
    width: 120px;
    height: 50px;
    border-radius: 0px;
    cursor: pointer;
}

.c-right {
    display: flex;
    gap: 30px;
    
}
.c-ms-del {
    background-color: #5589e996;
    font-size: 1.3rem;
    border: none;
    color: white;
    cursor: pointer;
}

/* 미션 완료 버튼이 없는 경우에 적용할 추가 스타일 */
.c-ms-del-no-complete {
    margin-left: 610px;
}

.c-ms-up {
    background-color: #5589e996;
    font-size: 1.3rem;
    border: none;
    color: white;
    cursor: pointer;
}

.c-ms-complete {
    margin-left: 460px;
    background-color: #5589e996;
    font-size: 1.3rem;
    border: none;
    color: white;
    cursor: pointer;
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
    /* border: 3px solid #5589e996; */
    border-radius: 50px;
    padding: 3px;
}



/* 버튼 */
.modal-close {
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

.child-theme {
    background-color: #5589e996;
}

  /* 버튼 손모양 */
  button {
    cursor: pointer;
  }

  /* 뒷배경 */
  .base-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
  }

  /* 모달 박스 */
  .base-modal-box {
    background-color: #fff;
    padding: 25px;
    border-radius: 10px;
    width: 430px;
    height: 330px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    border: 3px solid #A2CAAC;
  }

    /* 각 넓이 설정 */
    .base-modal-content, .base-modal-btn {
    width: 100%;
    }

    .base-modal-content {
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    font-size: 1.4rem;
    height: 100%;
    }

  /* 버튼 중앙 정렬 */
  .base-modal-btn {
    display: flex;
    justify-content: center;
    align-items: center;
    column-gap: 75px;
  }

  /* 각 버튼 설정 */
  .base-modal-btn > button {
    padding: 12px 40px;
    border: none;
    border-radius: 5px;
    font-size: 1.1rem;
  }

  /* 확인 버튼 */
  .base-modal-submit {
    background-color: #A2CAAC;
    color: #fff;
  }

  /* 취소 버튼 */
  .base-modal-cancel {
    background-color: #F3F3F3;
  }
</style>