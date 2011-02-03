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
 * Miscellaneous helper methods for the locallang js extension.
 * 
 * @author Simon Tuck <stu@rtp.ch>
 * @package TYPO3
 * @subpackage rtp_locallang_js
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 *
 */
class tx_rtplocallangjs_div
{
	/**
	 * 
	 * Extension configuration
	 * @var array
	 */
	private static $_conf;
		
	/**
	 * 
	 * Locallang variable name
	 * @var string
	 */
	private static $_locallangVarName	= null;
		
	/**
	 * 
	 * Default locallang variable name
	 * @var string
	 */
	const DEFAULT_LANG_VAR_NAME			= 'TYPO3.lang';	
	
	/**
	 * 
	 * Creates the plugin configuration from the system-wide 
	 * plugin configuration and any TypoScript configuration settings.
	 * @param array $conf Plugin TypoScript configuration
	 * @return void
	 */
	public static function setConf($conf)
	{
		$extConf = array_filter(unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['rtp_locallang_js']), 'strlen');
		$conf = is_array($conf) ? array_filter($conf, 'strlen') : array();
		self::$_conf = array_merge((array) $extConf, (array) $conf);
	}
	
	/**
	 * 
	 * Gets the extension configuration setting for a given key.
	 * @param string $key
	 * @return mixed
	 */
	public static function getConfValue($key)
	{		
		if(is_scalar($key)) return self::$_conf[$key];
		return null;
	}	
	
	/**
	 * 
	 * Splits a string into an array by delimiter and removes empty members.
	 * @param string $delim The delimiter to split the string by
	 * @param string $str The string to be split
	 * @param boolean $onlyNonEmptyValues Strips zero length values from the resulting array if true
	 * @return array
	 */
	public function trimExplode($delim, $str, $onlyNonEmptyValues = true)
	{
		$arr = array_map('trim', explode($delim, $str));
		return $onlyNonEmptyValues ? array_filter($arr, 'strlen') : $arr;
	}
	
	/**
	 * 
	 * Gets the JavaScript locallang variable name. Defaults
	 * to TYPO3.lang when undefined.
	 * @return string|null
	 */
	public static function getLocallangVarName()
	{
		if(is_null(self::$_locallangVarName)) {
			self::$_locallangVarName = trim(self::getConfValue('locallangVarName'));
			if(!self::$_locallangVarName) self::$_locallangVarName = self::DEFAULT_LANG_VAR_NAME;
		}
		return self::$_locallangVarName; 
	}
	
	/**
	 * 
	 * Gets the parts of the locallang variable name that need
	 * to be delcared and returns the declaration statement.
	 * @return string
	 */
	public static function getVarDeclarations()
	{
		$varDeclarations = '';
		$varParts = self::trimExplode('.', self::getLocallangVarName());
		for($i = 0, $var = $varParts[$i]; 
			$i < (count($varParts) - 1); 
			$i++, $var .= '.' . $varParts[$i]) {
				
				// Adds declaration for each variable part with condition that it's currently undefined 
				$varDeclarations .= 'if (typeof ' . $var . ' === \'undefined\') { ' . $var . ' = {} };' . chr(10);
		}
		return $varDeclarations;		
	}
	
	/**
	 * 
	 * Checks if the current TYPO3 version supports the
	 * includeFooterJs setting.
	 * @return boolean
	 */
	public static function hasIncludeJSFooter()
	{
		return version_compare(TYPO3_version, '4.3.0', '>=');
	}	
} 