<template>
  <div class="m-header">
      <img src="/user-img/default8.webp" alt="" class="m-user-image">
      <p class="m-user-profile">김주연</p>
      <p class="go-update"> > </p>
  </div>
  <!-- 
    자식 선택은 스와이퍼추가 / 자식 수가 늘어나면 옆으로 넘길수 있도록
    선택한 자식은 클래스 추가로 백그라운드 컬러만 m-main-content 에 추가해주시면됩니다 
  -->
  <div class="m-child-select-section">
      <div class="m-search-section">
          <p class="m-child-select menu-sec-first"> 배현진 </p>
          <p class="m-child-select"> 김현석 </p>
          <p class="m-child-select"> 최상민 </p>
      </div>
      <img src="/img/m-search.png" alt="" class="m-search-button">
  </div>
</template>

<script setup>

</script>

<style scoped>
  .m-header {
    display: flex;
    height: 70px;
    background-color: #A2CAAC;
    width: 100vw;
    align-items: center;
    gap: 10px;
  }
  
  .m-user-image {
    height: 50px;
    width: 50px;
    border-radius: 50px;
    border: 2px white solid;
    background-color: white;
    margin-left: 20px;
  }
  
  .m-user-profile {
    font-size: 1.7rem;
    width: 100px;
  }

  .go-update {
    font-size: 2rem;
    margin-left: 180px;
  }

  .m-child-select-section {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
  }

  .m-search-section {
    max-width: 360px;
    height: 50px;
    width: 100vw;
    display: flex;
    gap: 15px;
    align-items: center;
    scrollbar-width: 10px;
    overflow: scroll;
    white-space: nowrap;
  }

  .m-child-select {
    min-width: 70px;
    height: 30px;
    border-radius: 15px;
    border: 3px solid #A2CAAC;
    text-align: center;
    line-height: 25px;
  }

  .m-search-button {
    width: 30px;
    height: 30px;
    margin-right: 10px;
  }

  .m-header {
    display: flex;
    height: 70px;
    background-color: #A2CAAC;
    width: 100vw;
    align-items: center;
    gap: 10px;
  }
  
</style>