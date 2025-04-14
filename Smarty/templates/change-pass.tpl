<div class="form-container">
    <h2 class="text-center">Modifica la password</h2>
    {if $equalpassworderr == 1}
        <div class="mt-5">
            <div class="alert alert-danger" role="alert">
                La nuova password non può essere uguale a qualla vecchia!
            </div>
        </div>
    {/if}
    
    {if $errorpassupdate == 1}
        <div class="mt-5">
            <div class="alert alert-danger" role="alert">
                Le nuove password non coincidono! Riprovare
            </div>
        </div>
    {/if}

    {if $erroroldpass == 1}
        <div class="mt-5">
            <div class="alert alert-danger" role="alert">
                La vecchia password è sbagliata!
            </div>
        </div>
    {/if}

    <form class="registrationForm" method="POST" action="/EpTechProva/user/changePass">
        <div class="form-group">
            <input name="password" type="password" class="form-control" id="password" placeholder="Vecchia password..."
                required>
        </div>
        <div class="form-group">
            <input name="new-password" type="password" class="form-control" id="new-password"
                placeholder="Nuova password..." required>
        </div>
        <div class="form-group">
            <input name="new-confirm-password" type="password" class="form-control" id="new-confirm-password"
                placeholder="Conferma password..." required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Conferma</button>
    </form>
</div>