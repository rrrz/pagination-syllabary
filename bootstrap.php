<?php
/**
 * Pagination_Syllabary
 *
 * @package    Pagination_Syllabary
 * @version    1.0
 * @author     Takeshige Nii
 * @license    MIT License
 * @copyright  2015 rz Inc.
 * @link       http://rrr-z.jp/
 */

\Autoloader::add_core_namespace('Pagination_Syllabary');

\Autoloader::add_classes(array(
	'Pagination_Syllabary\\Syllabary'                        => __DIR__.'/classes/syllabary.php',
	'Pagination_Syllabary\\Compose'                          => __DIR__.'/classes/traits/compose.php',
	'Pagination_Syllabary\\Render'                           => __DIR__.'/classes/traits/render.php',
	'Pagination_Syllabary\\String'                           => __DIR__.'/classes/traits/string.php',
));

// Ensure the Pagination_Syllabary config is loaded for Temporal
\Config::load('syllabary', true);
