<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Report $report,
        public ?string $note = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $statusLabel = $this->report->status_label;
        $statusColor = match ($this->report->status) {
            'diproses' => '#17a2b8',
            'selesai'  => '#28a745',
            'ditolak'  => '#dc3545',
            default    => '#6c757d',
        };

        $mail = (new MailMessage)
            ->subject("📋 Update Laporan: {$this->report->title}")
            ->greeting("Halo, {$notifiable->name}!")
            ->line("Status laporan Anda telah diperbarui.")
            ->line("**Judul Laporan:** {$this->report->title}")
            ->line("**Lokasi:** {$this->report->location}")
            ->line("**Status Baru:** {$statusLabel}");

        if ($this->note) {
            $mail->line("**Catatan Admin:** {$this->note}");
        }

        if ($this->report->status === 'ditolak' && $this->report->rejection_reason) {
            $mail->line("**Alasan Penolakan:** {$this->report->rejection_reason}");
        }

        return $mail
            ->action('Lihat Detail Laporan', route('user.reports.show', $this->report))
            ->line('Terima kasih telah melaporkan kepada kami melalui **Materfasum**.')
            ->salutation('Salam, Tim Materfasum');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'report_id'    => $this->report->id,
            'title'        => $this->report->title,
            'status'       => $this->report->status,
            'status_label' => $this->report->status_label,
            'note'         => $this->note,
            'message'      => "Laporan \"{$this->report->title}\" telah diperbarui menjadi {$this->report->status_label}.",
        ];
    }
}
