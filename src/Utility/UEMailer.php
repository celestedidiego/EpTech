<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class UEMailer {
    private $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);
        // Imposta la codifica a UTF-8 per supportare caratteri speciali come €
        $this->mailer->CharSet = 'UTF-8';
        // Carica le configurazioni da un file esterno o da variabili d'ambiente
        $config = $this->loadConfig();

        // Configurazione del server
        $this->mailer->isSMTP();
        $this->mailer->Host = $config['smtp_host'];
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $config['smtp_username'];
        $this->mailer->Password = $config['smtp_password'];
        $this->mailer->SMTPSecure = $config['smtp_secure'];
        $this->mailer->Port = $config['smtp_port'];

        // Impostazioni del mittente
        $this->mailer->setFrom($config['from_email'], $config['from_name']);
    }

    private function loadConfig() {
        /** Nel file configMailer.php, va configurato il servizio di mail come segue
         * (nel nostro caso, abbiamo usato MailTrap, servizio online che ci permette di testare l'invio delle mail)
         * <?php
        

            return [
                'smtp_host' => 'sandbox.smtp.mailtrap.io',
                'smtp_username' => 'a940082c2f6a4e',
                'smtp_password' => '5a816ceb23d405',
                'smtp_secure' => 'tls',
                'smtp_port' => 2525,
                'from_email' => 'riccardo@riccardo.com',
                'from_name' => 'EpTech Admin',
            ]; 

        */
        return include __DIR__ .'/../../config/configMailer.php';
    }

    public function sendAccountDeletionEmail($userEmail) {
        try {
            $this->mailer->addAddress($userEmail);
            $this->mailer->Subject = 'Il tuo account EpTech e\' stato eliminato';
            $this->mailer->Body = 'Il tuo account e\' stato eliminato dall\'amministratore di EpTech.';

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function sendProductDeletionEmail($userEmail,$nameProduct) {
        try {
            $this->mailer->addAddress($userEmail);
            $this->mailer->Subject = 'Il prodotto e\' stato eliminato';
            $this->mailer->Body = 'Il prodotto '. $nameProduct .' e\' stato eliminato con successo.';

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function sendEmailConfirmation($userEmail, $confirmationLink) {
        try {
            $this->mailer->addAddress($userEmail);
            $this->mailer->Subject = 'Conferma la tua email su EpTech';
            $this->mailer->Body = "Grazie per esserti registrato su EpTech. Clicca sul link seguente per confermare la tua email: <a href='$confirmationLink'>$confirmationLink</a>";
            $this->mailer->AltBody = "Grazie per esserti registrato su EpTech. Clicca sul link seguente per confermare la tua email: $confirmationLink";
    
            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function sendWelcomeEmail($userEmail) {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($userEmail);
            $this->mailer->Subject = 'Benvenuto su EpTech!';
            $this->mailer->Body = "Ciao e benvenuto su EpTech! Siamo felici di averti con noi.";
            $this->mailer->AltBody = "Ciao e benvenuto su EpTech! Siamo felici di averti con noi.";

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function sendOrderConfirmationEmail($userEmail, $order) {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($userEmail);
            $this->mailer->Subject = 'Conferma ordine EpTech';
            $orderId = $order->getIdOrder();
            $orderTotal = $order->getTotalPrice();
            $orderDate = $order->getDateTime()->format('d/m/Y H:i');
            $this->mailer->Body = "Grazie per il tuo ordine su EpTech!\n Dettagli ordine: \n Numero ordine: $orderId Data: $orderDate Totale: €: $orderTotal \n Riceverai aggiornamenti sullo stato dell'ordine via email.";
            $this->mailer->AltBody = "Grazie per il tuo ordine su EpTech! \n Numero ordine: $orderId, Data: $orderDate, Totale: €: $orderTotal";

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function sendOrderStatusUpdateEmail($userEmail, $order, $newStatus) {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($userEmail);
            $this->mailer->Subject = 'Aggiornamento stato ordine EpTech';
            $orderId = $order->getIdOrder();
            $orderDate = $order->getDateTime()->format('d/m/Y H:i');
            $statusText = ucfirst($newStatus);
            $this->mailer->Body = "Il tuo ordine EpTech (n. $orderId, effettuato il $orderDate) ha cambiato stato: $statusText.";
            $this->mailer->AltBody = "Il tuo ordine EpTech (n. $orderId, effettuato il $orderDate) ha cambiato stato: $statusText.";

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}