let display = document.querySelector('display'),
    prevBtn = document.querySelector('.left'),
    nextBtn = document.querySelector('.right'),
    days = document.querySelector('.days'),
    selected = document.querySelector('.selected') ;

let dateToday = new Date();
// console.log(dateToday.getFullYear());   // 년
// console.log(dateToday.getMonth()+1);    // 월   
// console.log(dateToday.getDate());       // 일  
// console.log(dateToday.getHours());      // 시
// console.log(dateToday.getMinutes());    // 분
// console.log(dateToday.getSeconds());    // 초



// function displayCalendar(){
//     let year = dateToday.getFullYear();
//     let month = dateToday.getMonth()+1;

//     display.innerHTML = month+'월' +' '+year;
// }

function displayCalendar(){
    let formattedDate = dateToday.toLocaleString('ko-KR', {
        year: "numeric", 
        month: "long",
        timeZone:'Asia/Seoul'
    })

    display.innerHTML = formattedDate;
}


displayCalendar();