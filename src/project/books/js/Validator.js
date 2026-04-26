document.querySelector("form").addEventListener("submit", function (e) {
    const errors = {};

    const title = document.getElementById("title").value.trim();
    const author = document.getElementById("author").value.trim();
    const publisher = document.getElementById("publisher_id").value;
    const year = document.getElementById("year").value.trim();
    const isbn = document.getElementById("isbn").value.trim();
    const description = document.getElementById("description").value.trim();
    const fileInput = document.getElementById("cover_filename");

    if (!title || title.length < 1 || title.length > 255) {
        errors.title = "Title must be between 1 and 255 characters.";
    }

    if (!author || author.length < 1 || author.length > 255) {
        errors.author = "Author must be between 1 and 255 characters.";
    }

    if (!publisher || isNaN(publisher)) {
        errors.publisher_id = "Invalid publisher selected.";
    }

    if (!year) {
        errors.year = "Year is required.";
    }

    if (!isbn || isbn.length > 20) {
        errors.isbn = "ISBN is required and must be under 20 characters.";
    }

    if (!description || description.length < 10 || description.length > 5000) {
        errors.description = "Description must be between 10 and 5000 characters.";
    }

    if (Object.keys(errors).length > 0) {
        e.preventDefault();

        for (let field in errors) {
            const errorElement = document.querySelector(`[name="${field}"]`)?.parentElement?.querySelector("p");
            
            errorElement.textContent = errors[field];
        }
    }
});