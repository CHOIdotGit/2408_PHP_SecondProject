<template>
<div class="error-container">
    <div class="error-icon">
        <img src="/img/icon-error.png" alt="">
        <p>404 Not Found</p>
    </div>
    <div class="error-message">
        <h1>페이지를 찾을 수 없습니다</h1>
        <p>페이지가 존재하지 않거나 사용할 수 없는 페이지 입니다.</p>
        <p>입력하신 주소가 정확한지 다시 한 번 확인해주세요. </p>
    </div>
</div>


</template>
<script setup>

</script>
<style scoped>
.error-container {
    display: flex;
    justify-content: center;
    margin-top: 200px;
}

img {
    width: 200px;
}

.error-icon {
    text-align: center;
    margin-right: 30px;
}

.error-message {
    display: flex;
    flex-direction: column;
    gap: 10px; 
    justify-content: center;
}


</style>
