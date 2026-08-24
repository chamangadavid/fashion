<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Revenue Report</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 30px;
            color: #222;
            background: #fff;
        }

        .report-container {
            max-width: 1100px;
            margin: auto;
        }

        .report-header {
            border-bottom: 3px solid #111;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }

        .report-header h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }

        .report-header p {
            margin: 4px 0;
            color: #666;
        }

        .actions {
            margin-bottom: 25px;
        }

        .actions button,
        .actions a {
            display: inline-block;
            padding: 10px 18px;
            margin-right: 8px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .print-btn {
            background: #111;
            color: #fff;
        }

        .back-btn {
            background: #eee;
            color: #222;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .summary-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 18px;
        }

        .summary-card span {
            display: block;
            color: #777;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .summary-card strong {
            font-size: 22px;
        }

        .section {
            margin-top: 30px;
        }

        .section h2 {
            font-size: 20px;
            margin-bottom: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #f5f5f5;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            color: #777;
            font-size: 12px;
        }

        @media print {
            body {
                padding: 0;
            }

            .actions {
                display: none;
            }

            .report-container {
                max-width: none;
            }
        }

        @media (max-width: 800px) {
            .summary {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>

<body>

<div class="report-container">

    <div class="actions">
        <button
            class="print-btn"
            onclick="window.print()"
        >
            Print / Save as PDF
        </button>

        <a
            href="{{ url()->previous() }}"
            class="back-btn"
        >
            Back
        </a>
    </div>

    <div class="report-header">

        <h1>Revenue Report</h1>

        <p>
            Report Period:
            <strong>
                {{ $reports['date_range']['start'] }}
                -
                {{ $reports['date_range']['end'] }}
            </strong>
        </p>

        <p>
            Generated:
            {{ now()->format('d M Y H:i') }}
        </p>

    </div>


    {{-- SUMMARY --}}

    <div class="summary">

        <div class="summary-card">
            <span>Gross Revenue</span>

            <strong>
                ZMW {{ number_format($reports['summary']['gross_revenue'], 2) }}
            </strong>
        </div>

        <div class="summary-card">
            <span>Paid Revenue</span>

            <strong>
                ZMW {{ number_format($reports['summary']['paid_revenue'], 2) }}
            </strong>
        </div>

        <div class="summary-card">
            <span>Pending Revenue</span>

            <strong>
                ZMW {{ number_format($reports['summary']['pending_revenue'], 2) }}
            </strong>
        </div>

        <div class="summary-card">
            <span>Total Orders</span>

            <strong>
                {{ number_format($reports['summary']['total_orders']) }}
            </strong>
        </div>

    </div>


    {{-- REVENUE BY DATE --}}

    <div class="section">

        <h2>Revenue by Date</h2>

        <table>

            <thead>
                <tr>
                    <th>Date</th>
                    <th class="text-right">Revenue</th>
                </tr>
            </thead>

            <tbody>

                @forelse($reports['revenueByDate'] as $row)

                    <tr>

                        <td>
                            {{ $row->date }}
                        </td>

                        <td class="text-right">
                            ZMW {{ number_format($row->revenue, 2) }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="2">
                            No revenue data available.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- PAYMENT METHODS --}}

    <div class="section">

        <h2>Revenue by Payment Method</h2>

        <table>

            <thead>

                <tr>
                    <th>Payment Method</th>
                    <th>Orders</th>
                    <th class="text-right">Revenue</th>
                </tr>

            </thead>

            <tbody>

                @forelse($reports['revenueByPaymentMethod'] as $row)

                    <tr>

                        <td>
                            {{ ucfirst(str_replace('_', ' ', $row->payment_method)) }}
                        </td>

                        <td>
                            {{ $row->orders }}
                        </td>

                        <td class="text-right">
                            ZMW {{ number_format($row->revenue, 2) }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="3">
                            No payment data available.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- PAYMENT STATUS --}}

    <div class="section">

        <h2>Revenue by Payment Status</h2>

        <table>

            <thead>

                <tr>
                    <th>Payment Status</th>
                    <th>Orders</th>
                    <th class="text-right">Revenue</th>
                </tr>

            </thead>

            <tbody>

                @forelse($reports['revenueByPaymentStatus'] as $row)

                    <tr>

                        <td>
                            {{ ucfirst($row->payment_status) }}
                        </td>

                        <td>
                            {{ $row->orders }}
                        </td>

                        <td class="text-right">
                            ZMW {{ number_format($row->revenue, 2) }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="3">
                            No payment status data available.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    <div class="footer">

        Revenue Report generated by Fashion Styles.

    </div>

</div>

</body>
</html>