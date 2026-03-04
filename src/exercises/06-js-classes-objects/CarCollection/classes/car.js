class Car{

    constructor(_make, _model, _year, _colour){
        this.make = _make;
        this.model = _model;
        this.year = _year;
        this.colour = _colour;
    }

    toString() {
        return `
    Make: ${this.make}
    Model: ${this.model}
    Year: ${this.year}
    Colour: ${this.colour}
    `;
    }

}

export default Car;