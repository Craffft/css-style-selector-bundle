<?php

/*
 * This file is part of the CssStyleSelector Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Craffft\CssStyleSelectorBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Craffft\CssStyleSelectorBundle\CraffftCssStyleSelectorBundle;
use MadeYourDay\RockSolidCustomElements\RockSolidCustomElementsBundle;

/**
 * Plugin for the Contao Manager.
 *
 * @author Fritz Michael Gschwantner <fmg@inspiredminds.at>
 */
class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(CraffftCssStyleSelectorBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class, RockSolidCustomElementsBundle::class])
                ->setReplace(['css-style-selector']),
        ];
    }
}
