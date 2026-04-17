<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location',
        'latitude',
        'longitude',
        'category',
        'photo',
        'status',
        'rejection_reason',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function updates()
    {
        return $this->hasMany(ReportUpdate::class)->latest();
    }

    public function latestUpdate()
    {
        return $this->hasOne(ReportUpdate::class)->latest();
    }

    /** Foto-foto laporan (multiple) */
    public function photos()
    {
        return $this->hasMany(ReportPhoto::class)->orderBy('order');
    }

    /** Semua URL foto: prioritaskan photos table, fallback ke kolom photo lama */
    public function getAllPhotosAttribute(): array
    {
        if ($this->photos->isNotEmpty()) {
            return $this->photos->map(fn($p) => \Storage::url($p->path))->toArray();
        }

        if ($this->photo) {
            return [\Storage::url($this->photo)];
        }

        return [];
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
            default     => 'Tidak Diketahui',
        };
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'jalan'          => 'Jalan',
            'jembatan'       => 'Jembatan',
            'lampu'          => 'Lampu Jalan',
            'taman'          => 'Taman',
            'drainase'       => 'Drainase',
            'fasilitas_umum' => 'Fasilitas Umum',
            default          => 'Lainnya',
        };
    }

    public static function categories(): array
    {
        return [
            'jalan'          => 'Jalan',
            'jembatan'       => 'Jembatan',
            'lampu'          => 'Lampu Jalan',
            'taman'          => 'Taman',
            'drainase'       => 'Drainase',
            'fasilitas_umum' => 'Fasilitas Umum',
            'lainnya'        => 'Lainnya',
        ];
    }
}