<div class="container mt-5">
    <h2 class="text-center">Storico Ordini</h2>
    {if $orders|@count > 0}
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>ID Ordine</th>
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
                        <td>{$order->getDateTime()->format('d/m/Y')}</td>
                        <td>€{$order->getTotalPrice()|string_format:"%.2f"}</td>
                        <td>{$order->getOrderStatus()}</td>
                        <td>
                            <a href="/EpTechProva/purchase/detailOrder/{$order->getIdOrder()}" class="btn btn-info btn-sm">Dettagli</a>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    {else}
        <p class="alert alert-warning text-center">Non hai ordini registrati.</p>
    {/if}
    <div class="text-center mt-4">
        <a href="/EpTechProva/user/home" class="btn btn-primary">Torna alla Home</a>
    </div>
</div>
