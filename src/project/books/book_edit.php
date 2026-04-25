<?php
require_once 'php/lib/config.php';
require_once 'php/lib/session.php';
require_once 'php/lib/forms.php';
require_once 'php/lib/utils.php';

startSession();

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        throw new Exception('Invalid request method.');
    }
    if (!array_key_exists('id', $_GET)) {
        throw new Exception('No book ID provided.');
    }
    $id = $_GET['id'];

    $book = Book::findById($id);
    if ($book === null) {
        throw new Exception("Book not found.");
    }

    $publishers = Publisher::findAll();
    $formats = FormatName::findAll();
    $bookFormats = Format::findByBookId($book->id);

    $selectedFormats = array_map(
    fn($f) => $f->format_id,
    $bookFormats
);

}
catch (PDOException $e) {
    setFlashMessage('error', 'Error: ' . $e->getMessage());
    redirect('/index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include 'php/inc/head_content.php'; ?>
        <title>Edit Book</title>
    </head>
    <body>
        <div class="container">
            <div class="width-12">
                <?php require 'php/inc/flash_message.php'; ?>
            </div>
            <div class="width-12">
                <h1>Edit Book</h1>
            </div>
            <div class="width-12">
                <form action="book_update.php" method="POST" enctype="multipart/form-data">
                    <div class="input">
                        <input type="hidden" name="id" value="<?= h($book->id) ?>">
                    </div>
                    <div class="input">
                        <label class="special" for="title">Title:</label>
                        <div>
                            <input type="text" id="title" name="title" value="<?= old('title', $book->title) ?>" required>
                            <p><?= error('title') ?></p>
                        </div>
                    </div>

                    <div class="input">
                        <label class="special" for="author">Author:</label>
                        <div>
                            <input type="text" id="author" name="author" value="<?= old('author', $book->author) ?>" required>
                            <p><?= error('author') ?></p>
                        </div>
                    </div>

                    <div class="input">
                        <label class="special" for="publisher_id">Publisher:</label>
                        <div>
                            <select id="publisher_id" name="publisher_id" required>
                                <?php foreach ($publishers as $publisher) { ?>
                                    <option value="<?= h($publisher->id) ?>" <?= chosen('publisher_id', $publisher->id) ? "selected" : "" ?>>
                                        <?= h($publisher->name) ?>
                                    </option>
                                <?php } ?>
                            </select>
                            <p><?= error('publisher_id') ?></p>
                        </div>
                    </div>

                    <div class="input">
                        <label class="special" for="year">Release Year:</label>
                        <div>
                            <input type="int" id="year" name="year" value="<?= old('year', $book->year) ?>" required>
                            <p><?= error('year') ?></p>
                        </div>
                    </div>

                    <div class="input">
                        <label class="special" for="isbn">ISBN:</label>
                        <div>
                            <input type="text" id="isbn" name="isbn" value="<?= old('isbn', $book->isbn) ?>" required>
                            <p><?= error('isbn') ?></p>
                        </div>
                    </div>

                    <div class="input">
                        <label class="special" for="description">Description:</label>
                        <div>
                            <textarea id="description" name="description" required><?= old('description', $book->description) ?></textarea>
                            <p><?= error('description') ?></p>
                        </div>
                    </div>

                    <?php foreach($formats as $f){ ?>
                        <label class="special">
                            <?= h($f->name) ?>
                            <input type="checkbox" name="formats[]" value="<?= h($f->id) ?>"
                            <?= in_array($f->id, $selectedFormats) ? 'checked' : '' ?>>
                        </label>
                    <?php } ?> 
                    <p><?= error('formats') ?></p>

                    <div><img src="images/<?= $book->cover_filename ?>" /></div>
                    <div class="input">
                        <label class="special" for="cover_filename">cover:</label>
                        <div>
                            <input type="file" id="cover_filename" name="cover_filename" accept="image/*">
                            <p><?= error('cover_filename') ?></p>
                        </div>
                    </div>
                    <div class="input">
                        <button class="button" type="submit">Update Book</button>
                        <div class="button"><a href="index.php">Cancel</a></div>
                    </div>
                </form>
            </div>
        </div>

        <script src="js/Validator.js"></script>

    </body>
</html>
<?php
// Clear form data after displaying
clearFormData();
// Clear errors after displaying
clearFormErrors();
?>