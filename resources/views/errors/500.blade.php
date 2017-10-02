<!DOCTYPE html>
<html>
<head>
    <title>Oops! Something went wrong.</title>

    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            color: #B0BEC5;
            display: table;
            font-weight: 100;
            font-family: 'Lato';
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 42px;
            text-align: center;
        }
        .button {
            background-color: #3693ef;
            padding: 10px 15px;
            border-radius: 4px;
            font-size: 18px;
            color: #FFF;
            text-decoration: none;
            margin-top: 25px;
            font-weight:300;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="title">Something went wrong but we're on it.</div>
        <div class="title"><a href="/home" class="button">Go back to your to app</a></div>
        @if(app()->bound('sentry') && !empty(Sentry::getLastEventID()))
            <div class="subtitle">Error ID: {{ Sentry::getLastEventID() }}</div>

            <!-- Sentry JS SDK 2.1.+ required -->
            <script src="https://cdn.ravenjs.com/3.3.0/raven.min.js"></script>

            <script>
                Raven.showReportDialog({
                    eventId: '{{ Sentry::getLastEventID() }}',
                    // use the public DSN (dont include your secret!)
                    dsn: 'https://e9ebbd88548a441288393c457ec90441@sentry.io/3235',
                    user: {
                        'name': 'Jane Doe',
                        'email': 'jane.doe@example.com',
                    }
                });
            </script>
        @endif
    </div>
</div>
</body>
</html>
