<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">

    <title>Sales Report</title>

    <style>

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h1 {
            margin-bottom: 5px;
        }

        .date {
            color: #777;
            margin-bottom: 20px;
        }

        .summary {
            width: 100%;
            margin-bottom: 25px;
        }

        .summary td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #f5f5f5;
        }

    </style>
</head>

<body>

    <h1>Sales Report</h1>

    <div class="date">
        {{ $startDate->format('d M Y') }}
        -
        {{ $endDate->format('d M Y') }}
    </div>

    <table class="summary">

        <tr>
            <td>
                <strong>Total Orders</strong><br>
                {{ $totalOrders }}
            </td>

            <td>
                <strong>Total Sales</strong><br>
                K{{ number_format($totalSales, 2) }}
            </td>

            <td>
                <strong>Items Sold</strong><br>
                {{ $itemsSold }}
            </td>

            <td>
                <strong>Average Order</strong><br>
                K{{ number_format($averageOrderValue, 2) }}
            </td>
        </tr>

    </table>


    <h2>Orders</h2>

    <table>

        <thead>

            <tr>
                <th>Order</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Total</th>
                <th>Date</th>
            </tr>

        </thead>

        <tbody>

            @foreach($orders as $order)

                <tr>

                    <td>
                        {{ $order->order_number }}
                    </td>

                    <td>
                        {{ $order->shipping_first_name }}
                        {{ $order->shipping_last_name }}
                    </td>

                    <td>
                        {{ ucfirst($order->status) }}
                    </td>

                    <td>
                        K{{ number_format($order->total_amount, 2) }}
                    </td>

                    <td>
                        {{ $order->created_at->format('d M Y') }}
                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</body>
</html>