<?php

	namespace App;

	use Illuminate\Database\Eloquent\Model;
	use Illuminate\Database\Eloquent\SoftDeletes;

	class Usuario extends Model
	{
		use SoftDeletes;

		protected $table = 'usuarios';
		/**
		 * The attributes that are mass assignable.
		 *
		 * @var array
		 */
		protected $fillable = [
			'nombre', 'ife', 'usuario', 'foto_user',
		];
		/**
		 * The attributes excluded from the model's JSON form.
		 *
		 * @var array
		 */
		protected $hidden = [];
	}
