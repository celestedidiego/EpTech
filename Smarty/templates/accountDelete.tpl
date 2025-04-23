<div class="mt-4-c">
    <br>
    <div class="form-group">
        <label>Desideri eliminare il tuo account?</label>
        <button type="button" id="deleteBtn" class="btn btn-danger btn-block">Elimina Account</button>
    </div>
</div>

<form method="POST" action="/EpTech/user/deleteAccount">
    <div id="confirmationPopup" class="popup" style="display: none;">
        <div class="popup-content">
            <h2>Conferma eliminazione account</h2>
            <p>Per confermare l'eliminazione dell'account, digitare "CONFERMA" nel campo sottostante e cliccare sul pulsante "Conferma"</p>
            <input type="text" id="confirmInput" placeholder="Digitare CONFERMA" required>
            <div class="bottoni">
                <button type="submit" class="btn btn-danger btn-block" id="confirmBtn">Conferma</button>
                <button type="button" class="btn btn-secondary btn-block" id="cancelBtn">Annulla</button>
            </div>
        </div>
    </div>
</form>
<script>
    document.getElementById('deleteBtn').addEventListener('click', function () {
        document.getElementById('confirmationPopup').style.display = 'block';
    });

    document.getElementById('cancelBtn').addEventListener('click', function () {
        document.getElementById('confirmationPopup').style.display = 'none';
    });
</script>