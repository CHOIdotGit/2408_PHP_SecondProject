<template>
    <div class="cal-container">
        <div class="nav-section">
            <div class="select-kids">
                <img class=selected-kid src="/img/icon-boy-1.png" alt="" width="90px" height="90px">
                <img class="not-selected" src="/img/icon-boy-5.png" alt="" width="90px" height="90px">
                <img class="not-selected" src="/img/icon-girl-1.png" alt="" width="90px" height="90px">
                <img class="not-selected" src="/img/icon-girl-3.png" alt="" width="90px" height="90px">
            </div>
            <div class="blank"></div>
            <div  class="btn-box">
                <button class="content-btn">내역</button>
                <button class="data-btn">통계</button>
            </div>
            <div class="money-history">
                <div class="money-title">
                    <li>식비</li>
                    <li>교통비</li>
                    <li>쇼핑</li>
                    <li>교통비</li>
                    <li>보너스</li>
                    <li>미션</li>
                    <li>총합</li>
                </div>
                <div class="money-cost">
                    <ul>
                        <li class="cost">7,000원</li>
                        <li class="cost">15,000원</li>
                        <li class="cost">12,000</li>
                        <li class="cost">8,000원</li>
                        <li class="cost">15,000원</li>
                        <li class="cost">40,000원</li>
                        <li class="cost">6,000원</li>
                    </ul>
                </div>
            </div>
            </div>
        </div>
        </div>
        <div class="sec-container">
            <div class="calendar">
                <div class="sec-header">
                    <pre class="left"> ◀ </pre>
                    <div class="header-display">
                        <p class="display"></p>
                    </div>
                    <pre class="right"> ▶ </pre>
                </div>
                <div class="week">
                    <div>월</div>
                    <div>화</div>
                    <div>수</div>
                    <div>목</div>
                    <div>금</div>
                    <div>토</div>
                    <div>일</div>
                </div>
                <div class="days">
                    <!-- days will be filled here -->
                    
                </div>
            
            <div class="display-selected">
                <p class="selected">
                    
                </p>
            </div>
        </div>
    </div>
</div>
</template>
<script setup>


</script>
<style>
   @font-face {
    font-family: 'Ownglyph_okticon-Bd';
    src: url('https://fastly.jsdelivr.net/gh/projectnoonnu/2408@1.0/Ownglyph_okticon-Bd.woff2') format('woff2');
    font-weight: 700;
    font-style: normal;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Ownglyph_okticon-Bd';
}

.main{
    width: 93%;
}

.container {
    width: 93%;
    display: flex;

    gap: 30px;
    margin-top: 20px;
    margin-left: 50px;
}

.nav-section {
    background-color: white;
    height: 720px;
}

.selected-kid {
    border: 3px solid #ffBDD0;
    background-color: white;
    border-radius: 50px;
    padding: 3px;
}

.select-kids {
    margin-top: 30px;
    margin-left: 10px;
    display: flex;
    gap: 20px;
}
.not-selected {
    border: 3px solid #cacaca;
    background-color: #cacaca;
    border-radius: 50px;
    padding: 3px;
}

.blank {
    height: 40px;
}

.btn-box {
    display: flex;
    /* justify-content: space-between; */

}

.content-btn{
    width: 140px;
    height: 50px;
    margin-left: 20px;
    font-size: 2rem;
    border: none;
    background-color: #cacaca;
}

.data-btn  {
    width: 140px;
    height: 50px;
    font-size: 2rem;
    border: none;
    background-color: #737373;
}

li {
    height: 50px;
    font-size: 1.5rem;
    margin-left: 30px;
    line-height: 50px;
    margin-top: 20px;
}


.money-history {
    width: 400px;
    height: 490px;
    margin-left: 20px;
    margin-right: 20px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    font-size: 1.5rem;
    line-height: 50px;
    background-color: #cacaca;
}


.cost  {
    list-style-type: none;
}

/* .calendar {
    background-color: white;
    width: 1300px;
} */

:root {
    --white: white;
    --main: #A4D8E1;
    --accent: #cacaca;
    --accent-2: #ffbdd0;
}

.sec-container {
    display: flex;
    height: 500px;
    justify-content: center;
    align-items: center;
    background: var(--main);
}

.calendar {
    background: var(--white);
    border-radius: 25px;
    padding: 0 1em;
}

.sec-header {
    display: flex;
    justify-content: space-between;
    padding: 10px ;
}

.header-display {
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 1.5rem;
    color: var(--accent-2);
    word-spacing: 0.5rem;
}

pre {
    padding: 10px;
    font-size: 1.2rem;
    color: var(--accent-2);
    cursor: pointer;
}

.days, .week {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    padding: 0 20px;
    justify-content: space-between;
    div{
        display: flex;
        justify-content: center;
        align-items: center;
        width: 3rem;
        height: 3rem;
        opacity: 0.5;
    }
    
}
.days div{
    
    border-radius: 50%;
    opacity: 1;
    &:hover {
        background: var(--accent-2);
        color: #737373;
        cursor: pointer;
        
    }
}

.display-selected {
    text-align: center;
    padding: 20px;
    margin-top: 10px;
}

.current-date {
    background: var(--accent);
    color: var(--white);
} 
</style>