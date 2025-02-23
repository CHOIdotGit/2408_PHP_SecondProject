<template>
  <footer>
    <div class="m-footer-menu">
      <div class="m-menu">
          <div class="m-menu-section">
            <img src="/img/icon-home.png" alt="" class="m-home menu-sec-first">
            <p class="m-menu-title   menu-sec-first"> 홈 </p>
          </div>
          <div class="m-menu-section">
            <img src="/img/icon-piggy-bank.png" alt="" class="m-mission">
            <p class="m-menu-title"> 미션 </p>
          </div>
          <div class="m-menu-section">
            <img src="/img/icon-coin.png" alt="" class="m-expense">
            <p class="m-menu-title"> 지출 </p>
          </div>
          <div class="m-menu-section">
            <img src="/img/icon-calendar.png" alt="" class="m-calendar">
            <p class="m-menu-title"> 달력 </p>
          </div>
          <div class="m-menu-section">
            <img src="/img/icon-sack-dollar.png" alt="" class="m-bank">
            <p class="m-menu-title"> 모아통장 </p>
          </div>
          <div class="m-menu-section">
            <img src="/img/mobile-etc.png" alt="" class="m-etc">
          </div>
      </div>
    </div>
  </footer>
</template>

<script setup>

</script>

<style scoped>

  .m-menu {
    display: flex;
    gap: 15px;
    border-top: 2px solid black;
    img {
        filter: contrast(0.5);
        margin-top: 10px;
        width: 25px;
        height: 25px;
    }
  }

  .m-menu-section {
      display: flex;
      flex-direction: column;
      width: 60px;
      justify-content: center;
      align-items: center;
      gap: 3px;
  }

  .m-home {
      margin-left: 15px;
  }

  .m-menu-title {
      font-size: 0.8rem;
  }
  
  .menu-sec-first {
      margin-left: 15px;
  }
  
</style>