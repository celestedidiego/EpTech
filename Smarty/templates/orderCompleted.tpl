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
	{include file='headerSection.tpl'}
    <div class="container mt-5">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2>Ordine Completato con Successo</h2>
                    <p>Il tuo ordine numero {$order->getIdOrder()} è stato registrato correttamente.</p>
					<p>Importo totale: €{$order->getTotalPrice()|string_format:"%.2f"}</p>
					<p>Data ordine: {$order->getDateTime()->format('d/m/Y')}</p>
					<p>Stato ordine: {$order->getOrderStatus()}</p>
					<p>Grazie per il tuo acquisto!</p>
                    <a href="/EpTech/user/userHistoryOrders" class="btn btn-info">Visualizza Storico Ordini</a>
                    <a href="/EpTech/user/home" class="btn btn-primary">Torna alla Home</a>
                </div>
            </div>
        </div>
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