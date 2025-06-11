<?php
header('Content-Type: application/json');

// Lấy URL từ query string
$url = $_GET['url'] ?? '';

// Kiểm tra hợp lệ
if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
    echo json_encode(['error' => 'Thiếu URL hợp lệ']);
    exit;
}

// Gửi request đến trang sản phẩm Lazada
$html = @file_get_contents($url);
if (!$html) {
    echo json_encode(['error' => 'Không lấy được nội dung từ Lazada']);
    exit;
}

// Lấy tiêu đề sản phẩm
preg_match('/<title>(.*?)<\/title>/', $html, $m);
$title = $m[1] ?? '';

// Lấy ảnh sản phẩm (meta tag og:image)
preg_match('/<meta property="og:image" content="(.*?)"/', $html, $img);
$image = $img[1] ?? '';

// Lấy giá bán & giá gốc
preg_match('/"price":"([\d\.]+)"/', $html, $matchPrice);
preg_match('/"originalPrice":"([\d\.]+)"/', $html, $matchListPrice);

$price = $matchPrice[1] ?? '';
$listPrice = $matchListPrice[1] ?? '';

// Trả kết quả JSON
echo json_encode([
    'title' => $title,
    'image' => $image,
    'price' => $price,
    'list_price' => $listPrice
]);