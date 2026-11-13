<?php

namespace App\Libraries;

use Config\Email as EmailConfig;

/**
 * Wraps CI4 email service using the same SMTP settings as CI3 config/email.php (.env).
 */
class RomanMailer
{
    /** View paths under app/Views/email/templates/ (no .php suffix). */
    public const TEMPLATE_PRODUCT_INQUIRY           = 'product_inquiry';

    public const TEMPLATE_ACCOUNT_ACTIVATION        = 'account_activation';

    public const TEMPLATE_ACCOUNT_ACTIVATION_SIMPLE = 'account_activation_simple';

    protected EmailConfig $config;

    public function __construct(?EmailConfig $config = null)
    {
        $this->config = $config ?? config(EmailConfig::class);
    }

    /**
     * Render an HTML email view from app/Views/email/templates/.
     */
    public function renderTemplate(string $template, array $data = []): string
    {
        return view('email/templates/' . $template, $data);
    }

    public function sendHtml(string $to, string $subject, string $message, string $from, string $fromName = '', ?string $replyTo = null): bool
    {
        $email = service('email');
        $email->clear(true);
        $email->initialize([
            'protocol'   => $this->config->protocol,
            'SMTPHost'   => $this->config->SMTPHost,
            'SMTPUser'   => $this->config->SMTPUser,
            'SMTPPass'   => $this->config->SMTPPass,
            'SMTPPort'   => $this->config->SMTPPort,
            'SMTPCrypto' => $this->config->SMTPCrypto,
            'mailType'   => $this->config->mailType,
            'charset'    => $this->config->charset,
            'newline'    => "\r\n",
            'CRLF'       => "\r\n",
            'SMTPTimeout'=> $this->config->SMTPTimeout,
        ]);

        $email->setFrom($from, $fromName);

        if ($replyTo !== null && $replyTo !== '') {
            $email->setReplyTo($replyTo);
        }

        $email->setTo($to);
        $email->setSubject($subject);
        $email->setMessage($message);

        return $email->send(false);
    }

    public function getFromEmail(): string
    {
        if ($this->config->fromEmail !== '') {
            return $this->config->fromEmail;
        }

        return $this->config->SMTPUser;
    }

    public function getFromName(): string
    {
        return $this->config->fromName !== '' ? $this->config->fromName : 'Roman Thomas';
    }

    public function getReplyTo(): string
    {
        if ($this->config->replyTo !== '') {
            return $this->config->replyTo;
        }

        return $this->getFromEmail();
    }

    /**
     * Send using operational From/Reply-To from .env (SMTP auth still from email.SMTP*).
     */
    public function sendOperationalHtml(string $to, string $subject, string $message): bool
    {
        return $this->sendHtml(
            $to,
            $subject,
            $message,
            $this->getFromEmail(),
            $this->getFromName(),
            $this->getReplyTo()
        );
    }

    /**
     * Send mail using a template from app/Views/email/templates/ and operational From/Reply-To.
     */
    public function sendOperationalTemplate(string $to, string $subject, string $template, array $data = []): bool
    {
        return $this->sendOperationalHtml($to, $subject, $this->renderTemplate($template, $data));
    }

    /**
     * Send mail using a template with explicit From/Reply-To (e.g. test_mail).
     */
    public function sendTemplate(
        string $to,
        string $subject,
        string $template,
        array $data,
        string $from,
        string $fromName = '',
        ?string $replyTo = null
    ): bool {
        return $this->sendHtml(
            $to,
            $subject,
            $this->renderTemplate($template, $data),
            $from,
            $fromName,
            $replyTo
        );
    }

    public function printDebugger(): string
    {
        if (! CI_DEBUG) {
            return 'Email debug output is disabled outside debug mode.';
        }

        return service('email')->printDebugger(['headers']);
    }
}
