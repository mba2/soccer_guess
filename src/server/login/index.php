<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>LOGIN - CODE HELPER</title>

    <?php require_once("../resources/templates/head.php"); ?>
  </head>
  <body>

	<link rel="stylesheet" type="text/css" href="./styles/code-help.css">

	<main class="container">

		<header class="l-header">
			<div class="logo"><a href="/">logo</a></div>	
		</header>
	
		<section data-id="sign-home" class="row sign-area sign-home">
			<h1 class="sign-heading">welcome to <strong>code helper</strong></h1>
			<p>Please, log in to access all your <strong>code samples</strong></p>
			<button data-target="sign-in" id="btn--sign-in" class="btn btn-block btn--sign-in js-btn--sign">sign in</button>
			<button data-target="sign-up" id="btn--sign-up" class="btn btn-block btn--sign-up js-btn--sign">sign up</button>
		</section>
		
		<section data-id="sign-in" class="row sign-area sign-in">
			<h1 class="sign-heading">sign in</h1>
			<p>Don't have an account? <a data-target="sign-up" class="js-btn--sign" href="#"><strong>Create here</strong></a></p>
			<form action="" class="form">
				<div class="form--block">
					<label for="sign-in--username-email" class="label label--form">email or username</label>
					<input id="sign-in--username-email" type="email">
				</div>
				<div class="form--block">
					<label for="sign-in--pass" class="label label--form">password</label>
					<input id="sign-in--pass" type="password">
				</div>
				
				<input type="submit" name="" value="sign in">
			</form>

			<p><a data-target="sign-recover-pass" class="js-btn--sign" href="#"><strong>Forgot your password</strong></a></p>
		</section>
		<!-- 
			<section class="row sign-area sign-up">
				<h1 class="sign-heading"></h1>
				<button class="btn btn-sign_in">sign in</button>
				<button class="btn btn-sign_up">sign up</button>
			</section> -->
			
		<section data-id="sign-recover-pass" class="row sign-area sign-recover-pass">
			<h1 class="sign-heading">password</h1>
			<p>Enter your email address and we'll send you a link to reset your password</p>
			
			<form action="" class="form">
				<div class="form--block">
					<label for="recover--email" class="label label--form">email address</label>
					<input id="recover--email" type="email">
				</div>
					
				<input id="" name="" type="submit" class="" value="submit"/>
			</form>
				
			<section class="sign-recover-pass--options">
				<p><a data-target="sign-in" class="js-btn--sign" href="#"><strong>sign in<strong/></a> or <strong><a data-target="sign-up" class="js-btn--sign" href="">create an account</strong></a></p>
			</section>
		</section>

		<!-- FOOTER GOES HERE --> 
    </main>
    <?php require_once("../resources/templates/scripts.php")?>
	<script src="../public/js/login.js"></script>
  </body>
</html>