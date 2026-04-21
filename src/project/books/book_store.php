<?php
require_once 'php/lib/config.php';
require_once 'php/lib/session.php';
require_once 'php/lib/forms.php';
require_once 'php/lib/utils.php';

startSession();

try {
    // Initialize form data array
    $data = [];
    // Initialize errors array
    $errors = [];

    // Check if request is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method.');
    }

    // Get form data
    $data = [
        'title' => $_POST['title'] ?? null,
        'author' => $_POST['author'] ?? null,
        'publisher_id' => $_POST['publisher_id'] ?? null,
        'year' => $_POST['year'] ?? null,
        'isbn' => $_POST['isbn'] ?? null,
        'description' => $_POST['description'] ?? null,
        'cover_filename' => $_FILES['cover_filename'] ?? null,
        'formats' => $_POST['formats'] ?? [],
    ];

    // Define validation rules
    $rules = [
        'title' => 'required|notempty|min:1|max:255',
        'author' => 'required|notempty|min:1|max:255',
        'publisher_id' => 'required|integer',
        'year' => 'required|notempty',
        'isbn' => 'required|max:20',
        'description' => 'required|notempty|min:10|max:5000',
        'cover_filename' => 'required|file|image|mimes:jpg,jpeg,png|max_file_size:5242880',
    ];

    // Validate all data (including file)
    $validator = new Validator($data, $rules);

    if ($validator->fails()) {
        // Get first error for each field
        foreach ($validator->errors() as $field => $fieldErrors) {
            $errors[$field] = $fieldErrors[0];
        }

        throw new Exception('Validation failed.');
    }

    // All validation passed - now process and save
    // Verify genre exists
    $publisher = Publisher::findById($data['publisher_id']);
    if (!$publisher) {
        throw new Exception('Selected publisher does not exist.');
    }

    if (empty($data['formats'])) {
        throw new Exception("At least one format must be selected.");
    }

    // Process the uploaded image (validation already completed)
    $uploader = new ImageUpload();
    $imageFilename = $uploader->process($_FILES['cover_filename']);

    if (!$imageFilename) {
        throw new Exception('Failed to process and save the image.');
    }

    // Create new game instance
    $book = new Book();
    $book->title = $data['title'];
    $book->author = $data['author'];
    $book->publisher_id = $data['publisher_id'];
    $book->year = $data['year'];
    $book->isbn = $data['isbn'];
    $book->description = $data['description'];
    $book->cover_filename = $imageFilename;

    $book->save();

    foreach($data['formats'] as $f){
        $format = new Format();
        $format->book_id = $book->id;
        $format->format_id = $f;

        $format->save();
    }

    // Clear any old form data
    clearFormData();
    // Clear any old errors
    clearFormErrors();

    // Set success flash message
    setFlashMessage('success', 'Book stored successfully.');

    // Redirect to game details page
    redirect('book_view.php?id=' . $book->id);
}
catch (Exception $e) {


    // Error - clean up uploaded image
    if (isset($imageFilename) && $imageFilename) {
        $uploader->deleteImage($imageFilename);
    }

    // Set error flash message
    setFlashMessage('error', 'Error: ' . $e->getMessage());

    // Store form data and errors in session
    setFormData($data);
    setFormErrors($errors);

    redirect('book_create.php');
}