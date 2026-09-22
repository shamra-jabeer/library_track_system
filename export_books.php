<?php
session_start();
include 'db.php';

if(!isset($_SESSION['admin'])){
    header("Location: index.php");
    exit();
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="library_books_export.csv"');

$output = fopen('php://output', 'w');

fputcsv($output, ['ID', 'Title', 'Author', 'Genre', 'Year', 'Status', 'Borrowed By', 'Due Date']);

$result = $conn->query("SELECT * FROM books ORDER BY id");
while($row = $result->fetch_assoc()){
    fputcsv($output, [
        $row['id'],
        $row['title'],
        $row['author'],
        $row['genre'],
        $row['year'],
        $row['status'],
        $row['borrowed_by'],
        $row['due_date']
    ]);
}

fclose($output);
exit();
?>