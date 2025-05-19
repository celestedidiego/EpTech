<!-- Filter Section -->
<div class="form-container">
    <form method="GET" action="/EpTech/{if $check_login_admin == 1}product/listProducts{else}purchase/shop{/if}" id="filterForm">
        <input type="hidden" name="query" id="hiddenQuery" value="{$applied_filters.query}">
        <h2>Sezione filtri</h2>
        <div class="form-group">
            <label for="categoryFilter">Categoria</label>
            <select id="categoryFilter" name="category" class="form-control">
                <option value="">Tutte le categorie</option>
                {foreach from=$array_category item=category}
                <option value="{$category.nameCategory}" {if $applied_filters.category == $category.nameCategory}selected{/if}>{$category.nameCategory}</option>
                {/foreach}
            </select>
        </div>
        <div class="form-group">
            <label for="brandFilter">Marca</label>
            <select id="brandFilter" name="brand" class="form-control">
                <option value="">Tutte le marche</option>
                {foreach from=$brands item=brand}
                <option value="{$brand}" {if $applied_filters.brand == $brand}selected{/if}>{$brand}</option>
                {/foreach}
            </select>
        </div>
        <div class="form-group">
            <label for="priceRange">Prezzo massimo: <span id="priceValue">€{if isset($smarty.get.prezzo_max)}{$smarty.get.prezzo_max}{else}5000{/if}</span></label>
            <input type="range" class="form-control-range" id="priceRange" name="prezzo_max" min="0" max="5000" value="{if isset($smarty.get.prezzo_max)}{$smarty.get.prezzo_max}{else}5000{/if}" oninput="updatePriceValue(this.value)">
        </div>
        <div class="form-group">
            <label for="orderBy">Ordina per</label>
            <select id="orderBy" name="order_by" class="form-control">
                <option value="">Predefinito</option>
                <option value="sold_desc" {if $applied_filters.order_by == 'sold_desc'}selected{/if}>Più venduti</option>
                <option value="rating_desc" {if $applied_filters.order_by == 'rating_desc'}selected{/if}>Valutazione (dalla più alta)</option>
                <option value="price_asc" {if $applied_filters.order_by == 'price_asc'}selected{/if}>Prezzo crescente</option>
                <option value="price_desc" {if $applied_filters.order_by == 'price_desc'}selected{/if}>Prezzo decrescente</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Applica filtri</button>
    </form>
</div>

<script>
    function updatePriceValue(value) {
        const priceValue = document.getElementById('priceValue');
        priceValue.textContent = value+"€";
        if(priceValue.textContent == "5000€"){
            priceValue.textContent = value+"€ e più";
        }
    }
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Trova l'input di ricerca nella barra di navigazione
    var searchInput = document.querySelector('input[name="query"]');
    var hiddenQuery = document.getElementById('hiddenQuery');
    var filterForm = document.getElementById('filterForm');

    // Aggiorna il valore nascosto quando l'utente digita nella barra di ricerca
    searchInput.addEventListener('input', function() {
        hiddenQuery.value = this.value;
    });

    // Aggiorna il valore nascosto prima dell'invio del form
    filterForm.addEventListener('submit', function() {
        hiddenQuery.value = searchInput.value;
    });
});
</script>
