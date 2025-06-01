<?php
// require_once 'db.php';

// try {
//     createBooksTableIfNotExists();
//     $inputJSON = file_get_contents('php://input');
//     $input = json_decode($inputJSON, true);

//     if (!isset($input['books']) || !is_array($input['books'])) {
//         echo json_encode(['status' => 'error', 'message' => 'Некорректные данные']);
//         exit;
//     }

//     $books = $input['books'];
//     $sql = 'INSERT INTO books (title, author) VALUES (?, ?)';
//     $query = $pdo->prepare($sql);

//     foreach ($books as $book) {
//         if (!isset($book['title']) || !isset($book['author']))
//             continue;

//         $title = trim($book['title']);
//         $author = trim($book['author']);
//         $query->execute([$title, $author]);
//     }

//     echo json_encode(['status' => 'success', 'message' => 'Книги сохранены']);
// } catch (PDOException $e) {
//     echo json_encode(['status' => 'error', 'message' => 'Ошибка: ' . $e->getMessage()]);
// }
