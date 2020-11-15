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
use function explode;
use function str_replace;

class CssStyleSelectorUtil
{
    public function saveCallback(?string $value, DataContainer $dc, bool $cssIdType, string $name): ?string
    {
        if (!$dc->activeRecord) {
            return null;
        }

        $value = (string) $value;

        $classesValue = $this->getValueFromPostOrActiveRecord($dc, $name, $cssIdType);
        $classes = $this->getClassesArrayFromValue($classesValue, $cssIdType);

        // Remove all known cssStyleSelector classes from cssID classes
        $classes = array_diff($classes, $this->getAllCssStyleSelectorClassesByTable($dc->table));

        // Add all selected classes of CssStyleSelector to the classes of cssID
        $cssClassesSelectorIds = $this->convertSerializedCssStyleSelectorToArray($value);
        $classes = array_merge($classes, $this->getCssStyleSelectorClassesByIds($cssClassesSelectorIds));
        $classes = array_unique($classes);

        $this->saveClasses($classes, $dc, $cssIdType, $name);

        return $value;
    }

    public function hasClassesOfSelector(
        string $selectorClassesValue,
        string $relationTableClassesValue,
        bool $cssIdType
    ) {
        $selectorClasses = $this->convertClassesStringToArray(StringUtil::deserialize($selectorClassesValue));
        $relationTableClasses = $this->getClassesArrayFromValue($relationTableClassesValue, $cssIdType);

        return (count(array_intersect($relationTableClasses, $selectorClasses)) === count($selectorClasses));
    }

    protected function getValueFromPostOrActiveRecord(DataContainer $dc, string $name)
    {
        $value = Input::post($this->getName($dc->id, $name));

        if ($value === null) {
            $value = $dc->activeRecord->{$name};
        }

        return $value;
    }

    protected function getClassesArrayFromValue($value, bool $cssIdType): array
    {
        $value = StringUtil::deserialize($value);

        if (is_array($value)) {
            $value = count($value) === 2 ? $value[1] : '';
        }

        if (!is_string($value)) {
            $value = '';
        }

        return $this->convertClassesStringToArray($value);
    }

    protected function getName(int $id, string $name): string
    {
        return $name.((Input::get('act') === 'editAll') ? '_'.$id : '');
    }

    protected function convertSerializedCssStyleSelectorToArray(string $value): array
    {
        $ids = StringUtil::deserialize($value);

        if (!is_array($ids)) {
            $ids = [];
        }

        return $ids;
    }

    protected function saveClasses(array $classes, DataContainer $dc, bool $cssIdType, string $name): void
    {
        if ($cssIdType) {
            $this->saveClassesToCssID($classes, $dc, $name);
        } else {
            $this->saveClassesToCssClass($classes, $dc, $name);
        }
    }

    protected function saveClassesToCssID(array $classes, DataContainer $dc, string $name): void
    {
        $cssIDName = $this->getName($dc->id, $name);

        $postedCssID = Input::post($cssIDName);
        $postedCssID[1] = implode(' ', $classes);
        $postedCssID[1] = str_replace('  ', ' ', $postedCssID[1]);
        $postedCssID[1] = trim($postedCssID[1]);

        $dc->activeRecord->cssID = serialize($postedCssID);
        Input::setPost($cssIDName, $postedCssID);

        $objDatabase = Database::getInstance();
        $objDatabase->prepare("UPDATE $dc->table SET ".$name."=? WHERE id=?")
            ->execute(serialize($postedCssID), $dc->id);
    }

    protected function saveClassesToCssClass(array $arrClasses, DataContainer $dc, string $name): void
    {
        $cssClassName = $this->getName($dc->id, $name);

        $classes = implode(' ', $arrClasses);
        $classes = str_replace('  ', ' ', $classes);
        $classes = trim($classes);

        $dc->activeRecord->cssClass = $classes;
        Input::setPost($cssClassName, $classes);

        $objDatabase = Database::getInstance();
        $objDatabase->prepare("UPDATE $dc->table SET ".$name."=? WHERE id=?")
            ->execute($classes, $dc->id);
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

        $type = CssStyleSelectorModel::getTypeByTable($strTable);

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
