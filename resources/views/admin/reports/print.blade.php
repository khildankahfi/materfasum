<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Materfasum - {{ now()->format('d M Y') }}</title>
    <style>
        * { font-family: Arial, sans-serif; font-size: 12px; margin: 0; padding: 0; }
        body { padding: 20px; color: #1e293b; }
        .header { text-align: center; border-bottom: 2px solid #2563eb; padding-bottom: 12px; margin-bottom: 16px; }
        .header h2 { font-size: 18px; color: #2563eb; margin-bottom: 4px; }
        .header p { color: #64748b; font-size: 11px; }
        .meta { display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 11px; color: #64748b; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        thead { background: #2563eb; color: white; }
        thead th { padding: 8px 10px; text-align: left; font-size: 11px; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 10px; font-weight: bold; }
        .badge-menunggu { background: #fef3c7; color: #92400e; }
        .badge-diproses { background: #dbeafe; color: #1e40af; }
        .badge-selesai  { background: #d1fae5; color: #065f46; }
        .badge-ditolak  { background: #fee2e2; color: #991b1b; }
        .footer { text-align: center; color: #94a3b8; font-size: 10px; border-top: 1px solid #e2e8f0; padding-top: 10px; }
        .print-btn {
            position: fixed; bottom: 24px; right: 24px;
            background: #2563eb; color: white; border: none;
            padding: 12px 20px; border-radius: 10px; cursor: pointer;
            font-size: 14px; font-weight: bold; box-shadow: 0 4px 15px rgba(37,99,235,.4);
        }
        .no-print { }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Materfasum — Laporan Kerusakan Fasilitas</h2>
        <p>Dicetak pada {{ now()->format('d F Y, H:i') }} WIB &nbsp;·&nbsp; Total {{ $reports->count() }} laporan</p>
    </div>

    <div class="meta">
        <span>
            @if(request('status')) Status: <strong>{{ ucfirst(request('status')) }}</strong> @endif
            @if(request('category')) &nbsp;|&nbsp; Kategori: <strong>{{ $categories[request('category')] ?? '-' }}</strong> @endif
            @if(!request('status') && !request('category')) Semua laporan @endif
        </span>
        <span>Halaman 1</span>
    </div>

    <table>
        <thead>
            <tr>
                <th width="30">#</th>
                <th>Judul Laporan</th>
                <th width="90">Kategori</th>
                <th>Lokasi</th>
                <th>Pelapor</th>
                <th width="70">Status</th>
                <th width="80">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $i => $report)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $report->title }}</td>
                    <td>{{ $report->category_label }}</td>
                    <td>{{ $report->location }}</td>
                    <td>
                        {{ $report->user->name ?? '-' }}<br>
                        <span style="color:#94a3b8;font-size:10px;">{{ $report->user->email ?? '' }}</span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $report->status }}">{{ $report->status_label }}</span>
                    </td>
                    <td>{{ $report->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center;color:#94a3b8;padding:20px;">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Materfasum — Sistem Pelaporan Fasilitas Umum &copy; {{ date('Y') }}
    </div>

    <button class="print-btn no-print" onclick="window.print()">
        🖨️ Cetak / Simpan PDF
    </button>

</body>
</html>