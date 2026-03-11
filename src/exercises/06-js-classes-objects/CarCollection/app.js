import Car from "./classes/car.js";

let bmw = new Car('BMW', '5 series', 2025, 'Green', ['heated seats', 'heated wheel']);
let skoda = new Car('Skoda', 'Octavia', 2025, 'Blue', ['skylight', 'heated wheel']);

let myCarCollection = [bmw, skoda];

myCarCollection.forEach(car => {
    console.log(car.getExtras());
});