<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laporan Penjualan Cafelora</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 14mm;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            color: #0f172a;
            background: #ffffff;
        }
        .container {
            width: 100%;
            max-width: 980px;
            margin: 0 auto;
            padding: 8px 0 12px;
        }
        .title {
            text-align: center;
            font-size: 16px;
            font-weight: 700;
            text-decoration: underline;
            margin-bottom: 8px;
        }
        .period {
            text-align: right;
            font-size: 10px;
            margin-bottom: 8px;
        }
        .table-wrap {
            border: 1px solid #cbd5e1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            border: none;
        }
        .header-table,
        .header-table tbody,
        .header-table tr,
        .header-table td {
            border: 0;
        }
        thead th {
            background: #1f2937;
            color: #ffffff;
            font-size: 10px;
            font-weight: 600;
            padding: 6px 6px;
            border: 1px solid #334155;
            text-align: left;
        }
        tbody td {
            font-size: 10px;
            padding: 6px 6px;
            border: 1px solid #cbd5e1;
            vertical-align: top;
        }
        tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        tfoot td {
            background: #1f2937;
            color: #ffffff;
            font-weight: 600;
            padding: 8px 6px;
            border: 1px solid #334155;
            font-size: 10px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .nowrap { white-space: nowrap; }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 999px;
            border: 1px solid;
            font-size: 9px;
            font-weight: 600;
        }
        .badge-completed { background: #d1fae5; color: #047857; border-color: #a7f3d0; }
        .badge-paid { background: #dbeafe; color: #1d4ed8; border-color: #bfdbfe; }
        .badge-pending { background: #fef3c7; color: #b45309; border-color: #fde68a; }
        .badge-cancelled { background: #f1f5f9; color: #334155; border-color: #e2e8f0; }
        .badge-qris { background: #e0e7ff; color: #4338ca; border-color: #c7d2fe; }
        .badge-cash { background: #f1f5f9; color: #334155; border-color: #e2e8f0; }
        .badge-debit { background: #cffafe; color: #0e7490; border-color: #a5f3fc; }
    </style>
</head>
<body>
@php
    $rp = fn($n) => 'Rp' . number_format((float) $n, 0, ',', '.');

    $statusBadge = function ($status) {
        $status = strtolower((string) $status);
        if ($status === 'completed') return 'badge-completed';
        if ($status === 'paid') return 'badge-paid';
        if ($status === 'pending') return 'badge-pending';
        return 'badge-cancelled';
    };

    $methodBadge = function ($method) {
        $method = strtolower((string) $method);
        if ($method === 'qris') return 'badge-qris';
        if ($method === 'cash') return 'badge-cash';
        if ($method === 'debit') return 'badge-debit';
        return 'badge-cash';
    };
@endphp

<div class="container">
    <table class="header-table" style="width: 100%; border-collapse: collapse; border-spacing: 0; margin: 0 auto 6px;">
        <tr>
            <td colspan="6" style="text-align: center; font-size: 16px; font-weight: 700; text-decoration: underline; padding: 6px 0;">
                Laporan Penjualan Cafelora
            </td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: center; font-size: 10px; padding: 2px 0 10px;">
                Periode:&nbsp;<strong>{{ $startDate }}</strong>&nbsp;sampai dengan&nbsp;<strong>{{ $endDate }}</strong>
            </td>
        </tr>
    </table>

    <div class="table-wrap" style="margin: 0 auto;">
        <table>
            <thead>
                <tr>
                    <th class="nowrap" style="width: 160px;">Tanggal</th>
                    <th>Invoice</th>
                    <th style="width: 160px;">Kasir</th>
                    <th class="text-center" style="width: 110px;">Status</th>
                    <th class="text-center" style="width: 100px;">Metode</th>
                    <th class="text-right" style="width: 120px;">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $row)
                    @php
                        $method = optional($row->payments->first())->payment_method;
                    @endphp
                    <tr>
                        <td class="nowrap">{{ $row->created_at->format('d M Y H:i') }}</td>
                        <td>{{ $row->invoice }}</td>
                        <td>{{ $row->user->name ?? 'N/A' }}</td>
                        <td class="text-center">
                            @if(!empty($isExcel))
                                {{ $row->status }}
                            @else
                                <span class="badge {{ $statusBadge($row->status) }}">{{ $row->status }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if(!empty($isExcel))
                                {{ $method ?: 'n/a' }}
                            @else
                                <span class="badge {{ $methodBadge($method) }}">{{ $method ?: 'n/a' }}</span>
                            @endif
                        </td>
                        <td class="text-right nowrap">
                            @if(!empty($isExcel))
                                {{ (float) $row->total }}
                            @else
                                {{ $rp($row->total) }}
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center" style="padding: 16px;">
                            Belum ada transaksi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5">Total Penjualan</td>
                    <td class="text-right nowrap">
                        @if(!empty($isExcel))
                            {{ (float) $totalSales }}
                        @else
                            {{ $rp($totalSales) }}
                        @endif
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
</body>
</html>