<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>EpTech</title>

		<!-- Google font -->
		<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

		<!-- Bootstrap -->
		<link type="text/css" rel="stylesheet" href="/EpTech/skin/electroMaster/css/bootstrap.min.css"/>

		<!-- Slick -->
		<link type="text/css" rel="stylesheet" href="/EpTech/skin/electroMaster/css/slick.css"/>
		<link type="text/css" rel="stylesheet" href="/EpTech/skin/electroMaster/css/slick-theme.css"/>

		<!-- nouislider -->
		<link type="text/css" rel="stylesheet" href="/EpTech/skin/electroMaster/css/nouislider.min.css"/>

		<!-- Font Awesome Icon -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

		<!-- Custom stlylesheet -->
		<link type="text/css" rel="stylesheet" href="/EpTech/skin/electroMaster/css/style.css"/>
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
                <form method="POST" action="/EpTech/user/login">
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
                <a href="/EpTech/user/signUp">Non sei registrato? Registrati!</a>
            </div>
            <br>
        </div>
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.bundle.min.js"></script>
    </body>
</html>