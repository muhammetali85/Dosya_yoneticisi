<?php
include 'auth.php';
$base = 'uploads/';
$path = $_GET['path'] ?? '';
$file = basename($_GET['file']);
$fullPath = realpath($base . '/' . $path . '/' . $file);

if (file_exists($fullPath)) {
  $mime = mime_content_type($fullPath);
  header('Content-Type: ' . $mime);
  readfile($fullPath);
  exit;
} else {
  echo "Dosya bulunamadı.";
}
