<div class="form-container">
    <style>
        .form-container {
            max-width: 500px; /* Limita la larghezza massima del modulo */
            margin: 50px auto; /* Centra il modulo orizzontalmente e aggiunge un margine verticale */
            padding: 20px; /* Aggiunge uno spazio interno */
            background-color: #f9f9f9; /* Imposta un colore di sfondo chiaro */
            border: 1px solid #ddd; /* Aggiunge un bordo sottile grigio chiaro */
            border-radius: 8px; /* Arrotonda gli angoli del contenitore */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Aggiunge un'ombra leggera */
        }
    </style>
    
    <h2 class="text-center">I miei dati personali</h2>

    <form class="registrationForm" method="POST" action="/EpTechProva/user/changeUserData">
        <div class="form-group">
            <label>Nome</label>
            <input name="name" type="text" class="form-control" id="name" placeholder="Nome..." value={$name} required>
        </div>
        <div class="form-group">
        <label>Cognome</label>
            <input name="surname" type="text" class="form-control" id="surname" placeholder="Cognome..." value={$surname} required>
        </div>
        {if $check_login_registeredUser == 1}
            <div class="form-group">
            <label>Nome utente</label>
                <input name="username" type="text" class="form-control" id="username" placeholder="Username..." value={$username} required>
            </div>
            <div class="form-group">
            <label>E-mail</label>
                <input name="email" type="email" class="form-control" id="email" value={$email} disabled>
                <h6 class="attention-note">*Attenzione: non è possibile modificare l'E-mail</h6>
            </div>
        {/if}
        <button type="submit" class="btn btn-primary btn-block">Modifica</button>
        <br>
        <a id="linkpass" href="/EpTechProva/user/changePass">Modifica la password</a>
    </form>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>