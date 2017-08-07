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
$GLOBALS['TL_DCA']['tl_css_style_selector'] = array
(
    // Config
    'config'   => array
    (
        'dataContainer'               => 'Table',
        'enableVersioning'            => true,
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary'
            )
        )
    ),
    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 11,
            'fields'                  => array('styleDesignation'),
            'panelLayout'             => 'filter;search,limit'
        ),
        'label' => array
        (
            'fields'                  => array('styleDesignation', 'cssClasses', 'articleEnabled', 'calendarEventEnabled', 'contentEnabled', 'formEnabled', 'formFieldEnabled', 'layoutEnabled', 'moduleEnabled', 'newsEnabled', 'pageEnabled'),
            'showColumns'             => true,
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
            }
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'                => 'act=select',
                'class'               => 'header_edit_all',
                'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations' => array
        (
            'edit' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_css_style_selector']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'copy'   => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_css_style_selector']['copy'],
                'href'                => 'act=paste&amp;mode=copy',
                'icon'                => 'copy.gif',
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_css_style_selector']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_css_style_selector']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif'
            )
        )
    ),
    // Palettes
    'palettes' => array
    (
        'default' => '{style_legend},styleDesignation;{css_legend},cssClasses;{permissions_legend},disableInArticle,disableInContent,disableInCalendarEvent,disableInForm,disableInFormField,disableInLayout,disableInModule,disableInNews,disableInPage'
    ),
    // Fields
    'fields'   => array
    (
        'id' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        'styleDesignation' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_css_style_selector']['styleDesignation'],
            'exclude'                 => true,
            'search'                  => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'cssClasses' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_css_style_selector']['cssClasses'],
            'exclude'                 => true,
            'inputType'               => 'text',
            'eval'                    => array('mandatory'=>true, 'maxlength'=>255, 'rgxp'=>'alphanumeric', 'tl_class'=>'w50'),
            'sql'                     => "varchar(255) NOT NULL default ''"
        ),
        'disableInArticle' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_css_style_selector']['disableInArticle'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'sql'                     => "int(1) NOT NULL default '0'"
        ),
        'disableInCalendarEvent' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_css_style_selector']['disableInCalendarEvent'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'sql'                     => "int(1) NOT NULL default '0'"
        ),
        'disableInContent' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_css_style_selector']['disableInContent'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'sql'                     => "int(1) NOT NULL default '0'"
        ),
        'disableInForm' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_css_style_selector']['disableInForm'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'sql'                     => "int(1) NOT NULL default '0'"
        ),
        'disableInFormField' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_css_style_selector']['disableInFormField'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'sql'                     => "int(1) NOT NULL default '0'"
        ),
        'disableInLayout' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_css_style_selector']['disableInLayout'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'sql'                     => "int(1) NOT NULL default '0'"
        ),
        'disableInModule' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_css_style_selector']['disableInModule'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'sql'                     => "int(1) NOT NULL default '0'"
        ),
        'disableInNews' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_css_style_selector']['disableInNews'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'sql'                     => "int(1) NOT NULL default '0'"
        ),
        'disableInPage' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_css_style_selector']['disableInPage'],
            'exclude'                 => true,
            'filter'                  => true,
            'inputType'               => 'checkbox',
            'sql'                     => "int(1) NOT NULL default '0'"
        ),
        'articleEnabled' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_css_style_selector']['articleEnabled'],
        ),
        'calendarEventEnabled' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_css_style_selector']['calendarEventEnabled'],
        ),
        'contentEnabled' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_css_style_selector']['contentEnabled'],
        ),
        'formEnabled' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_css_style_selector']['formEnabled'],
        ),
        'formFieldEnabled' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_css_style_selector']['formFieldEnabled'],
        ),
        'layoutEnabled' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_css_style_selector']['layoutEnabled'],
        ),
        'moduleEnabled' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_css_style_selector']['moduleEnabled'],
        ),
        'newsEnabled' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_css_style_selector']['newsEnabled'],
        ),
        'pageEnabled' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_css_style_selector']['pageEnabled'],
        )
    )
);
