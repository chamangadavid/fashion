<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Products Report</title>

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
            grid-template-columns: repeat(2, 1fr);
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

        <h1>
            Products Report
        </h1>

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
                Items Sold
            </span>

            <strong>
                {{ number_format($reports['summary']['items_sold']) }}
            </strong>

        </div>


        <div class="summary-card">

            <span>
                Products Sold
            </span>

            <strong>
                {{ number_format($reports['summary']['products_count']) }}
            </strong>

        </div>

    </div>


    {{-- PRODUCT PERFORMANCE --}}

    <div class="section">

        <h2>
            Product Performance
        </h2>


        <table>

            <thead>

                <tr>

                    <th>
                        #
                    </th>

                    <th>
                        Product
                    </th>

                    <th>
                        Quantity Sold
                    </th>

                    <th>
                        Orders
                    </th>

                    <th class="text-right">
                        Revenue
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($reports['products'] as $product)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $product->product_name }}
                        </td>

                        <td>
                            {{ number_format($product->quantity_sold) }}
                        </td>

                        <td>
                            {{ number_format($product->orders_count) }}
                        </td>

                        <td class="text-right">

                            ZMW

                            {{ number_format($product->revenue, 2) }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5">

                            No product data available.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    {{-- TOP PRODUCTS --}}

    <div class="section">

        <h2>
            Top 10 Products
        </h2>


        <table>

            <thead>

                <tr>

                    <th>
                        #
                    </th>

                    <th>
                        Product
                    </th>

                    <th>
                        Quantity Sold
                    </th>

                    <th class="text-right">
                        Revenue
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($reports['top_products'] as $product)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $product->product_name }}
                        </td>

                        <td>
                            {{ number_format($product->quantity_sold) }}
                        </td>

                        <td class="text-right">

                            ZMW

                            {{ number_format($product->revenue, 2) }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="4">
                            No top products available.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>


    <div class="footer">

        Products Report generated by Fashion Styles.

    </div>


</div>

</body>

</html>