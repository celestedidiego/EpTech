<div class="container mt-5">
    <div class="row d-flex justify-content-between mb-2">
        <div class="col-md-10">
            <h2>Le mie carte di credito</h2>
        </div>
        <div class="col-md-2">
            <a href="/EpTechProva/user/addCards" class="btn btn-primary btn-block">Aggiungi</a>
        </div>
    </div>
    {if isset($messages.success)}
        <div class="mt-3 d-flex justify-content-center mt-3">
            <div class="alert alert-success" role="alert">
                {$messages.success}
            </div>
        </div>
    {/if}
    {if isset($messages.info)}
        <div class="mt-3 d-flex justify-content-center mt-3">
            <div class="alert alert-info" role="alert">
                {$messages.info}
            </div>
        </div>
    {/if}
    {if isset($messages.error)}
        <div class="mt-3 d-flex justify-content-center mt-3">
            <div class="alert alert-danger" role="alert">
                {$messages.error}
            </div>
        </div>
    {/if}

    {if $errorDeleteCard == 1}
        <div class="mt-5 d-flex justify-content-center mt-3">
            <div class="alert alert-danger" role="alert">
                Errore: carta non esistente
            </div>
        </div>
    {/if}

    {if isset($errorDeleteCard) && $errorDeleteCard}
        <div class="alert alert-danger mt-3" role="alert">
            Si è verificato un errore durante l'eliminazione della carta di credito. La carta potrebbe non esistere o potrebbe esserci un problema nel sistema.
        </div>
    {/if}
    <br>
    
    {if isset($credit_cards) && !empty($credit_cards)}
        <table class="table">
            <thead>
                <tr>
                    <th>Nome titolare</th>
                    <th>Numero carta</th>
                    <th>Scadenza</th>
                    <th>Stato</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                {foreach $credit_cards as $card}
                <tr class="{if $card->isDeleted()}row-hidden{/if}">
                    <td>{$card->getCardHolderName()}</td>
                    <td>**** **** **** {$card->getCardNumber()|substr:-4}</td>
                    <td>{$card->getEndDate()}</td>
                    <td>{if $card->isDeleted()}Nascosta{else}Attiva{/if}</td>
                    <td>
                        {if $card->isDeleted()}
                            <a href="/EpTechProva/user/reactivateCreditCard/{$card->getCardNumber()}" class="btn btn-success btn-sm">Riattiva</a>
                        {else}
                            <a href="/EpTechProva/utente/deleteCreditCard/{$card->getCardNumber()}" class="btn btn-danger btn-sm">Elimina</a>
                        {/if}
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    {else}
        <div class="alert alert-info" role="alert">
            Non hai ancora salvato nessuna carta di credito.
        </div>
    {/if}
</div>