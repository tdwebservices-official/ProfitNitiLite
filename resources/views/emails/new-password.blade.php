<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your New Password</title>
</head>
<body style="margin:0; padding:0; background:#f4f6f9; font-family: Arial, Helvetica, sans-serif;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9; padding:40px 0;">
    <tr>
      <td align="center">
        <table role="presentation" width="480" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:12px; overflow:hidden;">
          <tr>
            <td style="background:#060d1e; padding:24px 32px;">
              <span style="color:#ffffff; font-size:20px; font-weight:700;">ProfitNiti Lite</span>
            </td>
          </tr>
          <tr>
            <td style="padding:32px;">
              <h2 style="margin:0 0 16px; color:#0f172a;">Hi {{ $user->username ?? $user->email }},</h2>
              <p style="margin:0 0 16px; color:#334155; font-size:15px; line-height:1.6;">
                We received a request to reset your password. Your new temporary password is below.
                Please sign in and change it as soon as possible.
              </p>
              <div style="margin:24px 0; padding:16px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; text-align:center;">
                <span style="font-size:20px; font-weight:700; letter-spacing:1px; color:#1a5cff;">{{ $newPassword }}</span>
              </div>
              <p style="margin:0 0 24px; color:#64748b; font-size:13px; line-height:1.6;">
                If you did not request a new password, please contact our support team immediately.
              </p>
              <a href="{{ route('login.show') }}" style="display:inline-block; background:#1a5cff; color:#ffffff; text-decoration:none; padding:12px 24px; border-radius:8px; font-size:14px; font-weight:600;">
                Sign In
              </a>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
