<?php
function baseUrl($path = '') {
	$protocol = isset($_SERVER['HTTPS'])  && $_SERVER['HTTPS'] !== 'off' ? 'https://' : 'http://';
	$host = $_SERVER['HTTP_HOST'];
	$baseUrl = $protocol . $host;
	return $baseUrl . '/' . ltrim($path, '/');
}

function basePath($path = '') {
	$rootPath = dirname(__DIR__);
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