<?php
declare(strict_types=1);

enum Category: string {
    case TEXT_NEUTRAL  = 'italic text-zinc-500';
    case TEXT_ERROR    = 'bold text-red-300';
    case POPUP_SUCCESS = 'bg-green-400 py-4 px-8 rounded-md';
    case POPUP_ERROR   = 'bg-red-400 py-4 px-8 rounded-md';
    case POPUP_NEUTRAL = 'bg-zinc-500 py-4 px-8 rounded-md';
}

enum Tag: string {
    case DIV = 'div';
    case LI = 'li';
}

class AlertsView {
    public static function notification(string $text, Category $style, Tag $html = Tag::DIV): string {
        return "<$html->value class='$style->value'>$text</$html->value>";
    }
}

?>