<?php

namespace SERVERSOAP\abstracts\filter;

use SERVERSOAP\implement\helper\SOAPhelper;

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
	 * @var SOAPhelper::ATTACHMENTS_TYPE_SWA
	 */
	protected $attachmentType = SOAPhelper::ATTACHMENTS_TYPE_SWA;
	
	/**
	 * construct of class
	 *
	 * @param unknown $attachmentType        	
	 */
	public function __construct($attachmentType) {
		$this->attachmentType = $attachmentType;
	}
}