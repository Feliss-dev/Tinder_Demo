<!DOCTYPE html>
<html>
<head>
    <title>System Information PDF</title>
    <style>
        /* Add some basic styling for the PDF */
        body { font-family: Arial, sans-serif; }
        h1 { text-align: center; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; padding: 8px; text-align: center; }
    </style>
</head>
<body>
    <h1>System Information</h1>
    <table>
        <tr>
            <th>Metric</th>
            <th>Count</th>
        </tr>
        <tr>
            <td>Total Users</td>
            <td>{{ $users }}</td>
        </tr>
        <tr>
            <td>Total Matches</td>
            <td>{{ $matches }}</td>
        </tr>
        <tr>
            <td>Total Images</td>
            <td>{{ $images }}</td>
        </tr>
    </table>
    <p style="margin-top: 20px;">This document provides a summary of the system's key metrics.</p>
</body>
</html>
