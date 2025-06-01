<?php
// require_once 'db.php';
// require_once 'Book.php';

// try {
//     createBooksTableIfNotExists();
//     $sql = 'SELECT title, author, id FROM books';
//     $result = $pdo->query($sql);
//     $rows = $result->fetchAll(PDO::FETCH_ASSOC);

//     $books = [];
//     foreach ($rows as $row) {
//         $books[] = new Book($row['title'], $row['author'], $row['id']);
//     }

//     echo json_encode(array_map(fn($b) => $b->toArray(), $books));
// } catch (PDOException $e) {
//     echo json_encode([]);
// }