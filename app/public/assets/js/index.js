// Переменные
let timer, n;
// Запуска слайдера после загрузки страницы
window.onload = () => slider((n = 0));

// Функция переключения слайдов
function slider() {
    // Ширина элемента
    let width = document.querySelector("#slider .slide").offsetWidth + 20;

    // Количество элементов для переключения
    let count = document.querySelectorAll("#slider .slide").length;
    count = 3;

    // Проверки на текущий элемент
    if (n >= count) n = 0;
    else if (n < 0) n = count - 1;

    // Смещение блока
    document.querySelector("#slider .slides").style.left = `-${
        width * n
    }px`;

    // Очистка таймера
    clearTimeout(timer);

    // Рекурсия
    timer = setTimeout(() => slider(++n), 3000);
}