<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Platform extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'logo_image_path',
    ];

    // Platform adı kaydedilirken slug otomatik oluşturulsun
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($platform) {
            if (empty($platform->slug)) {
                $platform->slug = Str::slug($platform->name);
            }
        });

        static::updating(function ($platform) {
            if ($platform->isDirty('name') && empty($platform->slug)) { // Sadece isim değişirse ve slug boşsa slug'ı güncelle
                $platform->slug = Str::slug($platform->name);
            } elseif ($platform->isDirty('name') && !empty($platform->slug) && $platform->getOriginal('slug') === Str::slug($platform->getOriginal('name'))) {
                // Eğer isim değiştiyse VE slug boş değilse VE eski slug eski isme göre oluşturulmuşsa, yeni slug'ı da güncelle
                // Bu, kullanıcının özel bir slug girmemiş olduğu durumlar içindir.
                $platform->slug = Str::slug($platform->name);
            }
        });
    }

    /**
     * Bu platforma ait item'lar (diziler/filmler).
     */
    public function items()
    {
        return $this->hasMany(Items::class);
    }
}
