<?php
namespace Settings\Service;

use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;

class MySendMail{
	
	// mail config service
	protected $diConfig = array(
			'name'              => 'smtp.163.com',
			'host'              => 'smtp.163.com',
			'port' => 25,
			'connectionClass'  => 'login',
			'connectionConfig' => array(
					'username' => '83008@163.com',
					'password' => '214348aaa',
			),
	);
	
	protected $transport,$mail,$body;

	protected $is_ok = true;

	// init
	public function __construct(array $config = array()){
		if(count($config)>0){
			$this->diConfig = $config;
		}
		$this->mail = new Message();
		$this->transport = new SmtpTransport();
		$options   = new SmtpOptions($this->diConfig);
		$this->transport->setOptions($options);
	}

	public function send(){

		//	var_dump($this->mail->getHeaders());
		$transport = $this->transport;
		$transport->send($this->mail);

	}

	public function setBodyCallback(callback $fname = null){
		if(is_callable($name)){
			$this->mail->setBody($fname($this));
		}
		return $this;
	}
	public function subject($title = null){
		$this->mail->setSubject($title);

		return $this;
	}
	public function setBody($body = null){
		
		$html = new MimePart($body);
		$html->type = "text/html";
		$body = new MimeMessage();
		$body->addPart($html);
		$this->mail->setBody($body);
		return $this;
	}
	public function addTo($address = null,$fullName = null){
		$this->mail->addTo($address,$fullName);
		return $this;
	}

	public function setFrom($address = null,$fullName = null){
		$this->mail->setFrom($address,$fullName);
		return $this;
	}
}
