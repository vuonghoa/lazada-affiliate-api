<?php
header('Content-Type: application/json');

$url = $_GET['url'] ?? '';
if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
    echo json_encode(['error' => 'Thiếu URL hợp lệ']);
    exit;
}

// Gửi request đến Lazada
$html = file_get_contents($url);

// Tiêu đề
preg_match('/<title>(.*?)<\/title>/', $html, $m);
$title = $m[1] ?? '';

// Ảnh đại diện
preg_match('/<meta property="og:image" content="(.*?)"/', $html, $img);
$image = $img[1] ?? '';

// Giá
preg_match('/"price":"([\d\.]+)"/', $html, $matchPrice);
preg_match('/"originalPrice":"([\d\.]+)"/', $html, $matchListPrice);

echo json_encode([
    'title' => $title,
    'image' => $image,
    'price' => $matchPrice[1] ?? '',
    'list_price' => $matchListPrice[1] ?? ''
]);