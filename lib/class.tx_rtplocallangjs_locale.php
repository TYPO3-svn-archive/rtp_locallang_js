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
 * Locale detection methods for the locallang js extension.
 * 
 * @author Simon Tuck <stu@rtp.ch>
 * @package TYPO3
 * @subpackage rtp_locallang_js
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 *
 */
class tx_rtplocallangjs_locale
{	
	/**
	 * 
	 * Default langauge code in locallang files
	 * @var string
	 */
	const DEFAULT_LANG_CODE						= 'default';
	
	/**
	 * 
	 * Returns the TYPO3 code for the current language.
	 * @return string
	 */
	public static function getLangCode()
	{
		return $GLOBALS['TSFE']->lang;
	}	
	
	/**
	 * 
	 * Returns the charset used for internal rendering 
	 * of the page content.
	 * @return string
	 */
	public static function getRenderCharset()
	{
		return $GLOBALS['TSFE']->renderCharset;		
	}
} 