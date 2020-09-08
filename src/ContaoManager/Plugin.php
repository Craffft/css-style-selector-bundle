<?php

/*
 * This file is part of the CssStyleSelector Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Craffft\CssStyleSelectorBundle\ContaoManager;

use Contao\CalendarBundle\ContaoCalendarBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\NewsBundle\ContaoNewsBundle;
use Craffft\CssStyleSelectorBundle\CraffftCssStyleSelectorBundle;
use MadeYourDay\RockSolidCustomElements\RockSolidCustomElementsBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(CraffftCssStyleSelectorBundle::class)
                ->setLoadAfter([
                    ContaoCoreBundle::class, 
                    ContaoNewsBundle::class,
                    ContaoCalendarBundle::class,
                    RockSolidCustomElementsBundle::class
                ])
                ->setReplace(['css-style-selector']),
        ];
    }
}
