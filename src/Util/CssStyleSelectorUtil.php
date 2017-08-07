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
use Craffft\CssStyleSelectorBundle\Models\CssStyleSelectorModel;

class CssStyleSelectorUtil
{
    /**
     * @param $varValue
     * @param DataContainer $dc
     * @param string $specialName
     * @return bool
     */
    public function saveCssIdCallback($varValue, DataContainer $dc, $specialName = 'cssID')
    {
        if (!$dc->activeRecord) {
            return false;
        }

        $arrCssID = $this->getCssIDValue($dc, $specialName);
        $arrClasses = $this->getClassesFromCssIDAsArray($arrCssID);

        // Remove all known cssStyleSelector classes from cssID classes
        $arrClasses = array_diff($arrClasses, $this->getAllCssStyleSelectorClassesByTable($dc->table));

        // Add all selected classes of CssStyleSelector to the classes of cssID
        $arrCssClassesSelectorIds = $this->convertSerializedCssStyleSelectorToArray($varValue);
        $arrClasses = array_merge($arrClasses, $this->getCssStyleSelectorClassesByIds($arrCssClassesSelectorIds));

        $arrClasses = array_unique($arrClasses);

        $this->saveClassesToCssID($arrClasses, $dc, $specialName);

        return $varValue;
    }

    /**
     * @param $varValue
     * @param DataContainer $dc
     * @param string $specialName
     * @return bool
     */
    public function saveCssClassCallback($varValue, DataContainer $dc, $specialName = 'cssClass')
    {
        if (!$dc->activeRecord) {
            return false;
        }

        $strCssClasses = $this->getCssClassValue($dc, $specialName);
        $arrClasses = $this->convertClassesStringToArray($strCssClasses);

        // Remove all known cssStyleSelector classes from cssID classes
        $arrClasses = array_diff($arrClasses, $this->getAllCssStyleSelectorClassesByTable($dc->table));

        // Add all selected classes of CssStyleSelector to the classes of cssID
        $arrCssClassesSelectorIds = $this->convertSerializedCssStyleSelectorToArray($varValue);
        $arrClasses = array_merge($arrClasses, $this->getCssStyleSelectorClassesByIds($arrCssClassesSelectorIds));

        $arrClasses = array_unique($arrClasses);

        $this->saveClassesToCssClass($arrClasses, $dc, $specialName);

        return $varValue;
    }

    /**
     * @param $intId
     * @param string $specialName
     * @return string
     */
    protected function getCssIDName($intId, $specialName = 'cssID')
    {
        return $specialName . ((Input::get('act') == 'editAll') ? '_' . $intId : '');
    }

    /**
     * @param DataContainer $dc
     * @param string $specialName
     * @return array
     */
    protected function getCssIDValue(DataContainer $dc, $specialName = 'cssID')
    {
        $arrCssID = Input::post($this->getCssIDName($dc->id, $specialName));

        if ($arrCssID === null) {
            $arrCssID = deserialize($dc->activeRecord->cssID);
        }

        if (!is_array($arrCssID)) {
            $arrCssID = array();
        }

        return $arrCssID;
    }

    /**
     * @param $intId
     * @param string $specialName
     * @return string
     */
    protected function getCssClassName($intId, $specialName = 'cssClass')
    {
        return $specialName . ((Input::get('act') == 'editAll') ? '_' . $intId : '');
    }

    /**
     * @param DataContainer $dc
     * @param string $specialName
     * @return string
     */
    protected function getCssClassValue(DataContainer $dc, $specialName = 'cssClass')
    {
        $strCssClass = Input::post($this->getCssClassName($dc->id, $specialName));

        if ($strCssClass === null) {
            $strCssClass = $dc->activeRecord->cssClass;
        }

        if (!is_string($strCssClass)) {
            $strCssClass = '';
        }

        return $strCssClass;
    }

    /**
     * @param string $strValue
     * @return array
     */
    protected function convertSerializedCssStyleSelectorToArray($strValue)
    {
        $arrIds = deserialize($strValue);

        if (!is_array($arrIds)) {
            $arrIds = array();
        }

        return $arrIds;
    }

    /**
     * @param array $arrClasses
     * @param DataContainer $dc
     * @param string $specialName
     */
    protected function saveClassesToCssID(array $arrClasses, DataContainer $dc, $specialName = 'cssID')
    {
        $strCssIDName = $this->getCssIDName($dc->id, $specialName);

        $arrPostedCssID = Input::post($strCssIDName);
        $arrPostedCssID[1] = implode(' ', $arrClasses);
        $arrPostedCssID[1] = str_replace('  ', ' ', $arrPostedCssID[1]);
        $arrPostedCssID[1] = trim($arrPostedCssID[1]);

        $dc->activeRecord->cssID = serialize($arrPostedCssID);
        Input::setPost($strCssIDName, $arrPostedCssID);

        $objDatabase = Database::getInstance();
        $objDatabase->prepare("UPDATE $dc->table SET " . $specialName . "=? WHERE id=?")
            ->execute(serialize($arrPostedCssID), $dc->id);
    }

    /**
     * @param array $arrClasses
     * @param DataContainer $dc
     * @param string $specialName
     */
    protected function saveClassesToCssClass(array $arrClasses, DataContainer $dc, $specialName = 'cssClass')
    {
        $strCssClassName = $this->getCssClassName($dc->id, $specialName);

        $strClasses = implode(' ', $arrClasses);
        $strClasses = str_replace('  ', ' ', $strClasses);
        $strClasses = trim($strClasses);

        $dc->activeRecord->cssClass = $strClasses;
        Input::setPost($strCssClassName, $strClasses);

        $objDatabase = Database::getInstance();
        $objDatabase->prepare("UPDATE $dc->table SET " . $specialName . "=? WHERE id=?")
            ->execute($strClasses, $dc->id);
    }

    /**
     * @param array $arrCssID
     * @return array
     */
    protected function getClassesFromCssIDAsArray(array $arrCssID)
    {
        list($strId, $strClasses) = $arrCssID;

        $arrClasses = $this->convertClassesStringToArray($strClasses);

        return $arrClasses;
    }

    /**
     * @param array $arrIds
     * @return array
     */
    protected function getCssStyleSelectorClassesByIds(array $arrIds)
    {
        if (empty($arrIds)) {
            return array();
        }

        $arrClasses = CssStyleSelectorModel::findCssClassesByIds($arrIds);

        return $this->convertCombinedClassesToSingleClasses($arrClasses);
    }

    /**
     * @param string $strTable
     * @return array
     */
    protected function getAllCssStyleSelectorClassesByTable($strTable)
    {
        if (empty($strTable)) {
            return array();
        }

        $strType = strtolower(substr($strTable, 3));

        $arrClasses = CssStyleSelectorModel::findCssClassesByNotDisabledType($strType);
        $arrClasses = $this->convertCombinedClassesToSingleClasses($arrClasses);

        return $arrClasses;
    }

    /**
     * @param array $arrClasses
     * @return array
     */
    protected function convertCombinedClassesToSingleClasses(array $arrClasses)
    {
        $arrSingleClasses = array();

        if (is_array($arrClasses)) {
            foreach ($arrClasses as $k => $v) {
                $arrSingleClasses = array_merge($arrSingleClasses, $this->convertClassesStringToArray($v));
            }
        }

        $arrSingleClasses = array_unique($arrSingleClasses);

        return $arrSingleClasses;
    }

    /**
     * @param string $strClasses
     * @return array
     */
    protected function convertClassesStringToArray($strClasses)
    {
        $arrClasses = explode(' ', $strClasses);

        if (empty($arrClasses)) {
            $arrClasses = array();
        }

        return $arrClasses;
    }
}
