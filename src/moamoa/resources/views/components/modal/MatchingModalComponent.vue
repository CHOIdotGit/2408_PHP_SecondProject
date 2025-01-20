<template>
  <div v-show="modalFlg" class="modal-overlay">
    <div class="modal-box">
      <div class="data-info">
        <div class="btn-group">
          <span>
            <div class="profile-info">
              <div class="icon-img">
                <!-- <img src="/img/profile-icon/icon-girl-5.png"> -->
                <img :src="parent.profile">
              </div>
            </div>
            <!-- <span class="color-green">OOO</span> 님이 맞습니까? -->
            <span class="color-green">{{ parent.name }}</span> {{ props.message }}
          </span>
        </div>

        <div class="btn-group">
          <button @click="modalCancel" type="button">아닙니다</button>
          <button @click="modalConfirm" type="button">맞습니다</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';
import { useStore } from 'vuex';

  const store = useStore();

  // 매칭 부모 정보 --------------------------------------------------------------------------------------------
  const parent = computed(() => store.state.auth.matchInfo);

  // 모달 스위치 --------------------------------------------------------------------------------------------
  const modalFlg = computed(() => store.state.auth.modalFlg);

  const props = defineProps({
    sendInfo: Object,
    message: String,
    action: String,
  });

  
  const modalCancel = () => {
    store.commit('auth/setModalFlg', false);
  };

  // const emit = defineEmits(['confirm']);
  const modalConfirm = () => {
    // emit('confirm');

    props.sendInfo.parent_id = parent.value.parent_id;

    store.dispatch('auth/' + props.action, props.sendInfo);
  };
</script>

<style scoped>

  .color-green {
    color:#008000;
  }

  /* 폼 기본 설정 */
  input, button {
    outline: none; /* 아웃라인 제거 */
    border: none;
    background-color: #E8E8E8;
  }

  /* 커서 손모양으로 변경 */
  a, button {
    cursor: pointer;
  }

  /* 기본 버튼 호버 */
  button:hover {
    background-color: #737373;
    color: #fff;
  }

  /* 전체 암색 음영 */
  .modal-overlay {
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
  .modal-box {
    background-color: #fff;
    width: 710px;
    max-width: 38vw;
    height: 500px;
    flex-direction: column;
    padding: 10px;
    margin: 0 auto;
  }

  /* 텍스트 문단 */
  .data-info {
    font-size: 3rem;
    height: 100%;
    text-align: center;
    display: flex;
    flex-direction: column;
    justify-content: center;
    max-width: 80vw;
  }

  /* 아이콘 문단 */
  .profile-info {
    display: inline-block;
    background-color: #8A9FF6;
    border-radius: 50%;
    width: 90px;
    height: 90px;
    margin-right: 10px;
    vertical-align: bottom;
  }

  /* 아이콘 크기 */
  .icon-img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    overflow: hidden;
    margin: 5px auto;
  }

  /* 이미지 크기 */
  .icon-img > img {
    width: 100%;
    height: 100%;
  }

  .btn-group {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 40px;
  }

  .btn-group button {
    background-color: #E8E8E8;
    padding: 15px 50px;
    margin: 30px;
    font-size: 2.3rem;
    max-width: 40vw;
  }

  .btn-group button:hover {
    background-color: #737373;
    color: #fff;
  }
</style>