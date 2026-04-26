<?php
require_once 'php/lib/config.php';
require_once 'php/lib/utils.php';

try {
    $books = Book::findAll();
    $publishers = Publisher::findAll();
    $formats = FormatName::findAll();
} 
catch (PDOException $e) {
    die("<p>PDO Exception: " . $e->getMessage() . "</p>");
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include 'php/inc/head_content.php'; ?>
        
        <title>Books</title>

    </head>
    <body>
        <div class="container">
            <div class="width-12 header">
                <?php require 'php/inc/flash_message.php'; ?>
                
                <div class="button">
                    <a href="book_create.php">Add New Book</a>
                </div>
            </div>
            <?php if (!empty($books)) { ?>
                 <div class="width-12 filters">
                    <form id="filters">
                        
                        <div>

                            <label for="title_filter">Title:</label>
                            <input type="text" id="title_filter" name="title_filter">
                        </div>

                        <div>
                            <label for="publisher_filter">Publisher:</label>
                            <select id="publisher_filter" name="publisher_filter">
                                <option value="">All Publishers</option>
                                <?php  foreach ($publishers as $publisher) { ?>
                                    <option value="<?=  h($publisher->id) ?>"><?=  h($publisher->name) ?></option>
                                <?php  } ?>
                            </select>
                        </div>

                        

                        <div>
                            <label for="format_filter">Format:</label>
                            <select id="format_filter" name="format_filter">
                                <option value="">All formats</option>
                                <?php  foreach ($formats as $format) { ?>
                                    <option value="<?=  h($format->id) ?>"><?=  h($format->name) ?></option>
                                <?php  } ?>
                            </select>
                        </div>

                             <div>
                            <label for="year_filter">Year:</label>
                            <select id="year_filter" name="year_filter">
                                <option value="">All Years</option>
                                <option value="before_2000">Before 2000</option>
                                <option value="2000_later">2000 and later</option>

                            </select>
                        </div>

                        <div>
                            <button type="submit" id="apply_filters">Apply Filters</button>
                            <button type="button" id="clear_filters">Clear Filters</button>
                        </div>
                    </form>
                </div> 
            <?php } ?>
        </div>
        <div class="container">
            <?php if (empty($books)) { ?>
                <p>No books found.</p>
            <?php } else { ?>
                <div id="cards" class="width-12 cards">
                    <?php foreach ($books as $book) { ?>
                    <?php $bookFormat = Format::findByBookId($book->id);
                    $formatIDs = array_map(fn($f) => $f->format_id, $bookFormat); ?>

                        <div class="card" 
                        data-title="<?= h($book->title) ?>"
                        data-publisher="<?= h($book->publisher_id) ?>"
                        data-format="<?= h(implode(',', array_filter($formatIDs))) ?>"
                        data-year="<?= h($book->year) ?>">

                            <div class="top-content">
                                <h2>Title: <?= h($book->title) ?></h2>
                                <p>Release Year: <?= h($book->year) ?></p>
                            </div>
                            <div class="bottom-content">
                                <img src="images/<?= h($book->cover_filename) ?>" alt="Image for <?= h($book->title) ?>" />
                                <div class="actions">
                                    <a href="book_view.php?id=<?= h($book->id) ?>">View</a>/ 
                                    <a href="book_edit.php?id=<?= h($book->id) ?>">Edit</a>/ 
                                    <a href="book_delete.php?id=<?= h($book->id) ?>">Delete</a>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>

        <script src="js/filter.js"></script>
    </body>
</html>