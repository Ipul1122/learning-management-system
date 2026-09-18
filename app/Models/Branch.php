<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'code', 'address', 'city', 'phone', 'is_active'])]
class Branch extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Pemetaan singkatan kota/wilayah untuk auto-generate kode cabang standar.
     */
    public const CITY_CODE_MAP = [
        'jakarta pusat' => 'JKT',
        'jakarta barat' => 'JKB',
        'jakarta selatan' => 'JKS',
        'jakarta timur' => 'JKT-TMR',
        'jakarta utara' => 'JKU',
        'jakarta' => 'JKT',
        'bandung barat' => 'BDG',
        'bandung timur' => 'BDT',
        'bandung selatan' => 'BDS',
        'bandung' => 'BDG',
        'surabaya' => 'SBY',
        'semarang' => 'SMG',
        'medan' => 'MDN',
        'makassar' => 'MKS',
        'yogyakarta' => 'YOG',
        'jogja' => 'YOG',
        'jogjakarta' => 'YOG',
        'denpasar' => 'DPS',
        'bali' => 'DPS',
        'solo' => 'SLO',
        'surakarta' => 'SLO',
        'malang' => 'MLG',
        'palembang' => 'PLM',
        'tangerang selatan' => 'TSL',
        'tangerang' => 'TNG',
        'bekasi' => 'BKS',
        'bogor' => 'BGR',
        'depok' => 'DPK',
        'balikpapan' => 'BPN',
        'samarinda' => 'SMD',
        'pontianak' => 'PTK',
        'banjarmasin' => 'BDJ',
        'manado' => 'MND',
        'batam' => 'BTM',
        'pekanbaru' => 'PKU',
        'padang' => 'PDG',
        'bandar lampung' => 'LPG',
        'lampung' => 'LPG',
        'cirebon' => 'CRB',
        'serang' => 'SRG',
        'cilegon' => 'CLG',
        'sukabumi' => 'SKB',
        'tasikmalaya' => 'TSM',
        'purwokerto' => 'PWT',
        'tegal' => 'TGL',
        'magelang' => 'MGL',
        'kediri' => 'KDR',
        'jember' => 'JBR',
        'madiun' => 'MDN',
        'banyuwangi' => 'BWI',
        'mataram' => 'MTR',
        'kupang' => 'KPG',
        'ambon' => 'AMB',
        'jayapura' => 'JPR',
        'kendari' => 'KDI',
        'palu' => 'PLU',
        'gorontalo' => 'GTO',
        'bengkulu' => 'BKL',
        'jambi' => 'JMB',
        'pangkal pinang' => 'PGP',
        'banda aceh' => 'BNA',
        'aceh' => 'ACH',
    ];

    /**
     * Generate unique branch code based on branch name.
     */
    public static function generateCode(string $name, ?int $ignoreId = null): string
    {
        $clean = strtolower(trim($name));
        $clean = preg_replace('/^(kantor\s+cabang|kantor\s+operasional|kantor|cabang|branch|kcp|kc|pusat\s+pelatihan)\s+/i', '', $clean);
        $clean = trim($clean);

        $baseCode = null;

        // 1. Cek kecocokan kamus kota
        foreach (self::CITY_CODE_MAP as $key => $val) {
            if ($clean === $key || str_starts_with($clean, $key . ' ')) {
                $baseCode = 'CBG-' . $val;
                break;
            }
        }

        // 2. Jika tidak ada di kamus, buat dari kata yang ada
        if (! $baseCode) {
            $words = array_values(array_filter(preg_split('/[^a-z0-9]+/i', $clean)));
            if (empty($words)) {
                $baseCode = 'CBG-NEW';
            } elseif (count($words) === 1) {
                $word = strtoupper($words[0]);
                $baseCode = substr('CBG-' . $word, 0, 20);
            } else {
                $combined = 'CBG-' . strtoupper(implode('-', $words));
                if (strlen($combined) <= 20) {
                    $baseCode = $combined;
                } else {
                    $initials = '';
                    foreach ($words as $w) {
                        $initials .= strtoupper($w[0]);
                    }
                    $baseCode = substr('CBG-' . $initials, 0, 20);
                }
            }
        }

        // 3. Pastikan kode unik di tabel branches
        $code = $baseCode;
        $counter = 2;

        while (static::where('code', $code)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $suffix = '-' . str_pad((string) $counter, 2, '0', STR_PAD_LEFT);
            $maxBaseLen = 20 - strlen($suffix);
            $code = substr($baseCode, 0, $maxBaseLen) . $suffix;
            $counter++;
        }

        return $code;
    }

    /**
     * Seluruh pengguna yang terafiliasi dengan cabang ini.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Log aktivitas pada cabang ini.
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Seluruh kelas pelatihan yang diselenggarakan cabang ini.
     */
    public function classes(): HasMany
    {
        return $this->hasMany(TrainingClass::class);
    }

    /**
     * Bank soal yang dimiliki cabang ini.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Seluruh berita atau pengumuman cabang ini.
     */
    public function newsPosts(): HasMany
    {
        return $this->hasMany(NewsPost::class);
    }
}
