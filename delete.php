<?php
include 'auth.php';
$base = 'uploads/';
$name = basename($_POST['name']);
$path = $_POST['path'] ?? '';
$file = realpath($base) . '/' . trim($path, '/') . '/' . $name;

if (file_exists($file)) unlink($file);
