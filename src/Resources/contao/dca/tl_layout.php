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

if (isset($GLOBALS['TL_DCA']['tl_layout'])) {
    if (isset($GLOBALS['TL_DCA']['tl_layout']['palettes'])) {
        foreach ($GLOBALS['TL_DCA']['tl_layout']['palettes'] as $k => $v) {
            if ($k === '__selector__') {
                continue;
            }

            PaletteManipulator::create()
                ->addField('cssStyleSelector', 'cssClass', PaletteManipulator::POSITION_BEFORE)
                ->applyToPalette($k, 'tl_layout');
        }
    }

    $GLOBALS['TL_DCA']['tl_layout']['fields']['cssStyleSelector'] = CssStyleSelector::getFieldConfig();
}
