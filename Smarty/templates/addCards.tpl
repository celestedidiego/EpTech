<div class="form-container">
    <h2 class="text-center">Aggiungi una carta di credito</h2>

    {if isset($errors) && !empty($errors)}
        <div class="alert alert-danger">
            <ul>
                {foreach $errors as $error}
                    <li>{$error}</li>
                {/foreach}
            </ul>
        </div>
    {/if}

    <form id="creditCardForm" method="POST" action="/EpTech/user/addCards">
        <div class="form-group">
            <label>Nome e Cognome titolare</label>
            <input name="cardHolderName" type="text" class="form-control" id="nome" placeholder="es. Mario Rossi" required>
        </div>
        <div class="form-group">
            <label>Numero Carta</label>
            <input name="cardNumber" type="text" class="form-control" id="numeroCarta" placeholder="es. 5432123412341234" required>
        </div>
        <div class="form-group">
            <label>Scadenza</label>
            <input name="endDate" type="text" class="form-control" id="scadenza" placeholder="es. MM/YY" required>
        </div>
        <div class="form-group">
            <label>CVV</label>
            <input name="cvv" type="text" class="form-control" id="cvv" placeholder="es. 111" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Aggiungi</button>
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>