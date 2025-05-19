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
                    <th>Richiesta Reso o Rimborso</th>
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
                            <a href="/EpTech/purchase/detailOrder/{$order->getIdOrder()}" class="btn btn-info btn-sm">Dettagli</a>
                        </td>
                        <td>
                            {if $order->hasRefundRequest()}
                                {assign var="refundRequests" value=$order->getRefundRequests()}
                                {assign var="refundStatus" value=$refundRequests[0]->getStatus()}
                                <p class="mt-2">Stato richiesta: {$refundStatus}</p>
                            {else}
                                {if $order->getOrderStatus() == 'Consegnato'}
                                    {if isset($order->refund_expired) && $order->refund_expired}
                                        <span class="text-danger">Reso/Rimborso Non Più Disponibile</span>
                                    {else}
                                        <a href="#" class="btn btn-warning btn-sm" onclick="confirmRefundRequest('{$order->getIdOrder()}')">Richiesta Reso/Rimborso</a>
                                    {/if}
                                {/if}
                            {/if}
                        </td>
                    </tr>
                {/foreach}
            </tbody>
        </table>
    {else}
        <p class="alert alert-warning text-center">Non hai ordini registrati.</p>
    {/if}
</div>
<script>
    function confirmRefundRequest(orderId) {
        if (confirm("Sei sicuro di voler richiedere un reso o rimborso per questo ordine?")) {
            window.location.href = '/EpTech/order/requestRefund/' + orderId;
        }
    }
</script>