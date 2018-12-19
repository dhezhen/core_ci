<?php

class Email extends CI_Controller {

    public function send() {
    	$config = Array(
	        'protocol' => 'smtp',
	        'smtp_host' => 'ssl://smtp.googlemail.com',
	        'smtp_port' => 465,
	        'smtp_user' => 'ermawan@bijb.co.id',
	        // 'smtp_pass' => 'SelaluBisa456!',
	        'mailtype' => 'html',
	        'charset' => 'iso-8859-1',
	        'wordwrap' => TRUE,
	        'newline' => "\r\n",
	        'crlf' => "\r\n"
	    );

	    $to = "cinta.tech@gmail.com"; 
        $subject = "ada test"; 
        $message = "test aja yahhh";

        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");

        $this->email->from("ermawan@bijb.co.id");
        $this->email->to($to);
        $this->email->subject($subject);

        $message .= '<br><br><br><br>Sistem E-procurement - BIJB';
        $this->email->message($message);

        return $this->email->send();
    }
}