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

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->

    </head>
    <body>
        <div class="form-container">
            <h2 class="text-center">Registrazione</h2>
            {if $errore_r == 1}
                <div class="mt-5">
                <div class="alert alert-danger" role="alert">
                    Email già esistente! Registrati con un'altra email!
                </div>
            </div>
            {/if}

            {if $check_pass == 1}
            <div class="mt-5">
                <div class="alert alert-danger" role="alert">
                    Le password non coincidono! Riprovare
                </div>
            </div>
            {/if}

            <div class="card-body">
                <form method="POST" action="/EpTech/user/signUp">
                    <!--
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="userType" id="registeredUser" value="registeredUser" required>
                        <label class="form-check-label" for="acquirente">
                            Utente registrato
                        </label>
                    </div>
                    -->
                    <div class="form-group">
          
                        <input name="name" type="text" class="form-control" id="name" placeholder="Nome..." required>
                    </div>
                    <div class="form-group">
                        
                        <input name="surname" type="text" class="form-control" id="surname" placeholder="Cognome..." required>
                    </div>
                    <div class="form-group">
                        <input name="birthDate" type="date" class="form-control" id="birthDate" placeholder="Data di nascita..." required>
                    </div>
                    <div class="form-group">
                        
                        <input name="username" type="text" class="form-control" id="username" placeholder="Username..." required>
                    </div>
                    <div class="form-group">
                        
                        <input name="email" type="email" class="form-control" id="email" placeholder="es. prova@example.com" required autocomplete="email">
                    </div>
                    <div class="form-group">
          
                        <input name="password" type="password" class="form-control" id="password" placeholder="Password..." required autocomplete="new-password">
                    </div>
                    <div class="form-group">
                        
                        <input name="confirm-password" type="password" class="form-control" id="confirm-password" placeholder="Conferma password..." required autocomplete="new-password">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Registrati</button>
                    <br>
                    <a href="/EpTech/user/login" id="linkpass">Hai già un account? Accedi</a>
                </form>
            </div>
        </div>
        <script src="/EpTech/skin/electroMaster/js/scripts-for-template.js"></script>
        <!-- 
        <script src="/EpTech/skin/electroMaster/js/jquery.min.js"></script>
        <script src="/EpTech/skin/electroMaster/js/bootstrap.bundle.min.js"></script> -->
        <!-- Include any other JS files needed -->
    </body>
</html>