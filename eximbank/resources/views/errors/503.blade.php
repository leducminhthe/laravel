<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống đang bảo trì vui lòng quay lại sau</title>
    <style>
        *{ margin: 0px; padding: 0px; }
        body{ font-family: Arial, sans-serif;}
        .container{ margin: 0px 20px; padding: 20px; }
        .text-center{ text-align: center; }
        .title{ font-size: 30px; }
        .subtitle{ font-size:20px; color: #aaa; margin-top: 50px; }
    </style>
</head>
<body>
<div class="container">
    <h1 class="text-center title">Hệ thống đang bảo trì vui lòng quay lại sau</h1>
    <div class="text-center">
            <img src="{{ asset('images/maintenance.png') }}" alt="Maintenance Image" class="maintenance-image">
    </div>
</div>
</body>
</html>
