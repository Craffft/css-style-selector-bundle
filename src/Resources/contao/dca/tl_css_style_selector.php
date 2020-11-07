<?php

/*
 * This file is part of the CssStyleSelector Bundle.
 *
 * (c) Daniel Kiesel <https://github.com/iCodr8>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Table tl_css_style_selector
 */
$GLOBALS['TL_DCA']['tl_css_style_selector'] = [
    // Config
    'config' => [
        'dataContainer' => 'Table',
        'enableVersioning' => true,
        'sql' => [
            'keys' => [
                'id' => 'primary',
            ],
        ],
    ],
    // List
    'list' => [
        'sorting' => [
            'mode' => 11,
            'fields' => ['styleDesignation'],
            'panelLayout' => 'filter;search,limit',
        ],
        'label' => [
            'fields' => [
                'styleDesignation',
                'cssClasses',
                'articleEnabled',
                'calendarEventEnabled',
                'contentEnabled',
                'formEnabled',
                'formFieldEnabled',
                'layoutEnabled',
                'moduleEnabled',
                'newsEnabled',
                'pageEnabled',
            ],
            'showColumns' => true,
            'label_callback' => function ($row, $label, DataContainer $dc, $args) {
                $args[2] = $GLOBALS['TL_LANG']['MSC'][($row['disableInArticle'] ? 'no' : 'yes')];
                $args[3] = $GLOBALS['TL_LANG']['MSC'][($row['disableInCalendarEvent'] ? 'no' : 'yes')];
                $args[4] = $GLOBALS['TL_LANG']['MSC'][($row['disableInContent'] ? 'no' : 'yes')];
                $args[5] = $GLOBALS['TL_LANG']['MSC'][($row['disableInForm'] ? 'no' : 'yes')];
                $args[6] = $GLOBALS['TL_LANG']['MSC'][($row['disableInFormField'] ? 'no' : 'yes')];
                $args[7] = $GLOBALS['TL_LANG']['MSC'][($row['disableInLayout'] ? 'no' : 'yes')];
                $args[8] = $GLOBALS['TL_LANG']['MSC'][($row['disableInModule'] ? 'no' : 'yes')];
                $args[9] = $GLOBALS['TL_LANG']['MSC'][($row['disableInNews'] ? 'no' : 'yes')];
                $args[10] = $GLOBALS['TL_LANG']['MSC'][($row['disableInPage'] ? 'no' : 'yes')];

                return $args;
            },
        ],
        'global_operations' => [
            'all' => [
                'label' => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href' => 'act=select',
                'class' => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ],
        ],
        'operations' => [
            'edit' => [
                'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['edit'],
                'href' => 'act=edit',
                'icon' => 'edit.gif',
            ],
            'copy' => [
                'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['copy'],
                'href' => 'act=copy',
                'icon' => 'copy.gif',
            ],
            'delete' => [
                'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['delete'],
                'href' => 'act=delete',
                'icon' => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\'))return false;Backend.getScrollOffset()"',
            ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['show'],
                'href' => 'act=show',
                'icon' => 'show.gif',
            ],
        ],
    ],
    // Palettes
    'palettes' => [
        'default' => '{style_legend},styleDesignation;{css_legend},cssClasses;{permissions_legend},disableInArticle,disableInContent,disableInCalendarEvent,disableInForm,disableInFormField,disableInLayout,disableInModule,disableInNews,disableInPage',
    ],
    // Fields
    'fields' => [
        'id' => [
            'sql' => "int(10) unsigned NOT NULL auto_increment",
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'",
        ],
        'styleDesignation' => [
            'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['styleDesignation'],
            'exclude' => true,
            'search' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'cssClasses' => [
            'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['cssClasses'],
            'exclude' => true,
            'inputType' => 'text',
            'eval' => ['mandatory' => true, 'maxlength' => 255, 'rgxp' => 'alphanumeric', 'tl_class' => 'w50'],
            'sql' => "varchar(255) NOT NULL default ''",
        ],
        'disableInArticle' => [
            'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['disableInArticle'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'sql' => "int(1) NOT NULL default '0'",
        ],
        'disableInCalendarEvent' => [
            'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['disableInCalendarEvent'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'sql' => "int(1) NOT NULL default '0'",
        ],
        'disableInContent' => [
            'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['disableInContent'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'sql' => "int(1) NOT NULL default '0'",
        ],
        'disableInForm' => [
            'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['disableInForm'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'sql' => "int(1) NOT NULL default '0'",
        ],
        'disableInFormField' => [
            'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['disableInFormField'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'sql' => "int(1) NOT NULL default '0'",
        ],
        'disableInLayout' => [
            'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['disableInLayout'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'sql' => "int(1) NOT NULL default '0'",
        ],
        'disableInModule' => [
            'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['disableInModule'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'sql' => "int(1) NOT NULL default '0'",
        ],
        'disableInNews' => [
            'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['disableInNews'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'sql' => "int(1) NOT NULL default '0'",
        ],
        'disableInPage' => [
            'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['disableInPage'],
            'exclude' => true,
            'filter' => true,
            'inputType' => 'checkbox',
            'sql' => "int(1) NOT NULL default '0'",
        ],
        'articleEnabled' => [
            'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['articleEnabled'],
        ],
        'calendarEventEnabled' => [
            'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['calendarEventEnabled'],
        ],
        'contentEnabled' => [
            'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['contentEnabled'],
        ],
        'formEnabled' => [
            'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['formEnabled'],
        ],
        'formFieldEnabled' => [
            'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['formFieldEnabled'],
        ],
        'layoutEnabled' => [
            'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['layoutEnabled'],
        ],
        'moduleEnabled' => [
            'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['moduleEnabled'],
        ],
        'newsEnabled' => [
            'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['newsEnabled'],
        ],
        'pageEnabled' => [
            'label' => &$GLOBALS['TL_LANG']['tl_css_style_selector']['pageEnabled'],
        ],
    ],
];
