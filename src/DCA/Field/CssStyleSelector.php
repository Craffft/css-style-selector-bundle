<?php

/*
 * This file is part of the CssStyleSelector Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Craffft\CssStyleSelectorBundle\DCA\Field;

class CssStyleSelector
{
    public static function getFieldConfig()
    {
        return [
            'label' => &$GLOBALS['TL_LANG']['MSC']['cssStyleSelector'],
            'exclude' => true,
            'inputType' => 'select',
            'search' => true,
            'eval' => ['chosen' => true, 'multiple' => true, 'tl_class' => 'clr'],
            'sql' => "blob NULL",
        ];
    }
}
