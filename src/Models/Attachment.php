<?php

namespace Chang\Erp\Models;


use Chang\Erp\Traits\MoneyFormatableTrait;

class Attachment extends Model
{

    use MoneyFormatableTrait;

    protected $priceField = 'amount';

    protected $fillable = [
        'attachment_type_id',
        'amount',
    ];

    public function type()
    {
        return $this->belongsTo(AttachmentType::class);
    }

    public function attachmentable()
    {
        return $this->morphTo();
    }
}
