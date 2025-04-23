<div class="form-container">
    <h2 class="text-center">Aggiungi un indirizzo</h2>

    {if isset($errors) && !empty($errors)}
        <div class="alert alert-danger">
            <ul>
                {foreach $errors as $error}
                    <li>{$error}</li>
                {/foreach}
            </ul>
        </div>
    {/if}
    
    <form id="registrationForm" method="POST" action="/EpTech/user/addShipping">
        <div class="form-group">
            <label>Indirizzo</label>
            <input name="address" type="text" class="form-control" id="via" placeholder="es. Via Roma 3" required>
        </div>
        <div class="form-group">
            <label>CAP</label>
            <input name="cap" type="text" class="form-control" id="cap" placeholder="es. 00021" required>
        </div>
        <div class="form-group">
            <label>Città</label>
            <input name="city" type="text" class="form-control" id="city" placeholder="es. Roma" required>
        </div>
        <div class="form-group">
            <label>Nome destinatario</label>
            <input name="recipientName" type="text" class="form-control" id="recipientName" placeholder="es. Mario" required>
        </div>
        <div class="form-group">
            <label>Cognome destinatario</label>
            <input name="recipientSurname" type="text" class="form-control" id="recipientSurname" placeholder="es. Rossi" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Aggiungi</button>
        <br>
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>