@extends('layouts.user')

@section('title', 'Beranda')

@section('content')

{{-- Welcome Header --}}
<div class="mb-5">
    <h4 style="font-size:1.5rem;font-weight:700;color:#111827;margin-bottom:.35rem;">
        Selamat Datang Kembali, {{ explode(' ', auth()->user()->name)[0] }}! 👋
    </h4>
    <p style="color:#6b7280;font-size:.9rem;margin:0;">
        Pilih kategori fasilitas umum di bawah ini untuk memulai pengaduan cepat.
    </p>
</div>

<div class="row g-4">

    {{-- Left Column: Category Cards --}}
    <div class="col-12 col-lg-7">

        <h6 style="font-weight:600;color:#374151;margin-bottom:1rem;font-size:.95rem;">Pilih Kategori Fasilitas</h6>

        <div class="row g-3">

            {{-- Jalan & Jembatan --}}
            <div class="col-6">
                <div class="card p-4 h-100" style="transition:border-color .15s,box-shadow .15s;cursor:pointer;"
                     onmouseenter="this.style.borderColor='#93c5fd';this.style.boxShadow='0 2px 8px rgba(37,99,235,.06)'"
                     onmouseleave="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                    <div style="font-size:1.3rem;margin-bottom:.6rem;">🛣️ Jalan & Jembatan</div>
                    <div style="font-size:.8rem;color:#6b7280;margin-bottom:1rem;line-height:1.6;">
                        Aspal ambles<br>lubang jalan
                    </div>
                    <a href="{{ route('user.reports.create', ['category' => 'jalan_jembatan']) }}"
                       style="color:#2563eb;font-size:.83rem;font-weight:500;text-decoration:none;">
                        Laporkan →
                    </a>
                </div>
            </div>

            {{-- Penerangan Jalan --}}
            <div class="col-6">
                <div class="card p-4 h-100" style="transition:border-color .15s,box-shadow .15s;cursor:pointer;"
                     onmouseenter="this.style.borderColor='#93c5fd';this.style.boxShadow='0 2px 8px rgba(37,99,235,.06)'"
                     onmouseleave="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                    <div style="font-size:1.3rem;margin-bottom:.6rem;">💡 Penerangan Jalan (PJU)</div>
                    <div style="font-size:.8rem;color:#6b7280;margin-bottom:1rem;line-height:1.6;">
                        Lampu jalan mati<br>kabel menjuntai
                    </div>
                    <a href="{{ route('user.reports.create', ['category' => 'penerangan_jalan']) }}"
                       style="color:#2563eb;font-size:.83rem;font-weight:500;text-decoration:none;">
                        Laporkan →
                    </a>
                </div>
            </div>

            {{-- Taman & Fasum --}}
            <div class="col-6">
                <div class="card p-4 h-100" style="transition:border-color .15s,box-shadow .15s;cursor:pointer;"
                     onmouseenter="this.style.borderColor='#93c5fd';this.style.boxShadow='0 2px 8px rgba(37,99,235,.06)'"
                     onmouseleave="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                    <div style="font-size:1.3rem;margin-bottom:.6rem;">🌳 Taman & Fasum</div>
                    <div style="font-size:.8rem;color:#6b7280;margin-bottom:1rem;line-height:1.6;">
                        Fasilitas taman rusak<br>pohon rawan tumbang
                    </div>
                    <a href="{{ route('user.reports.create', ['category' => 'taman']) }}"
                       style="color:#2563eb;font-size:.83rem;font-weight:500;text-decoration:none;">
                        Laporkan →
                    </a>
                </div>
            </div>

            {{-- Saluran Air --}}
            <div class="col-6">
                <div class="card p-4 h-100" style="transition:border-color .15s,box-shadow .15s;cursor:pointer;"
                     onmouseenter="this.style.borderColor='#93c5fd';this.style.boxShadow='0 2px 8px rgba(37,99,235,.06)'"
                     onmouseleave="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
                    <div style="font-size:1.3rem;margin-bottom:.6rem;">💧 Saluran Air / Drainase</div>
                    <div style="font-size:.8rem;color:#6b7280;margin-bottom:1rem;line-height:1.6;">
                        Got tersumbat<br>banjir
                    </div>
                    <a href="{{ route('user.reports.create', ['category' => 'drainase']) }}"
                       style="color:#2563eb;font-size:.83rem;font-weight:500;text-decoration:none;">
                        Laporkan →
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- Right Column: Stats + Recent --}}
    <div class="col-12 col-lg-5">

        {{-- Ringkasan Laporan --}}
        <div class="card p-4 mb-3">
            <h6 style="font-weight:600;color:#111827;margin-bottom:1rem;font-size:.95rem;">Ringkasan Laporan Saya</h6>
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <span style="color:#374151;font-size:.875rem;">
                    <strong style="font-size:1rem;">{{ $totalReports }}</strong> Total Aduan
                </span>
                <div class="d-flex flex-column align-items-end gap-1" style="font-size:.82rem;">
                    @if($inProgressReports > 0)
                        <a href="{{ route('user.reports.index', ['status' => 'diproses']) }}"
                           style="color:#2563eb;text-decoration:none;font-weight:500;">
                            {{ $inProgressReports }} Sedang Diproses
                        </a>
                    @endif
                    @if($completedReports > 0)
                        <a href="{{ route('user.reports.index', ['status' => 'selesai']) }}"
                           style="color:#2563eb;text-decoration:none;font-weight:500;">
                            {{ $completedReports }} Selesai Diperbaiki
                        </a>
                    @endif
                    @if($pendingReports > 0)
                        <a href="{{ route('user.reports.index', ['status' => 'menunggu']) }}"
                           style="color:#d97706;text-decoration:none;font-weight:500;">
                            {{ $pendingReports }} Menunggu
                        </a>
                    @endif
                    @if($totalReports === 0)
                        <span style="color:#9ca3af;">Belum ada laporan</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Perbaikan Terbaru --}}
        <div class="card p-4">
            <h6 style="font-weight:600;color:#111827;margin-bottom:1rem;font-size:.95rem;">Perbaikan Terbaru di Sekitarmu</h6>

            @if($recentActivity->isEmpty())
                <div style="text-align:center;padding:1.5rem 0;color:#9ca3af;font-size:.85rem;">
                    <i class="bi bi-inbox d-block mb-2" style="font-size:1.5rem;"></i>
                    Belum ada aktivitas terbaru
                </div>
            @else
                <div style="display:flex;flex-direction:column;gap:1rem;">
                    @foreach($recentActivity as $report)
                        <a href="{{ route('user.reports.show', $report) }}" style="text-decoration:none;color:inherit;">
                            <div style="display:flex;flex-direction:column;gap:.3rem;padding:.5rem;border-radius:8px;transition:background .15s;"
                                 onmouseenter="this.style.background='#f9fafb'"
                                 onmouseleave="this.style.background='transparent'">
                                <div style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;">
                                    {{-- Status badge --}}
                                    @if($report->status === 'selesai')
                                        <span style="display:inline-flex;align-items:center;gap:4px;padding:.2em .6em;border-radius:4px;background:#d1fae5;color:#059669;font-size:.72rem;font-weight:600;">
                                            ✓ SELESAI
                                        </span>
                                    @elseif($report->status === 'diproses')
                                        <span style="display:inline-flex;align-items:center;gap:4px;padding:.2em .6em;border-radius:4px;background:#dbeafe;color:#2563eb;font-size:.72rem;font-weight:600;">
                                            ⟳ PROSES
                                        </span>
                                    @elseif($report->status === 'menunggu')
                                        <span style="display:inline-flex;align-items:center;gap:4px;padding:.2em .6em;border-radius:4px;background:#fef3c7;color:#d97706;font-size:.72rem;font-weight:600;">
                                            ◷ MENUNGGU
                                        </span>
                                    @else
                                        <span style="display:inline-flex;align-items:center;gap:4px;padding:.2em .6em;border-radius:4px;background:#fee2e2;color:#dc2626;font-size:.72rem;font-weight:600;">
                                            ✕ DITOLAK
                                        </span>
                                    @endif
                                    <span style="color:#9ca3af;font-size:.78rem;">{{ $report->updated_at->diffForHumans() }}</span>
                                </div>
                                <div style="font-size:.85rem;color:#374151;font-weight:500;">
                                    {{ $report->title }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                @if($totalReports > 3)
                    <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid #f3f4f6;">
                        <a href="{{ route('user.reports.index') }}"
                           style="color:#2563eb;font-size:.83rem;font-weight:500;text-decoration:none;">
                            Lihat semua laporan →
                        </a>
                    </div>
                @endif
            @endif
        </div>

    </div>
</div>

@endsection
