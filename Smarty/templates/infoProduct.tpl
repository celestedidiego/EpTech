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
    
{include file='headerSection.tpl'}
<!-- SECTION -->
<div class="section">
<!-- container -->
<div class="container">
	<!-- row -->
	<div class="row">
		
		<!-- Product main img -->
		<div class="col-md-5 col-md-push-2">
			<div id="product-main-img">
				<img style="width:474px; height: auto;" id="immagine-principale" src="data:{$images[0].type};base64,{$images[0].imageData}" alt="Image Product">
			</div>
		</div>
		<!-- /Product main img -->

		<!-- Product thumb imgs -->
		<div class="col-md-2  col-md-pull-5">
			<div id="product-imgs">
				{foreach from=$images item=image}
					<div class="product-preview">
					<div class="thumbnail-image-container">
						<img class="thumbnail-image" src="data:{$image.type};base64,{$image.imageData}" alt="Image Product">
					</div>
				</div>
            	{/foreach}
			</div>
		</div>
		<!-- /Product thumb imgs -->

        <!-- Product details -->
        <div class="col-md-5">
            <div class="product-details">
                <h2 class="product-name">{$nameProduct}</h2>
                <div>
                    <div class="product-rating">
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star-o"></i>
                    </div>
                </div>
                <div>
                    <h3 class="product-price">€{$priceProduct}</h3>
                    <span class="product-available">Disponibilità: {$avQuantity}</span>
                </div>

                <ul class="product-links">
                    <li>Categoria:</li>
                    <li>{$category}</li>
                </ul>
				<br>
                <div class="add-to-cart padding-top-20">
                    <div class="tab-content">
                        <div class="row">
                            <div class="col-md-12">
                                <p>{$description}</p>
                            </div>
                        </div>
                    </div>
                    <form id="purchase" action="/EpTech/purchase/addToCart/{$productId}" method="POST">
                        {if $avQuantity > 0}
                        <select class="input-select margin-bottom-20" id="quantity" name="quantity">
                            <!-- Mostra fino a 10 opzioni o il massimo disponibile -->
                            {if $avQuantity >= 10}
                                {for $i=1 to 10}
                                    <option value="{$i}">Quantità: {$i}</option>
                                {/for}
                            {else}
                                {for $i=1 to $avQuantity}
                                    <option value="{$i}">Quantità: {$i}</option>
                                {/for}
                            {/if}
                        </select>
                        {/if}

                        <div class="mt-3">
							<br>
                            {if $avQuantity== 0}
                                <p class="card-text"><i>Questo prodotto è attualmente terminato</i></p>
                            {else}
                                <button class="add-to-cart-btn" type="submit"><i class="fas fa-table"></i> Aggiungi al Carrello</button>
                            {/if}
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /Product details -->

		<!-- Product tab -->
		<div class="col-md-12">
			<div id="product-tab">
				<!-- product tab nav -->
				<ul class="tab-nav">
					<li class="active"><a data-toggle="tab" href="#tab1">Description</a></li>
					<li><a data-toggle="tab" href="#tab3">Reviews ({$reviews.n_reviews})</a></li>
				</ul>
				<!-- /product tab nav -->

				<!-- product tab content -->
				<div class="tab-content">
					<!-- tab1  -->
					<div id="tab1" class="tab-pane fade in active">
						<div class="row">
							<div class="col-md-12">
								<p>{$description}</p>
							</div>
						</div>
					</div>
					<!-- /tab1  -->

					<!-- tab3  -->
					<div class="mt-5 d-flex justify-content-center">
						{if $successMessage}
							<div class="alert alert-success" role="alert">
								{$successMessage}
							</div>
						{/if}
						{if $errorMessage}
							<div class="alert alert-danger" role="alert">
								{$errorMessage}
							</div>
						{/if}
					</div>
					<div id="tab3" class="tab-pane fade in">
					{if $reviews['n_reviews'] > 1}
						<!-- Pagination -->
						<ul class="reviews-pagination">
						{if $reviews['currentPage'] > 1}
							<li><a href="?reviews_page={$reviews['currentPage']-1}"><i class="fa fa-angle-left"></i></a></li>
						{/if}
			
						{for $page=1 to $reviews['totalPages']}
						<li {if $page == $reviews['currentPage']}class="active"{/if}><a href="?reviews_page={$page}">
							{$page}
						</a></li>
						{/for}
			
						{if $reviews['currentPage'] < $reviews['totalPages']}
							<li><a href="?reviews_page={$reviews['currentPage']+1}"><i class="fa fa-angle-right"></i></a></li>
						{/if}
						</ul>
						<!-- /Pagination -->
					{/if}
						<div class="row">
							<!-- Reviews -->
							<div class="col-md-7">
								<div id="reviews">
									<ul class="reviews">
									{if $reviews.n_reviews > 0}
										{foreach from=$reviews.items item=review}
										<li>
											<div class="review-heading">
												<h5 class="name">{$review->getRegisteredUser()->getUsername()}</h5>
												
												<p class="date"><i>Acquisto verificato</i></p>
												<div class="review-rating">
												{for $i=1 to 5}
													{if $i <= $review->getVote()}
														<i class="fa fa-star"></i>
													{else}
														<i class="fa fa-star-o empty"></i>
													{/if}
												{/for}
												</div>
											</div>
											<div class="review-body">
												<p>{$review->getText()}</p>
											</div>
											
											{if isset($review->getResponseAdmin())}
												<div class="review-reply">
													<h6>Risposta dell'admin:</h6>
													<p>{$review->getResponseAdmin()}</p>
												
												</div>
											{/if}
										</li>
										<hr>
										{/foreach}

									{else}
										<p>Nessuna recensione disponibile per questo prodotto.</p>
									{/if}
								</div>
							</div>
							<!-- /Reviews -->
							{if $can_review}
							<!-- Review Form -->
							<div class="col-md-5">
								<div id="review-form">
									<h4>{if $review_user}Modifica la tua recensione{else}Scrivi una recensione{/if}</h4>
									<form action="/EpTech/review/{if $review_user}modifica{else}aggiungi{/if}/{$productId}" method="POST" class="review-form">
									<textarea style="width:400px;height:200px" name="text" placeholder="La tua recensione" required>{if $review_user}{$review_user->getText()}{/if}</textarea>
										<div class="input-rating">
											<span>Valuta: </span>
											<div class="stars">
												{for $i=5 to 1 step -1}
												<input id="star{$i}" name="vote" value="{$i}" type="radio" {if $review_user && $review_user->getVote() == $i}checked{/if}><label for="star{$i}"></label>
												{/for}
											</div>
										</div>
									{if $review_user}<input type="hidden" name="idReview" value="{$review_user->getReviewId()}">{/if}
									<button class="primary-btn">{if $review_user}Modifica Recensione{else}Invia Recensione{/if}</button>
									</form>
								</div>
							</div>
							<!-- /Review Form -->
							{/if}
						</div>
						 
					</div>
					<!-- /tab3  -->
				</div>
				<!-- /product tab content  -->
			</div>
		</div>
		<!-- /product tab -->
	</div>
	<!-- /row -->
</div>
<!-- /container -->
</div>
<!-- /SECTION -->

<!-- RICORDARE: non visualizza i prodotti simili, rivedere la query in FProduct -->

<!-- Section -->
<div class="section">
<!-- container -->
<div class="container">
	<!-- row -->
	<div class="row">

	{if $same_cat_products['n_products'] == 1}
		<div class="col-md-12">
			<div class="section-title text-center">
				<h3 class="title">Prodotti simili</h3>
			</div>
			
				<div class="alert-w alert-warning">
					Non ci sono prodotti simili!
				</div>
		</div>
	
	{elseif $same_cat_products['n_products'] > 1}
			<!-- Pagination -->
			<ul class="reviews-pagination">
			{if $same_cat_products['currentPage'] > 1}
				<li><a href="?products_same_page={$same_cat_products['currentPage']-1}"><i class="fa fa-angle-left"></i></a></li>
			{/if}

			{for $page=1 to $same_cat_products['totalPages']}
			<li {if $page == $same_cat_products['currentPage']}class="active"{/if}><a href="?products_same_page={$page}">
				{$page}
			</a></li>
			{/for}

			{if $same_cat_products['currentPage'] < $same_cat_products['totalPages']}
				<li><a href="?products_same_page={$same_cat_products['currentPage']+1}"><i class="fa fa-angle-right"></i></a></li>
			{/if}
			</ul>
			<!-- /Pagination --> 

		<!-- product -->
		{foreach from=$same_cat_products['products'] item=same_cat_product}
		<div class="col-md-3 col-xs-6">
			<div class="product">
				<div class="product-img">
					{if isset($same_cat_product->getImages()->last()->getImageData()) && isset($same_cat_product->getImages()->last()->getType())}
						<img src="data:{$same_cat_product->getImages()->last()->getType()};base64,{$same_cat_product->getImages()->last()->getEncodedData()}" alt="Image">
					{else}
						<p>Immagine non trovata</p>
					{/if}        
				</div>
				<div class="product-body">
					<p class="product-category">{$same_cat_product->getNameCategory()->getNameCategory()}</p>
					<h3 class="product-name">{$same_cat_product->getNameProduct()}</h3>
                    <h4 class="product-price">€{$same_cat_product->getPriceProduct()}</h4>
				
					<form class="product-btns" method="GET" action="/EpTech/purchase/viewProduct/{$same_cat_product->getProductId()}">											
						<button type="submit" class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">vedi prodotto</span></button>
					</form>
				</div>
			</div>
		</div>
		{/foreach}
		<!-- /product --> 
	{/if}
	</div>
	<!-- /row -->
</div>
<!-- /container -->
</div>
<!-- /Section -->

<!-- FOOTER -->
<footer id="footer">
<!-- bottom footer -->
<div id="bottom-footer" class="section">
	<div class="container">
		<!-- row -->
		<div class="row">
			<div class="col-md-12 text-center">
				<ul class="footer-payments">
					<li><a href="#"><i class="fab fa-cc-visa" style="color: #ffffff;"></i></a></li>
					<li><a href="#"><i class="fab fa-cc-paypal" style="color: #ffffff;"></i></a></li>
					<li><a href="#"><i class="fab fa-cc-mastercard" style="color: #ffffff;"></i></a></li>
					<li><a href="#"><i class="fab fa-cc-discover" style="color: #ffffff;"></i></a></li>
					<li><a href="#"><i class="fab fa-cc-amex" style="color: #ffffff;"></i></a></li>
				</ul>
				<span class="copyright">
					<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
					Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
				<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
				</span>
			</div>
		</div>
			<!-- /row -->
	</div>
	<!-- /container -->
</div>
<!-- /bottom footer -->
</footer>
<!-- /FOOTER -->
<script>
    var immaginePrincipale = document.getElementById('immagine-principale');
    var thumbnailImages = document.getElementsByClassName('thumbnail-image');

    for (var i = 0; i < thumbnailImages.length; i++) {
        thumbnailImages[i].addEventListener('click', function() {
        immaginePrincipale.src = this.src;
        });
    }
</script>
<script>
    function cambiaAzione(action) {
        document.getElementById('gestioneAcquisti').action = action;
    }
</script>
<script src="/EpTech/skin/electroMaster/js/scripts-for-template.js"></script>
<script src="/EpTech/skin/electroMaster/js/jquery.min.js"></script>
<script src="/EpTech/skin/electroMaster/js/bootstrap.min.js"></script>
<script src="/EpTech/skin/electroMaster/js/nouislider.min.js"></script>
<script src="/EpTech/skin/electroMaster/js/main.js"></script>

</body>
</html>