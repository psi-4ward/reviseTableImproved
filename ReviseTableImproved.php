<?php

/**
 * @copyright 4ward.media 2013 <http://www.4wardmedia.de>
 * @author Christoph Wiechert <wio@psitrax.de>
 */

class ReviseTableImproved extends \System
{

	/**
	 * Inject oncreate_callbacks to any DCA containers
	 * @param $table
	 */
	public function injectCallbacks($table)
	{
		$GLOBALS['TL_DCA'][$table]['config']['oncreate_callback'][] = array('ReviseTableImproved', 'createNewrecord');
	}


	/**
	 * Save the "newrecord" not only in the session,
	 * store it in the database to be able to delete it if the session is gone
	 *
	 * @param $table
	 * @param $insertID
	 */
	public function createNewrecord($table, $insertID)
	{
		\Database::getInstance()->prepare('INSERT INTO tl_newrecords %s')->set(array(
			'tl_sessionId' => session_id(),
			'newrecord_table' => $table,
			'newrecord_id' => $insertID,
		))->executeUncached();
	}


	/**
	 * Delete all incomplete records without reference to an existing session
	 *
	 * @param $strTable
	 * @return bool
	 */
	public function reviseTable($strTable)
	{
		$db = \Database::getInstance();
		$reload = false;

		$objRecords = $db->executeUncached('SELECT * FROM tl_newrecords WHERE NOT EXISTS (SELECT sessionID FROM tl_session WHERE tl_newrecords.tl_sessionId = tl_session.sessionID)');
		$arrRevised = array();
		while($objRecords->next())
		{
			$objNew = $db->executeUncached('SELECT tstamp FROM '.$objRecords->newrecord_table.' WHERE id='.$objRecords->newrecord_id);
			$arrRevised[] = $objRecords->id;
			if($objNew->tstamp != null && $objNew->tstamp == 0)
			{
				$db->executeUncached('DELETE FROM '.$objRecords->newrecord_table.' WHERE id='.$objRecords->newrecord_id.' LIMIT 1');
				if($strTable == $objRecords->newrecord_table)
				{
					$reload = true;
				}
			}
		}
		if(!empty($arrRevised))
		{
			$db->executeUncached('DELETE FROM tl_newrecords WHERE id IN ('.implode(',' ,$arrRevised).')');
		}

		return $reload;
	}
}