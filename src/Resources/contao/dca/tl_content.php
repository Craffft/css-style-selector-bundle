<?php

/*
 * This file is part of the CssStyleSelector Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Craffft\CssStyleSelectorBundle\DCA\Field\CssStyleSelector;

if (isset($GLOBALS['TL_DCA']['tl_content'])) {
    // This field will be added to the palette by CssStyleSelectorListener::onLoadContent()
    $GLOBALS['TL_DCA']['tl_content']['fields']['cssStyleSelector'] = CssStyleSelector::getFieldConfig();
}
