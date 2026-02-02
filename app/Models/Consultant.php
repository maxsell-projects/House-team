<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consultant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'email', 
        'phone', 
        'role', 
        'photo', 
        'bio', 
        'is_active',
        'order',
        // NOVOS CAMPOS LP
        'domain',
        'lp_slug',
        'lp_settings',
        'has_lp',
        // Redes Sociais
        'facebook',
        'instagram',
        'linkedin',
        'tiktok',
        'whatsapp',
        'crm_user_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'has_lp' => 'boolean',
        'lp_settings' => 'array',
    ];

    /**
     * Acessor inteligente para a URL da imagem.
     * Resolve o problema de misturar imagens do Seeder (img/team) com Uploads (storage).
     * CORREÇÃO: Adicionado suporte a Case Insensitive para servidores Linux.
     */
    public function getImageUrlAttribute()
    {
        // 1. Sem foto definida -> Retorna avatar padrão
        if (!$this->photo) {
            return asset('img/default-avatar.png');
        }

        // 2. Se já for uma URL completa (ex: link externo) -> Retorna a própria URL
        if (filter_var($this->photo, FILTER_VALIDATE_URL)) {
            return $this->photo;
        }

        // 3. Lógica para Imagens do Seeder (Legado)
        // Se o nome do arquivo NÃO tiver barras (ex: "Hugo.png"), assumimos que é uma imagem estática
        if (!str_contains($this->photo, '/')) {
            $path = 'img/team/' . $this->photo;
            
            // Check 1: Tenta encontrar o arquivo com o nome EXATO
            if (file_exists(public_path($path))) {
                return asset($path);
            }
            
            // Check 2: Tenta encontrar a versão em minúsculas (Linux Case Sensitive Fix)
            // Ex: Banco tem "Hugo.png", arquivo é "hugo.png"
            $lowerPath = 'img/team/' . strtolower($this->photo);
            if (file_exists(public_path($lowerPath))) {
                return asset($lowerPath);
            }
        }

        // 4. Lógica Padrão (Uploads)
        // Assume que é um caminho do Storage (ex: "consultants/hash.jpg")
        return asset('storage/' . $this->photo);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }
}