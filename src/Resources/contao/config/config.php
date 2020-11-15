<?php

/*
 * This file is part of the CssStyleSelector Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$GLOBALS['BE_MOD']['design']['cssStyleSelector'] = [
    'tables' => [
        'tl_css_style_selector',
        'tl_css_style_selector_group',
    ],
];

if (defined('TL_MODE') && TL_MODE == 'BE')
{
    $GLOBALS['TL_CSS'][] = 'bundles/craffftcssstyleselector/style.css|static';
}
