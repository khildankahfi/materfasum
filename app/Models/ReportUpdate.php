<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'admin_id',
        'status',
        'note',
        'photo_after',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'menunggu'  => 'warning',
            'diproses'  => 'info',
            'selesai'   => 'success',
            'ditolak'   => 'danger',
            default     => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu'  => 'Menunggu',
            'diproses'  => 'Diproses',
            'selesai'   => 'Selesai',
            'ditolak'   => 'Ditolak',
            default     => '-',
        };
    }
}
