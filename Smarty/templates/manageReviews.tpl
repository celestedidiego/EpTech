<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Gestione Recensioni</title>

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
        <h2 class="text-center mb-4">Recensioni dei prodotti</h2>
        
        <!-- FILTER BY NAME -->
        <form method="GET" action="/EpTech/admin/manageReviews">
            <div class="form-group">
                <label for="product_name">Filtra per Nome Prodotto:</label>
                <input type="text" name="product_name" class="form-control" placeholder="Cerca recensioni per nome prodotto" value="{if isset($smarty.get.product_name)}{$smarty.get.product_name}{/if}">
            </div>
            <button type="submit" class="btn btn-primary">Cerca</button>
        </form>
        <br>
        <!-- /FILTER BY NAME -->

        {if isset($success)}
            <div class="alert alert-success">
                {$success}
            </div>
        {/if}
    
        {if isset($error)}
            <div class="alert alert-danger">
                {$error}
            </div>
        {/if}
    
        {if isset($reviews.n_reviews) && $reviews.n_reviews == 0}
            <div class="alert alert-warning text-center">
                Non ci sono recensioni per i tuoi prodotti!
            </div>
        {else}
            <div class="row">
                {foreach from=$reviews['items'] item=review}
                    <div class="col-md-6">
                        <div class="card mb-4 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">{$review->getProduct()->getNameProduct()}</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Utente:</strong> {$review->getRegisteredUser()->getUsername()}</p>
                                <p><strong>Voto:</strong> {$review->getVote()} / 5</p>
                                <p><strong>Testo:</strong> {$review->getText()}</p>
    
                                {if $review->getResponseAdmin()}
                                    <div class="mt-3 p-3 border bg-light">
                                        <h6>La tua risposta:</h6>
                                        <p>{$review->getResponseAdmin()}</p>
                                    </div>
                                {else}
                                    <form method="POST" action="/EpTech/review/respondToReview/{$review->getReviewId()}">
                                        <div class="form-group">
                                            <label for="risposta">Rispondi alla recensione:</label>
                                            <textarea class="form-control" id="risposta" name="risposta" rows="3" required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success mt-2">Invia Risposta</button>
                                    </form>
                                {/if}
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