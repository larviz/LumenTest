<?php
	/**
	 * Created by PhpStorm.
	 * User: Krow
	 * Date: 17/04/20
	 * Time: 15:51
	 */

	namespace App;
	class Settings
	{
		protected $image_url = "http://localhost/public/";
		function __construct ()
		{
		}
		function getImageUrl()
		{
			return $this->image_url;
		}
	}