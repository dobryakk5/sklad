<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
if (empty($arResult)) {
    return "";
}
$scheme = (
    (isset($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) === 'on')
    || (isset($_SERVER['SERVER_PORT']) && (string)$_SERVER['SERVER_PORT'] === '443')
) ? 'https' : 'http';
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
// Убираем :443 и :80 из хоста
$host = preg_replace('/:(443|80)$/', '', $host);
$requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
$currentPath = parse_url($requestUri, PHP_URL_PATH);
if (!$currentPath) {
    $currentPath = '/';
}
$currentUrl = $scheme . '://' . $host . $currentPath;

function cleanBreadcrumbUrl($url) {
    $parts = parse_url($url);
    if (!$parts) return $url;
    $query = [];
    if (!empty($parts['query'])) {
        parse_str($parts['query'], $query);
        // Удаляем roistat_visit и другие трекинговые параметры
        unset($query['roistat_visit'], $query['utm_source'], $query['utm_medium'], $query['utm_campaign'], $query['utm_term'], $query['utm_content']);
    }
    $scheme = isset($parts['scheme']) ? $parts['scheme'] : 'https';
    $host   = isset($parts['host'])   ? $parts['host']   : '';
    // Убираем :443 и :80 из хоста в ссылках
    $host = preg_replace('/:(443|80)$/', '', $host);
    $path   = isset($parts['path'])   ? $parts['path']   : '/';
    $result = $scheme . '://' . $host . $path;
    if (!empty($query)) {
        $result .= '?' . http_build_query($query);
    }
    return $result;
}

$strReturn = '<ul class="breadcrumb" id="navigation" itemscope itemtype="https://schema.org/BreadcrumbList">';
for ($index = 0, $itemSize = count($arResult); $index < $itemSize; $index++) {
    $title    = htmlspecialcharsbx($arResult[$index]["TITLE"]);
    $position = $index + 1;
    $isLast   = ($index === $itemSize - 1);
    if (!$isLast && !empty($arResult[$index]["LINK"])) {
        $link = $arResult[$index]["LINK"];
        if (strpos($link, 'http://') !== 0 && strpos($link, 'https://') !== 0) {
            $link = $scheme . '://' . $host . $link;
        }
        $link = htmlspecialcharsbx(cleanBreadcrumbUrl($link));
        $strReturn .= '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" id="bx_breadcrumb_' . $index . '">';
        $strReturn .= '<a href="' . $link . '" title="' . $title . '" itemprop="item">';
        $strReturn .= '<span itemprop="name">' . $title . '</span>';
        $strReturn .= '</a>';
        $strReturn .= '<meta itemprop="position" content="' . $position . '" />';
        $strReturn .= '</li>';
    } elseif (!$isLast) {
        $strReturn .= '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" id="bx_breadcrumb_' . $index . '">';
        $strReturn .= '<span itemprop="name">' . $title . '</span>';
        $strReturn .= '<meta itemprop="position" content="' . $position . '" />';
        $strReturn .= '</li>';
    } else {
        $lastLink = htmlspecialcharsbx(cleanBreadcrumbUrl($currentUrl));
        $strReturn .= '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem" id="bx_breadcrumb_' . $index . '" class="active">';
        $strReturn .= '<a href="' . $lastLink . '" title="' . $title . '" itemprop="item">';
        $strReturn .= '<span itemprop="name">' . $title . '</span>';
        $strReturn .= '</a>';
        $strReturn .= '<meta itemprop="position" content="' . $position . '" />';
        $strReturn .= '</li>';
    }
}
$strReturn .= '</ul>';
return $strReturn;
?>
