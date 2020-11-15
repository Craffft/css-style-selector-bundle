<?php

/*
 * This file is part of the CssStyleSelector Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Craffft\CssStyleSelectorBundle\Util;

use Contao\Database;
use Contao\DataContainer;
use Contao\Input;
use Contao\StringUtil;
use Craffft\CssStyleSelectorBundle\Models\CssStyleSelectorModel;
use function array_key_exists;
use function explode;
use function str_replace;
use function strpos;

class CssStyleSelectorUtil
{
    public function saveCssIdCallback(?string $value, DataContainer $dc, string $specialName = 'cssID'): ?string
    {
        if (!$dc->activeRecord) {
            return null;
        }

        $value = (string) $value;

        $cssID = $this->getCssIDValue($dc, $specialName);
        $classes = $this->getClassesFromCssIDAsArray($cssID);

        // Remove all known cssStyleSelector classes from cssID classes
        $classes = array_diff($classes, $this->getAllCssStyleSelectorClassesByTable($dc->table));

        // Add all selected classes of CssStyleSelector to the classes of cssID
        $cssClassesSelectorIds = $this->convertSerializedCssStyleSelectorToArray($value);
        $classes = array_merge($classes, $this->getCssStyleSelectorClassesByIds($cssClassesSelectorIds));

        $classes = array_unique($classes);

        $this->saveClassesToCssID($classes, $dc, $specialName);

        return $value;
    }

    public function saveCssClassCallback(?string $value, DataContainer $dc, string $specialName = 'cssClass'): ?string
    {
        if (!$dc->activeRecord) {
            return null;
        }

        $value = (string) $value;

        $cssClasses = $this->getCssClassValue($dc, $specialName);
        $classes = $this->convertClassesStringToArray($cssClasses);

        // Remove all known cssStyleSelector classes from cssID classes
        $classes = array_diff($classes, $this->getAllCssStyleSelectorClassesByTable($dc->table));

        // Add all selected classes of CssStyleSelector to the classes of cssID
        $cssClassesSelectorIds = $this->convertSerializedCssStyleSelectorToArray($value);
        $classes = array_merge($classes, $this->getCssStyleSelectorClassesByIds($cssClassesSelectorIds));

        $classes = array_unique($classes);

        $this->saveClassesToCssClass($classes, $dc, $specialName);

        return $value;
    }

    protected function getCssIDName(int $id, $specialName = 'cssID'): string
    {
        return $specialName.((Input::get('act') === 'editAll') ? '_'.$id : '');
    }

    protected function getCssIDValue(DataContainer $dc, string $specialName = 'cssID'): array
    {
        $cssID = Input::post($this->getCssIDName($dc->id, $specialName));

        if ($cssID === null) {
            $cssID = StringUtil::deserialize($dc->activeRecord->cssID);
        }

        if (!is_array($cssID)) {
            $cssID = [];
        }

        return $cssID;
    }

    protected function getCssClassName(int $id, $specialName = 'cssClass'): string
    {
        return $specialName.((Input::get('act') === 'editAll') ? '_'.$id : '');
    }

    protected function getCssClassValue(DataContainer $dc, string $specialName = 'cssClass'): string
    {
        $cssClass = Input::post($this->getCssClassName($dc->id, $specialName));

        if ($cssClass === null) {
            $cssClass = $dc->activeRecord->cssClass;
        }

        if (!is_string($cssClass)) {
            $cssClass = '';
        }

        return $cssClass;
    }

    protected function convertSerializedCssStyleSelectorToArray(string $value): array
    {
        $ids = StringUtil::deserialize($value);

        if (!is_array($ids)) {
            $ids = [];
        }

        return $ids;
    }

    protected function saveClassesToCssID(array $classes, DataContainer $dc, string $specialName = 'cssID'): void
    {
        $cssIDName = $this->getCssIDName($dc->id, $specialName);

        $postedCssID = Input::post($cssIDName);
        $postedCssID[1] = implode(' ', $classes);
        $postedCssID[1] = str_replace('  ', ' ', $postedCssID[1]);
        $postedCssID[1] = trim($postedCssID[1]);

        $dc->activeRecord->cssID = serialize($postedCssID);
        Input::setPost($cssIDName, $postedCssID);

        $objDatabase = Database::getInstance();
        $objDatabase->prepare("UPDATE $dc->table SET ".$specialName."=? WHERE id=?")
            ->execute(serialize($postedCssID), $dc->id);
    }

    protected function saveClassesToCssClass(array $arrClasses, DataContainer $dc, string $specialName = 'cssClass'): void
    {
        $cssClassName = $this->getCssClassName($dc->id, $specialName);

        $classes = implode(' ', $arrClasses);
        $classes = str_replace('  ', ' ', $classes);
        $classes = trim($classes);

        $dc->activeRecord->cssClass = $classes;
        Input::setPost($cssClassName, $classes);

        $objDatabase = Database::getInstance();
        $objDatabase->prepare("UPDATE $dc->table SET ".$specialName."=? WHERE id=?")
            ->execute($classes, $dc->id);
    }

    protected function getClassesFromCssIDAsArray(array $cssID): array
    {
        [$id, $classes] = $cssID;

        return $this->convertClassesStringToArray($classes);
    }

    protected function getCssStyleSelectorClassesByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $classes = CssStyleSelectorModel::findCssClassesByIds($ids);

        return $this->convertCombinedClassesToSingleClasses($classes);
    }

    protected function getAllCssStyleSelectorClassesByTable(string $strTable): array
    {
        if (empty($strTable)) {
            return [];
        }

        $type = strtolower(substr($strTable, 3));

        $classes = CssStyleSelectorModel::findCssClassesByNotDisabledType($type);
        $classes = $this->convertCombinedClassesToSingleClasses($classes);

        return $classes;
    }

    protected function convertCombinedClassesToSingleClasses(array $classes): array
    {
        $singleClasses = [];

        if (is_array($classes)) {
            foreach ($classes as $k => $v) {
                $singleClasses = array_merge($singleClasses, $this->convertClassesStringToArray($v));
            }
        }

        $singleClasses = array_unique($singleClasses);

        return $singleClasses;
    }

    protected function convertClassesStringToArray(string $strClasses): array
    {
        $classes = explode(' ', $strClasses);

        if (empty($classes)) {
            $classes = [];
        }

        return $classes;
    }
}
