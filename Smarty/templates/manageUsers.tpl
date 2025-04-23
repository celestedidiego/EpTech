<!DOCTYPE html>
<html lang="en">
	<head>

        <meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		 <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

		<title>Gestione Utenti</title>

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
	{include file='headerSection.tpl'}

    <div class="container mt-5">
    <br>
    <h2>Gestione Utenti</h2>

    {if isset($message)}
        <div class="alert alert-success">{$message}</div>
    {/if}

    {if isset($error)}
        <div class="alert alert-danger">{$error}</div>
    {/if}

    <form method="post" action="/EpTech/admin/filterUsersPaginated">
        <div class="form-group">
            <label for="id">Filtra per ID:</label>
            <input type="text" class="form-control" id="registeredUserId" name="registeredUserId">
        </div>
        <button type="submit" class="btn btn-primary">Filtra</button>
    </form>

    
    {if $users_info['users'] > 1}
        <!-- Pagination -->
        <ul class="reviews-pagination">
        {if $users_info['currentPage'] > 1}
            <li><a href="?page={$users_info['currentPage']-1}"><i class="fa fa-angle-left"></i></a></li>
        {/if}

        {for $page=1 to $users_info['totalPages']}
        <li {if $page == $users_info['currentPage']}class="active"{/if}><a href="?page={$page}">
            {$page}
        </a></li>
        {/for}

        {if $users_info['currentPage'] < $users_info['totalPages']}
            <li><a href="?page={$users_info['currentPage']+1}"><i class="fa fa-angle-right"></i></a></li>
        {/if}
        </ul>
        <!-- /Pagination -->
    {/if}
    <br>
    {if $users_info.totalItems == 0}
        <div class="alert alert-warning">
            Non ci sono utenti attivi!
        </div>
    {else}
        <div class="row">
            {foreach from=$users_info.users item=user}
                <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <p><strong>Nome:</strong> {$user.name} {$user.surname}</p>
                        <p><strong>Email:</strong> {$user.email}</p>
                        <p><strong>Stato:</strong> {if $user.is_blocked}Bloccato{else}Attivo{/if}</p>
                        <div class="mt-3">
                            <a href="/EpTech/admin/deleteUser/{$user.registeredUserId}" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo utente? Questa azione non può essere annullata.');">Elimina</a>
                            {if $user.is_blocked}
                                <a href="/EpTech/admin/unblockUser/{$user.registeredUserId}" class="btn btn-success btn-sm">Sblocca</a>
                            {else}
                                <a href="/EpTech/admin/blockUser/{$user.registeredUserId}" class="btn btn-warning btn-sm">Blocca</a>
                            {/if}
                        </div>
                    </div>
                </div>
                </div>
            {/foreach}
        </div>
    {/if}
    </div>

<script src="/EpTech/skin/electroMaster/js/scripts-for-template.js"></script>
	<!-- jQuery Plugins -->
	<script src="/EpTech/skin/electroMaster/js/jquery.min.js"></script>
	<script src="/EpTech/skin/electroMaster/js/bootstrap.min.js"></script>
	<script src="/EpTech/skin/electroMaster/js/slick.min.js"></script>
	<script src="/EpTech/skin/electroMaster/js/nouislider.min.js"></script>
	<script src="/EpTech/skin/electroMaster/js/jquery.zoom.min.js"></script>
	<script src="/EpTech/skin/electroMaster/js/main.js"></script>
    </body>
</html>