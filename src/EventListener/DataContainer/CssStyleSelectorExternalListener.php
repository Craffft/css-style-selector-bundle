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
    public function saveCssStyleSelectorToCssId($value, DataContainer $dc): string
    {
        $cssStyleSelectorUtil = new CssStyleSelectorUtil();

        return $cssStyleSelectorUtil->saveCssIdCallback($value, $dc);
    }

    /**
     * @Callback(table="tl_calendar_events", target="fields.cssStyleSelector.save")
     * @Callback(table="tl_layout", target="fields.cssStyleSelector.save")
     * @Callback(table="tl_news", target="fields.cssStyleSelector.save")
     * @Callback(table="tl_page", target="fields.cssStyleSelector.save")
     */
    public function saveCssStyleSelectorToCssClass($value, DataContainer $dc): string
    {
        $cssStyleSelectorUtil = new CssStyleSelectorUtil();

        return $cssStyleSelectorUtil->saveCssClassCallback($value, $dc);
    }

    /**
     * @Callback(table="tl_form", target="fields.cssStyleSelector.save")
     */
    public function saveCssStyleSelectorToAttributes($value, DataContainer $dc): string
    {
        $cssStyleSelectorUtil = new CssStyleSelectorUtil();

        return $cssStyleSelectorUtil->saveCssIdCallback($value, $dc, 'attributes');
    }

    /**
     * @Callback(table="tl_form_field", target="fields.cssStyleSelector.save")
     */
    public function saveCssStyleSelectorToClass($value, DataContainer $dc): string
    {
        $cssStyleSelectorUtil = new CssStyleSelectorUtil();

        return $cssStyleSelectorUtil->saveCssIdCallback($value, $dc, 'class');
    }

    /**
     * @Callback(table="tl_content", target="config.onload")
     */
    public function onLoadContent(?DataContainer $dc): void
    {
        $cssStyleSelectorUtil = new CssStyleSelectorUtil();
        $cssStyleSelectorUtil->onLoadContentCallback($dc);
    }
}
