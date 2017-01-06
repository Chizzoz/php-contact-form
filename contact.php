<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Page | PHP regex CSS</title>
	<style>
		body {
			background: whitesmoke;
		}
		span.error {
			display: block;
			padding: 5px;
			background: red;
			color: white;
		}
		span.hidden {
			visibility: hidden;
			padding: 0;
			margin: 0;
		}
		span.success {
			display: block;
			padding: 5px;
			background: green;
			color: white;
		}
		span.success.hidden {
			visibility: hidden;
			padding: 0;
			margin: 0;
		}
	</style>
</head>

<body>
	<section id="contact">
		<div>
			<div>

				<div>
					<h2>Contacts Us</h2>
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
				</div>
			</div>
		</div>
	</section>
</body>

</html>
