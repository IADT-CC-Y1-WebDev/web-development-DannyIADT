const myBtn = document.getElementById('myButton');
myBtn.addEventListener('click', addParagraph);

function addParagraph(){
    const input = document.getElementById('myInput');
    const value = input.value;

    const p = document.createElement('p');
    p.innerHTML = value;
    document.body.appendChild(p);
}