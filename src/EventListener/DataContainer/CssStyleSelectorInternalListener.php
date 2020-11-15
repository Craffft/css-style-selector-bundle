<?php

/*
 * This file is part of the CssStyleSelector Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Craffft\CssStyleSelectorBundle\EventListener\DataContainer;

use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\DataContainer;

class CssStyleSelectorInternalListener
{
    /**
     * @Callback(table="tl_css_style_selector", target="list.label.label")
     */
    public function labelCallback(array $row, string $label, DataContainer $dc, array $args): string
    {
        $fieldNames = [
            'disableInPage',
            'disableInArticle',
            'disableInContent',
            'disableInForm',
            'disableInFormField',
            'disableInNews',
            'disableInCalendarEvent',
            'disableInLayout',
            'disableInModule',
        ];

        $html = '';
        foreach ($fieldNames as $index => $fieldName) {
            $argIndex = $index + 2;
            $args[$argIndex] = $GLOBALS['TL_LANG']['MSC'][($row[$fieldName] ? 'no' : 'yes')];
            $html .= '<td class="tl_file_list">'.$args[$argIndex].'</td>';
        }

        return $label.'</td>'
            .'<td class="tl_file_list" style="min-width: 0 !important; padding-left: 0 !important; padding-right: 0 !important; width: 0 !important;"></td>'
            .$html;
    }

    /**
     * @Callback(table="tl_css_style_selector", target="list.label.group")
     */
    public function groupCallback(
        ?string $group,
        ?string $mode,
        ?string $field,
        ?array $recordData,
        ?DataContainer $dc
    ): string {
        $fieldNames = [
            'pageEnabled',
            'articleEnabled',
            'contentEnabled',
            'formEnabled',
            'formFieldEnabled',
            'newsEnabled',
            'calendarEventEnabled',
            'layoutEnabled',
            'moduleEnabled',
        ];

        $html = '';
        foreach ($fieldNames as $fieldName) {
            $html .= '<td class="tl_folder_list">'.$GLOBALS['TL_LANG']['tl_css_style_selector'][$fieldName][0].'</td>';
        }

        $group = $group ? $group : '-';

        return $group.'</td>'.$html.'<td class="tl_folder_list">&nbsp;</td>';
    }
}
