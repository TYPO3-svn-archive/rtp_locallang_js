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

require_once(t3lib_extMgm::extPath('rtp_locallang_js') . 'lib/class.tx_rtplocallangjs_div.php');
require_once(t3lib_extMgm::extPath('rtp_locallang_js') . 'lib/class.tx_rtplocallangjs_files.php');
require_once(t3lib_extMgm::extPath('rtp_locallang_js') . 'lib/class.tx_rtplocallangjs_locale.php');
require_once(t3lib_extMgm::extPath('rtp_locallang_js') . 'lib/class.tx_rtplocallangjs_temp.php');

/**
 * Plugin 'Javascript Language Labels' for the 'rtp_locallang_js' extension.
 *
 * @author	Simon Tuck <stu@rtp.ch>
 * @package	TYPO3
 * @subpackage	tx_rtplocallangjs
 */
class tx_rtplocallangjs_pi1 
{	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string $content The PlugIn content
	 * @param	array $conf The PlugIn configuration
	 * @return	void
	 */
	function main($content, $conf)	
	{		
		// Sets the conf array from typoscript
		// settings and extension configuration
		tx_rtplocallangjs_div::setConf($conf);
		
		// Generates the javascript locallang file
		if(tx_rtplocallangjs_files::hasLanguageFiles()) {
			
			// Sets unique id based on timestamps of locallang files,
			// current language and locallang variable name
			tx_rtplocallangjs_temp::setTempFileId(tx_rtplocallangjs_files::getLanguageFiles());
			
			// Insert the locallang js file into the header or (if supported
			// and configured) at the end of the page.
			// Note: File is prepended (array_unshift) so that locallang labels 
			// are included *before* other js files and header data.
			if( tx_rtplocallangjs_div::getConfValue('includeJSFooter')
				&& tx_rtplocallangjs_div::hasIncludeJSFooter()) {
					
				// Insert in footer
				if(empty($GLOBALS['TSFE']->pSetup['includeJSFooter.'])) {
					$GLOBALS['TSFE']->pSetup['includeJSFooter.'] = array();	
				}
				array_unshift($GLOBALS['TSFE']->pSetup['includeJSFooter.'], self::_getLocallangJs());
						
			} else {
				
				// Insert in header
				if(empty($GLOBALS['TSFE']->pSetup['includeJS.'])) {
					$GLOBALS['TSFE']->pSetup['includeJS.'] = array();
				}
				array_unshift($GLOBALS['TSFE']->pSetup['includeJS.'], self::_getLocallangJs());
				
			}			
		}				
	}
	
	/**
	 * 
	 * Returns the relative path to the temp file containing
	 * the javascript localization for the current language.
	 * @return string
	 */
	private static function _getLocallangJs()
	{
		// Creates the locallang js if no corresponding temp file exists
		if(!tx_rtplocallangjs_temp::hasTempFile()) self::_setLocallangJs();

		// Returns the relative path to the locallang js file
		return tx_rtplocallangjs_temp::getTempFile();	
	}
	
	/**
	 * 
	 * Builds the locallang javascript from the source language
	 * files and saves the result to the typo3temp dir.
	 * @return void
	 */
	private static function _setLocallangJs()
	{
		// Builds an array of language labels and values based on the default  
		// language and the current language from the available language files
		foreach(tx_rtplocallangjs_files::getLanguageFiles() as $languageFile) {
			$languageFileLabels = tx_rtplocallangjs_files::readLanguageFile($languageFile);						
			$labels	= array_merge((array) $languageFileLabels[tx_rtplocallangjs_locale::DEFAULT_LANG_CODE], 
								  (array) $languageFileLabels[tx_rtplocallangjs_locale::getLangCode()],
								  (array) $labels);		
		}
		
		// Declare the js locallang variable
		$locallangJs  = tx_rtplocallangjs_div::getVarDeclarations();
		$locallangJs .= tx_rtplocallangjs_div::getLocallangVarName() . ' = {';
		
		// Builds the locallang array in js
		if(!empty($labels)) {
			$locallangJs .= chr(10);
			foreach ($labels as $key => $value) {
				$locallangJs .= chr(9) . '\'' . addslashes($key) . '\' : \'' . addslashes($value) . '\',' . chr(10);
			}
			$locallangJs = substr($locallangJs, 0, -2) . chr(10);					
		}
		$locallangJs .= '};';

		// Writes the locallang js to the typo3temp dir
		tx_rtplocallangjs_temp::saveTempFile($locallangJs);		
	}
}