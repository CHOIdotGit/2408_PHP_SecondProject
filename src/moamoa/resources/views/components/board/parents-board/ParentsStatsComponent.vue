<template>
    <div class="stat-container"> 
        <div class="graph-btn">
            <button>주간</button>
            <button>월간</button>   
        </div>
        <div class="stat-section">  
            <div class="each-part">
                <div class="graph-section">
                    그래프들어갈자리
                </div>
                <div class="notice-section">
                    <p>가장 큰 지출 : 54,000   </p>
                    <p>가장 많이 쓴 카테고리 : 쇼핑</p>
                    <p>지출 총합 : 270,000</p>
                    <p>용돈 총합 : 300,00</p>
                </div>
            </div>
            <div class="each-part">
                <div class="graph-section">
                    그래프들어갈자리
                </div>
                <div class="notice-section">
                    <p>가장 큰 지출 : 54,000   </p>
                    <p>가장 많이 쓴 카테고리 : 쇼핑</p>
                    <p>지출 총합 : 270,000</p>
                    <p>용돈 총합 : 300,00</p>
                </div>
            </div>
            <div class="each-part">
                <div class="graph-section">
                    그래프들어갈자리
                </div>
                <div class="notice-section">
                    <p>가장 큰 지출 : 54,000   </p>
                    <p>가장 많이 쓴 카테고리 : 쇼핑</p>
                    <p>지출 총합 : 270,000</p>
                    <p>용돈 총합 : 300,00</p>
                </div>
            </div>
            
        </div>
    </div>
            
</template>
<script>

</script>

<style>
.stat-container {
    width: 1500px;
    height: 765px;
    margin-top: 20px;
    background-color: white;
    margin-left: 240px;
    
}

.stat-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    margin-right: 20px;
    margin-bottom: 20px;
    margin-left: 20px;
    /* width: 90%; */
    height: 90%;
    overflow: auto;
}
.each-part {
    height: 620px;
}
.graph-section {
    height: 450px;
    background-color: #a2caac;
    /* margin-top: 40px; */
}

.notice-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-template-rows: 1fr 1fr ;
    height: 150px;
    margin-top: 25px;
    p {
        font-size: 1.5rem;
        text-indent: 30px;
        line-height: 75px;
    }
}

.graph-btn {
    position: sticky;
    margin-top: 20px;
    margin-left: 20px;
    button{
        height: 50px;
        width: 100px;
        font-size: 1.2rem;
        border: none;
    }
}

</style>