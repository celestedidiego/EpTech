<div class="section-container">
    <style>
        .section-container {
            padding: 20px;
            text-align: center;
        }

        .gestione-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* Due colonne */
            grid-template-rows: repeat(2, 1fr); /* Due righe */
            gap: 10px; /* Spaziatura tra le sezioni */
            width: 100%;
            max-width: 1200px; /* Larghezza massima del rettangolo */
            height: 380px; /* Aumenta l'altezza complessiva del rettangolo */
            margin: 0 auto; /* Centra il rettangolo */
            border: 2px solid #ddd; /* Bordo del rettangolo */
            border-radius: 10px; /* Angoli arrotondati */
            background-color: #f9f9f9; /* Colore di sfondo */
            box-sizing: border-box; /* Include il padding nel calcolo delle dimensioni */
            padding: 10px; /* Distanza tra il bordo e il contenuto */
        }

        .gestione-item {
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: #333;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            height: 100%; /* Riempie completamente la cella della griglia */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-sizing: border-box; /* Include il padding nel calcolo delle dimensioni */
        }

        .gestione-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
        }

        .gestione-title {
            font-size: 18px;
            font-weight: bold;
        }
    </style>

    <h2 class="text-center">Gestione</h2>
    <div class="gestione-grid mt-4">
        <a href="/EpTech/admin/manageProducts" class="gestione-item">
            <h5 class="gestione-title">Gestione Prodotti</h5>
        </a>
        <a href="/EpTech/admin/manageUsers" class="gestione-item">
            <h5 class="gestione-title">Gestione Utenti</h5>
        </a>
        <a href="/EpTech/admin/manageReviews" class="gestione-item">
            <h5 class="gestione-title">Gestione Recensioni</h5>
        </a>
        <a href="/EpTech/admin/manageOrders" class="gestione-item">
            <h5 class="gestione-title">Gestione Ordini</h5>
        </a>
    </div>
</div>