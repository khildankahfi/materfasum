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
        'rating',
        'rating_comment',
        'department_id',
        'target_completion_date',
    ];

    protected $casts = [
        'target_completion_date' => 'datetime',
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

    public function supports()
    {
        return $this->hasMany(Support::class);
    }

    public function comments()
    {
        return $this->hasMany(ReportComment::class)->oldest();
    }

    public function isSupportedBy($userId)
    {
        if (!$userId) return false;
        return $this->supports()->where('user_id', $userId)->exists();
    }

    public function categoryDetails()
    {
        return $this->belongsTo(Category::class, 'category', 'slug');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
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
        if ($this->categoryDetails) {
            return $this->categoryDetails->name;
        }

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

    public function getSlaRemainingDaysAttribute()
    {
        if (!$this->target_completion_date) return null;
        $target = \Carbon\Carbon::parse($this->target_completion_date)->startOfDay();
        $today = \Carbon\Carbon::today();
        
        if ($today->greaterThan($target)) {
            $diff = $today->diffInDays($target);
            return $diff === 0 ? 'Batas waktu hari ini' : "Terlambat {$diff} hari";
        }
        
        $diff = $today->diffInDays($target);
        return $diff === 0 ? 'Batas waktu hari ini' : "{$diff} hari lagi";
    }

    public static function categories(): array
    {
        return Category::pluck('name', 'slug')->toArray();
    }
}