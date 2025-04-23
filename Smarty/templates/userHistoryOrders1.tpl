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

    <div class="container mt-5">
    <br>
    <h2>Stato Ordini</h2>

    {if isset($success)}
        <div class="alert alert-success">{$success}</div>
    {/if}

    {if isset($error)}
        <div class="alert alert-danger">{$error}</div>
    {/if}
    
    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination">
            {if $array_ordini['currentPage'] > 1}
                <li class="page-item">
                    <a class="page-link" href="?orderpage={$array_ordini['currentPage']-1}">Precedente</a>
                </li>
            {/if}
            
            {for $page=1 to $array_ordini['totalPages']}
                <li class="page-item {if $page == $array_ordini['currentPage']}active{/if}">
                    <a class="page-link" href="?orderpage={$page}">{$page}</a>
                </li>
            {/for}
            
            {if $array_ordini['currentPage'] < $array_ordini['totalPages']}
                <li class="page-item">
                    <a class="page-link" href="?orderpage={$array_ordini['currentPage']+1}">Successivo</a>
                </li>
            {/if}
        </ul>
    </nav>
    {if $array_ordini['n_ordini'] == 0}
        <div class="alert alert-warning">
            Non ci sono ordini presi in carico!
        </div>
    {else}
        <div class="row">
            {foreach from=$array_ordini['ordini'] item=ordine}
                <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Ordine #{$ordine->getId_ordine()}</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Data Ordine:</strong> {$ordine->getData_ordine()->format('d/m/Y')}</p>
                        <p><strong>Acquirente:</strong> {$ordine->getAcquirente()->getNome()} {$ordine->getAcquirente()->getCognome()}</p>
                        <p><strong>Indirizzo:</strong> {$ordine->getIndirizzo_spedizione()->getIndirizzo()}, {$ordine->getIndirizzo_spedizione()->getCap()}</p>
                        <p><strong>Stato Ordine Complessivo:</strong> {$ordine->getStato_ordine()}</p>
                        <br>
                        <h5 class="mt-4">I tuoi prodotti in questo ordine:</h5>
                        {foreach from=$ordine->getQProdottoOrdine() item=ordineProdotto}
                            {if $ordineProdotto->getProdottoId()->getVenditore()->getIdVenditore() == $smarty.session.utente->getIdVenditore()}
                                <div class="mt-3 p-3 border">
                                    <p><strong>Prodotto:</strong> {$ordineProdotto->getProdottoId()->getNome()}</p>
                                    <p><strong>Quantità:</strong> {$ordineProdotto->getQuantitaOrdinataProdotto()}</p>
                                    <p><strong>Stato:</strong> {$ordineProdotto->getStato_ordine()}</p>
                        
                                    {if $ordineProdotto->getStato_ordine() != 'Consegnato' && $ordineProdotto->getStato_ordine() != 'In elaborazione'}
                                        <form method="POST" action="/TekHub/gestioneOrdiniInAttesa/aggiornaStatoOrdine/{$ordine->getId_ordine()}/{$ordineProdotto->getProdottoId()->getIdProdotto()}">
                                            <div class="form-group">
                                                <label for="nuovoStato">Aggiorna stato:</label>
                                                <select class="form-control" name="nuovoStato">
                                                    {if $ordineProdotto->getStato_ordine() == 'Preso in carico'}
                                                        <option value="In spedizione">In spedizione</option>
                                                    {elseif $ordineProdotto->getStato_ordine() == 'In spedizione'}
                                                        <option value="Consegnato">Consegnato</option>
                                                    {/if}
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Aggiorna</button>
                                        </form>
                                    {/if}              
                                </div>
                            {/if}
                        {/foreach}
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
    </body>
</html>