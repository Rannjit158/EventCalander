<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Event Reminder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #3b82f6;
            color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .event-details {
            background-color: white;
            padding: 15px;
            border-left: 4px solid #3b82f6;
            margin: 15px 0;
        }
        .footer {
            text-align: center;
            color: #6c757d;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔔 Event Reminder</h1>
    </div>

    <div class="content">
        <p>Hello!</p>

        <p>This is a reminder for your upcoming event:</p>

        <div class="event-details">
            <h3>{{ $event->title }}</h3>

            @if($event->description)
                <p><strong>Description:</strong> {{ $event->description }}</p>
            @endif

            <p><strong>Date & Time:</strong> {{ $event->event_at->format('l, F j, Y \a\t g:i A') }}</p>

            <p><strong>Time remaining:</strong> {{ $event->event_at->diffForHumans() }}</p>
        </div>

        <p>Don't forget to prepare for your event!</p>
    </div>

    <div class="footer">
        <p>This is an automated reminder from your Event Calendar application.</p>
    </div>
</body>
</html>
