<!-- row -->
<div class="row">
    <style>
        .product-img1 {
            background-color: #ffffff; /* Sfondo bianco */
            width: 100%; /* Larghezza del contenitore */
            height: 300px; /* Altezza fissa per uniformare le dimensioni */
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
    <div class="row">
        {if isset($applied_filters.query) && $applied_filters.query != ''}
            <div class="col-12">
                <h3>Risultati della ricerca per: {$applied_filters.query}</h3>
            </div>
        {/if}

        {if isset($array_products.totalPages) && $array_products.totalPages == 0}
            <div class="col-md-10">
                <div class="alert alert-warning">
                    Nessun prodotto trovato.
                </div>
            </div>
        {/if}
    </div>
    <!-- Pagination -->
    {if isset($array_products.totalPages) && $array_products.totalPages > 1}
    <ul class="reviews-pagination">
        {if $array_products.currentPage > 1}
            <li>
                <a href="?page={math equation='x-1' x=$array_products.currentPage}&query={$applied_filters.query}&category={$applied_filters.category}&brand={$applied_filters.brand}&prezzo_max={$applied_filters.prezzo_max}">
                    <i class="fa fa-angle-left"></i>
                </a>
            </li>
        {/if}
    
        {for $page=1 to $array_products.totalPages}
            <li {if $page == $array_products.currentPage}class="active"{/if}>
                <a href="?page={$page}&query={$applied_filters.query}&category={$applied_filters.category}&brand={$applied_filters.brand}&prezzo_max={$applied_filters.prezzo_max}">
                    {$page}
                </a>
            </li>
        {/for}
    
        {if $array_products.currentPage < $array_products.totalPages}
            <li>
                <a href="?page={$array_products.currentPage+1}&query={$applied_filters.query}&category={$applied_filters.category}&brand={$applied_filters.brand}&prezzo_max={$applied_filters.prezzo_max}">
                    <i class="fa fa-angle-right"></i>
                </a>
            </li>
        {/if}
    </ul>
    {/if}

    {if isset($array_products.products) && $array_products.products|@count > 0}
        {foreach from=$array_products['products'] item=product}
            <!-- product -->
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="product">

                    <div class="product-img1">
                        {if isset($product->getImages()->first()->getImageData()) && isset($product->getImages()->first()->getType())}
                            <img src="data:{$product->getImages()->first()->getType()};base64,{$product->getImages()->first()->getEncodedData()}" alt="Image">
                        {else}
                            <p>Immagine non trovata</p>
                        {/if}         
                    </div>

                    <div class="product-body">
                        <p class="product-category">{$product->getNameCategory()->getNameCategory()}</p>
                        <h3 class="product-name">{$product->getNameProduct()}</h3>
                        <h4 class="product-price">{$product->getPriceProduct()}</h4>
                        <form class="product-btns" method="GET" action="/EpTech/purchase/viewProduct/{$product->getProductId()}">											
                            <button type="submit" class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">vedi prodotto</span></button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /product -->
        {/foreach}
    {/if}
</div>