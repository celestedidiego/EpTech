<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class UEMailer {
    private $mailer;

    public function __construct() {
        $this->mailer = new PHPMailer(true);

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
            $this->mailer->Subject = 'Un tuo prodotto e\' stato eliminato';
            $this->mailer->Body = 'Il prodotto '. $nameProduct .' che avevi messo in vendita e\' stato eliminato dall\'amministratore di EpTech.';

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
}