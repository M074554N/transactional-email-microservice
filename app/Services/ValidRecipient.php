<?php

namespace App\Services;

use Exception;

class ValidRecipient{
	/**
	 * Sanitized & Valid Recipients
	 *
	 * @var $recipients
	 */
	private $recipients = array();

	/**
	 * Create Recipients Instance
	 *
	 * @var String $recipients Comma separated email addresses
	 */
	public function __construct($recipients=''){
		if(!$recipients){
			throw new Exception('Recipients cannot be empty.');
		}

		if(!is_string($recipients)){
			throw new Exception('Recipients must be a string of comma separated values.');
		}

		foreach(explode(',', $recipients) as $recipient){
			if(filter_var($recipient, FILTER_VALIDATE_EMAIL)){
				$this->recipients[] = $recipient;
			}
		}
	}

	/**
	 * Get recipients List
	 *
	 * @return Array $this->recipients
	 */
	public function getRecipients(){
		if(count($this->recipients) < 1){
			throw new Exception('Recipients must contain at least one valid email address.');
		}

		return $this->recipients;
	}
}