// *****햄버거 버튼***** 
const HAMBURGER_BTN = document.querySelector('.hamburger-btn');
const HAMBURGER_MENU = document.querySelector('.dropdown');

// *****종버튼*****
const BELL_BTN = document.querySelector('.bell-btn');
const BELL_MENU = document.querySelector('.dropdown-bell');

// *****햄버거 버튼 클릭하면 메뉴 드랍*****
const HAMBURGER_TOGGLE = function() {
    HAMBURGER_MENU.classList.toggle('show');
};

HAMBURGER_BTN.addEventListener('click', function (e) {
    e.stopPropagation(); //두번 실행되는 걸 방지
    HAMBURGER_TOGGLE();
});


// 드롭다운 메뉴 닫기
document.documentElement.addEventListener('click', function () {
    if(HAMBURGER_MENU.classList.contains('show')) {
        HAMBURGER_TOGGLE();
        BELL_MENU.classList.remove('show');
    }
});


// *****종 버튼 클릭하면 메뉴 드랍*****
const BELL_TOGGLE = function() {
    BELL_MENU.classList.toggle('show');
    HAMBURGER_MENU.classList.remove('show');
};

BELL_BTN.addEventListener('click', function (e) {
    e.stopPropagation(); //두번 실행되는 걸 방지
    BELL_TOGGLE();
});

// *****드롭다운 메뉴 닫기*****
document.documentElement.addEventListener('click', function () {
    if(BELL_MENU.classList.contains('show')) {
        BELL_TOGGLE
        HAMBURGER_MENU.classList.remove('show');
    }
});
