<html>
<head>
    <title>{{ $title }}</title>
    <link rel="shortcut icon" href="{{ image_file(\App\Models\Config::getFavicon()) }}" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="{{ asset('modules/online/scorm/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('modules/online/scorm/js/load-ajax.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('modules/online/scorm/css/scorm.css') }}">
</head>
<body>
<div id="xapi-display">
    <iframe id="scorm_object"
            allowfullscreen="allowfullscreen"
            webkitallowfullscreen="webkitallowfullscreen"
            mozallowfullscreen="mozallowfullscreen"
            src="/online/xapi/redirect?scoid={{ $activity->id }}&attempt={{ $attempt->id }}&uuid={{$attempt->uuid}}">

    </iframe>
</div>
</body>
</html>
