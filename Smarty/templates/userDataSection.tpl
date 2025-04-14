{if $changepasswordsucces == 1}
    <div class="mt-5">
        <div class="alert alert-success" role="alert">
            Modifica della password avvenuta con successo!
        </div>
    </div>
{/if}
{if $changeuserdatasucces == 1}
    <div class="mt-5">
        <div class="alert alert-success" role="alert">
            Modifica dati personali avvenuta con successo!
        </div>
    </div>
{/if}
<div class="section-container">
    <style>
        .section-container {
            max-width: 650px; /* Limita la larghezza massima del contenitore a 600px */
            margin: 50px auto; /* Centra il contenitore orizzontalmente e aggiunge un margine verticale di 50px */
            padding: 20px; /* Aggiunge uno spazio interno di 20px */
            background-color: #f9f9f9; /* Imposta un colore di sfondo chiaro */
            border: 1px solid #ddd; /* Aggiunge un bordo sottile grigio chiaro */
            border-radius: 8px; /* Arrotonda gli angoli del contenitore */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Aggiunge un'ombra leggera per un effetto di profondità */
            text-align: center; /* Centra il testo all'interno del contenitore */
        }
        .summary-item {
            text-align: left; /* Allinea il contenuto a sinistra */
            margin-bottom: 15px; /* Aggiunge spazio tra gli elementi */
        }
        .summary-item label {
            font-weight: bold;
            margin-right: 10px; /* Spazio tra l'etichetta e il valore */
        }
    </style>

    <h2 class="text-center">I miei dati personali</h2>
    <br>
    <div class="summary-item">
    <label>Nome: </label><span id="summary-name">{$name}</span>
    </div>
    <br>
    <div class="summary-item">
    <label>Cognome: </label><span id="summary-name">{$surname}</span>
    </div>
    <br>
    {if $check_login_registeredUser == 1}
    <div class="summary-item">
    <label>Username: </label><span id="summary-name">{$username}</span>
    </div>
    {/if}
    <div class="row d-flex justify-content-center">
        {if $check_login_admin == 0}
            <div class="col-md-4">
                <a href="/EpTechProva/user/userDataForm" class="btn btn-primary btn-block">Modifica dati personali</a>
            </div>
            <div class="col-md-4">
                <a href="/EpTechProva/user/shipping" class="btn btn-primary btn-block">I miei indirizzi</a>
            </div>
            <div class="col-md-4">
                <a href="/EpTechProva/user/creditCards" class="btn btn-primary btn-block">Le mie carte di credito</a>
            </div>
        {else}
            <div class="col">
                <a href="/EpTechProva/user/userDataForm" class="btn btn-primary btn-block">Modifica dati personali</a>
            </div>
        {/if}
    </div>
    {if $check_login_admin==0}
    {include file = 'accountDelete.tpl'}
    {/if}
</div>