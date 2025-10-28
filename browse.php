<?php
include 'auth.php';
$base = 'uploads/';
$path = $_GET['path'] ?? '';
$filter = $_GET['filter'] ?? '';
$dir = realpath($base) . '/' . trim($path, '/');

$result = [];

foreach (scandir($dir) as $item) {
  if ($item === '.' || $item === '..') continue;
  $fullPath = "$dir/$item";
  $isDir = is_dir($fullPath);
  $type = $isDir ? 'folder' : mime_content_type($fullPath);
  $ext = pathinfo($item, PATHINFO_EXTENSION);
  if ($filter && !$isDir && strtolower($ext) !== strtolower($filter)) continue;

  $result[] = [
    'name' => $item,
    'type' => $type,
    'size' => $isDir ? '-' : round(filesize($fullPath) / 1024, 2) . ' KB',
    'url' => $isDir ? '' : $fullPath,
    'isDir' => $isDir,
    'path' => trim($path . '/' . $item, '/')
  ];
}

header('Content-Type: application/json');
echo json_encode($result);
