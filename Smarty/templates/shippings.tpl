<div class="container mt-5">
    <div class="row d-flex justify-content-between mx-2">
        <div class="col-md-10">
            <h2>I miei indirizzi</h2>
        </div>
        <div class="col-md-2">
            <a href="/EpTech/user/addShipping" class="btn btn-primary btn-block">Aggiungi</a>
        </div>
    </div>
    {if isset($messages.success)}
        <div class="mt-3 d-flex justify-content-center">
            <div class="alert alert-success" role="alert">
                {$messages.success}
            </div>
        </div>
    {/if}
    {if isset($messages.info)}
        <div class="mt-3 d-flex justify-content-center">
            <div class="alert alert-info" role="alert">
                {$messages.info}
            </div>
        </div>
    {/if}
    {if isset($messages.error)}
        <div class="mt-3 d-flex justify-content-center">
            <div class="alert alert-danger" role="alert">
                {$messages.error}
            </div>
        </div>
    {/if}

    {if $errorDeleteShipping== 1}
        <div class="mt-5 d-flex justify-content-center">
            <div class="alert alert-danger" role="alert">
                Errore: indirizzo non esistente
            </div>
        </div>
    {/if}

    {if !(isset($array_shipping)) || empty($array_shipping)}
        <div class="mt-5 d-flex justify-content-center">
            <div class="alert alert-info" role="alert">
                Non hai ancora salvato nessun indirizzo
            </div>
        </div>
    {/if}
    <br>
    {if isset($array_shipping) && !empty($array_shipping)}
            <table class="table">
                <thead>
                    <tr>
                        <th>Via</th>
                        <th>CAP</th>
                        <th>Stato</th>
                        <th>Gestisci indirizzi</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$array_shipping item=address}
                        <tr class="{if $address->isDeleted()}row-hidden{/if}">
                            <td>{$address->getAddress()}</td>
                            <td>{$address->getCap()}</td>
                            <td>{if $address->isDeleted()}Nascosto{else}Attivo{/if}</td>
                            <td>
                                <div class="row d-flex align-items-center justify-content-center">
                                <!-- Considerazioni:
                                Gli indirizzi sono generalmente dati statici che raramente cambiano.
                                Quando un utente si trasferisce, di solito aggiunge un nuovo indirizzo piuttosto che modificare quello esistente.

                                Mantenere una cronologia degli indirizzi può essere utile per scopi di tracciamento o fatturazione.
                                Modificare un indirizzo esistente potrebbe compromettere questa cronologia.

                                La maggior parte degli e-commerce e delle piattaforme online offre solo le opzioni per aggiungere nuovi indirizzi o eliminare quelli esistenti.

                                Se un utente inserisce un indirizzo errato, è più semplice eliminarlo e aggiungerne uno nuovo piuttosto che modificarlo.
                                -->
                                    <div class="col-6">
                                        {if $address->isDeleted()}
                                            <a href="/EpTech/user/reactivateShipping/{$address->getAddress()}/{$address->getCap()}"
                                                class="btn btn-success btn-sm">Riattiva</a>
                                        {else}
                                            <a href="/EpTech/user/deleteShipping/{$address->getAddress()}/{$address->getCap()}"
                                                class="btn btn-danger btn-sm">Elimina</a>
                                        {/if}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>        
    {/if}
    <div class="col-md-2">
        <a href="/EpTech/purchase/checkout" class="btn btn-primary">Procedi al checkout</a>
    </div>
</div>