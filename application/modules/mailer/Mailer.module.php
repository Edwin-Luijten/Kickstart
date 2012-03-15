<?php
class Mailer 
{
    private $_options;
    private $_recipients;
    private $_sender;
    
    function __construct()
    {
    	
    }
    
    private function compose_recipients($recipients)
    {
		if(is_array($recipients))
		{
		    foreach($recipients as $recipient)
		    {
				$recipients .= "{$recipient['realname']}<{$recipient['email']}>,";
		    }
			
		    $this->_recipients = $recipients;
		}
    }
    
    private function compose_attachments()
    {
		// Obtain file upload vars
		$fileatt      = $_FILES['attachment']['tmp_name'];
		$fileatt_type = $_FILES['attachment']['type'];
		$fileatt_name = $_FILES['attachment']['name'];
			
		// create a boundary string. It must be unique
		$semi_rand = md5(time());
		$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
	
		// Add the headers for a file attachment
		$headers = "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" ." boundary=\"{$mime_boundary}\"";
	
		// Add a multipart boundary above the plain message
		$message ="This is a multi-part message in MIME format.\n\n";
	
		$message.="--{$mime_boundary}\n";
		$message.="Content-Type: text/plain; charset=\"iso-8859-1\"\n";
		$message.="Content-Transfer-Encoding: 7bit\n\n";
		
		if(is_uploaded_file($fileatt))
		{
		    //Read the file to be attached ('rb' = read binary)
		    $file = fopen($fileatt,'rb');
		    $data = fread($file,filesize($fileatt));
			
		    fclose($file);
	
		    //Base64 encode the file data
		    $data = chunk_split(base64_encode($data));
	
		    //Add file attachment to the message
		    $message .= "--{$mime_boundary}\n" .
			"Content-Type: {$fileatt_type};\n" .
			" name=\"{$fileatt_name}\"\n" .
			//"Content-Disposition: attachment;\n" .
			//" filename=\"{$fileatt_name}\"\n" .
			"Content-Transfer-Encoding: base64\n\n" .
			$data . "\n\n" .
			"--{$mime_boundary}--\n
		    ";
		}
    }
    
    function mail($options = array())
    {
		if(is_array($options))
		{
		    if(!empty($options['recipients']))
		    {
				$this->compose_recipients($options['recipients']);
		    }
		    else
		    {
			
		    }
		}
	
		if(!mail($this->_recipients,$subject,$message,$headers))
		{
		    
		}
	
    }
}