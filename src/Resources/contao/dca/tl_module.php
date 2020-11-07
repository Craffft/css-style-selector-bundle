<?php

/*
 * This file is part of the CssStyleSelector Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Craffft\CssStyleSelectorBundle\DCA\Field\CssStyleSelector;

if (isset($GLOBALS['TL_DCA']['tl_module'])) {
    if (isset($GLOBALS['TL_DCA']['tl_module']['palettes'])) {
        foreach ($GLOBALS['TL_DCA']['tl_module']['palettes'] as $k => $v) {
            PaletteManipulator::create()
                ->addField('cssStyleSelector', 'cssID', PaletteManipulator::POSITION_BEFORE)
                ->applyToPalette($k, 'tl_module');
        }
    }

    $GLOBALS['TL_DCA']['tl_module']['fields']['cssStyleSelector'] = CssStyleSelector::getFieldConfig();
}
