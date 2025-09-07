<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Course Notification</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        img { max-width: 100%; height: auto; }
        a { color: #007bff; text-decoration: none; }
        .footer { margin-top: 20px; font-size: 12px; color: #666; }
        .debug { background: #f0f0f0; padding: 10px; margin: 10px 0; font-size: 12px; border: 1px solid #ccc; }
    </style>
</head>
<body>
    <div class="container">
        <h1>New Course Arrived!</h1>
        <p>We're excited to announce a new course has been added to our platform.</p>

        <h2>{{ $title ?? 'New Course Available' }}</h2>
        
        @if(isset($image) && $image)
            <img src="{{ asset($image) }}" alt="{{ $title ?? 'Course Image' }}">
        @endif

        @if(isset($published_at))
            <p><strong>Published At:</strong> {{ \Carbon\Carbon::parse($published_at)->format('F j, Y') }}</p>
        @endif

        @if(isset($what_is_this_course))
            <div><strong>What is this course?</strong></div>
            <p>{!! $what_is_this_course !!}</p>
        @endif

        @if(isset($why_is_this_course))
            <div><strong>Why take this course?</strong></div>
            <p>{!! $why_is_this_course !!}</p>
        @endif

        @if(isset($slug))
            <p style="margin-top: 20px;">
                <a href="{{ url('/course/' . $slug) }}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block;">View Course Details</a>
            </p>
        @endif

        <div class="footer">
            <p>Thank you for subscribing to {{ config("mail.from.name") }} updates.</p>
            <p>If you have any questions, contact us at <a href="mailto:{{ config('mail.from.address') }}">{{ config('mail.from.address') }}</a></p>
        </div>
    </div>
</body>
</html>