{if $errorImageUpload == 1}
    <div class="mt-5">
        <div class="alert alert-danger" role="alert">
            Errore nell'upload delle immagini! Size troppo grande o tipo del file diverso da jpeg/png !
        </div>
    </div>
{/if}

<div class="form-container width-90">
    <h2>Modifica il prodotto</h2>
    <form class="registrationForm" id="modifyProductForm" method="POST" action="/EpTechProva/product/modifyProduct/{$productId}" enctype="multipart/form-data">
        <div class="left-column">
            <div class="form-group">
                <label>Nome prodotto</label>
                <input name="name" type="text" class="form-control" id="name" value="{$nameProduct}" placeholder="Nome..." required>
            </div>
            
            <div class="form-group">
            <label>Description</label>
            <br>
                <textarea name="description" id="description" rows="10" cols="57" required>{$description}</textarea>
            </div>
            <div class="form-group">
            <label>Category</label>
                <select name="Category" id="category" class="form-control" disabled>
                    <option value="{$category}">{$category}</option>
                </select>
            </div>

            <div class="form-group">
                <label for="images">Aggiungi al massimo 5 immagini (le immagini attuali saranno eliminate) :</label>
                    <input id="images" name="images[]" type="file" multiple>
            </div>

            <div class="image-preview" id="imagePreview">
                {foreach from=$images item=image}
                    <div class="image-container">
                        <img src="data:{$image.type};base64,{$image.imageData}" alt="Image">
                        <button type="button" class="remove-button">Immagine attuale</button>
                    </div>
                {/foreach}
            </div>

        </div>

        <div class="right-column">
            <fieldset>
                <legend>Specifice dell'oggetto</legend>

                <label for="brand">Marca</label>
                <input type="text" name="brand" id="brand" value="{$brand}" required>

                <label for="model">Modello</label>
                <input type="text" name="model" id="model" value="{$model}" required>

                <label for="quantity">Quantità Disponibile</label>
                <input type="number" name="avQuantity" value={$avQuantity} min="0" step="1" id="avQuantity" required>
                <!--
                {if $isProdottoNuovo == 1}
                <label for="quantity">Quantità</label>
                <input type="number" name="quantita_disp" value={$quantita_disp} min="0" step="1" id="quantita_disp" required>
                {/if}
                -->
                <label for="color">Colore</label>
                <input type="text" name="color" id="color" value="{$color}" required>
            </fieldset>

            <!--
            {if $isProdottoNuovo == 1}
                <div class="form-group">
                    <label for="price">Prezzo</label>
                    <input type="number" name="prezzo-nuovo" id="pricefornew" value={$prezzo_fisso} min="1" step="0.01" placeholder="€####" required>
                </div>
            {elseif $isProdottoNuovo == 0}
                <div class="form-group">
                    <label for="starting_price">Prezzo di partenza</label>
                    <input type="number" id="prezzo-asta" value={$floor_price} min="1" step="0.01" disabled>
                </div>
                <div class="form-group">
                    <label for="auction_date">Data inizio asta</label>
                    <input type="text" id="data-inizio-asta-mod" value="{$data_inizio_asta}" pattern="^(\d\d\d\d)-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]) ([01]\d|2[0-3]):([0-5]\d):([0-5]\d)$" disabled>
                </div>
                <div class="form-group">
                    <label for="auction_date">Data fine asta</label>
                    <input type="text" name="data-fine-asta" id="data-fine-asta-mod" value="{$data_fine_asta}" pattern="^(\d\d\d\d)-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]) ([01]\d|2[0-3]):([0-5]\d):([0-5]\d)$" placeholder="YYYY-MM-DD HH:MM:SS" required>
                </div>
            {/if}
            -->

            <label for="price">Prezzo</label>
                    <input type="number" name="priceProduct" id="priceProduct" value={$priceProduct} min="1" step="0.01" placeholder="€####" required>

            <button type="submit" class="btn btn-primary">Modifica</button>
        </div>

    </form>
</div>
<script>
const input = document.getElementById('images');
const imagePreview = document.getElementById('imagePreview');
let imageArray = [];

input.addEventListener('change', () => {
    const files = Array.from(input.files);
    if (files.length + imageArray.length > 5) {
            alert('Puoi caricare un massimo di 5 immagini.');
            input.value = ''; // Resetta il campo file input
            return;
        }

    imageArray = imageArray.concat(files);

    updateImagePreview();
    updateInputFiles();
});

function updateImagePreview() {
    imagePreview.innerHTML = '';

    imageArray.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = () => {
            const imgContainer = document.createElement('div');
            imgContainer.classList.add('image-container');

            const img = document.createElement('img');
            img.src = reader.result;
            imgContainer.appendChild(img);

            const removeButton = document.createElement('button');
            removeButton.classList.add('remove-button');
            removeButton.textContent = 'Rimuovi';
            removeButton.onclick = () => {
                imageArray.splice(index, 1);
                updateImagePreview();
                updateInputFiles();
            };

            imgContainer.appendChild(removeButton);
            imagePreview.appendChild(imgContainer);
        };
        reader.readAsDataURL(file);
    });
}

function updateInputFiles() {
    const dataTransfer = new DataTransfer();
    imageArray.forEach(file => dataTransfer.items.add(file));
    input.files = dataTransfer.files;
}

function removeImage(button) {
    const index = Array.from(imagePreview.children).indexOf(button.parentElement);
    imageArray.splice(index, 1);
    updateImagePreview();
    updateInputFiles();
}
</script>