<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>



<body style="margin:0; padding:0; font-family: Arial, sans-serif; background-color: #f4f4f4;">

    <table width="100%" bgcolor="#f4f4f4" cellpadding="0" cellspacing="0" style="padding: 20px 0;">
        <tr>
            <td align="center">

                <table width="600" bgcolor="#ffffff" cellpadding="0" cellspacing="0"
                    style="border-radius: 8px; overflow: hidden;">
                    <tr>
                        <td align="center" style="padding: 20px; background-color: #007bff; color: #ffffff;">
                            <h1 style="margin: 0; font-size: 24px;">{{ config('app.name') }}</h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 30px; color: #333333;">
                            <h2 style="margin-top: 0;">Reset Your Password</h2>
                            <p>You recently requested to reset your password. Click the button below to reset it.</p>

                            <p style="text-align: center; margin: 30px 0;">
                                <a href="{{ $url }}"
                                    style="background-color: #007bff; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block;">
                                    Reset Password
                                </a>
                            </p>

                            <p>If you did not request a password reset, please ignore this email.</p>
                        </td>
                    </tr>

                    <tr>
                        <td
                            style="padding: 20px; text-align: center; font-size: 12px; color: #888888; background-color: #f9f9f9;">
                            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>

</html>
