<?php
/*
Credits: Bit Repository
URL: http://www.bitrepository.com/
*/

//include 'config.php';

error_reporting (E_ALL ^ E_NOTICE);

$post = (!empty($_POST)) ? true : false;



function submitForm()
{
				

	$name = stripslashes($_POST['name']);
	$infringingurl = trim($_POST['infringingurl']);
	$originalurl = stripslashes($_POST['originalurl']);
	$email = stripslashes($_POST['email']);
	$comments = stripslashes($_POST['comments']);
	$who = stripslashes($_POST['who']);
	$subject = "Copyright evaluation";


	$error = '';

	// Check name

	if(!$name || $name == 'Your Name')
	{
	$error .= 'Please enter your name.<br />';
	}

	// Check infringing url

	if(!$infringingurl || $infringingurl == 'Infringing URL')
	{
	$error .= 'Please type the infringing URL stealing content.<br />';
	}

	// Check original url --- taken out

	//if(!$originalurl || $originalurl == 'Original Content URL')
	//{
	//$error .= 'Please type your URL that contains your content.<br />';
	//}

	// Check comments

	if(!$comments || $comments == 'Describe the infringement')
	{
	$error .= 'Please add any details about your content and the infringing URL.<br />';
	}

	// Check email

	if(!$email || $email == 'Your Email')
	{
	$error .= 'Please enter an e-mail address.<br />';
	}

	if($email && !ValidateEmail($email))
	{
	$error .= 'Please enter a valid e-mail address.<br />';
	}

	//build the message
	$message = "NAME: ".$name."\r\nINFRINGING URL: ".$infringingurl."\r\nORIGINAL URL: ".$originalurl."\r\nEMAIL: ".$email."\r\nCOMMENTS: ".$comments."\r\nWHO OWNS: ".$who."\r\n";


	if(!$error)
	{
	$to = "evaluations@dooplee.com, archon10@gmail.com";

	// $mail = mail("archon10@gmail.com", $subject, $message,
		 // "From: ".$name." <".$email.">\r\n"
		// ."Reply-To: ".$email."\r\n"
		// ."X-Mailer: PHP/" . phpversion());
		
	wp_mail( $to, $subject, $message );

	if($error == '')
	{
		echo "<font color='red'>Your DMCA evaluation has been sent. We will evaluate the complaint and send you a message within 48 hours</font>";  
	}

	}
	else
	{
		echo "<font color='red'><b>There were some errors submitting your form</b><br>".$error."</font>";
	}

}

function ValidateEmail($email)
{
	/*
	(Name) Letters, Numbers, Dots, Hyphens and Underscores
	(@ sign)
	(Domain) (with possible subdomain(s) ).
	Contains only letters, numbers, dots and hyphens (up to 255 characters)
	(. sign)
	(Extension) Letters only (up to 10 (can be increased in the future) characters)
	*/

	$regex = '/([a-z0-9_.-]+)'. # name

	'@'. # at

	'([a-z0-9.-]+){2,255}'. # domain & possibly subdomains

	'.'. # period

	'([a-z]+){2,10}/i'; # domain extension 

	if($email == '') { 
		return false;
	}
	else {
	$eregi = preg_replace($regex, '', $email);
	}

	return empty($eregi) ? true : false;
}
?>