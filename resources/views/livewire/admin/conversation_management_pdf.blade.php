<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="">

    <title>Conversation Informations</title>
    <style>
        table, thead, tbody, th, td { border: 1px solid black; padding: 8px; text-align: center; }
    </style>
</head>
<body>
<h1 class="text-center">Conversation Information</h1>

<table style="width: 100%; border-collapse: collapse">
    <thead>
        <tr>
            <th>Metric</th>
            <th>Count</th>
        </tr>
        </thead>

        <tbody>
        <tr>
            <td>Number of Conversations</td>
            <td>{{ $conversation_count }}</td>
        </tr>
        <tr>
            <td>Number of Messages</td>
            <td>{{ $message_count }}</td>
        </tr>
    </tbody>
</table>
</body>
</html>
