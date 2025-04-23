<?php

class CProduct {
    public static function listProducts() {
        $view = new VProduct();
        
        $filters = [
            'query' => isset($_GET['query']) ? $_GET['query'] : '',
            'category' => isset($_GET['category']) ? $_GET['category'] : '',
            'brand' => isset($_GET['brand']) ? $_GET['brand'] : '',
            'max_price' => isset($_GET['max_price']) ? (int)$_GET['max_price'] : 5000,
        ];
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        
        // Check for success messages in the session
        $product_added = isset($_SESSION['product_added']) && $_SESSION['product_added'];
        $product_modified = isset($_SESSION['product_modified']) && $_SESSION['product_modified'];
        $product_deleted = isset($_SESSION['product_deleted']) && $_SESSION['product_deleted'];
        
        // Remove success messages from the session
        unset($_SESSION['product_added']);
        unset($_SESSION['product_modified']);
        unset($_SESSION['product_deleted']);

        if (CUser::isLogged()) {
            if ($_SESSION['user'] instanceof EAdmin) {
                $products = FPersistentManager::getInstance()->getAllProductsByAdmin($_SESSION['user'], $page, $filters);
                $categories = FPersistentManager::getInstance()->getAllCategories();
                $brands = FPersistentManager::getInstance()->getAllBrands();
                $view->listProducts($products, $categories, $brands, $filters, $product_added, $product_modified, $product_deleted);
            } else {
                header('Location: /EpTech/user/home');
            }
        } else {
            header('Location: /EpTech/user/login');
        }
    }
    
    public static function addProduct()
    {
        $view = new VProduct();
        $array_category = FPersistentManager::getInstance()->getAllCategories();

        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            if ($_SESSION['user'] instanceof EAdmin) {
                $view->addProductForm($array_category);
            } else {
                header('Location: /EpTech/user/login');
            }
        } elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
            $postData = $_POST;
            foreach ($postData as $key => $value) {
                $array_data[$key] = $value;
            }

            // Recupera l'ID della categoria selezionata dal form
            $categoryId = $array_data['category'];

            // Recupera l'amministratore loggato
            $found_seller = FPersistentManager::getInstance()->find(EAdmin::class, $_SESSION['user']->getIdAdmin());

            if (!$found_seller) {
                exit('Errore: amministratore non trovato.');
            }

            // Recupera la categoria selezionata
            $found_category = FPersistentManager::getInstance()->find(ECategory::class, $categoryId);

            if (!$found_category) {
                exit('Errore: categoria non trovata.');
            }

            // Crea il prodotto e associa l'amministratore e la categoria
            $product = new EProduct(
                $array_data['name'],
                (float)$array_data['priceProduct'],
                $array_data['description'],
                $array_data['brand'],
                $array_data['model'],
                $array_data['color'],
                (int)$array_data['avQuantity']
            );
            $product->setAdmin($found_seller);
            $product->setNameCategory($found_category);

            // Salva il prodotto
            FPersistentManager::getInstance()->insertProduct($product);

            // Salva le immagini associate al prodotto
            foreach ($_FILES['images']['tmp_name'] as $key => $value) {
                $fileName = $_FILES['images']['name'][$key];
                $fileSize = $_FILES['images']['size'][$key];
                $fileType = $_FILES['images']['type'][$key];
                $content = file_get_contents($_FILES['images']['tmp_name'][$key]);
                $base64content = base64_encode($content);
                $image = new EImage($fileName, $fileSize, $fileType, $base64content);

                FPersistentManager::getInstance()->insertImage($image);

                // Trova l'immagine appena inserita
                $found_image = FPersistentManager::getInstance()->find(EImage::class, $image->getIdImage());

                // Collega l'immagine al prodotto
                FPersistentManager::getInstance()->updateImageProduct($product, $found_image);
            }

            $_SESSION['product_added'] = true;
            header('Location: /EpTech/admin/manageProducts?page=1');
        }
    }

    public static function modifyProduct($productId){
        
        $view = new VProduct();
        $product_to_modify = FPersistentManager::getInstance()->find(EProduct::class, $productId);
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            if($_SESSION['user'] instanceof EAdmin){
                $tmp_images = FPersistentManager::getInstance()->getAllImages($product_to_modify);
                
                foreach($tmp_images as $image){
                    $array_images[] = $image;
                }
                $view->modifyProductForm($product_to_modify, $array_images);
            }else{
                header('Location: /EpTech/user/login');
            }
        } elseif ($_SERVER['REQUEST_METHOD'] == "POST") {
            $postData = $_POST;
            foreach ($postData as $key => $value) {
                $array_data[$key] = $value;
            }
            $allowed_types = array('image/jpeg', 'image/png');

            FPersistentManager::getInstance()->updateProduct($product_to_modify, $array_data);

            if(isset($_FILES['images']) && !empty($_FILES['images']['tmp_name'][0])){
                //Elimino tutte le immagini nel database relative al prodotto
                FPersistentManager::getInstance()->deleteAllImages($productId);
                // Controllo se le immagini inserite eccedono una dimensione di 1MB
                foreach($_FILES['images']['size'] as $key => $value) {
                    if($_FILES['images']['size'][$key] > 1000000){
                        $view->errorImageUpload();
                        exit;
                    }
                }
                // Controllo il tipo di file caricati
                foreach($_FILES['images']['type'] as $key => $value) {
                    if(!(in_array($_FILES['images']['type'][$key], $allowed_types))){
                        $view->errorImageUpload();
                        exit;
                    }
                }

                // Trova il prodotto appena inserito
                $found_product = FPersistentManager::getInstance()->find(EProduct::class, $productId);
                
                // Inserisci ogni immagine e collegala al prodotto
                foreach($_FILES['images']['tmp_name'] as $key => $value) {
                    $fileName = $_FILES['images']['name'][$key];
                    $fileSize = $_FILES['images']['size'][$key];
                    $fileType = $_FILES['images']['type'][$key];
                    $content = file_get_contents($_FILES['images']['tmp_name'][$key]);
                    $base64content = base64_encode($content);
                    $image = new EImage($fileName, $fileSize, $fileType, $base64content);
                        
                    FPersistentManager::getInstance()->insertImage($image);
                    
                    // Trova l'immagine appena inserita
                    $found_image = FPersistentManager::getInstance()->find(EImage::class, $image->getIdImage());
                    
                    // Collega l'immagine al prodotto
                    FPersistentManager::getInstance()->updateImageProduct($found_product, $found_image);   
                }
            }

            $_SESSION['product_modified'] = true;
            header('Location: /EpTech/admin/?page=1');
        }
    }


    public static function deleteProduct($productId) {
        FPersistentManager::getInstance()->deleteProduct($productId);
        $_SESSION['product_deleted'] = true;
        header('Location: /EpTech/product/listProducts?page=1');
    }

}