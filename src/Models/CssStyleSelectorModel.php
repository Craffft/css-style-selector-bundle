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
        return array(
            self::TYPE_ARTICLE,
            self::TYPE_CALENDAR_EVENTS,
            self::TYPE_CONTENT,
            self::TYPE_FORM,
            self::TYPE_FORM_FIELD,
            self::TYPE_LAYOUT,
            self::TYPE_NEWS,
            self::TYPE_MODEL,
            self::TYPE_PAGE
        );
    }

    /**
     * @param array $arrIds
     * @return array
     */
    public static function findCssClassesByIds(array $arrIds)
    {
        $t = self::$strTable;
        $objDatabase = Database::getInstance();

        $objCssStyleSelector = $objDatabase->prepare("SELECT cssClasses FROM $t WHERE id IN(". implode(',', array_map('intval', array_unique($arrIds))) .")")->execute();

        return $objCssStyleSelector->fetchEach('cssClasses');
    }

    /**
     * @param $strType
     * @return array
     */
    public static function findCssClassesByNotDisabledType($strType)
    {
        if (!in_array($strType, self::getAvailableTypes())) {
            return array();
        }

        $t = self::$strTable;
        $objDatabase = Database::getInstance();

        $objCssStyleSelector = $objDatabase
            ->prepare("SELECT cssClasses FROM $t WHERE disableIn" . ucfirst($strType) . "=?")
            ->execute(0);

        return $objCssStyleSelector->fetchEach('cssClasses');
    }

    /**
     * @param $strType
     * @return array
     */
    public static function findStyleDesignationByNotDisabledType($strType)
    {
        if (!in_array($strType, self::getAvailableTypes())) {
            return array();
        }

        $t = self::$strTable;
        $objDatabase = Database::getInstance();

        $objCssStyleSelector = $objDatabase
            ->prepare("SELECT id, styleDesignation FROM $t WHERE disableIn" . ucfirst($strType) . "=? ORDER BY styleDesignation ASC")
            ->execute(0);

        return $objCssStyleSelector->fetchEach('styleDesignation');
    }
}
