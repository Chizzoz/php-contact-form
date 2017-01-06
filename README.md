# PHP Contact Form
**A simple one page contact form implemented using PHP with Perform Regular Expression Match (preg_match) validation and some CSS styling.**

I have noticed this question asked a number of times in some developer groups on Facebook, WhatsApp, forums, etc. I usually feel lazy to respond because I feel it would be a lengthy response, so I have finally decided to create this repository in case I ever need to respond to such a question or even better, if someone stumbles upon it and they find it helpful or share some improvements.

##Scenario
So, let's say you have created your website:
- maybe a portfolio website using HTML or PHP, with a couple of pages such as Home, About, Portfolio and Contact OR
- a simple product/ service launch or coming soon landing page where visitors can sign up.

You have created a beautiful form on your Contact page or section with fields such as Name, Phone, Email and Inquiry.

##Problem
You have this beautiful form, but you are not really sure how to make it work.

##Possible Solution
There are several ways in which one can get feedback or inquiries submitted using such a form. What I will be sharing is a solution that:

1. Validates user input; Name, Phone, Email and Inquiry fields using Perform Regular Expression Match (preg_match),
2. Highlights errors using CSS and
3. Send an email to a specified email address using PHP.

It's as simple as that. If you like this implementation, you can build upon it with better CSS styling, you can add a drop down combo box to select from different categories such as "Request for Quotation", "Feedback", "General Inquiry", etc, you can integrate it into your CMS or framework... and so on.

##The Code
Below is the embedded code for you to quickly have a look at. You can also clone or download the [contact.php](https://github.com/Chizzoz/php-contact-form/blob/first/contact.php "contact.php") file in this repository.

``` php
<?php
	// define variables and set to empty values
	$nameErr = $emailErr = $phoneErr = $inquiryErr = "";
	$name = $email = $phone = $inquiry = $email_message = "";
	$submitted = 0;

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	   if (empty($_POST["name"])) {
		 $nameErr = "Name is required";
	   } else {
		 $name = clean_data($_POST["name"]);
		 $fill["name"] = $name;
		 // check if name only contains letters and whitespace
		 if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
		   $nameErr = "Only letters and white space allowed"; 
		 }
	   }
	   
	   if (empty($_POST["email"])) {
		 $emailErr = "Email is required";
	   } else {
		 $email = clean_data($_POST["email"]);
		 $fill["email"] = $email;
		 // check if e-mail address is well-formed
		 if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		   $emailErr = "Invalid email format"; 
		 }
	   }
		 
	   if (empty($_POST["phone"])) {
		 $phone = "";
	   } else {
		 $phone = clean_data($_POST["phone"]);
		 $fill["phone"] = $phone;
		 // check if phone number format is valid
		 if (ctype_alpha(preg_replace('/[0-9]+/', '',$phone))) {
		   $phoneErr = "Phone Number Cannot Include Letters"; 
		 }
		 if (!ctype_digit(preg_replace('~[^0-9]~', '',$phone))) {
		   $phoneErr = "Your Phone Number Does Not Include Digits"; 
		 }
	   }

	   if (empty($_POST["inquiry"])) {
		 $inquiryErr = "You Cannot Submit an Empty Inquiry";
	   } else {
		 $inquiry = clean_data($_POST["inquiry"]);
		 $fill["inquiry"] = $inquiry;
	   }
	}

	function clean_data($data) {
		// Strip whitespace (or other characters) from the beginning and end of string
		$data = trim($data);
		// Un-quotes quoted string
		$data = stripslashes($data);
		// Convert special characters to HTML entities
		$data = htmlspecialchars($data);
		return $data;
	}
	// Send email if no errors
	if (isset($fill)) {
		if (empty($nameErr) && empty($emailErr) && empty($phoneErr) && empty($inquiryErr)) {
			// Inquiry sent from address below
			$email_from = "no-reply@emailadress.com";
			
			// Send form contents to address below
			$email_to = "info@emailadress.com";
			
			// Email message subject
			$today = date("j F, Y. H:i:s");
			$email_subject = "Website Submission [$today]";
			
			function clean_string($string) {

				$bad = array("content-type","bcc:","to:","cc:","href");

				return str_replace($bad,"",$string);

			}

			$email_message .= "Name: ".clean_string($name)."\n";

			$email_message .= "Email: ".clean_string($email)."\n";

			$email_message .= "Phone: ".clean_string($phone)."\n";

			$email_message .= "Inquiry: ".clean_string($inquiry)."\n";
			
			// create email headers
			$headers = 'From: '.$email_from."\r\n".
			 
			'Reply-To: '.$email_from."\r\n" .
			 
			'X-Mailer: PHP/' . phpversion();
			 
			@mail($email_to, $email_subject, $email_message, $headers);
			
			$submitted = 1;
		}
	}
?>
<div>
	<form name="contactus" method="post" action="contact.php">
		<div>
			<span>* Name, Email and Inquiry are required fields.</span>
		</div>
		<div>
			<div>
				<span>Name</span>
			</div>
			<div>
				<input type="text" name="name" placeholder="Name" value="<?php
					if (isset($fill["name"]) && $submitted == 0) {
						echo $fill["name"];
					}?>">
				<span class="<?php
					if (empty($nameErr)) {
						 echo "hidden";
					   } else {
						 echo "error";
					}
				?>"><?php echo $nameErr;?></span>
			</div>
		</div>
		<div>
			<div>
				<span>Email</span>
			</div>
			<div>
				<input type="text" name="email" placeholder="Email Address" value="<?php
					if (isset($fill["email"]) && $submitted == 0) {
						echo $fill["email"];
					}?>">
				<span class="<?php
					if (empty($emailErr)) {
						 echo "hidden";
					   } else {
						 echo "error";
					}
				?>"><?php echo $emailErr;?></span>
			</div>
		</div>
		<div>
			<div>
				<span class="prefix">Phone</span>
			</div>
			<div>
				<input type="text" name="phone" placeholder="Phone Number" value="<?php
					if (isset($fill["phone"]) && $submitted == 0) {
						echo $fill["phone"];
					}?>">
				<span class="<?php
					if (empty($phoneErr)) {
						 echo "hidden";
					   } else {
						 echo "error";
					}
				?>"><?php echo $phoneErr;?></span>
			</div>
		</div>
		<div>
			<div>
				<span>Inquiry</span>
			</div>
			<div>
				<textarea name="inquiry" placeholder="Enter Your Inquiry Here"><?php
					if (isset($fill["inquiry"]) && $submitted == 0) {
						echo $fill["inquiry"];
					}?></textarea>
				<span class="<?php
					if (empty($inquiryErr)) {
						 echo "hidden";
					   } else {
						 echo "error";
					}
				?>"><?php echo $inquiryErr;?></span>
				<div>
					<input type="submit" value="Submit" class="small button" />
				</div>
			</div>
		</div>
	</form>
			
	<!-- Success message -->
	<span class="success <?php if ($submitted == 0) { echo "hidden"; } ?>" >Inquiry <strong>Successfully sent</strong></span>
</div>
```
