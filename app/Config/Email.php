<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    /** Operational From (may differ from SMTPUser). Set via email.fromEmail in .env */
    public string $fromEmail  = '';

    public string $fromName   = '';

    /** Set via email.replyTo in .env */
    public string $replyTo = '';

    public string $recipients = '';

    /** Webhooks::test_mail — set via email.testMailFrom / To / ReplyTo in .env */
    public string $testMailFrom = '';

    public string $testMailTo = '';

    public string $testMailReplyTo = '';

    /** Welcome::index debug recipient — email.welcomeTestRecipient in .env */
    public string $welcomeTestRecipient = '';

    /**
     * The "user agent"
     */
    public string $userAgent = 'CodeIgniter';

    /**
     * The mail sending protocol: mail, sendmail, smtp
     */
    public string $protocol = 'smtp';

    /**
     * The server path to Sendmail.
     */
    public string $mailPath = '/usr/sbin/sendmail';

    /**
     * SMTP Server Hostname
     */
    public string $SMTPHost = '';

    /**
     * Which SMTP authentication method to use: login, plain
     */
    public string $SMTPAuthMethod = 'login';

    /**
     * SMTP Username
     */
    public string $SMTPUser = '';

    /**
     * SMTP Password
     */
    public string $SMTPPass = '';

    /**
     * SMTP Port
     */
    public int $SMTPPort = 25;

    /**
     * SMTP Timeout (in seconds)
     */
    public int $SMTPTimeout = 5;

    /**
     * Enable persistent SMTP connections
     */
    public bool $SMTPKeepAlive = false;

    /**
     * SMTP Encryption.
     *
     * @var string '', 'tls' or 'ssl'. 'tls' will issue a STARTTLS command
     *             to the server. 'ssl' means implicit SSL. Connection on port
     *             465 should set this to ''.
     */
    public string $SMTPCrypto = 'tls';

    /**
     * Enable word-wrap
     */
    public bool $wordWrap = true;

    /**
     * Character count to wrap at
     */
    public int $wrapChars = 76;

    /**
     * Type of mail, either 'text' or 'html'
     */
    public string $mailType = 'text';

    /**
     * Character set (utf-8, iso-8859-1, etc.)
     */
    public string $charset = 'UTF-8';

    /**
     * Whether to validate the email address
     */
    public bool $validate = false;

    /**
     * Email Priority. 1 = highest. 5 = lowest. 3 = normal
     */
    public int $priority = 3;

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $CRLF = "\r\n";

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $newline = "\r\n";

    /**
     * Enable BCC Batch Mode.
     */
    public bool $BCCBatchMode = false;

    /**
     * Number of emails in each BCC batch
     */
    public int $BCCBatchSize = 200;

    /**
     * Enable notify message from server
     */
    public bool $DSN = false;

    public function __construct()
    {
        parent::__construct();

        $this->protocol   = env('email.protocol', $this->protocol) ?? $this->protocol;
        $this->SMTPHost   = env('email.SMTPHost', $this->SMTPHost) ?? $this->SMTPHost;
        $this->SMTPUser   = env('email.SMTPUser', $this->SMTPUser) ?? $this->SMTPUser;
        $this->SMTPPass   = env('email.SMTPPass', $this->SMTPPass) ?? $this->SMTPPass;
        $this->SMTPCrypto = env('email.SMTPCrypto', $this->SMTPCrypto) ?? $this->SMTPCrypto;
        $this->mailType   = env('email.mailType', $this->mailType) ?? $this->mailType;
        $this->charset    = env('email.charset', $this->charset) ?? $this->charset;
        $this->fromEmail  = env('email.fromEmail', $this->fromEmail) ?? $this->fromEmail;
        $this->fromName   = env('email.fromName', $this->fromName) ?? $this->fromName;
        $this->replyTo    = env('email.replyTo', $this->replyTo) ?? $this->replyTo;
        $this->testMailFrom       = env('email.testMailFrom', $this->testMailFrom) ?? $this->testMailFrom;
        $this->testMailTo         = env('email.testMailTo', $this->testMailTo) ?? $this->testMailTo;
        $this->testMailReplyTo    = env('email.testMailReplyTo', $this->testMailReplyTo) ?? $this->testMailReplyTo;
        $this->welcomeTestRecipient = env('email.welcomeTestRecipient', $this->welcomeTestRecipient) ?? $this->welcomeTestRecipient;

        $port = env('email.SMTPPort');
        if ($port !== null && $port !== false && $port !== '') {
            $this->SMTPPort = (int) $port;
        }

        $timeout = env('email.SMTPTimeout');
        if ($timeout !== null && $timeout !== false && $timeout !== '') {
            $this->SMTPTimeout = (int) $timeout;
        }
    }
}
