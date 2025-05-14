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

    {if $qty_updated == 1}
        <div class="mt-5 d-flex justify-content-center">
            <div class="alert alert-success" role="alert">
                Quantità aggiornata con successo
            </div>
        </div>
    {/if}

    {if $is_cart_empty == 1}
        <div class="container">
            <h2>Il tuo carrello è vuoto</h2>
            <p>Aggiungi qualche prodotto per iniziare lo shopping!</p>
            <a href="/EpTech/purchase/shop" class="btn btn-primary">Continua lo shopping</a>
        </div>
    {else}
        <div class="container">
            <h2>Il tuo carrello</h2>
            {foreach from=$products_cart item=product}
                {assign var="p_image" value=$product['product']->getImages()->toArray()}
                <div class="row mt-4">
                    
                    <div class="col-md-3">
                        <div class="product-img">
                            {if isset($p_image[0]->getImageData()) && isset($p_image[0]->getType())}
                                <img style="width:200px; height: auto;" src="data:{$p_image[0]->getType()};base64,{$p_image[0]->getEncodedData()}" alt="Image">
                            {else}
                                <p>Immagine non trovata</p>
                            {/if}        
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <h4>{$product['product']->getNameProduct()}</h4>
                        <p>{$product['product']->getDescription()|truncate:300}</p>
                    </div>
                    <div class="col-md-2">
                        <form action="/EpTech/purchase/updateQuantity/{$product['product']->getProductId()}" method="POST">
                            <select class="input-select margin-bottom-20" id="quantity" name="quantity">
                                <!--Controllo la quantità disponibile:
                                se è minore di 10, mette tanti option quante sono le quantità 
                                altrimenti fissa la quantità max a 10-->
                                {if $product['product']->getAvQuantity() >= 10}
                                    {for $i=1 to 10}
                                    <option value="{$i}" {if $cart[$product['product']->getProductId()] == $i}selected{/if}>
                                        Quantità: {$i}</option>
                                    {/for}
                                {else}
                                    {for $i=1 to $product['product']->getAvQuantity()}
                                    <option value="{$i}" {if $cart[$product['product']->getProductId()] == $i}selected{/if}>
                                        Quantità: {$i}</option>
                                    {/for}
                                {/if}
                            </select>
                            <button type="submit" class="btn btn-md btn-info ml-3"> Aggiorna quantità</button>
                        </form>
                    </div>
                    <div class="col-md-1">
                        <p>Totale: €{$product['product']->getPriceProduct() * $product['quantity']}</p>
                    </div>
                    <div class="col-md-3">
                        <a href="/EpTech/purchase/viewProduct/{$product['product']->getProductId()}" class="btn btn-info">Dettagli</a>
                        <a href="/EpTech/purchase/removeFromCart/{$product['product']->getProductId()}" class="btn btn-danger mt-2">Rimuovi</a>
                    </div>
                </div>
            {/foreach}
            <br>
            <div class="row mt-4 d-flex">
                <div class="col-md-6">
                    <a href="/EpTech/purchase/emptyCart" class="btn btn-warning">Svuota carrello</a>
                </div>
                <div class="col-md-6 text-right">
                    <h3>Totale: €{$subtotal|string_format:"%.2f"}</h3>
                    <a href="/EpTech/purchase/shop" class="btn btn-primary">Continua lo shopping</a>
                    <a href="/EpTech/purchase/checkout" class="btn btn-primary">Procedi al checkout</a>
                </div>
            </div>
        </div>
    {/if}
    </body>
</html>