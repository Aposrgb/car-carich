let cars = [
    new Car({
        battery: "53.6 — 68.8 кВт.ч",
        engine: "134 — 150 кВт / 182 — 204 л. с.",
        size: "4390 × 1790 × 1560 мм",
        weight: "1689 кг.",
        price: 199999,
        name: "Honda ENs1aasd",
        year: "2023 г.",
        img: "img/honda-card.svg",
        typeEngine: 'Электро',
        stamp: "Хундей",
        mileage: "542 000 км",
        country: "Кетай"
    }),
    new Car({
        battery: "53.6 — 68.8 кВт.ч",
        engine: "134 — 150 кВт / 182 — 204 л. с.",
        size: "4390 × 1790 × 1560 мм",
        weight: "1689 кг.",
        price: 3300300,
        name: "Honda ENs1",
        year: "2023 г.",
        img: "img/honda-card.svg",
        typeEngine: 'Двигатель',
        stamp: "Tesla",
        mileage: "5 000 км",
        country: "Рассия"
    }),
    new Car({
        battery: "53.6 — 68.8 кВт.ч",
        engine: "134 — 150 кВт / 182 — 204 л. с.",
        size: "4390 × 1790 × 1560 мм",
        weight: "1689 кг.",
        price: 3300300,
        name: "Honda ENs1",
        year: "2023 г.",
        img: "img/honda-card.svg",
        typeEngine: 'Двигатель',
        stamp: "Tesla",
        mileage: "5 000 км",
        country: "Рассия"
    }),
    new Car({
        battery: "53.6 — 68.8 кВт.ч",
        engine: "134 — 150 кВт / 182 — 204 л. с.",
        size: "4390 × 1790 × 1560 мм",
        weight: "1689 кг.",
        price: 3300300,
        name: "Honda ENs1",
        year: "2023 г.",
        img: "img/honda-card.svg",
        typeEngine: 'Двигатель',
        stamp: "Tesla",
        mileage: "5 000 км",
        country: "Рассия"
    }),
    new Car({
        battery: "53.6 — 68.8 кВт.ч",
        engine: "134 — 150 кВт / 182 — 204 л. с.",
        size: "4390 × 1790 × 1560 мм",
        weight: "1689 кг.",
        price: 3300300,
        name: "Honda ENs1",
        year: "2023 г.",
        img: "img/honda-card.svg",
        typeEngine: 'Двигатель',
        stamp: "Tesla",
        mileage: "5 000 км",
        country: "Рассия"
    }),
];

document.addEventListener("DOMContentLoaded", () => {
    let carsCardsDiv = document.querySelector(".cars-cards");
    for (let car of cars) {
        carsCardsDiv.innerHTML += car.createHtmlForCarCard({hasButtonDetail: true});
    }
});
const COLOR_TYPE = 1, BUTTON_TYPE = 2;
let selectableClasses = [];
selectableClasses[COLOR_TYPE] = ["color-selectable", "color-selected"];
selectableClasses[BUTTON_TYPE] = ["btn-selectable", "btn-selected"];

function elementSelectable(el, type, funcAfterSelected = () => {}) {
    let selectableClass = selectableClasses[type];
    el.classList.toggle(selectableClass[0]);
    el.classList.toggle(selectableClass[1]);
    funcAfterSelected();
}

function toggleFilters() {
    let filters = document.querySelector(".view-filters");
    let display = filters.style.display;
    if (display === "none" || display === "") {
        filters.style.display = "block";
        document.querySelector(".close-filter").classList.toggle('d-none');
        document.querySelector('.toggle-filter-img').classList.toggle('invert-img');
    } else {
        document.querySelector('.toggle-filter-img').classList.toggle('invert-img');
        document.querySelector(".close-filter").classList.toggle('d-none');
        filters.style.display = "none";
    }
}