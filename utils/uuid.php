<?php

namespace PixelShadow\Blogify\Utils;

/**
 * Pure functions for UUID generation
 */

/**
 * Generates a Version 4 (random) UUID.
 * This is a pure function that generates a UUID following the Version 4 specification.
 *
 * @return string Returns a Version 4 UUID.
 */
function generate_uuid(): string {
    return sprintf(
        '%08x-%04x-%04x-%04x-%012x',
        random_int(0x00000000, PHP_INT_MAX),
        random_int(0x0000, 0xffff),
        random_int(0x4000, 0x4fff), // Version 4
        random_int(0x8000, 0xbfff), // Variant 1
        random_int(0x000000000000, PHP_INT_MAX)
    );
}
