<?php

	use Illuminate\Database\Migrations\Migration;
	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;

	class CreateUsersTable extends Migration
	{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up ()
		{
			Schema::create('usuarios', function (Blueprint $table) {
				$table->unsignedBigInteger('id')->autoIncrement()->index();
				$table->string('nombre');
				$table->string('ife')->unique();
				$table->string('correo')->unique();
				$table->string('password');
				$table->text('comentarios');
				$table->string('foto_user');
				$table->softDeletes();
				$table->timestamps();
			});
		}

		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down ()
		{
			Schema::dropIfExists('usuarios');
		}
	}
