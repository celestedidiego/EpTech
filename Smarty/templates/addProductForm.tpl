
{if $errorImageUpload == 1}
    <div class="mt-5">
        <div class="alert alert-danger" role="alert">
            Errore nell'upload delle immagini! Size troppo grande o tipo del file diverso da jpeg/png !
        </div>
    </div>
{/if}

<div class="form-container width-90">
        <h2>Aggiungi un prodotto</h2>
        <form class="registrationForm" id="addProductForm" method="POST" action="/EpTech/product/addProduct" enctype="multipart/form-data">
            <div class="left-column">
                <div class="form-group">
                    <label>Nome prodotto</label>
                    <input name="name" type="text" class="form-control" id="nome" placeholder="Nome..." required>
                </div>
                
                <div class="form-group">
                <label>Descrizione</label>
                <br>
                    <textarea name="description" id="description" rows="10" cols="57" required></textarea>
                </div>
                <div class="form-group">
                <label>Categoria</label>
                <select name="category" id="category" required>
                {foreach from=$array_category item=category}
                    <option value="{$category.categoryId}">{$category.nameCategory}</option>
                {/foreach}
                </select>
                </div>

                <div class="form-group">
                    <label for="images">Aggiungi un massimo di 5 immagini:</label>
                    <input id="images" name="images[]" type="file" multiple required>
                </div>

                <div class="image-preview" id="imagePreview">
                </div>

                <div class="form-group mb-3">
                    <label for="price">Prezzo</label>
                    <input type="number" name="priceProduct" id="pricefornew" value={$priceProduct} min="1" step="0.01" placeholder="€####">
                </div>
            </div>

            <div class="right-column">
                <fieldset>
                    <legend>Specifiche dell'oggetto</legend>
            
                    <div class="form-group mb-3">
                        <label for="brand">Marca</label>
                        <input type="text" name="brand" id="brand" class="form-control" required>
                    </div>
            
                    <div class="form-group mb-3">
                        <label for="model">Modello</label>
                        <input type="text" name="model" id="model" class="form-control" required>
                    </div>
            
                    <div class="form-group mb-3">
                        <label for="quantity">Quantità</label>
                        <input type="number" name="avQuantity" id="quantita_disp" class="form-control" min="1" step="1" required>
                    </div>
            
                    <div class="form-group mb-3">
                        <label for="color">Colore</label>
                        <input type="text" name="color" id="color" class="form-control" required>
                    </div>
                </fieldset>
            
                <button type="submit" class="btn btn-primary mt-3">Aggiungi</button>
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
