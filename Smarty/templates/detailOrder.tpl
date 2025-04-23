<form action="#" method="post">
    <div class="container mt-5">
        <style>
            form {
                padding: 20px;
                border: 1px solid #ddd;
                border-radius: 5px;
                background-color: #f9f9f9;
            }

            form h4 {
                margin-top: 20px;
                border-bottom: 2px solid #007bff;
                padding-bottom: 5px;
                width: 70%;
            }

            .row {
                margin-bottom: 20px;
            }

            .row.mt-4 {
                margin-top: 30px;
            }

            .col-md-6 {
                margin-bottom: 20px;
            }

            .card {
                border: 1px solid #ddd;
                border-radius: 5px;
                overflow: hidden;
            }

            .card img {
                max-width: 100%;
                height: auto;
                display: block;
                margin: 0 auto;
            }

            .card-body {
                padding: 15px;
            }

            .card-title {
                font-size: 1.25rem;
                font-weight: bold;
                margin-bottom: 10px;
            }

            .card-text {
                margin-bottom: 10px;
            }

            .row.mt-4 > .col-md-4 {
                border-right: 1px solid #ddd;
            }

            .row.mt-4 > .col-md-4:last-child {
                border-right: none;
            }

            .row.mt-4 .card {
                display: flex;
                flex-direction: row;
                align-items: center;
            }

            .row.mt-4 .card img {
                width: 150px;
                height: auto;
                margin-right: 15px;
            }

            .row.mt-4 .card-body {
                flex: 1;
            }

            .text-center.mt-4 {
                margin-top: 30px;
            }

            .text-center.mt-4 a {
                padding: 10px 20px;
                font-size: 1rem;
                text-decoration: none;
                color: #fff;
                background-color: #007bff;
                border-radius: 5px;
                transition: background-color 0.3s;
            }

            .text-center.mt-4 a:hover {
                background-color: #0056b3;
            }
        </style>
        <h2 class="text-center">Dettagli Ordine</h2>
        <div class="row">
            <div class="col-md-6">
                <h4>Informazioni Ordine</h4>
                <p><strong>ID Ordine:</strong> {$order->getIdOrder()}</p>
                <p><strong>Data:</strong> {$order->getDateTime()->format('d/m/Y')}</p>
                <p><strong>Importo Totale:</strong> €{$order->getTotalPrice()|string_format:"%.2f"}</p>
                <p><strong>Stato:</strong> {$order->getOrderStatus()}</p>
            </div>
            <div class="col-md-6">
                <h4>Informazioni Spedizione</h4>
                <p><strong>Indirizzo:</strong> {$order->getShipping()->getAddress()}, {$order->getShipping()->getCap()}</p>
                <p><strong>Città:</strong> {$order->getShipping()->getCity()}</p>
                <p><strong>Destinatario:</strong> {$order->getShipping()->getRecipientName()} {$order->getShipping()->getRecipientSurname()}</p>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <h4>Informazioni Carta di Credito</h4>
                <p><strong>Numero Carta:</strong> **** **** **** {$order->getCreditCard()->getCardNumber()|substr:-4}</p>
                <p><strong>Intestatario:</strong> {$order->getCreditCard()->getCardHolderName()}</p>
                <p><strong>Scadenza:</strong> {$order->getCreditCard()->getEndDate()}</p>
            </div>
            <div class="col-md-6">
                <h4>Informazioni Utente</h4>
                <p><strong>Nome:</strong> {$order->getRegisteredUser()->getName()} {$order->getRegisteredUser()->getSurname()}</p>
                <p><strong>Email:</strong> {$order->getRegisteredUser()->getEmail()}</p>
            </div>
        </div>

        <div class="row mt-4">
            <h4>Prodotti dell'Ordine</h4>
            <div class="row">
                {foreach $order->getItemOrder() as $item}
                    <div class="col-md-6">
                        <div class="card mb-4">
                            {foreach $item->getProduct()->getImages() as $image}
                                <img style="width:200px; height: auto;" src="data:{$image->getType()};base64,{$image->getEncodedData()}" class="card-img-top" alt="{$item->getProduct()->getNameProduct()}">
                            {/foreach}
                            <div class="card-body">
                                <h5 class="card-title">{$item->getProduct()->getNameProduct()}</h5>
                                <p class="card-text"><strong>Descrizione:</strong> {$item->getProduct()->getDescription()}</p>
                                <p class="card-text"><strong>Prezzo:</strong> €{$item->getProduct()->getPriceProduct()|string_format:"%.2f"}</p>
                                <p class="card-text"><strong>Quantità:</strong> {$item->getQuantity()}</p>
                                <p class="card-text"><strong>Totale:</strong> €{$item->getProduct()->getPriceProduct() * $item->getQuantity()|string_format:"%.2f"}</p>
                            </div>
                        </div>
                    </div>
                    {if $smarty.foreach.item.iteration % 2 == 0}
                        </div><div class="row">
                    {/if}
                {/foreach}
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="/EpTech/user/userHistoryOrders" class="btn btn-primary">Torna allo Storico Ordini</a>
        </div>
    </div>
</form>