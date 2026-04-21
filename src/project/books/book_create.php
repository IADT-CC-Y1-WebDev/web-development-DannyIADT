<?php
require_once 'php/lib/config.php';
require_once 'php/lib/session.php';
require_once 'php/lib/forms.php';
require_once 'php/lib/utils.php';

startSession();

try {
    $publishers = Publisher::findAll();
    $formats = FormatName::findAll();
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
        <title>View Book</title>
    </head>
    <body>
        <div class="container">
            <div class="width-12">
                <?php require 'php/inc/flash_message.php'; ?>
            </div>
            <div class="width-12">
                <h1>Create Book</h1>
            </div>
            <div class="width-12">
                <form action="book_store.php" method="POST" enctype="multipart/form-data">
                    <div class="input">
                        <label class="special" for="title">Title:</label>
                        <div>
                            <input type="text" id="title" name="title" value="<?= old('title') ?>">
                            <p><?= error('title') ?></p>
                        </div>
                    </div>

                    <div class="input">
                        <label class="special" for="author">Author:</label>
                        <div>
                            <input type="text" id="author" name="author" value="<?= old('author') ?>">
                            <p><?= error('author') ?></p>
                        </div>
                    </div>

                    <div class="input">
                        <label class="special" for="publisher_id">Publisher:</label>
                        <div>
                            <select id="publisher_id" name="publisher_id">
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
                            <input type="number" id="year" name="year" value="<?= old('year') ?>">
                            <p><?= error('year') ?></p>
                        </div>
                    </div>

                    <div class="input">
                        <label class="special" for="isbn">ISBN:</label>
                        <div>
                            <input type="text" id="isbn" name="isbn" value="<?= old('isbn') ?>">
                            <p><?= error('isbn') ?></p>
                        </div>
                    </div>

                    <div class="input">
                        <label class="special" for="description">Description:</label>
                        <div>
                            <textarea id="description" name="description"><?= old('description') ?></textarea>
                            <p><?= error('description') ?></p>
                        </div>
                    </div>

                    <?php foreach($formats as $f){ ?>
                        <label class="special">
                            <?= $f->name ?>
                            <input type="checkbox" name="formats[]" value="<?= h($f->id) ?>">
                        </label>
                    <?php } ?> 
                    <p><?= error('formats') ?></p>

                    <div class="input">
                        <label class="special" for="cover_filename">Image (required):</label>
                        <div>
                            <input type="file" id="cover_filename" name="cover_filename" accept="image/*">
                            <p><?= error('cover_filename') ?></p>
                        </div>
                    </div>
                    <div class="input">
                        <button  class="button" type="submit">Store Book</button>
                        <div class="button"><a href="index.php">Cancel</a></div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
<?php
// Clear form data after displaying
clearFormData();
// Clear errors after displaying
clearFormErrors();
?>