<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    protected $table = 'invoice_items';
    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'servis_id',
        'nama_item',
        'qty',
        'harga_satuan',
        'subtotal',
        'catatan',
        'created_by',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal'     => 'decimal:2',
        'created_at'   => 'datetime',
    ];

    /**
     * Convert datetime to application timezone
     */
    protected function asDateTime($value)
    {
        $dateTime = parent::asDateTime($value);
        if ($dateTime) {
            return $dateTime->setTimezone(config('app.timezone'));
        }
        return $dateTime;
    }

    /**
     * Automatically compute subtotal before saving
     */
    protected static function booted(): void
    {
        static::saving(function (InvoiceItem $item) {
            $item->subtotal = $item->qty * $item->harga_satuan;
        });
    }

    public function servis(): BelongsTo
    {
        return $this->belongsTo(Servis::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
