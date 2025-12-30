    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::create('soit_transmis', function (Blueprint $table) {
                $table->id();
                $table->string('numero');
                $table->text('description')->nullable();
                $table->enum('status', ['En cours', 'Termine', 'Imprime'])->default('En cours');
                $table->unsignedBigInteger('users_id')->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('users_id')->references('id')->on('users');
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::dropIfExists('soit_transmis');
        }
    };
