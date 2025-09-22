<!DOCTYPE html>
<html>
<head>
    <title>Maintenance Report</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            font-size: 12px;
            color: #333;
            padding: 20px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 20px;
            color: #111;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border-radius: 6px;
            overflow: hidden;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background: #f4f4f8;
            font-weight: 600;
            text-align: left;
        }
        tr:nth-child(even) {
            background: #fafafa;
        }
        tr:hover {
            background: #f1f1f1;
        }
        .status {
            font-weight: bold;
            text-transform: capitalize;
        }
        .pending { color: #d97706; }   /* amber */
        .ongoing { color: #2563eb; }   /* blue */
        .done    { color: #059669; }   /* green */
    </style>
</head>
<body>
    <h2>Maintenance Report - {{ \Carbon\Carbon::now()->format('F Y') }}</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Item</th>
                <th>Quantity</th>
                <th>Notes</th>
                <th>Start At</th>
                <th>Finish At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($maintains as $data)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $data->item->name }}</td>
                <td>{{ $data->quantity }}</td>
                <td>{{ $data->notes ?? '-'}}</td>
                <td>{{ $data->start_at }}</td>
                <td>{{ $data->finish_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
