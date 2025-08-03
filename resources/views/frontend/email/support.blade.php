<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template</title>
</head>
<body>
    <p>Dear {{ $data['Name'] }},</p>

    <p>Thank you for contacting us!</p>
    
    <h1>Details</h1>

    <table style="width: 50%; border-collapse: collapse;">
        <tr>
            <th style="text-align: left; padding: 10px; border: 1px solid #ddd;">Name</th>
            <td style="text-align: left; padding: 10px; border: 1px solid #ddd;">{{ $data['Name'] }}</td>
        </tr>
        <tr>
            <th style="text-align: left; padding: 10px; border: 1px solid #ddd;">Email</th>
            <td style="text-align: left; padding: 10px; border: 1px solid #ddd;">{{ $data['Email'] }}</td>
        </tr>
        <tr>
            <th style="text-align: left; padding: 10px; border: 1px solid #ddd;">Phone</th>
            <td style="text-align: left; padding: 10px; border: 1px solid #ddd;">{{ $data['Phone'] }}</td>
        </tr>
        
        <tr>
            <th style="text-align: left; padding: 10px; border: 1px solid #ddd;">Message</th>
            <td style="text-align: left; padding: 10px; border: 1px solid #ddd;">{{ $data['Message'] }}</td>
        </tr>
       
    </table>

    <p>Best Regards,<br>Ursbid</p>
    <p>Phone No. :- +91 9984555300</p>
   
    <p>Visit Us :-✨Village - Parewpur, Post - Dharshawa, District - Shrawasti, Uttar Pradesh, 271835</p>

</body>
</html>
