<?php
function baseUrl($path = '') {
	$protocol = isset($_SERVER['HTTPS'])  && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://';
	$host = $_SERVER['HTTP_HOST'];
	$baseUrl = $protocol . $host . '/' . PROJECT_DIR;
	return $baseUrl . '/' . ltrim($path, '/');
}

function basePath($path = '') {
	$rootPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . PROJECT_DIR;
	return $rootPath . DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR);
}

function uploadsPath($filename = '') {
	return basePath('uploads') . DIRECTORY_SEPARATOR . $filename;
}

function uploadsUrl($filename = '') {
	return baseUrl('uploads/') . ltrim($filename, '/');
}

function assetsUrl($filename = '') {
	return baseUrl('assets/') . ltrim($filename, '/');
}

function redirect($url) {
    header('Location: ' . baseUrl($url));
    exit;
}

function isPostRequest() {
	return $_SERVER['REQUEST_METHOD'] === 'POST';
}

function getPostData($field, $default = null) {
	return isset($_POST[$field]) ? trim($_POST[$field]) : $default;
}