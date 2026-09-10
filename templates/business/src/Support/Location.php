<?php

namespace Templates\Business\Support;

/**
 * Where the "Konumumuz" buttons (header, mobile bar, home) should send the
 * visitor: the embedded map on the contact page when one is configured, a
 * Google Maps search for the address otherwise, and the contact page as a
 * last resort.
 */
final class Location
{
    public static function mapUrl(): string
    {
        if (setting('map_embed')) {
            return route('contact.show').'#harita';
        }

        if (setting('address')) {
            return 'https://www.google.com/maps/search/?api=1&query='.rawurlencode((string) setting('address'));
        }

        return route('contact.show');
    }

    /** Whether the map link leaves the site (opens in a new tab). */
    public static function isExternal(): bool
    {
        return ! setting('map_embed') && (bool) setting('address');
    }
}
