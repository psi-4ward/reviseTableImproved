<?php

/**
 * @copyright 4ward.media 2013 <http://www.4wardmedia.de>
 * @author Christoph Wiechert <wio@psitrax.de>
 */


if(\Input::get('do') != 'repository_manager' && TL_MODE == 'BE')
{
	$GLOBALS['TL_HOOKS']['loadDataContainer'][] = array('ReviseTableImproved','injectCallbacks');
	$GLOBALS['TL_HOOKS']['reviseTable'][] = array('ReviseTableImproved','reviseTable');
}
