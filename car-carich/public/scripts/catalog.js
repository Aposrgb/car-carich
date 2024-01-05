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
const COLOR_TYPE = 1, BUTTON_TYPE = 2
let selectableClasses = {
    btn: ["btn-selectable", "btn-selected"],
    color: ["color-selectable", "color-selected"]
};

function elementSelectable(el, type) {
    let selectableClass = []
    if (type === COLOR_TYPE) {
        selectableClass = selectableClasses.color
    } else if (type === BUTTON_TYPE) {
        selectableClass = selectableClasses.btn
    }
    el.classList.toggle(selectableClass[0])
    el.classList.toggle(selectableClass[1])
}