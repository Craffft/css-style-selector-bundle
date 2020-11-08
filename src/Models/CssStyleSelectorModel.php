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

    protected static $strTable = 'tl_css_style_selector';

    public static function getAvailableTypes(): array
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

    public static function getTypeByTable(string $table): string
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

    public static function findCssClassesByIds(array $ids): array
    {
        $t = self::$strTable;
        $objDatabase = Database::getInstance();

        $objCssStyleSelector = $objDatabase->prepare(
            "SELECT cssClasses FROM $t WHERE id IN(".implode(',', array_map('intval', array_unique($ids))).")"
        )->execute();

        return $objCssStyleSelector->fetchEach('cssClasses');
    }

    public static function findCssClassesByNotDisabledType(string $type): array
    {
        if (!in_array($type, self::getAvailableTypes())) {
            return [];
        }

        $t = self::$strTable;
        $objDatabase = Database::getInstance();

        $objCssStyleSelector = $objDatabase
            ->prepare("SELECT cssClasses FROM $t WHERE disableIn".ucfirst($type)."=?")
            ->execute(0);

        return $objCssStyleSelector->fetchEach('cssClasses');
    }

    public static function findStyleDesignationByNotDisabledType(string $type): array
    {
        if (!in_array($type, self::getAvailableTypes())) {
            return [];
        }

        $t = self::$strTable;
        $objDatabase = Database::getInstance();

        $objCssStyleSelector = $objDatabase
            ->prepare(
                "SELECT id, styleDesignation, styleGroup, cssClasses FROM $t WHERE disableIn".ucfirst(
                    $type
                )."=? ORDER BY styleGroup, styleDesignation ASC"
            )
            ->execute(0);

        $styles = [];
        foreach ($objCssStyleSelector->fetchAllAssoc() as $item) {
            $value = $item['styleDesignation'];

            if ($item['cssClasses'] !== '' && Config::get('cssStyleSelectorAddClassesToListItem')) {
                $value .= ' ('.$item['cssClasses'].')';
            }

            if ($item['styleGroup']) {
                $styles[$item['styleGroup']][$item['id']] = $value;
            } else {
                $styles[$item['id']] = $value;
            }
        }

        foreach ($styles as $style) {
            if (is_array($style)) {
                natsort($style);
            }
        }

        natsort($styles);

        return $styles;
    }
}
