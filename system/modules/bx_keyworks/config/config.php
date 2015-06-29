<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Back end modules
 */
array_insert($GLOBALS['BE_MOD']['system'], 0, array
(
	'bx_keyworks' => array
	(
		'tables'      => array('tl_bx_keyworks'),
		'icon'        => 'system/modules/bx_keyworks/html/icon.png',
	)
));