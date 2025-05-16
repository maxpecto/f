<?php

use Illuminate\Database\Migrations\Migration;
// Blueprint ve Schema fasadları doğrudan kullanılmayacak ama silinmeyebilir.
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // DB fasadını ekledik

class AlterPersonsTableMakeTmdbIdAndGenderNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // persons tablosundaki tmdb_id ve gender sütunlarının mevcut tiplerini bilmeniz önemlidir.
        // Aşağıdaki sorgular VARCHAR(255) olduğunu varsayar. Farklıysa (örn: INT, TEXT), güncelleyin.

        // tmdb_id sütununu nullable yapma
        // Eğer INT ise: DB::statement('ALTER TABLE persons MODIFY COLUMN tmdb_id INT NULL');
        // Eğer VARCHAR ise:
        DB::statement('ALTER TABLE persons MODIFY COLUMN tmdb_id VARCHAR(255) NULL');

        // gender sütununu nullable yapma
        // Eğer INT ise: DB::statement('ALTER TABLE persons MODIFY COLUMN gender INT NULL');
        // Eğer VARCHAR ise:
        DB::statement('ALTER TABLE persons MODIFY COLUMN gender VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Bu kısım, değişikliği geri almak içindir.
        // Alanların orijinal `NOT NULL` haline ve tiplerine döndürülmesi gerekir.
        // Orijinal tiplerini VARCHAR(255) NOT NULL olarak varsayıyorum.

        DB::statement('ALTER TABLE persons MODIFY COLUMN tmdb_id VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE persons MODIFY COLUMN gender VARCHAR(255) NOT NULL');
    }
}
