<?php

/*
 * This file is part of the CssStyleSelector Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (isset($GLOBALS['TL_DCA']['tl_content'])) {
    // Callbacks
    $GLOBALS['TL_DCA']['tl_content']['config']['onload_callback'][] = array('Craffft\CssStyleSelectorBundle\Util\CssStyleSelectorUtil', 'onLoadContentCallback');

    // Fields
    $GLOBALS['TL_DCA']['tl_content']['fields']['cssStyleSelector'] = array
    (
        'label'            => &$GLOBALS['TL_LANG']['MSC']['cssStyleSelector'],
        'exclude'          => true,
        'inputType'        => 'select',
        'options_callback' => function () {
            return \Craffft\CssStyleSelectorBundle\Models\CssStyleSelectorModel::findStyleDesignationByNotDisabledType(
                \Craffft\CssStyleSelectorBundle\Models\CssStyleSelectorModel::TYPE_CONTENT
            );
        },
        'search'           => true,
        'eval'             => array('chosen' => true, 'multiple' => true, 'tl_class' => 'clr'),
        'save_callback'    => array
        (
            array('Craffft\\CssStyleSelectorBundle\\Util\\CssStyleSelectorUtil', 'saveCssIdCallback')
        ),
        'sql'              => "blob NULL"
    );
}
