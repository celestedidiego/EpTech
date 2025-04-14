<div class="container mt-5">
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
        {foreach $order->getItemOrder() as $item}
            <div class="col-md-4">
                <div class="card mb-4">
                    {foreach $item->getProduct()->getImages() as $image}
                        <!-- <img src="{$image->getImageData()}" class="card-img-top" alt="{$item->getProduct()->getNameProduct()}"> -->
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
        {/foreach}
    </div>

    <div class="text-center mt-4">
        <a href="/EpTechProva/user/userHistoryOrders" class="btn btn-primary">Torna allo Storico Ordini</a>
    </div>
</div>