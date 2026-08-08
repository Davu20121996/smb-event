<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LandingLead extends Model
{
    public $table = 'landing_leads';

    protected $fillable = [
        'landing_page_id',
        'name',
        'email',
        'phone',
        'crm_tag',
        'is_synced',
        'document_url',
        'source_url',
    ];

    protected $casts = [
        'is_synced' => 'boolean',
    ];

    public function landingPage()
    {
        return $this->belongsTo(LandingPage::class, 'landing_page_id');
    }
}
