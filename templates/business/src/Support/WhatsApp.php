<?php

namespace Templates\Business\Support;

/**
 * Builds wa.me links from the "whatsapp" site setting. Returns null when the
 * setting is empty so views can hide the button entirely.
 */
final class WhatsApp
{
    public static function number(): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) setting('whatsapp'));

        return $digits !== '' ? $digits : null;
    }

    public static function link(?string $text = null): ?string
    {
        $number = self::number();

        if ($number === null) {
            return null;
        }

        return 'https://wa.me/'.$number.($text ? '?text='.rawurlencode($text) : '');
    }
}
