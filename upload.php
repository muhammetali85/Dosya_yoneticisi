<?php
include 'auth.php';
$base = 'uploads/';
$path = $_POST['path'] ?? '';
$targetDir = realpath($base) . '/' . trim($path, '/');

if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

foreach ($_FILES['files']['name'] as $i => $name) {
  $tmp = $_FILES['files']['tmp_name'][$i];
  move_uploaded_file($tmp, $targetDir . '/' . basename($name));
}
