<?php

/*
 * This file is part of the CssStyleSelector Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Craffft\CssStyleSelectorBundle\Models;

use Contao\Config;
use Contao\Database;
use Contao\Model;

class CssStyleSelectorModel extends Model
{
    const TYPE_ARTICLE = 'article';
    const TYPE_CALENDAR_EVENTS = 'calendarEvent';
    const TYPE_CONTENT = 'content';
    const TYPE_FORM = 'form';
    const TYPE_FORM_FIELD = 'formField';
    const TYPE_LAYOUT = 'layout';
    const TYPE_NEWS = 'news';
    const TYPE_MODEL = 'module';
    const TYPE_PAGE = 'page';

    /**
     * Name of the table
     * @var string
     */
    protected static $strTable = 'tl_css_style_selector';

    public static function getAvailableTypes()
    {
        return [
            self::TYPE_ARTICLE,
            self::TYPE_CALENDAR_EVENTS,
            self::TYPE_CONTENT,
            self::TYPE_FORM,
            self::TYPE_FORM_FIELD,
            self::TYPE_LAYOUT,
            self::TYPE_NEWS,
            self::TYPE_MODEL,
            self::TYPE_PAGE,
        ];
    }

    public static function getTypeByTable(string $table)
    {
        switch ($table) {
            case 'tl_article':
                $type = self::TYPE_ARTICLE;
                break;

            case 'tl_calendar_events':
                $type = self::TYPE_CALENDAR_EVENTS;
                break;

            case 'tl_content':
                $type = self::TYPE_CONTENT;
                break;

            case 'tl_form':
                $type = self::TYPE_FORM;
                break;

            case 'tl_form_field':
                $type = self::TYPE_FORM_FIELD;
                break;

            case 'tl_layout':
                $type = self::TYPE_LAYOUT;
                break;

            case 'tl_news':
                $type = self::TYPE_NEWS;
                break;

            case 'tl_model':
                $type = self::TYPE_MODEL;
                break;

            case 'tl_page':
                $type = self::TYPE_PAGE;
                break;

            default:
                $type = null;
                break;
        }

        return $type;
    }

    /**
     * @param array $arrIds
     *
     * @return array
     */
    public static function findCssClassesByIds(array $arrIds)
    {
        $t = self::$strTable;
        $objDatabase = Database::getInstance();

        $objCssStyleSelector = $objDatabase->prepare(
            "SELECT cssClasses FROM $t WHERE id IN(".implode(',', array_map('intval', array_unique($arrIds))).")"
        )->execute();

        return $objCssStyleSelector->fetchEach('cssClasses');
    }

    /**
     * @param $strType
     *
     * @return array
     */
    public static function findCssClassesByNotDisabledType($strType)
    {
        if (!in_array($strType, self::getAvailableTypes())) {
            return [];
        }

        $t = self::$strTable;
        $objDatabase = Database::getInstance();

        $objCssStyleSelector = $objDatabase
            ->prepare("SELECT cssClasses FROM $t WHERE disableIn".ucfirst($strType)."=?")
            ->execute(0);

        return $objCssStyleSelector->fetchEach('cssClasses');
    }

    /**
     * @param $strType
     *
     * @return array
     */
    public static function findStyleDesignationByNotDisabledType($strType)
    {
        if (!in_array($strType, self::getAvailableTypes())) {
            return [];
        }

        $t = self::$strTable;
        $objDatabase = Database::getInstance();

        $objCssStyleSelector = $objDatabase
            ->prepare(
                "SELECT id, styleDesignation, cssClasses FROM $t WHERE disableIn".ucfirst(
                    $strType
                )."=? ORDER BY styleDesignation ASC"
            )
            ->execute(0);

        $styles = [];
        foreach ($objCssStyleSelector->fetchAllAssoc() as $item) {
            $value = $item['styleDesignation'];

            if (Config::get('cssStyleSelectorAddClassesToListItem') && $item['cssClasses'] != '') {
                $value .= ' ('.$item['cssClasses'].')';
            }

            $styles[$item['id']] = $value;
        }

        return $styles;
    }
}
