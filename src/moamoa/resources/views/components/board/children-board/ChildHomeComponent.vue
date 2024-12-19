<template>
    <div class="d-flex">
        <div class="container">
            <div class="h-690">

            </div>
            <div class="">
                <div class="d-grid info">
                    <p>가장 큰 지출</p>
                    <p>가장 많이 사용한 카테고리</p>
                    <p>지출 총 합</p>
                    <p>용돈 총 합</p>
                </div>
                <div class="mission-box">
                    <div class="Recently-registered-mission">
                        <p>최근 등록된 미션</p>
                    </div>
                    <div class="Recently-completed-mission">
                        <p>최근 완료한 미션</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

</script>

<style>
.d-flex {
    display: flex;
    justify-content: center;
    align-items: center;
}

.container {
    margin-top: 20px;
    width: 1500px;
    height: 740px;
    background-color: white;
    display: flex;
    gap: 20px;
    justify-content: start;
    align-items: center;   
}

.h-690 {
    width: 800px;
    height: 690px;
    background-color: #D9D9D9;
    margin-left: 25px;
}

.d-grid{
    display: grid;
    grid-template-columns: 300px 300px;
    gap: 25px;
    
}

.info {
    width: 650px;
    height: 250px;
    margin-right: 25px
}

.mission-box {
    display: flex;
    /* justify-content: center; */
    align-items: center;
    gap: 25px;
    margin-right: 25px;
}

.Recently-registered-mission {
    width: 300px;
    height: 415px;
    margin-top: 25px;
    background-color: #D9D9D9;
}

.Recently-completed-mission {
    width: 300px;
    height: 415px;
    margin-top: 25px;
    background-color: #D9D9D9;
}
</style>