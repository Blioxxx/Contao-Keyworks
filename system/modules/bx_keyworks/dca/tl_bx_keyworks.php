<?php
$GLOBALS['TL_DCA']['tl_bx_keyworks'] = array
(

    // Config
    'config' => array
    (
        'dataContainer'               => 'Table',
        'enableVersioning'            => true,
        'onsubmit_callback' => array
        (
            array('tl_bx_keyworks', 'updateKeywords'),
            array('tl_bx_keyworks', 'updateDescription'),
        ),
        'sql' => array
        (
            'keys' => array
            (
                'id' => 'primary'
            )
        ),
    ),

    // List
    'list' => array
    (
        'sorting' => array
        (
            'mode'                    => 2,
            'fields'                  => array('id'),
            'flag'                    => 1,
            'panelLayout'             => 'search,limit'
        ),
        'label' => array
        (
            'fields'                  => array('name'),
            'format'                  => '%s',
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
                'label'               => &$GLOBALS['TL_LANG']['tl_bx_keyworks']['edit'],
                'href'                => 'act=edit',
                'icon'                => 'edit.gif'
            ),
            'delete' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_bx_keyworks']['delete'],
                'href'                => 'act=delete',
                'icon'                => 'delete.gif',
                'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
            ),
            'show' => array
            (
                'label'               => &$GLOBALS['TL_LANG']['tl_bx_keyworks']['show'],
                'href'                => 'act=show',
                'icon'                => 'show.gif',
                'attributes'          => 'style="margin-right:3px"'
            ),
        )
    ),

    // Palettes
    'palettes' => array
    (
        'default'                     => '{title_legend},name;{Sites},keywordSites,descriptionSites;{meta_data},keywords,description'
    ),

    // Fields
    'fields' => array
    (
        'id' => array
        (
            'sorting'                 => true,
            'sql'                     => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp' => array
        (
            'sql'                     => "int(10) unsigned NOT NULL default '0'"
        ),
        #general
        'name' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_bx_keyworks']['name'],
            'inputType'               => 'text',
            'exclude'                 => true,
            'sorting'                 => true,
            'flag'                    => 1,
            'search'                  => true,
            'eval'                    => array('mandatory'=>true, 'unique'=>true, 'decodeEntities'=>true, 'maxlength'=>128, 'tl_class'=>'w50'),
            'sql'                     => "varchar(128) NOT NULL default ''"
        ),
        #Sites
        'keywordSites' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_bx_keyworks']['keywordSites'],
            'inputType' => 'checkbox',
            'foreignKey'              => "tl_article.CONCAT('<b>',id,'</b> - ',title)",
            'eval' => array('multiple' => true),
            'sql' => "blob NULL"
        ),
        'descriptionSites' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_bx_keyworks']['descriptionSites'],
            'inputType' => 'checkbox',
            'foreignKey'              => "tl_page.CONCAT('<b>',id,'</b> - ',title)",
            'eval' => array('multiple' => true),
            'sql' => "blob NULL"
        ),
        #Editable
        'keywords' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_bx_keyworks']['keywords'],
            'inputType'               => 'text',
            'exclude'                 => true,
            'sorting'                 => false,
            'flag'                    => 1,
            'search'                  => false,
            'eval'                    => array('mandatory'=>true, 'unique'=>false, 'decodeEntities'=>true, 'maxlength'=>500, 'tl_class'=>'long'),
            'sql'                     => "text NOT NULL"
        ),
        'description' => array
        (
            'label'                   => &$GLOBALS['TL_LANG']['tl_bx_keyworks']['description'],
            'inputType'               => 'text',
            'exclude'                 => true,
            'sorting'                 => false,
            'flag'                    => 1,
            'search'                  => false,
            'eval'                    => array('mandatory'=>false, 'unique'=>false, 'decodeEntities'=>true, 'maxlength'=>500, 'tl_class'=>'long'),
            'sql'                     => "text NOT NULL"
        ),
    )
);

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Mirco Kibiger <http://mircokibiger.de>
 */
class tl_bx_keyworks extends Backend
{
    /**
     * @param DataContainer $dc
     */
    public function updateKeywords(DataContainer $dc){
        $currentNumber = count($dc->activeRecord->keywordSites)+1;
        $pages = deserialize($currentPage = $dc->activeRecord->descriptionSite);

        for ($i = 1; $i < $currentNumber;$i++){
            $arrSet['keywords'] = $dc->activeRecord->keywords;
            $this->Database->prepare("UPDATE tl_article %s WHERE id=?")->set($arrSet)->execute($i);
        }
    }

    /**
     * @param Datacontainer $dc
     */
    public function updateDescription(Datacontainer $dc){
        $currentNumber = count($dc->activeRecord->descriptionSites)+1;
        $pages = deserialize($currentPage = $dc->activeRecord->descriptionSites);

        for ($i = 0; $i < $currentNumber;$i++){
            $currentPage = $pages[$i];
            $arrSet['description'] = $dc->activeRecord->description;
            $this->Database->prepare("UPDATE tl_page %s WHERE id=?")->set($arrSet)->execute($currentPage);
        }
    }
}