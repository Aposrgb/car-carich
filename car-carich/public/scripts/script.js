
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
    let popCarsDiv = document.querySelector(".pop-cars");
    let carsCardsDiv = document.querySelector(".cars-cards");
    for (let car of cars) {
        popCarsDiv.innerHTML += car.createHtmlForBigSlider();
        carsCardsDiv.innerHTML += car.createHtmlForCarCard({});
    }

    let header = document.querySelector(".header-block"),
        headerHidden = document.querySelector(".header-hidden");
    window.onscroll = () => {
        if (window.scrollY >= header.offsetHeight) {
            headerHidden.classList.remove("d-none");
        } else {
            headerHidden.classList.add("d-none");
        }
    };
});
