# 🐼 [EpTech](http://www.eptech.infinityfreeapp.com/)

**EpTech** è un'applicazione e-commerce sviluppata con PHP, progettata per offrire un'esperienza d'acquisto solida, moderna e facilmente estendibile. Il progetto supporta la gestione di prodotti, carrelli, ordini e utenti, con un'interfaccia responsive basata su Bootstrap e interazioni dinamiche con jQuery.

## 🌍 [Visita EpTech](http://www.eptech.infinityfreeapp.com/)
- (Senza certificato `SSL` l'URL deve iniziare con `http`, non con `https`)

## 🚀 Funzionalità principali

- Catalogo prodotti con categorie e ricerca
- Sistema di carrello dinamico
- Checkout con riepilogo ordine
- Autenticazione e gestione utenti
- Dashboard amministrativa per prodotti e ordini
- Template con Smarty

## 🛠️ Stack Tecnologico

- **Linguaggio**: PHP 8.2
- **Framework**: Symfony
- **ORM**: Doctrine
- **Templating**: Smarty
- **Database**: MySQL
- **Gestione pacchetti**: Composer
- **Frontend**: Bootstrap, jQuery
- **Staging**: XAMPP, LAMPP 8.2.12 (PHP 8.2), VS Code, Git, GitHub

## 🔒 Credenziali utenti
- **Admin**: Login: admin@gmail.com | Password: admin
- **Celeste**: Login: celeste@gmail.com | Password: celeste
- **Alessia**: Login: alessia@gmail.com | Password: alessia
- **Riccardo**: Login: riccardo@gmail.com | Password:riccardo

## 📧 Configurazione Mailtrap

1. Crea un account su [Mailtrap](https://mailtrap.io/)

2. Crea il file `configMailer.php` con i parametri di connessione al server smtp di Mailtrap
```php
<?php
    return [
        'smtp_host' => 'sandbox.smtp.mailtrap.io',
        'smtp_username' => 'xxxxxxxxxxxxxx',
        'smtp_password' => 'xxxxxxxxxxxxxx',
        'smtp_secure' => 'tls',
        'smtp_port' => 2525,
        'from_email' => 'admin@eptech',
        'from_name' => 'EpTech Admin',
    ];
?>
```
3. Inserisci questo file in `EpTech/config/configMailer.php`
4. Ora sei in grado di ricevere le mail di EpTech

## 📦🪟 Installazione Windows

1. Posizionati nella directory `xampp/htdocs`

2. Clona il repository (o in alternativa estrai il .zip scaricato da github):
   ```shell
      git clone https://github.com/celestedidiego/EpTech
   ```

3. Crea un nuovo database con nome eptechprova su `localhost/phpmyadmin` e importa il db provaeptech.sql incluso nel repository.

## 📦🐧 Installazione Linux

1. Posizionati nella directory `/opt/lampp/htdocs`

2. Clona il repository (o in alternativa estrai il .zip scaricato da github):
```bash
   git clone https://github.com/celestedidiego/EpTech
```

3. Imposta correttamente il gruppo di utenti e i permessi per i file con questi comandi:
```bash
    sudo chown -R nome_utenete:daemon /opt/lampp/htdocs/
    sudo chmod -R 775 /opt/lampp/htdocs/
```
Puoi verificare la correttezza dei premessi con il comando:
```bash 
    ls -al
```
L'output deve essere di questo tipo:
```bash 
    nome_utente@nome_macchina:/opt/lampp/htdocs/EpTech$ ls -al
    totale 1568
    drwxrwxr-x  9 nome_utente daemon    4096 apr 23 11:48 .
    drwxrwxr-x 12 nome_utente daemon    4096 apr 23 11:53 ..
    -rwxrwxr-x  1 nome_utente daemon     650 apr 23 11:48 composer.json
    -rwxrwxr-x  1 nome_utente daemon  206745 apr 23 11:48 composer.lock
    drwxrwxr-x  2 nome_utente daemon    4096 apr 23 11:48 config
    drwxrwxr-x  8 nome_utente daemon    4096 apr 23 12:29 .git
    -rwxrwxr-x  1 nome_utente daemon     125 apr 23 11:48 .gitignore
    -rwxrwxr-x  1 nome_utente daemon     525 apr 23 11:48 .htaccess
    -rwxrwxr-x  1 nome_utente daemon     422 apr 23 11:48 index.php
    -rwxrwxr-x  1 nome_utente daemon    1109 apr 23 11:48 LICENSE
    -rwxrwxr-x  1 nome_utente daemon 1332882 apr 23 11:48 provaeptech.sql
    -rwxrwxr-x  1 nome_utente daemon    1476 apr 23 12:31 README.md
    drwxrwxr-x  3 nome_utente daemon    4096 apr 23 11:48 skin
    drwxrwxr-x  4 nome_utente daemon    4096 apr 23 11:53 Smarty
    drwxrwxr-x  7 nome_utente daemon    4096 apr 23 11:48 src
    drwxrwxr-x  2 nome_utente daemon    4096 apr 23 11:48 tests
    drwxrwxr-x 19 nome_utente daemon    4096 apr 23 11:48 vendor
```

4. Crea un nuovo database con nome eptechprova su `localhost/phpmyadmin` e importa il db provaeptech.sql incluso nel repository.

## 📦🍏 Installazione MacOS
Essendo basato su Unix, i passaggi sono i medesimi di Linux, sostituire lampp con mampp

## 📦🌍 Installazione Web
1. Segui i passi per l'installazione Linux

2. Segui i passi per la configurazione Mailtrap

3. Zippa la Directory EpTech

4. Collegarsi via FTP (oppure utilizzare il file manager dell'hosting web)

5. Unzippa la directory nella `/` (root) dell' hosting web (o dove indicato dall'hosting)

6. A seconda della posizone della directory EpTech spostare il `.htaccess` e l'`index.php` nella root dell'hosting (o dove indicato dall'hosting) e modificare gli URL di `index.php` per farli puntare alla posizone corretta della directory EpTech
```php
    <?php
    # index.php, istruzioni utili per il debug:
    # ini_set('display_errors', 'On');
    # error_reporting(E_ALL);
    # error_log("index.php Inizio esecuzione", 0);

    require_once __DIR__ .'/config/bootstrap.php'; # modificare questo path in accordo con la posizione nel file manger web
    require_once __DIR__ .'/config/StartSmarty.php' # modificare questo path in accordo con la posizione della directory EpTech nel file manager web

    $fc = new CFrontController();
    $fc->run();
    ?>
```

7. Collegarsi al phpmyadmin dell'hosting creare un nuovo DB eptech, importare il db fornito nel repository

8. Imposta i parametri di connessione al DB nel file `EpTech/config/bootstrap.php`
```php
    $connectionParams = [
        'dbname'   => 'eptech',
        'user'     => 'hostingwebuser',
        'password' => 'hostingwebpassword',
        'host'     => 'hostingwebipdb',
        'driver'   => 'pdo_mysql',
];
```

9. EpTech è pronto per il Web! (Assicurarsi che l'url inizi con `http` e non con `https`, ignorare gli avvisi di sicurezza dei browser essendo mancante il certificato `SSL` per `https`)

## 💻 Team di sviluppo
[Celeste Di Diego](https://github.com/celestedidiego), [Alessia Pulcini](https://github.com/alepulc), [Riccardo Beniamino](https://github.com/rickb3n)