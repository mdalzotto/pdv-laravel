<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;

    protected $fillable = ['cliente_id', 'data_venda','total'];

    protected $casts = [
        'data_venda' => 'datetime',
    ];

    public function getFormattedDataVendaAttribute()
    {
        return $this->data_venda ? $this->data_venda->format('d/m/Y H:i:s') : null;
    }
    public function itens()
    {
        return $this->hasMany(ItemVenda::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

}
