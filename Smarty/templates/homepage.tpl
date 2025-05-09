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
		<style>
			.product-img1 {
				background-color: #ffffff; /* Sfondo bianco */
				width: 100%; /* Larghezza del contenitore */
				height: 200px; /* Altezza fissa per uniformare le dimensioni */
				display: flex; /* Usa Flexbox per centrare il contenuto */
				align-items: center; /* Centra verticalmente l'immagine */
				justify-content: center; /* Centra orizzontalmente l'immagine */
				overflow: hidden; /* Nascondi eventuali parti dell'immagine che escono dal contenitore */
				border: 1px solid #ddd; /* Aggiungi un bordo sottile per separare visivamente */
				border-radius: 5px; /* Aggiungi angoli arrotondati (opzionale) */
			}

			.product-img1 img {
				max-width: 85%; /* L'immagine non supera la larghezza del contenitore */
				max-height: 85%; /* L'immagine non supera l'altezza del contenitore */
				object-fit: contain; /* Adatta l'immagine senza ritagliarla */
			}
		</style>
    </head>
    <body>
    {include file='headerSection.tpl'}

    {if $signUpSuccess == 1}
		<div class="mt-5">
			<div class="alert alert-success" role="alert">
				Registrazione avvenuta con successo! Ora puoi effettuare il login
			</div>
		</div>
	{/if}

    {if $added_to_cart == 1}
        <div class="mt-5">
            <div class="alert alert-success" role="alert">
                Prodotto aggiunto nel carrello!
            </div>
        </div>
    {/if}

    {if $cart_emptied == 1}
        <div class="mt-5 d-flex justify-content-center">
            <div class="alert alert-success" role="alert">
                Il carrello è stato svuotato
            </div>
        </div>
    {/if}

    {if $removed_from_cart == 1}
        <div class="mt-5">
            <div class="alert alert-success" role="alert">
                Prodotto rimosso dal carrello!
            </div>
        </div>
    {/if}

    {if $q_max_raggiunta == 1}
        <div class="mt-5">
            <div class="alert alert-warning" role="alert">
                Non puoi superare la quantità massima disponibile per ciascun prodotto
            </div>
        </div>
    {/if}

    {if $check_login_registered_user == 1 || $check_login == 0}
        <!-- HOT DEAL SECTION -->
        <div id="hot-deal" class="section">
            <!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<div class="col-md-12">
						<div class="hot-deal">
							<h2 class="text-uppercase">scopri tutti i nostri prodotti</h2>
							<p>Esplora le categorie che ti interessano</p>
							<a class="primary-btn cta-btn" href="/EpTech/purchase/shop">Vai allo shop</a>
						</div>
					</div>
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
        </div>
        <!-- /HOT DEAL SECTION -->

        <!-- SECTION -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">
					<!-- shop -->
					{foreach from=$array_category item=category}
					<div class="col-md-4 col-xs-6">
						<div class="shop">
							<div class="shop-img">
								<img src="/EpTech/skin/electroMaster/img/{$category.nameCategory}.png" alt="">
							</div>
							<div class="shop-body">
								<h3>{$category.nameCategory}</h3>
								<a href="/EpTech/purchase/shop?query=&category={$category.nameCategory}&brand=&prezzo_max=5000" class="cta-btn">Vai allo shop <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>
					</div>
					{/foreach}
					<!-- /shop -->
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->

        <!-- SECTION -->
		<div class="section">
			<!-- container -->
			<div class="container">
				<!-- row -->
				<div class="row">

					<!-- section title -->
					<div class="col-md-12">
						<div class="section-title">
							<h3 class="title">Nuovi prodotti recenti</h3>
						</div>
					</div>
					<!-- /section title -->

					<!-- Products tab & slick -->
					<div class="col-md-12">
						<div class="row">
							<div class="products-tabs">
								<!-- tab -->
								<div id="tab1" class="tab-pane active">
									<div class="products-slick" data-nav="#slick-nav-1">
										<!-- product -->
										{foreach from=$array_product item=product}
                                            <div class="product">
												<div class="product-img1">
													{if isset($product.images.imageData) && isset($product.images.type)}
														<img src="data:{$product.images.type};base64,{$product.images.imageData}" alt="Image">
													{else}
														<p>Immagine non trovata</p>
													{/if}        
												</div>                                               
                                                <div class="product-body">
                                                    <p class="product-category">{$product.nameCategory}</p>
                                                    <h3 class="product-name"><a href="#">{$product.nameProduct}</a></h3>
													<h4 class="product-price">€ {$product.priceProduct}</h4>
                                                    <form class="product-btns" method="GET" action="/EpTech/purchase/viewProduct/{$product.productId}">
                                                        <button type="submit" class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">vedi prodotto</span></button>
                                                    </form>
                                                </div>
                                            </div>
										{/foreach}
										<!-- /product -->
									</div>
									<div id="slick-nav-1" class="products-slick-nav"></div>
								</div>
								<!-- /tab -->
							</div>
						</div>
					</div>
					<!-- Products tab & slick -->
				</div>
				<!-- /row -->
			</div>
			<!-- /container -->
		</div>
		<!-- /SECTION -->

		<!-- SECTION ARTICLE -->
		<div class="section">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<h2 class="text-center">Articolo più recente</h2>
						{if $article && $article.title && $article.content}
							<h3>{$article.title}</h3>
							<p>{$article.content}</p>
						{else}
							<p class="text-center">Nessun articolo disponibile.</p>
						{/if}
					</div>
				</div>
			</div>
		</div>
		<!-- /SECTION ARTICLE -->
    {/if}

    <br>
    <br>
        <!-- FOOTER -->
        <footer id="footer">
            <!-- bottom footer -->
            <div id="bottom-footer" class="section">
                <!-- container -->
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
