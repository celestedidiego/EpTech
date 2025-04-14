<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		 <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

		<title>EpTech</title>

		<!-- Google font -->
		<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

		<!-- Bootstrap -->
		<link type="text/css" rel="stylesheet" href="/EpTechProva/skin/electroMaster/css/bootstrap.min.css"/>

		<!-- Slick -->
		<link type="text/css" rel="stylesheet" href="/EpTechProva/skin/electroMaster/css/slick.css"/>
		<link type="text/css" rel="stylesheet" href="/EpTechProva/skin/electroMaster/css/slick-theme.css"/>

		<!-- nouislider -->
		<link type="text/css" rel="stylesheet" href="/EpTechProva/skin/electroMaster/css/nouislider.min.css"/>

		<!-- Font Awesome Icon -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

		<!-- Custom stlylesheet -->
		<link type="text/css" rel="stylesheet" href="/EpTechProva/skin/electroMaster/css/style.css"/>

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
    </head>
    <body>
        <div class="form-container">
            <h2 class="text-center">Login</h2>
            {if $error_log == 1}
            <div class="mt-5">
                <div class="alert alert-danger" role="alert">
                    Email o password non corretti!
                </div>
            </div>
            {/if}
            <div class="card-body">
                <form method="POST" action="/EpTechProva/user/login">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input name="email-log" type="email" class="form-control" id="email" placeholder="ad es. prova@example.com">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input name="password-log" type="password" class="form-control" id="password" placeholder="Password...">
                    </div>
                    <br>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
            </div>
            <br>
            <div class="card-footer text-center">
                <a href="/EpTechProva/user/signUp">Non sei registrato? Registrati!</a>
            </div>
            <br>
        </div>
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
        <!-- Include any other JS files needed -->
    </body>
</html>