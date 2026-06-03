<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Welcome to MyLMS</title>
</head>
<body style="margin:0;padding:0;background:#F8FAFF;font-family:'Segoe UI',Arial,sans-serif;color:#1E293B;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#F8FAFF;padding:40px 0;">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;">

      {{-- Header --}}
      <tr>
        <td style="background:linear-gradient(135deg,#4F46E5,#7C3AED);border-radius:16px 16px 0 0;padding:36px 40px;text-align:center;">
          <div style="font-size:28px;font-weight:800;color:#fff;letter-spacing:-0.5px;">MyLMS</div>
          <div style="font-size:13px;color:rgba(255,255,255,.75);margin-top:4px;">Learning Management System</div>
        </td>
      </tr>

      {{-- Body --}}
      <tr>
        <td style="background:#fff;padding:36px 40px;">

          <p style="font-size:15px;font-weight:700;margin:0 0 6px;">Hello, {{ $studentName }}! 🎓</p>
          <p style="font-size:14px;color:#64748B;margin:0 0 24px;line-height:1.7;">
            Your registration has been reviewed and <strong style="color:#10B981;">approved</strong>.
            Your student account is now active. Use the credentials below to log in.
          </p>

          {{-- Credentials box --}}
          <table width="100%" cellpadding="0" cellspacing="0" style="background:#F8FAFF;border:1.5px solid #E2E8F0;border-radius:12px;margin-bottom:24px;">
            <tr>
              <td style="padding:20px 24px;">
                <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#94A3B8;margin:0 0 12px;">Login Credentials</p>
                <table width="100%" cellpadding="0" cellspacing="0">
                  <tr>
                    <td style="padding:6px 0;font-size:13px;color:#64748B;width:100px;">Email</td>
                    <td style="padding:6px 0;font-size:13px;font-weight:700;color:#1E293B;">{{ $email }}</td>
                  </tr>
                  <tr>
                    <td style="padding:6px 0;font-size:13px;color:#64748B;">Password</td>
                    <td style="padding:6px 0;">
                      <span style="background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff;padding:3px 12px;border-radius:6px;font-size:13px;font-weight:700;letter-spacing:1px;">
                        {{ $password }}
                      </span>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>

          {{-- Enrolled courses --}}
          <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#94A3B8;margin:0 0 10px;">Enrolled Modules</p>
          @foreach($enrolledCourses as $course)
          <div style="display:flex;align-items:center;gap:8px;margin-bottom:7px;">
            <span style="width:8px;height:8px;border-radius:50%;background:#4F46E5;display:inline-block;flex-shrink:0;"></span>
            <span style="font-size:13px;color:#1E293B;">{{ $course }}</span>
          </div>
          @endforeach

          <div style="margin-top:28px;padding:14px 18px;background:#FFFBEB;border:1px solid #FCD34D;border-radius:10px;">
            <p style="margin:0;font-size:12.5px;color:#92400E;line-height:1.6;">
              <strong>⚠ Important:</strong> Please change your password after your first login for security.
              Visit your profile settings once logged in.
            </p>
          </div>

          <div style="text-align:center;margin-top:28px;">
            <a href="{{ url('/login') }}"
               style="display:inline-block;background:linear-gradient(135deg,#4F46E5,#7C3AED);color:#fff;padding:13px 36px;border-radius:10px;font-size:14px;font-weight:700;text-decoration:none;box-shadow:0 6px 18px rgba(79,70,229,.3);">
              Login to MyLMS →
            </a>
          </div>

        </td>
      </tr>

      {{-- Footer --}}
      <tr>
        <td style="background:#F8FAFF;border-radius:0 0 16px 16px;padding:20px 40px;text-align:center;border-top:1px solid #E2E8F0;">
          <p style="font-size:12px;color:#94A3B8;margin:0;">
            © {{ date('Y') }} MyLMS. If you did not register for this account, please ignore this email.
          </p>
        </td>
      </tr>

    </table>
  </td></tr>
</table>

</body>
</html>
