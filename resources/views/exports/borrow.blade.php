<!DOCTYPE html>
<html>
<head>
    <title>Borrows Report</title>
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
    <h2>Borrowed Items Report - {{ \Carbon\Carbon::now()->format('F Y') }}</h2>
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Item</th>
                <th>Location</th>
                <th>Quantity</th>
                <th>Borrow Date</th>
                <th>Return Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrows as $borrow)
            <tr>
                <td>{{ $borrow->user->name ?? 'N/A' }}</td>
                <td>{{ $borrow->item->name ?? 'N/A' }}</td>
                <td>{{ $borrow->location->name ?? 'N/A' }}</td>
                <td>{{ $borrow->quantity }}</td>
                <td>{{ $borrow->borrow_date }}</td>
                <td>{{ $borrow->return_date ?? '-' }}</td>
                <td class="status {{ $borrow->status }}">
                    {{ ucfirst($borrow->status) }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
