<?php

/*
 * This file is part of the CssStyleSelector Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Craffft\CssStyleSelectorBundle\Models\CssStyleSelectorModel;

if (isset($GLOBALS['TL_DCA']['tl_article'])) {
    // Palettes
    foreach ($GLOBALS['TL_DCA']['tl_article']['palettes'] as $k => $v) {
        $GLOBALS['TL_DCA']['tl_article']['palettes'][$k] = str_replace(',cssID', ',cssStyleSelector,cssID', $v);
    }

    // Fields
    $GLOBALS['TL_DCA']['tl_article']['fields']['cssStyleSelector'] = [
        'label' => &$GLOBALS['TL_LANG']['MSC']['cssStyleSelector'],
        'exclude' => true,
        'inputType' => 'select',
        'options_callback' => function () {
            return CssStyleSelectorModel::findStyleDesignationByNotDisabledType(
                CssStyleSelectorModel::TYPE_ARTICLE
            );
        },
        'search' => true,
        'eval' => ['chosen' => true, 'multiple' => true, 'tl_class' => 'clr'],
        'save_callback' => [
            ['Craffft\\CssStyleSelectorBundle\\Util\\CssStyleSelectorUtil', 'saveCssIdCallback'],
        ],
        'sql' => "blob NULL",
    ];
}
