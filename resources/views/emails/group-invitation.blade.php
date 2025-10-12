<!DOCTYPE html>
<html>
<head>
    <title>Group Invitation</title>
    <style>
        .button {
            display: inline-block;
            background: #3490dc;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }

        .text-muted {
            color: #6b7280;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<h1>You've been invited to join {{ $group->name }}!</h1>

<p>{{ $group->description ?? 'Join this group to connect with other members.' }}</p>

<a href="{{ $acceptUrl }}" class="button">
    Accept Invitation
</a>

<p class="text-muted">
    This invitation link will expire in 24 hours.
</p>

<p class="text-muted">
    If you did not expect to receive this invitation, you can safely ignore this email.
</p>

<p style="color: #9ca3af; font-size: 12px; margin-top: 30px;">
    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
</p>
</body>
</html>
