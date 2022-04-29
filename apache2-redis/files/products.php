<?php

$redis = new Redis();
$redis->connect('10.0.0.106', 6379);


$key = 'PRODUCTS';

if (!$redis->get($key)) {
    $source = 'MySQL Server';
    $database_name     = 'test_store';
    $database_user     = 'lab_user';
    $database_password = '80925b13';
    $mysql_host        = '10.0.0.106';

    $pdo = new PDO('mysql:host=' . $mysql_host . '; dbname=' . $database_name, $database_user, $database_password);
    // $pdo = new PDO('mysql:host=' . $mysql_host . '; dbname=' . $database_name, $database_user);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql  = "SELECT * FROM products";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
       $products[] = $row;
    }

    $redis->set($key, serialize($products));
    $redis->expire($key, 600);

} else {
     $source = 'Redis Server';
     $products = unserialize($redis->get($key));

}

echo $source . ': <br>';
print_r($products);

    
if ($redis->ping()) {
    echo "OK";
}