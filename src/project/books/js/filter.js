let applyBtn = document.getElementById('apply_filters');
let clearBtn = document.getElementById('clear_filters');

let cardsContainer = document.getElementById("cards");
let cards = document.querySelectorAll('.card');

let form = document.getElementById("filters");



form.addEventListener('submit', (event) => {
    event.preventDefault();
    applyFilters();
})

clearBtn.addEventListener('click', (event) => {
    event.preventDefault();
    clearFilters();
});

function applyFilters() {
    console.log("Applying filters");
    let filters = getFilters();
    // let matches = [];
    for (let i = 0; i != cards.length; i++) {
        let card = cards[i];
        let match = cardMatches(card, filters);
        card.classList.toggle('hidden', !match);
    }
    let cardsArray = Array.from(cards);
    const sorted = sortCards(cardsArray, filters.sortBy);
    sorted.forEach(card => {
        cardsContainer.appendChild(card);
    });
}

function sortCards(cards, sortBy) {
    const list = cards.slice();
    
    list.sort((a, b) => {
        let titleA = a.dataset.title.toLowerCase();
        let titleB = b.dataset.title.toLowerCase();

        if (sortBy === "title_desc") return titleB.localeCompare(titleA);
        if (sortBy === "title_asc") return titleA.localeCompare(titleB);

        return titleA.localeCompare(titleB);
    });

    return list;
}

function cardMatches(crd, fltrs) {
    // console.log(crd.dataset.title, fltrs.titleFilter);
    let title = crd.dataset.title.toLowerCase();
    let publisher = crd.dataset.publisher;

    let matchTitle    = fltrs.titleFilter    === "" || title.includes(fltrs.titleFilter);
    let matchPublisher    = fltrs.publisherFilter    === "" || publisher === fltrs.publisherFilter;

    return matchTitle && matchPublisher;
}

function getFilters() {
    const titleEl = form.elements['title_filter'];
    const publisherEl = form.elements['publisher_filter'];

    let titleFilter = (titleEl.value || '').trim().toLowerCase();
    let publisherFilter = publisherEl.value || '';

    return {
        "titleFilter" : titleFilter,
        "publisherFilter" : publisherFilter,
        "sortBy" : "title_asc"
    };
}

function clearFilters() {
    form.reset();
    cards.forEach(card => card.classList.remove('hidden'));
    console.log("Clearing filters");
}