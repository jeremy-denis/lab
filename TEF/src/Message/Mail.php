<?php

namespace Message;

class Mail
{
	private $subject;
	private $from;
	private $to;
	private $body;
	
	public function __construct($subject,$from,$to,$body)
	{
		$this->subject = $subject;
		$this->from = $from;
		$this->to = $to;
		$this->body = $body;
	}
	
	public function getSubject()
	{
		return $this->subject;
	}
	
	public function setSubject($subject)
	{
		$this->subject = $subject;
	}
	
	public function setTo($to)
	{
		$this->to = $to;	
	}
	
	public function setFrom($from)
	{
		$this->from = $from;
	}
	
	public function setBody($body)
	{
		$this->body = $body;
	}
	
	public function send()
	{
		$headers = "From: <$this->from>"."\r\n";
		$headers .= '\r\n';
		mail($this->to, $this->subject, $this->body, $headers);
	}
}
