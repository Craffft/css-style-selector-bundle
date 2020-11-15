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
use Contao\Database;
use Contao\DataContainer;
use Contao\Input;
use Craffft\CssStyleSelectorBundle\Models\CssStyleSelectorModel;
use Craffft\CssStyleSelectorBundle\Util\CssStyleSelectorUtil;

class CssStyleSelectorExternalListener
{
    /**
     * @Callback(table="tl_article", target="fields.cssStyleSelector.options")
     * @Callback(table="tl_calendar_events", target="fields.cssStyleSelector.options")
     * @Callback(table="tl_content", target="fields.cssStyleSelector.options")
     * @Callback(table="tl_form", target="fields.cssStyleSelector.options")
     * @Callback(table="tl_form_field", target="fields.cssStyleSelector.options")
     * @Callback(table="tl_layout", target="fields.cssStyleSelector.options")
     * @Callback(table="tl_module", target="fields.cssStyleSelector.options")
     * @Callback(table="tl_news", target="fields.cssStyleSelector.options")
     * @Callback(table="tl_page", target="fields.cssStyleSelector.options")
     */
    public function getCssStyleSelectorOptions(DataContainer $dc): array
    {
        $type = CssStyleSelectorModel::getTypeByTable($dc->table);

        if (!$type) {
            return [];
        }

        return CssStyleSelectorModel::findStyleDesignationByNotDisabledType($type);
    }

    /**
     * @Callback(table="tl_article", target="fields.cssStyleSelector.save")
     * @Callback(table="tl_content", target="fields.cssStyleSelector.save")
     * @Callback(table="tl_module", target="fields.cssStyleSelector.save")
     */
    public function saveCssStyleSelectorToCssId(?string $value, DataContainer $dc): string
    {
        $cssStyleSelectorUtil = new CssStyleSelectorUtil();

        return $cssStyleSelectorUtil->saveCallback($value, $dc, true, 'cssID');
    }

    /**
     * @Callback(table="tl_calendar_events", target="fields.cssStyleSelector.save")
     * @Callback(table="tl_layout", target="fields.cssStyleSelector.save")
     * @Callback(table="tl_news", target="fields.cssStyleSelector.save")
     * @Callback(table="tl_page", target="fields.cssStyleSelector.save")
     */
    public function saveCssStyleSelectorToCssClass(?string $value, DataContainer $dc): string
    {
        $cssStyleSelectorUtil = new CssStyleSelectorUtil();

        return $cssStyleSelectorUtil->saveCallback($value, $dc, false, 'cssClass');
    }

    /**
     * @Callback(table="tl_form", target="fields.cssStyleSelector.save")
     */
    public function saveCssStyleSelectorToAttributes(?string $value, DataContainer $dc): string
    {
        $cssStyleSelectorUtil = new CssStyleSelectorUtil();

        return $cssStyleSelectorUtil->saveCallback($value, $dc, true, 'attributes');
    }

    /**
     * @Callback(table="tl_form_field", target="fields.cssStyleSelector.save")
     */
    public function saveCssStyleSelectorToClass(?string $value, DataContainer $dc): string
    {
        $cssStyleSelectorUtil = new CssStyleSelectorUtil();

        return $cssStyleSelectorUtil->saveCallback($value, $dc, true, 'class');
    }

    /**
     * onload_callback for the tl_content DCA to inject cssStyleSelector for any regular custom content element.
     *
     * @Callback(table="tl_content", target="config.onload")
     */
    public function onLoadContent(?DataContainer $dc): void
    {
        if (!($dc instanceof DataContainer)) {
            return;
        }

        // Get the type
        $type = null;
        if (Input::post('FORM_SUBMIT') === $dc->table) {
            $type = Input::post('type');
        } else {
            if ($dc->activeRecord) {
                $type = $dc->activeRecord->type;
            } else {
                $table = $dc->table;
                $id = $dc->id;

                if (Input::get('target')) {
                    $table = explode('.', Input::get('target'), 2)[0];
                    $id = (int) explode('.', Input::get('target'), 3)[2];
                }

                if ($table && $id) {
                    $record = Database::getInstance()->prepare("SELECT * FROM {$table} WHERE id=?")->execute($id);
                    if ($record->next()) {
                        $type = $record->type;
                    }
                }
            }
        }

        // The palette might not exist
        if (array_key_exists($type, $GLOBALS['TL_DCA'][$dc->table]['palettes'])) {

            // Get the palette
            $palette = &$GLOBALS['TL_DCA'][$dc->table]['palettes'][$type];

            // Check if cssID is in the palette and cssStyleSelector is not
            if (strpos($palette, 'cssID') !== false &&
                strpos($palette, 'cssStyleSelector') === false) {

                // Add the css style selector
                $palette = str_replace(',cssID', ',cssStyleSelector,cssID', $palette);
            }
        }
    }
}
