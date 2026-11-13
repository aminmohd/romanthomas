<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="x-apple-disable-message-reformatting" />
  <title>romanthomas Email Template</title>
</head>
<body style="margin:0; padding:0; background-color:#f2ece6;">
  <div style="display:none; font-size:1px; color:#f2ece6; line-height:1px; max-height:0; max-width:0; opacity:0; overflow:hidden;">
    romanthomas — furniture and home décor. Visit romanthomas.com
  </div>

  <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#f2ece6; margin:0; padding:24px 12px;">
    <tr>
      <td align="center">
        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" style="width:100%; max-width:600px; background-color:#fffdfa; border:1px solid #ded2c6;">
          <tr>
            <td style="height:6px; background-color:#b07a53; font-size:0; line-height:0;">&nbsp;</td>
          </tr>

          <tr>
            <td style="padding:26px 28px 18px 28px; border-bottom:1px solid #e8ddd1; background-color:#fffdfa;">
              <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                  <td align="left" style="font-family:Arial, Helvetica, sans-serif; color:#2f241c;">
                    <div style="font-size:28px; line-height:1.2; font-weight:700; letter-spacing:0.6px;"><a href="https://romanthomas.com" target="_blank" style="color:#2f241c; text-decoration:none;">ROMANTHOMAS</a></div>
                    <div style="font-size:13px; line-height:1.5; color:#8f7d6c; margin-top:4px;">
                      Timeless furniture for modern living
                    </div>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
          </tr>

          <tr>
            <td style="padding:26px 28px 10px 28px; font-family:Arial, Helvetica, sans-serif; color:#2f241c;" align="center">
              <h1 style="margin:0; font-size:22px; line-height:1.3; font-weight:700; color:#2c3e50;">We&rsquo;ve Received Your Inquiry &ndash; Thank You!</h1>
            </td>
          </tr>
          <tr>
            <td style="padding:10px 28px 8px 28px; font-family:Arial, Helvetica, sans-serif; color:#56493d;">
              <p style="margin:0 0 10px 0; font-size:15px; line-height:1.65;">Hi <?= esc($fullname ?? '') ?>,</p>
              <p style="margin:0 0 10px 0; font-size:15px; line-height:1.65;">Thank you for reaching out! We&rsquo;ve successfully received your product inquiry with the following details.</p>
            </td>
          </tr>
          <tr>
            <td style="padding:0 28px 14px 28px; font-family:Arial, Helvetica, sans-serif;">
              <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="border-collapse:collapse;">
                <tr>
                  <td style="padding:10px 8px; font-weight:700; color:#2f241c; width:40%; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;">Name</td>
                  <td style="padding:10px 8px; color:#56493d; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;"><?= esc($fullname ?? '') ?></td>
                </tr>
                <tr>
                  <td style="padding:10px 8px; font-weight:700; color:#2f241c; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;">Email Address</td>
                  <td style="padding:10px 8px; color:#56493d; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;"><?= esc($email ?? '') ?></td>
                </tr>
                <tr>
                  <td style="padding:10px 8px; font-weight:700; color:#2f241c; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;">Phone Number</td>
                  <td style="padding:10px 8px; color:#56493d; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;"><?= esc($phone ?? '') ?></td>
                </tr>
                <tr>
                  <td style="padding:10px 8px; font-weight:700; color:#2f241c; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;">Model Number</td>
                  <td style="padding:10px 8px; color:#56493d; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;"><?= esc($modelno ?? '') ?></td>
                </tr>
                <tr>
                  <td style="padding:10px 8px; font-weight:700; color:#2f241c; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;">Company</td>
                  <td style="padding:10px 8px; color:#56493d; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;"><?= esc($company ?? '') ?></td>
                </tr>
                <tr>
                  <td style="padding:10px 8px; font-weight:700; color:#2f241c; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;">Question</td>
                  <td style="padding:10px 8px; color:#56493d; vertical-align:top; border-bottom:1px solid #e8ddd1; font-size:14px;"><?= esc($question ?? '') ?></td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
            <td style="padding:0 28px 26px 28px; font-family:Arial, Helvetica, sans-serif; color:#56493d;">
              <p style="margin:0; font-size:15px; line-height:1.65;">
                We appreciate your interest and will get back to you shortly,<br />
                Thanks,<br />
                <strong style="color:#2f241c;">Roman Thomas</strong><br />
                <a href="https://romanthomas.com/" target="_blank" style="color:#9d6944; font-weight:700; text-decoration:underline;">https://romanthomas.com</a>
              </p>
            </td>
          </tr>

          <tr>
            <td style="padding:22px 28px 26px 28px; border-top:1px solid #e8ddd1; background-color:#f8f2eb; font-family:Arial, Helvetica, sans-serif;">
              <p style="margin:0 0 14px 0; font-size:13px; line-height:1.65; color:#6f5b49;">
                <strong style="color:#2f241c;">Romanthomas Furniture</strong><br />
                Furniture and home d&eacute;cor for every room &mdash; living, dining, bedroom, and accents &mdash; with sofas, seating, tables, lighting, storage, rugs, and more (assortment varies online and in store).<br />
                <a href="https://romanthomas.com/" target="_blank" style="color:#9d6944; font-weight:700; text-decoration:underline;">www.romanthomas.com</a>
              </p>

              <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin:0 0 14px 0;">
                <tr>
                  <td>
                    <a href="https://instagram.com/romanthomasny/" target="_blank" style="text-decoration:none;">
                      <img src="https://img.icons8.com/ios-filled/24/b07a53/instagram-new.png" width="24" height="24" alt="Instagram" style="display:block; border:0; outline:none; text-decoration:none;" />
                    </a>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
