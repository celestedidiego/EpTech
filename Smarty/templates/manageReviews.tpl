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
    </head>
    <body>
    {include file='headerSection.tpl'}

    <div class="container mt-5">
        <h2>Recensioni dei prodotti</h2>

        {if isset($success)}
            <div class="alert alert-success">
                {$success}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">Close</button>
            </div>
        {/if}

        {if isset($error)}
            <div class="alert alert-danger">
                {$error}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">Close</button>
            </div>
        {/if}
        
        {if isset($reviews.n_reviews) && $reviews.n_reviews == 0}
            <div class="alert alert-warning">
                Non ci sono recensioni per i tuoi prodotti!
            </div>
        {else}
            <div class="row">
                {foreach from=$reviews['items'] item=review}
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h4>Recensione per {$review->getProduct()->getNameProduct()}</h4>
                            </div>
                            <div class="card-body">
                                <p><strong>Utente:</strong> {$review->getRegisteredUser()->getUsername()}</p>
                                <p><strong>Voto:</strong> {$review->getVote()} / 5</p>
                                <p><strong>Testo:</strong> {$review->getText()}</p>
                                
                                {if $review->getResponseAdmin()}
                                    <div class="mt-3 p-3 border">
                                        <h5>La tua risposta:</h5>
                                        <p>{$review->getResponseAdmin()}</p>
                                    </div>
                                {else}
                                    <form method="POST" action="/EpTechProva/review/respondToReview/{$review->getReviewId()}">
                                        <div class="form-group">
                                            <label for="risposta">Rispondi alla recensione:</label>
                                            <textarea class="form-control" id="risposta" name="risposta" rows="3" required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Invia Risposta</button>
                                    </form>
                                {/if}
                            </div>
                        </div>
                    </div>
                {/foreach}
            </div>
        {/if}
    </div>

    <script src="/EpTechProva/skin/electroMaster/js/scripts-for-template.js"></script>
	<!-- jQuery Plugins -->
    <script src="/EpTechProva/skin/electroMaster/js/jquery.min.js"></script>
    <script src="/EpTechProva/skin/electroMaster/js/bootstrap.min.js"></script>
    <script src="/EpTechProva/skin/electroMaster/js/slick.min.js"></script>
    <script src="/EpTechProva/skin/electroMaster/js/nouislider.min.js"></script>
    <script src="/EpTechProva/skin/electroMaster/js/jquery.zoom.min.js"></script>
    <script src="/EpTechProva/skin/electroMaster/js/main.js"></script>
    </body>

</html>