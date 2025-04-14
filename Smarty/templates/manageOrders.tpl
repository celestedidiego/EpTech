<!DOCTYPE html>
<htm lang="en">
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
            <h2 class="text-center">Gestione Ordini</h2>
            {if $orders|@count > 0}
                <table class="table table-bordered mt-4">
                    <thead>
                        <tr>
                            <th>ID Ordine</th>
                            <th>Utente</th>
                            <th>Data</th>
                            <th>Importo Totale</th>
                            <th>Stato</th>
                            <th>Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $orders as $order}
                            <tr>
                                <td>{$order->getIdOrder()}</td>
                                <td>{$order->getRegisteredUser()->getUsername()}</td>
                                <td>{$order->getDateTime()->format('d/m/Y')}</td>
                                <td>€{$order->getTotalPrice()|string_format:"%.2f"}</td>
                                <td>
                                    <form method="POST" action="/EpTechProva/admin/changeOrderStatus/{$order->getIdOrder()}">
                                        <select name="orderStatus" class="form-control">
                                            <option value="In elaborazione" {if $order->getOrderStatus() == 'In elaborazione'}selected{/if}>In elaborazione</option>
                                            <option value="Preso in carico" {if $order->getOrderStatus() == 'Preso in carico'}selected{/if}>Preso in carico</option>
                                            <option value="In spedizione" {if $order->getOrderStatus() == 'In spedizione'}selected{/if}>In spedizione</option>
                                            <option value="Consegnato" {if $order->getOrderStatus() == 'Consegnato'}selected{/if}>Consegnato</option>
                                            <option value="Annullato" {if $order->getOrderStatus() == 'Annullato'}selected{/if}>Annullato</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm mt-2">Aggiorna</button>
                                    </form>
                                </td>
                                <td>
                                    <a href="/EpTechProva/purchase/detailOrder/{$order->getIdOrder()}" class="btn btn-info btn-sm">Dettagli</a>
                                </td>
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
            {else}
                <p class="alert alert-warning text-center">Non ci sono ordini disponibili.</p>
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