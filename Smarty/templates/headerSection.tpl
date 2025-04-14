
<!-- HEADER -->
<header>

<!-- MAIN HEADER -->
<div id="header">
    <!-- container -->
    <div class="container-fluid text-center">
        <!-- row -->
        <div class="row">
            <!-- LOGO -->
            <div class="col-lg-4 col-md-3">
                <div class="header-logo">
                    <a href="/EpTechProva/user/home" class="logo">
                        <img src="/EpTechProva/skin/electroMaster/img/logo_EpTech.png" alt="">
                    </a>
                </div>
            </div>
            <!-- /LOGO -->

            <!-- SEARCH BAR -->
            <div class="col-lg-4 col-md-6 col-sm-9 col-xs-9">
            {if $search_bar == 1}
                <div class="header-search">
                    <form action="/EpTechProva/purchase/shop" method="GET">
                        <select class="input-select" name="category">
                            <option value="">Tutte le categorie</option>
                            {foreach from=$array_category item=category}
                                <option value="{$category.nameCategory}">{$category.nameCategory}</option>
                            {/foreach}
                        </select>
                        <input class="input" name="query" id="searchInput" placeholder="Cerca il prodotto...">
                        <button class="search-btn" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            {/if}
            </div>
            <!-- /SEARCH BAR -->


            <!-- ACCOUNT -->
            <div class="col-lg-4 col-md-3 col-sm-2 col-xs-2">
                <div class="header-ctn">
                    <!-- My Account -->
                    {if $user_not_logged == 0}
                        <div>
                            <a href="/EpTechProva/user/logout">
                                <i class="fas fa-sign-out-alt" style="color: #ffffff;"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    {else}
                        <div>
                            <a href="/EpTechProva/user/login">
                                <i class="fas fa-sign-in-alt" style="color: #ffffff;"></i>
                                <span>Accedi</span>
                            </a>
                        </div>
                    {/if}

                    {if $check_login_registered_user == 1 || $user_not_logged == 1}
                    <!-- Cart -->
                    <div class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                            <i class="fas fa-shopping-cart" style="color: #ffffff;"></i>
                            <span>Carrello</span>
                            <div class="qty">{$cart_quantity}</div>
                        </a>
                        <div class="cart-dropdown">
                            <div class="cart-list">
                            {if $products_cart != 0}
                                {foreach from=$products_cart item=product}
                                <div class="product-widget">
                                    
                                    <div class="product-img">
                                        {if isset($product['product']->getImages()->last()->getImageData()) && isset($product['product']->getImages()->last()->getType())}
                                            <img src="data:{$product['product']->getImages()->last()->getType()};base64,{$product['product']->getImages()->last()->getEncodedData()}" alt="Image">
                                        {else}
                                            <p>Immagine non trovata</p>
                                        {/if}  
                                    </div>
                                    
                                    <div class="product-body">
                                        <h3 class="product-name">{$product['product']->getNameProduct()}</h3>
                                        <h4 class="product-price"><span class="qty">{$product.quantity}x</span>€{$product['product']->getPriceProduct()}</h4>
                                    </div>
                                    <form action="/EpTechProva/purchase/removeFromCart/{$product['product']->getProductId()}">
                                        <button class="delete"><i class="fas fa-times-circle"></i></button>
                                    </form>
                                </div>
                                {/foreach}
                            {else}
                                <div class="product-widget">
                                    <h5>Non ci sono prodotti nel carrello!</h5>
                                </div>
                            {/if}
                            </div>
                            <div class="cart-summary">
                                <small>{$cart_quantity} prodotti/o selezionati</small>
                                <h5>SUBTOTAL: €{$subtotal}</h5>
                            </div>
                            <div class="cart-btns">
                                <a href="/EpTechProva/purchase/showCart">Vai al carrello</a>
                                <a href="/EpTechProva/purchase/effettuaCheckout">Checkout  <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- /Cart -->
                    {/if}
                    <!-- Menu Toogle -->
                    <div class="menu-toggle">
                        <a href="#">
                            <i class="fa fa-bars"></i>
                            <span>Menu</span>
                        </a>
                    </div>
                    <!-- /Menu Toogle -->
                </div>
            </div>
            <!-- /ACCOUNT -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /MAIN HEADER -->
</header>
<!-- /HEADER -->

<!-- NAVIGATION -->
<nav id="navigation">
<!-- container -->
<div class="container products-container" style="display: flex; justify-content: center; align-items: center; text-align: center;">
    <!-- responsive-nav -->
    <div id="responsive-nav">
        <!-- NAV -->
        <ul class="main-nav nav navbar-nav">
        {if $check_login_registered_user == 1}
            <li><a href="/EpTechProva/user/userDataSection">Profilo</a></li>
            <li><a href="/EpTechProva/user/userHistoryOrders">Ordini</a></li>
        {elseif $check_login_admin == 1}
        <!--
        <li><a href="/EpTechProva/user/userDataSection">Profilo</a></li>
            <li><a href="/EpTechProva/admin/manageProducts">Gestione prodotti</a></li>
            <li><a href="/EpTechProva/admin/manageUsers">Gestione utenti registrati</a></li>
            <li><a href="/EpTechProva/admin/manageReviews">Gestione recensioni</a></li>
            <li><a href="/EpTechProva/admin/manageOrders">Gestione ordini</a></li>
        -->
        <li><a href="/EpTechProva/user/userDataSection">Profilo</a></li>
        <li><a href="/EpTechProva/admin/manageSection">Gestione</a></li>
        {/if}

        {if $user_not_logged == 1}
            <!-- <li><a href="/EpTechProva/user/login"><i class="fas fa-sign-in-alt"></i><span> Accedi</span></a></li> -->
            <!-- <li><a href="/EpTechProva/purchase/showCart"><span> Carrello</span></a></li> -->
        {else if $user_not_logged == 0 && $check_login_registered_user == 1}
            <li><a href="/EpTechProva/purchase/showCart"><span> Carrello</span></a></li> 
            <!-- <li><a href="/EpTechProva/user/logout"><i class="fas fa-sign-out-alt"></i><span> Logout</span></a></li> -->
        {/if}
        </ul>
        <!-- /NAV -->
    </div>
    <!-- /responsive-nav -->
</div>
<!-- /container -->
</nav>
<!-- /NAVIGATION -->




