<!DOCTYPE html>
<html>
<head>
    <title>Task Due</title>
</head>
<body>
    <h1>Task Due Reminder</h1>
    <p>Dear {{ $todo->user->name }},</p>
    <p>This is a reminder that your task "{{ $todo->content }}" is due on {{ $todo->due_date }}.</p>
    <p>Please take necessary actions.</p>
</body>
</html>
