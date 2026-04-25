document.querySelector("form").addEventListener("submit", function (e) {
    const errors = {};

    const title = document.getElementById("title").value.trim();
    const author = document.getElementById("author").value.trim();
    const publisher = document.getElementById("publisher_id").value;
    const year = document.getElementById("year").value.trim();
    const isbn = document.getElementById("isbn").value.trim();
    const description = document.getElementById("description").value.trim();
    const fileInput = document.getElementById("cover_filename");
    const formats = document.querySelectorAll("input[name='formats[]']:checked");

    // Reset old errors
    document.querySelectorAll("p").forEach(p => p.textContent = "");

    // Title
    if (!title || title.length < 1 || title.length > 255) {
        errors.title = "Title must be between 1 and 255 characters.";
    }

    // Author
    if (!author || author.length < 1 || author.length > 255) {
        errors.author = "Author must be between 1 and 255 characters.";
    }

    // Publisher
    if (!publisher || isNaN(publisher)) {
        errors.publisher_id = "Invalid publisher selected.";
    }

    // Year
    if (!year) {
        errors.year = "Year is required.";
    }

    // ISBN
    if (!isbn || isbn.length > 20) {
        errors.isbn = "ISBN is required and must be under 20 characters.";
    }

    // Description
    if (!description || description.length < 10 || description.length > 5000) {
        errors.description = "Description must be between 10 and 5000 characters.";
    }

    // Formats
    if (formats.length === 0) {
        errors.formats = "Select at least one format.";
    }

    // File validation
    const file = fileInput.files[0];
    if (!file) {
        errors.cover_filename = "Image is required.";
    } else {
        const allowedTypes = ["image/jpeg", "image/png"];
        if (!allowedTypes.includes(file.type)) {
            errors.cover_filename = "Only JPG and PNG images are allowed.";
        }

        const maxSize = 5 * 1024 * 1024; // 5MB
        if (file.size > maxSize) {
            errors.cover_filename = "File size must be under 5MB.";
        }
    }

    // If errors exist, prevent submit + display
    if (Object.keys(errors).length > 0) {
        e.preventDefault();

        for (let field in errors) {
            const errorElement = document.querySelector(`[name="${field}"]`)?.parentElement?.querySelector("p");

            if (field === "formats") {
                document.querySelector("p:has(+ label input[name='formats[]'])").textContent = errors[field];
            } else if (errorElement) {
                errorElement.textContent = errors[field];
            }
        }
    }
});