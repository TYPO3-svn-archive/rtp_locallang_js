<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 rtp <rtp.ch>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * 
 * Methods for retrieving, verifying and saving the locallang
 * javascript files in typo3temp.
 * 
 * @author Simon Tuck <stu@rtp.ch>
 * @package TYPO3
 * @subpackage rtp_locallang_js
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 *
 */
class tx_rtplocallangjs_temp
{	
	/**
	 * 
	 * Id used for the locallang file name.
	 * @var string
	 */
	private static $_tempFileId				= null;
	
	/**
	 * 
	 * File extension for the locallang files.
	 * @var string 
	 */
	const TEMP_FILE_EXTENSION				= 'js';
	
	/**
	 * 
	 * Directory relative to PATH_site where 
	 * the locallang files are stored.
	 * @var string
	 */
	const TEMP_DIR							= 'typo3temp/locallang/';
	
	/**
	 * 
	 * Checks whether javascript localization files 
	 * are available.
	 * @return boolean
	 */
	public static function hasTempFile()
	{
		return is_readable(self::_getAbsTempDir() . self::getTempFileName());
	}

	/**
	 * 
	 * Writes the js localization data to the typo3temp directory
	 * @param array $data Localization data for the current language
	 * @return void
	 */
	public static function saveTempFile($data)
	{
		self::_mkTempDir(); // Ensures temp dir exists
		t3lib_div::writeFileToTypo3tempDir(self::_getAbsTempDir() . self::getTempFileName(), $data);
	}
	
	/**
	 * 
	 * Gets the relative path to the locallang js file
	 * @return string	
	 */
	public static function getTempFile()
	{
		return self::TEMP_DIR . self::getTempFileName();
	}	
	
	/**
	 * 
	 * Retrieves the name of the locallang js file
	 * @return string
	 */
	public static function getTempFileName()
	{
		return self::getTempFileId() . '.' . self::TEMP_FILE_EXTENSION;
	}

	/**
	 * 
	 * Sets a temp id based on the timestamps of the 
	 * locallang files and the current language
	 * @param array $languageFiles
	 * @return void
	 */
	public static function setTempFileId(array $languageFiles) 
	{
		$languageFiles = array_map('filectime', $languageFiles);
		array_unshift($languageFiles, tx_rtplocallangjs_locale::getLangCode());
		array_unshift($languageFiles, tx_rtplocallangjs_div::getLocallangVarName());	
		self::$_tempFileId = substr(sha1(serialize($languageFiles)), 4, 12);
	}

	/**
	 * 
	 * Gets the temp id (used for the file name)
	 * @return string
	 */
	public static function getTempFileId() 
	{
		if(is_null(self::$_tempFileId)) {
			throw new t3lib_error_Exception('rtp_locallang_js: Undefined temp file id.', 1296648780); 
		}
		return self::$_tempFileId;
	}
	
	/**
	 * 
	 * Get the absolute path to the js file
	 * @return string
	 */
	private static function _getAbsTempDir()
	{
		return PATH_site . self::TEMP_DIR;
	}
	
	/**
	 * 
	 * Ensures that the temp dir is available
	 * creates the directory if unavailable
	 * @return void
	 */
	private static function _mkTempDir()
	{
		if(!is_dir(self::_getAbsTempDir())) t3lib_div::mkDir(self::_getAbsTempDir());
	}
} 