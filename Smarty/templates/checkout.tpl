<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Checkout Page">
    <meta name="keywords" content="checkout, order, payment">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Checkout - EpTech</title>

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
    {include file='headerSection.tpl'}

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                {foreach from=$products_cart item=product}
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                        {if isset($product['product']->getImages()->first()->getImageData()) && isset($product['product']->getImages()->first()->getType())}
                                            <img style="width:200px; height:auto;"
                                                src="data:{$product['product']->getImages()->first()->getType()};base64,{$product['product']->getImages()->first()->getEncodedData()}"
                                                alt="Image">
                                        {else}
                                            <p>Immagine non trovata</p>
                                        {/if}
                                </div>
                                <div class="col-md-8">
                                    <h5>{$product['product']->getNameProduct()}</h5>
                                    <p>Quantità: {$product['quantity']}</p>
                                    <p>Prezzo: €{$product['product']->getPriceProduct() * $product['quantity']|string_format:"%.2f"}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                {/foreach}
            </div>
            <br>
            <div class="col-md-4 order-details">
                <div class="section-title text-center">
                    <h3 class="title">Il tuo ordine</h3>
                </div>
                <div class="order-summary">
                    <!-- ... (mantieni il riepilogo dell'ordine esistente) ... -->
                </div>
                <form action="/EpTechProva/purchase/completeOrder" method="POST">
                    {assign var="hasActiveAddresses" value=false}
                    {assign var="hasActiveCards" value=false}

                    <div class="form-group">
                        <label for="indirizzo">Seleziona indirizzo di spedizione:</label>
                        
                        {foreach $shipping as $address}
                            {if !$address->isDeleted()}
                                {assign var="hasActiveAddresses" value=true}
                                {break}
                            {/if}
                        {/foreach}
                        
                        {if $hasActiveAddresses}
                            <select name="shipping" id="indirizzo" class="form-control" required>
                                {foreach $shipping as $address}
                                    {if !$address->isDeleted()}
                                        <option value="{$address->getAddress()}|{$address->getCap()}">
                                            {$address->getAddress()}, {$address->getCap()}
                                        </option>
                                    {/if}
                                {/foreach}
                            </select>
                        {else}
                            <p class="alert alert-warning">Non hai indirizzi attivi.</p>
                        {/if}
                        <br>
                        <a href="/EpTechProva/user/shipping" class="btn btn-primary">Aggiungi un indirizzo</a>
                    </div>

                    <div class="form-group">
                        <label for="carta">Seleziona carta di credito:</label>
                        {foreach $creditCards as $card}
                            {if !$card->isDeleted()}
                                {assign var="hasActiveCards" value=true}
                                {break}
                            {/if}
                        {/foreach}
                        
                        {if $hasActiveCards}
                            <select name="creditCard" id="carta" class="form-control" required>
                                {foreach $creditCards as $card}
                                    {if !$card->isDeleted()}
                                        <option value="{$card->getCardNumber()}">
                                            **** **** **** {$card->getCardNumber()|substr:-4} - Scadenza: {$card->getEndDate()}
                                        </option>
                                    {/if}
                                {/foreach}
                            </select>
                        {else}
                            <p class="alert alert-warning">Non hai carte di credito attive.</p>
                        {/if}
                        <br>
                        <a href="/EpTechProva/user/creditCards" class="btn btn-primary">Aggiungi una carta di credito</a>
                    </div>

                    <button type="submit" class="primary-btn order-submit" {if !$hasActiveAddresses || !$hasActiveCards}disabled{/if}>
                        Effettua l'ordine
                    </button>
                    
                    {if !$hasActiveAddresses || !$hasActiveCards}
                        <p class="text-danger mt-2">Per procedere con l'ordine, assicurati di avere almeno un indirizzo e una carta di credito attivi.</p>
                    {/if}
                </form>
            </div>
        </div>
    </div>
    <!-- Js Plugins -->
    <script src="/EpTechProva/skin/electroMaster/js/jquery-3.3.1.min.js"></script>
    <script src="/EpTechProva/skin/electroMaster/js/bootstrap.min.js"></script>
    <script src="/EpTechProva/skin/electroMaster/js/jquery.nice-select.min.js"></script>
    <script src="/EpTechProva/skin/electroMaster/js/jquery.slicknav.js"></script>
    <script src="/EpTechProva/skin/electroMaster/js/owl.carousel.min.js"></script>
    <script src="/EpTechProva/skin/electroMaster/js/main.js"></script>
</body>
</html>