<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Customers Report</title>

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
        }

        .print-btn {
            background: #111;
            color: #fff;
        }

        .back-btn {
            background: #eee;
            color: #222;
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
            margin: 5px 0;
            color: #666;
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
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        th {
            background: #f5f5f5;
            text-align: left;
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

        <h1>Customers Report</h1>

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

            <span>
                Total Customers
            </span>

            <strong>
                {{ number_format($reports['summary']['totalCustomers']) }}
            </strong>

        </div>


        <div class="summary-card">

            <span>
                New Customers
            </span>

            <strong>
                {{ number_format($reports['summary']['newCustomers']) }}
            </strong>

        </div>


        <div class="summary-card">

            <span>
                Total Orders
            </span>

            <strong>
                {{ number_format($reports['summary']['totalOrders']) }}
            </strong>

        </div>


        <div class="summary-card">

            <span>
                Total Revenue
            </span>

            <strong>
                ZMW
                {{ number_format($reports['summary']['totalRevenue'], 2) }}
            </strong>

        </div>

    </div>


    {{-- TOP CUSTOMERS --}}

    <div class="section">

        <h2>
            Top Customers
        </h2>


        <table>

            <thead>

                <tr>

                    <th>
                        #
                    </th>

                    <th>
                        Customer
                    </th>

                    <th>
                        Email
                    </th>

                    <th>
                        Orders
                    </th>

                    <th>
                        Revenue
                    </th>

                    <th>
                        Average Order
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($reports['topCustomers'] as $customer)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $customer['name'] }}
                        </td>

                        <td>
                            {{ $customer['email'] }}
                        </td>

                        <td>
                            {{ $customer['orders'] }}
                        </td>

                        <td class="text-right">

                            ZMW
                            {{ number_format($customer['revenue'], 2) }}

                        </td>

                        <td class="text-right">

                            ZMW
                            {{ number_format($customer['average_order'], 2) }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6">

                            No customer data available.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- CUSTOMER GROWTH --}}

    <div class="section">

        <h2>
            Customer Growth
        </h2>


        <table>

            <thead>

                <tr>

                    <th>
                        Month
                    </th>

                    <th>
                        Customers
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($reports['customerGrowth'] as $row)

                    <tr>

                        <td>
                            {{ $row->label }}
                        </td>

                        <td>
                            {{ $row->customers }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="2">
                            No customer growth data available.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    <div class="footer">

        Customers Report generated by Fashion Styles.

    </div>


</div>

</body>

</html>