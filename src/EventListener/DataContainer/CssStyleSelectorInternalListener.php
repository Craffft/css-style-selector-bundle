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

use Contao\ArticleModel;
use Contao\CalendarEventsModel;
use Contao\ContentModel;
use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\DataContainer;
use Contao\FormFieldModel;
use Contao\FormModel;
use Contao\LayoutModel;
use Contao\ModuleModel;
use Contao\NewsModel;
use Contao\PageModel;
use Contao\StringUtil;
use Craffft\CssStyleSelectorBundle\Util\CssStyleSelectorUtil;

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

    /**
     * @Callback(table="tl_css_style_selector", target="config.onsubmit")
     */
    public function submitCallback(?DataContainer $dc): void
    {
        if (TL_MODE !== 'BE') {
            return;
        }

        // TODO 1: Search in Tables and add cssstyleselector item to existing and matching classes
        // TODO 2: Search in Tables and update css classes on existing entries

        $modelsMap = [
            ArticleModel::class => 'cssID',
            CalendarEventsModel::class => 'cssClass',
            ContentModel::class => 'cssID',
            FormFieldModel::class => 'class',
            FormModel::class => 'attributes',
            LayoutModel::class => 'cssClass',
            ModuleModel::class => 'cssID',
            NewsModel::class => 'cssClass',
            PageModel::class => 'cssClass',
        ];

        foreach ($modelsMap as $modelClass => $field) {
            $model = $modelClass::findAll();

            if ($model !== null) {
                while ($model->next()) {
                    $cssStyleSelectorUtil = new CssStyleSelectorUtil();
                    $hasClassesOfSelector = $cssStyleSelectorUtil->hasClassesOfSelector(
                        $dc->activeRecord->cssClasses,
                        $model->{$field},
                        true
                    );

                    if ($hasClassesOfSelector) {
                        $cssStyleSelector = StringUtil::deserialize($model->cssStyleSelector);
                        $cssStyleSelector[] = $dc->id;
                        $cssStyleSelector = array_unique($cssStyleSelector);
                        $model->cssStyleSelector = StringUtil::deserialize($cssStyleSelector);
                        $model->save();
                    }
                }
            }
        }
    }
}
