<?php

class Format {
    public $book_id;
    public $format_id;

    private $db;

    public function __construct($data = []) {
        $this->db = DB::getInstance()->getConnection();

        if (!empty($data)) {
            $this->book_id = $data['book_id'] ?? null;
            $this->format_id = $data['format_id'] ?? null;
        }
    }

    // Find all games
    public static function findAll() {
        $db = DB::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM book_format");
        $stmt->execute();

        $formats = [];
        while ($row = $stmt->fetch()) {
            $formats[] = new Format($row);
        }

        return $formats;
    }

    // Find game by ID
    public static function findByBookId($book_id) {
        $db = DB::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM book_format WHERE book_id = :book_id");
        $stmt->execute(['book_id' => $book_id]);

        $rows = $stmt->fetchAll();

        return array_map(fn($row) => new Format($row), $rows);
    }

    // Save (insert or update)
    public function save() {
        // Insert new record
        $stmt = $this->db->prepare("
            INSERT INTO book_format (book_id, format_id)
            VALUES (:book_id, :format_id)
        ");

        $params = [
        'book_id' => $this->book_id,
        'format_id' => $this->format_id,
        ];
    
        // Execute statement
        $status = $stmt->execute($params);

        // Check for errors
        if (!$status) {
            $error_info = $stmt->errorInfo();
            $message = sprintf(
                "SQLSTATE error code: %d; error message: %s",
                $error_info[0],
                $error_info[2]
            );
            throw new Exception($message);  
        }

        // Ensure one row affected
        if ($stmt->rowCount() !== 1) {
            throw new Exception("Failed to save book.");
        }
    }

    // Convert to array for JSON output
    public function toArray() {
        return [
            'book_id' => $this->book_id,
            'format_id' => $this->format_id,
        ];
    }

    public static function DeleteByBookId($id){
        $db = DB::getInstance()->getConnection();

        $stmt = $db->prepare("
            DELETE FROM book_format
            WHERE book_id = :book_id
        ");

        $stmt->execute([
            'book_id' => $id
        ]);

        return $stmt->rowCount();
        }
}