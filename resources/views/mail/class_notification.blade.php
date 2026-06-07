<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Class Notification</title>
</head>
<body style="margin:0;padding:0;background:#F8FAFF;font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#F8FAFF;padding:20px 0;">
  <tr><td align="center">
    <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="background:#FFFFFF;border-radius:16px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.05);border:1px solid #E2E8F0;">
      
      <!-- Header -->
      <tr>
        <td style="padding:40px 40px 30px;background:linear-gradient(135deg, #4F46E5, #7C3AED);text-align:center;">
          <h1 style="margin:0;color:#FFFFFF;font-size:24px;font-weight:800;letter-spacing:-0.5px;">Important Class Update</h1>
          <p style="margin:10px 0 0;color:rgba(255,255,255,0.85);font-size:14px;font-weight:500;">{{ $moduleTitle }}</p>
        </td>
      </tr>

      <!-- Body -->
      <tr>
        <td style="padding:40px;">
          <p style="margin:0 0 20px;font-size:16px;color:#1E293B;font-weight:700;">Hello {{ $studentName }},</p>
          
          <div style="background:#F1F5F9;border-radius:12px;padding:25px;margin-bottom:30px;border-left:4px solid #4F46E5;">
            <p style="margin:0;font-size:15px;color:#334155;line-height:1.6;white-space: pre-line;">
              {{ $messageBody }}
            </p>
          </div>

          @if($classDate)
          <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-bottom:30px;">
            <tr>
              <td style="background:#F8FAFF;border:1.5px solid #E2E8F0;border-radius:10px;padding:15px;text-align:center;">
                <p style="margin:0 0 5px;font-size:11px;font-weight:800;text-transform:uppercase;color:#94A3B8;letter-spacing:1px;">Scheduled For</p>
                <p style="margin:0;font-size:18px;font-weight:800;color:#1E293B;">{{ $classDate }}</p>
              </td>
            </tr>
          </table>
          @endif

          <p style="margin:0 0 10px;font-size:14px;color:#64748B;">If you have any questions, please contact your instructor or reply to this email.</p>
          <p style="margin:0;font-size:14px;font-weight:700;color:#1E293B;">Best regards,<br>Academy Academic Team</p>
        </td>
      </tr>

      <!-- Footer -->
      <tr>
        <td style="padding:25px 40px;background:#F8FAFF;border-top:1px solid #F1F5F9;text-align:center;">
          <p style="font-size:12px;color:#94A3B8;margin:0;">
            © {{ date('Y') }} LMS Academy. All rights reserved.
          </p>
        </td>
      </tr>

    </table>
  </td></tr>
</table>

</body>
</html>
