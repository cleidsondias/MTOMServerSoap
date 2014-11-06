<?php

namespace SERVERSOAP\abstracts\filter;

require_once '/serversoap/implements/helper/SEISOAPhelper.php';

use SERVERSOAP\implement\helper\SEISOAPhelper;

/**
 * Abstract classe of filter.
 *
 * @author Cleidson Dias do Nascimento - cleidsondias@hotmail.com
 *        
 */
abstract class Filter {
	
	/**
	 * attrib for attachamentType
	 *
	 * @var SEISOAPhelper::ATTACHMENTS_TYPE_SWA
	 */
	protected $attachmentType = SEISOAPhelper::ATTACHMENTS_TYPE_SWA;
	
	/**
	 * construct of class
	 *
	 * @param unknown $attachmentType        	
	 */
	public function __construct($attachmentType) {
		$this->attachmentType = $attachmentType;
	}
}

?>