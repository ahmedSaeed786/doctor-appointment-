<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f6f8; font-family: Arial, sans-serif;">

    <table width="100%" bgcolor="#f4f6f8" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">

                ```
                <table width="400" cellpadding="0" cellspacing="0"
                    style="background:#ffffff; margin:40px 0; padding:30px; border-radius:8px;">

                    <!-- Logo / Title -->
                    <tr>
                        <td align="center" style="font-size:22px; font-weight:bold; color:#333;">
                            Ahmed Saeed
                        </td>
                    </tr>

                    <!-- Message -->
                    <tr>
                        <td align="center" style="padding:20px 0; font-size:16px; color:#555;">
                            Your One-Time Password (OTP)
                        </td>
                    </tr>

                    <!-- OTP BOX -->
                    <tr>
                        <td align="center">
                            <div
                                style="display:inline-block; padding:15px 25px; font-size:28px; letter-spacing:6px; font-weight:bold; color:#2d89ef; background:#f1f7ff; border-radius:6px;">
                                {{ Session::get('otp') }}
                            </div>
                        </td>
                    </tr>

                    <!-- Expiry -->
                    <tr>
                        <td align="center" style="padding-top:20px; font-size:14px; color:#888;">
                            This code will expire in 5 minutes.
                        </td>
                    </tr>

                    <!-- Warning -->
                    <tr>
                        <td align="center" style="padding-top:10px; font-size:13px; color:#999;">
                            Do not share this code with anyone.
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="padding:20px 0;">
                            <hr style="border:none; border-top:1px solid #eee;">
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="font-size:12px; color:#aaa;">
                            If you didn’t request this, you can ignore this email.
                        </td>
                    </tr>

                </table>
                ```

            </td>
        </tr>
    </table>

</body>

</html>
