<template>
    <div class="d-flex">
        <div class="container">
            <div class="h-690">
                <div class="w-750 gauge-Bar">

                </div>
                <div class="w-750">

                </div>
            </div>
            <div class="">
                <div class="info">
                    <div class="d-grid">
                        <div>
                            <p class="info-title">가장 큰 지출</p>
                            <p class="info-content">5,000,000원</p>
                        </div>
                        <div>
                            <p class="info-title">가장 많이 사용한 카테고리</p>
                            <p class="info-content">교통비</p>
                        </div>
                        <div>
                            <p class="info-title">지출 총 합</p>
                            <p class="info-content">10,000,000원</p>
                        </div>
                        <div>
                            <p class="info-title">용돈 총 합</p>
                            <p class="info-content">15,000,000원</p>
                        </div>
                    </div>
                </div>
                <div class="mission-box">
                    <div class="Recently-registered-mission missions">
                        <p class="mission-title">최근 등록된 미션</p>
                        <p class="mission-content">미션1</p>
                        <p class="mission-content">미션2</p>
                        <p class="mission-content">미션3</p>
                        <p class="mission-content">미션4</p>
                        <p class="mission-content">미션5</p>
                        <p class="mission-content">미션6</p>
                    </div>
                    <div class="Recently-completed-mission missions">
                        <p class="mission-title">최근 완료한 미션</p>
                        <p class="mission-content">미션1</p>
                        <p class="mission-content">미션2</p>
                        <p class="mission-content">미션3</p>
                        <p class="mission-content">미션4</p>
                        <p class="mission-content">미션5</p>
                        <p class="mission-content">미션6</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>

</script>

<style scoped>
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
    gap: 25px;
    justify-content: start;
    align-items: center;   
}

.h-690 {
    width: 800px;
    height: 690px;
    background-color: #D9D9D9;
    margin-left: 25px;
}

.w-750 {
    width: 750px;
}

.info {
    width: 625px;
    height: 250px;
}

.d-grid{
    display: grid;
    grid-template-columns: 300px 300px;
    gap: 25px;
    height: 250px;
    border: black 3px solid;
}

.mission-box {
    display: flex;
    /* justify-content: center; */
    align-items: center;
    gap: 25px;
    margin-right: 25px;
}

.info-title {
    padding: 10px;
    font-size: 1.5rem;
}

.info-content {
    padding: 10px;
    font-size: 1.3rem;
}

.Recently-registered-mission {

}

.Recently-completed-mission {

}

.missions {
    width: 300px;
    height: 415px;
    margin-top: 25px;
    background-color: #D9D9D9;
    text-align: center;
}

.mission-title {
    padding: 25px;
    font-size: 2rem;
}
.mission-content {
    font-size: 1.5rem;
    margin-top: 15px;
}
</style>