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
    <!-- E' un template dinamico che funge da contenitore principale per gestire diverse funzionalità relative all'utente. La sua flessibilità deriva dall'uso delle variabili passate dal controller e dall'inclusione di template specifici per ogni sezione. -->
    {include file='headerSection.tpl'}
        <!-- Contenuto principale -->
        <main>
        <div class="container-fluid d-flex justify-content-center">
            {if $listProducts == 1 || $shop == 1}
			<div class="col-lg-2 col-md-3 col-sm-4">
				{include file='filtersSection.tpl'}
			</div>
			{/if}
            {if $userDataForm == 1}
                <div class="col-12 content-area">
                {include file='userDataForm.tpl'}
            {elseif $userHistoryOrders == 1}
                <div class="col-12 content-area">
                {include file='userHistoryOrders.tpl'}
            {elseif $changepass == 1}
                <div class="col-12 content-area">
                {include file='change-pass.tpl'}
            {elseif $userDataSection == 1}
                <div class="col-12 content-area">
                {include file='userDataSection.tpl'}
            {elseif $manageSection == 1}
                <div class="col-12 content-area">
                {include file='manageSection.tpl'}
            {elseif $listProducts == 1}
                <div class="col-lg-9 col-md-8 col-sm-7 col-xs-12 content-area">
                {include file='listProducts.tpl'}
            {elseif $addProductForm == 1}
                <div class="col-12 content-area">
                {include file='addProductForm.tpl'}
            {elseif $modifyProductForm == 1}
                <div class="col-12 content-area">
                {include file='modifyProductForm.tpl'}
            {elseif $admin == 1}
                <div class="col-12 content-area">
                {include file='userDataSection.tpl'} 
            {elseif $shop == 1}
                <div class="col-lg-9 col-md-8 col-sm-7 col-xs-12 content-area">
                {include file='shop.tpl'}
            {elseif $shippings == 1}
                <div class="col-12 content-area">
                {include file='shippings.tpl'}
            {elseif $creditCards == 1}
                <div class="col-12 content-area">
                {include file='creditCards.tpl'}
            {elseif $addShipping == 1}
                <div class="col-12 content-area">
                {include file='addShipping.tpl'}
            {elseif $addCards == 1}
                <main class="col-12 content-area">
                {include file='addCards.tpl'}
            {elseif $detailOrder == 1}
                <div class="col-12 content-area">
                {include file='detailOrder.tpl'}
            {/if}
        </div>
        </main>
    
    <script src="/EpTechProva/skin/electroMaster/js/scripts-for-template.js"></script>
    <script src="/EpTechProva/skin/electroMaster/js/jquery.min.js"></script>
    <script src="/EpTechProva/skin/electroMaster/js/bootstrap.min.js"></script>
    <script src="/EpTechProva/skin/electroMaster/js/slick.min.js"></script>
    <script src="/EpTechProva/skin/electroMaster/js/nouislider.min.js"></script>
    <script src="/EpTechProva/skin/electroMaster/js/jquery.zoom.min.js"></script>
    <script src="/EpTechProva/skin/electroMaster/js/main.js"></script>
    </body>
</html>