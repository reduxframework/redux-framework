<?php

namespace Extendify\Onboarding\Helpers;

if (!defined('ABSPATH')) {
    die('No direct access.');
}

/**
 * Class used to expose some WP protectod methods
 */
class ThemeJson extends \WP_Theme_JSON
{
    /**
     * Expose the protected sanitize function to make sure the theme.json is OK.
     *
     * @param array $themeJson - parsed theme.json array.
     * @return array
     */
    public static function justSanitize($themeJson)
    {
        $themeJson = \WP_Theme_JSON_Schema::migrate($themeJson);
        $validBlockNames = array_keys(static::get_blocks_metadata());
        $validElementNames = array_keys(static::ELEMENTS);
        return static::sanitize($themeJson, $validBlockNames, $validElementNames);
    }
}
