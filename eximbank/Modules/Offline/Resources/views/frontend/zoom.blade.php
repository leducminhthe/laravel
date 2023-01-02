<!DOCTYPE html>
<head>
    <title>ZOOM WEB LIVE</title>
    <meta charset="utf-8" />
    <!--<link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="font-awesome/css/all.min.css">-->
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/2.4.0/css/bootstrap.css"/>
    <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
    <link type="text/css" rel="stylesheet" href="https://source.zoom.us/2.4.0/css/react-select.css" />
    <meta name="format-detection" content="telephone=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
</head>
<body oncontextmenu="return false;">
<style type="text/css">
    body {
        padding-top: 50px;
    }
    .navbar-inverse {
        background-color: #313131;
        border-color: #404142;
    }
    .navbar-header h4 {
        margin: 0;
        padding: 15px 15px;
        color: #c4c2c2;
    }



    .navbar-right h5 {
        margin: 0;
        padding: 9px 5px;
        color: #c4c2c2;
    }
    .navbar-inverse .navbar-collapse, .navbar-inverse .navbar-form{
        border-color: transparent;
    }
</style>


<nav id="nav-tool" class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <h4>Zoom online</h4>
        </div>
        <div class="navbar-form navbar-right">
            <h5> Host: <strong>Phòng học/hội họp</strong></h5>
        </div>
    </div>
</nav>
<script src="https://source.zoom.us/2.4.0/lib/vendor/react.min.js"></script>
<script src="https://source.zoom.us/2.4.0/lib/vendor/react-dom.min.js"></script>
<script src="https://source.zoom.us/2.4.0/lib/vendor/redux.min.js"></script>
<script src="https://source.zoom.us/2.4.0/lib/vendor/redux-thunk.min.js"></script>
<script src="https://source.zoom.us/2.4.0/lib/vendor/lodash.min.js"></script>
<script src="https://source.zoom.us/zoom-meeting-2.4.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script type="text/javascript">
    document.onkeydown = function(e) {
        if(event.keyCode == 123) {
            return false;
        }
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)) {
            return false;
        }
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)) {
            return false;
        }
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)) {
            return false;
        }
        if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)) {
            return false;
        }
    }

    ZoomMtg.preLoadWasm();
    ZoomMtg.prepareJssdk();
    var meetConfig = {
        apiKey: "{{config('app.zoom.key')}}",
        apiSecret: "{{config('app.zoom.secret')}}",
        meetingNumber: "{{$meeting->zoom_id}}",
        userName: "{{$full_name}}",
        passWord: "{{$meeting->password}}",
        leaveUrl: "{{route('module.offline.detail',['id'=>$course_id])}}",
        role: parseInt(0, 10)
    };
    var signature = ZoomMtg.generateSignature({
        meetingNumber: meetConfig.meetingNumber,
        apiKey: meetConfig.apiKey,
        apiSecret: meetConfig.apiSecret,
        role: meetConfig.role,
        success: function(res){
            console.log(res.result);
        }
    });
    ZoomMtg.i18n.load("en-US");
    ZoomMtg.init({
        leaveUrl: meetConfig.leaveUrl,
        isSupportAV: true,
        success: function () {
            ZoomMtg.i18n.load("en-US");
            ZoomMtg.join({
                meetingNumber: meetConfig.meetingNumber,
                userName: meetConfig.userName,
                signature: signature,
                apiKey: meetConfig.apiKey,
                passWord: meetConfig.passWord,
                success: function(res){
                    $('#nav-tool').hide();
                    console.log(res);
                },
                error: function(res) {
                    console.log(res);
                }
            });
        },
        error: function(res) {
            console.log(res);
        }
    });
</script>
</body>
