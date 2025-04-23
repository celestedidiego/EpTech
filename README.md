# EpTech

# 🛍️ EpTech

**EpTech** è un'applicazione e-commerce sviluppata con PHP, progettata per offrire un'esperienza d'acquisto solida, moderna e facilmente estendibile. Il progetto supporta la gestione di prodotti, carrelli, ordini e utenti, con un'interfaccia responsive basata su Bootstrap e interazioni dinamiche con jQuery.

## 🚀 Funzionalità principali

- Catalogo prodotti con categorie e ricerca
- Sistema di carrello dinamico
- Checkout con riepilogo ordine
- Autenticazione e gestione utenti
- Dashboard amministrativa per prodotti e ordini
- Template con Smarty

## 🛠️ Stack Tecnologico

- **Linguaggio**: PHP
- **Framework**: Symfony
- **ORM**: Doctrine
- **Templating**: Smarty
- **Database**: MySQL
- **Gestione pacchetti**: Composer
- **Frontend**: Bootstrap, jQuery

## 📦 Installazione Windows

1. Posizionati nella directory xampp/htdocs

2. Clona il repository (o in alternativa estrai il .zip scaricato da github):
   ```shell
      git clone https://github.com/celestedidiego/EpTech
   ```

3. Crea un nuovo database con nome eptechprova su `localhost/phpmyadmin` e importa il db provaeptech.sql incluso nel repository.

## 📦 Installazione Linux

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