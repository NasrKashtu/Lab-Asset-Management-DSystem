<?php
function status_chip(string $status): string {
    $key = strtolower($status);
    $classes = [
        'calibrated' => 'bg-green-100 text-green-700',
        'approved'   => 'bg-green-100 text-green-700',
        'due'        => 'bg-amber-100 text-amber-700',
        'pending'    => 'bg-amber-100 text-amber-700',
        'overdue'    => 'bg-red-100 text-red-700',
        'denied'     => 'bg-red-100 text-red-700',
    ][$key] ?? 'bg-gray-100 text-gray-700';
    $iconName = [
        'calibrated' => 'check-circle',
        'approved'   => 'check-circle',
        'due'        => 'clock',
        'pending'    => 'clock',
        'overdue'    => 'exclamation-triangle',
        'denied'     => 'exclamation-triangle',
    ][$key] ?? null;

    $iconHtml = $iconName ? icon($iconName, 'h-3.5 w-3.5') : '';

    return '<span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-medium ' . $classes . '">'
        . $iconHtml . htmlspecialchars($status) . '</span>';
}

function empty_state_row(int $colspan, string $text): string {
    return '<tr><td colspan="' . $colspan . '" class="px-4 py-12 text-center">'
        . '<div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-gray-400">' . icon('inbox', 'h-5 w-5') . '</div>'
        . '<p class="text-sm text-gray-500">' . htmlspecialchars($text) . '</p>'
        . '</td></tr>';
}
