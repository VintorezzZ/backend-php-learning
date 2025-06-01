<?php
// $host = 'localhost';
// $dbname = 'php-website';
// $user = 'root';
// $pass = 'root';
// $port = '3306';

// $localCachePath = './localCache.txt';

// try {
//     $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=$port;charset=utf8", $user, $pass);
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     die("Ошибка подключения к базе: " . $e->getMessage());
// }

// /**
//  * Проверяет наличие таблицы 'books', если её нет — создаёт и наполняет данными из кэша.
//  * @return void
//  */
// function createBooksTableIfNotExists(): void
// {
//     global $pdo;
//     $cacheBooks = getLocalCacheBooks();

//     try {
//         $showSql = "SHOW TABLES LIKE 'books'";
//         $result = $pdo->query($showSql);

//         if ($result && $result->rowCount() === 0) {
//             $createTableSql = "
//             CREATE TABLE books (
//                 id INT AUTO_INCREMENT PRIMARY KEY,
//                 title VARCHAR(255) NOT NULL,
//                 author VARCHAR(255) NOT NULL
//             ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
//         ";

//             $pdo->exec($createTableSql);

//             if (!empty($cacheBooks)) {
//                 $insertSql = 'INSERT INTO books (title, author) VALUES (?, ?)';
//                 $query = $pdo->prepare($insertSql);

//                 foreach ($cacheBooks as $book) {
//                     $title = trim($book->title);
//                     $author = trim($book->author);
//                     $query->execute([$title, $author]);
//                 }
//             }
//         }
//     } catch (PDOException $e) {
//         // При ошибке возвращаем локальный кэш
//         json_encode(array_map(fn($b) => $b->toArray(), $cacheBooks));
//     }
// }

// function getLocalCacheBooks(): array
// {
//     global $localCachePath;
//     $pathToJson = $localCachePath;

//     if (!file_exists($pathToJson)) {
//         return [];
//     }

//     $json = file_get_contents($pathToJson);
//     $data = json_decode($json, true);

//     if ($data === null) {
//         return [];
//     }

//     require_once './Book.php';

//     $books = [];

//     foreach ($data as $item) {
//         if (isset($item['title'], $item['author'])) {
//             $books[] = new Book($item['title'], $item['author'], $item['id']);
//         }
//     }

//     return $books;
// }
