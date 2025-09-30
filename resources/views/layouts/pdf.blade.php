<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <style>
        /* Use the font keys you registered in the Mpdf instantiation */
       html, body {
    font-family: "dankmono" !important;
    font-size: 11pt;
    color: #222;
    margin: 18mm 12mm;
}

                .bangla {
            font-family: "test", "solaimanlipi", "bangla" !important;
                line-height: 1.6;
}

        h1, h2, h3 {
            margin: 0 0 6px 0;
            font-weight: 700;
        }

        /* Header */
        .report-header {
            text-align: center;
            margin-bottom: 12px;
        }

        /* Summary card */
        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 10.5pt;
        }
        .summary th, .summary td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .summary th {
            background: #f4f4f4;
            text-align: left;
        }

        /* Tables */
        table.report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 10pt;
        }
        table.report-table th, table.report-table td {
            padding: 6px 8px;
            border: 1px solid #e1e1e1;
        }
        table.report-table th {
            background: #fafafa;
            text-align: left;
        }

        /* small print */
        .muted { color: #666; font-size: 9.5pt; }

        /* utility */
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* prevent page break inside rows for small tables */
        tr, td, th { page-break-inside: avoid; }
    </style>

    @stack('style')
</head>
<body>
    @yield('content')
</body>
</html>