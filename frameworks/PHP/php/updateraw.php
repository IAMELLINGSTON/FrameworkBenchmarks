<?php
header('Content-type: application/json');

// Database connection
// http://www.php.net/manual/en/ref.pdo-mysql.php
$pdo = new PDO('mysql:host=tfb-database;dbname=hello_world', 'benchmarkdbuser', 'benchmarkdbpass', [
  PDO::ATTR_PERSISTENT => true
]);

// Read number of queries to run from URL parameter
$query_count = 1;
if ($_GET['queries'] > 1) {
  $query_count = min($_GET['queries'], 500);
}

// Define query
$statement = $pdo->prepare('SELECT randomNumber FROM World WHERE id=?');
$updateStatement = $pdo->prepare('UPDATE World SET randomNumber=? WHERE id=?');

// For each query, store the result set values in the response array
while ($query_count--) {
    $id = mt_rand(1, 10000);
    $statement->execute([$id]);

    // Store result in array.
    $world = ['id' => $id, 'randomNumber' => $statement->fetchColumn()];
    $updateStatement->execute(
        [$world['randomNumber'] = mt_rand(1, 10000), $id]
    );

    $arr[] = $world;
}

// Use the PHP standard JSON encoder.
// http://www.php.net/manual/en/function.json-encode.php
echo json_encode($arr, JSON_NUMERIC_CHECK);
