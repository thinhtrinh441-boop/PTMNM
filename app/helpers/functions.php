<?php

function default_product_image(): string
{
    return 'https://placehold.co/400x400/f0f2f5/8b95a1?text=Không+có+ảnh';
}

function product_image_url(?string $image): string
{
    $image = trim((string) $image);
    return $image !== '' ? $image : default_product_image();
}

function cart_item_count(): int
{
    $total = 0;
    foreach ($_SESSION['cart'] ?? [] as $item) {
        $total += (int) ($item['quantity'] ?? 1);
    }
    return $total;
}

function wishlist_count(): int
{
    return count($_SESSION['wishlist'] ?? []);
}

function is_wishlisted(int $productId): bool
{
    return in_array($productId, $_SESSION['wishlist'] ?? [], true);
}

function flash_set(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function flash_get(): ?array
{
    if (empty($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function format_price(int $price): string
{
    return number_format($price, 0, ',', '.') . 'đ';
}

function render_stars(float $rating): string
{
    $rating = max(0, min(5, $rating));
    $html = '<span class="text-warning">';
    for ($i = 1; $i <= 5; $i++) {
        $html .= $i <= round($rating) ? '<i class="bi bi-star-fill"></i>' : '<i class="bi bi-star"></i>';
    }
    return $html . '</span>';
}

function pagination_links(int $current, int $totalPages, array $query = []): string
{
    if ($totalPages <= 1) {
        return '';
    }
    $html = '<ul class="pagination justify-content-center">';
    $mk = function (int $page, string $label, bool $disabled = false, bool $active = false) use ($query) {
        $q = array_merge($query, ['page' => $page]);
        $href = url('Product/list') . '?' . http_build_query($q);
        $cls = 'page-item' . ($disabled ? ' disabled' : '') . ($active ? ' active' : '');
        return '<li class="' . $cls . '"><a class="page-link" href="' . htmlspecialchars($href) . '">' . $label . '</a></li>';
    };
    $html .= $mk(max(1, $current - 1), '‹', $current <= 1);
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i === 1 || $i === $totalPages || abs($i - $current) <= 1) {
            $html .= $mk($i, (string) $i, false, $i === $current);
        } elseif (abs($i - $current) === 2) {
            $html .= '<li class="page-item disabled"><span class="page-link">…</span></li>';
        }
    }
    $html .= $mk(min($totalPages, $current + 1), '›', $current >= $totalPages);
    return $html . '</ul>';
}
