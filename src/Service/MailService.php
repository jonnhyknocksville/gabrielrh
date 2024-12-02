<?PHP

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailService
{
    private $mailer;

    private $params;

    public function __construct(ParameterBagInterface $params, MailerInterface $mailer) {
        $this->mailer = $mailer;
        $this->params = $params;

    }

    public function SendMail($data, $to, $subject, $template) {

        // gerer l'envoie de mail

        $email = (new TemplatedEmail())
        ->from($this->params->get('app.mail_address_dsn'))
        ->to(new Address($to))
        ->subject($subject)

        // chemin vers le template twig
        ->htmlTemplate($template)
        ->context($data);

        $this->mailer->send($email);
    }

    public function SendMailWithAttachments($data, $to, $subject, $template, array $attachments = []) {

        // Créer l'email avec template
        $email = (new TemplatedEmail())
            ->from($this->params->get('app.mail_address_dsn'))
            ->to(new Address($to))
            ->subject($subject)
            ->htmlTemplate($template)
            ->context($data);

        // Ajouter les fichiers en pièces jointes
        foreach ($attachments as $attachment) {
            $email->attachFromPath($attachment);
        }

        // Envoyer l'email
        $this->mailer->send($email);
    }

}

?>