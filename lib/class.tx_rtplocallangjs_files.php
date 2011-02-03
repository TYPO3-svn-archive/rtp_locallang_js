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
 * Methods for retrieving, verifying and reading
 * the source locallang files.
 * 
 * @author Simon Tuck <stu@rtp.ch>
 * @package TYPO3
 * @subpackage rtp_locallang_js
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 *
 */
class tx_rtplocallangjs_files
{
	/**
	 * 
	 * Array of source locallang files
	 * @var array
	 */
	private static $_languageFiles			= null;
	
	/**
	 * 
	 * Gets the list of source language files. Only
	 * returns files that are readable.
	 * @return array 
	 */
	public static function getLanguageFiles()
	{
		if(is_null(self::$_languageFiles)) {
			self::$_languageFiles = array();
			if($locallangFiles = tx_rtplocallangjs_div::getConfValue('locallangJsFiles')) {
				self::$_languageFiles = tx_rtplocallangjs_div::trimExplode(',', $locallangFiles);
				self::$_languageFiles = array_map(array('t3lib_div', 'getFileAbsFileName'), self::$_languageFiles);
				self::$_languageFiles = array_filter(self::$_languageFiles, 'is_readable');
			}			
		}
		return self::$_languageFiles;
	}
	
	/**
	 * 
	 * Checks availability of language files
	 * @return boolean
	 */
	public static function hasLanguageFiles()
	{
		return (boolean) self::getLanguageFiles() ? true : false;
	}		
	
	/**
	 * 
	 * Reads and returns the localizations for the 
	 * current language from a given locallang file.
	 * @param string $file Absolute path to language file
	 * @return array
	 */
	public static function readLanguageFile($file)
	{
		return t3lib_div::readLLfile($file, tx_rtplocallangjs_locale::getLangCode(), tx_rtplocallangjs_locale::getRenderCharset());
	}	
} 