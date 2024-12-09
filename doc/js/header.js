const DROPDOWN_BTN = document.querySelector('.hamburger-btn');
const DROPDOWN_MENU = document.querySelector('.dropdown');

const DROPDOWN_TOGGLE = function() {
    DROPDOWN_MENU.classList.toggle('show');
};

DROPDOWN_BTN.addEventListener('click', function (e) {
    //두번 실행되는 걸 방지
    e.stopPropagation();
    DROPDOWN_TOGGLE();
});


// 드롭다운 메뉴 닫기
document.documentElement.addEventListener('click', function () {
    if(DROPDOWN_MENU.classList.contains('show')) {
        DROPDOWN_TOGGLE();
    }
});