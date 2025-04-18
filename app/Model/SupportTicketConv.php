<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportTicketConv extends Model
{
    protected $casts = [
        'support_ticket_id' => 'integer',
        'admin_id'          => 'integer',
        'position'          => 'integer',

        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    public function adminInfo(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id','id');
    }
}
