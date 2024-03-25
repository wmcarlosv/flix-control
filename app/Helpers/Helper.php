<?php

	namespace App\Helpers;

	use DB;

	class Helper{

		public static function getDataSelect($attr){
			if($attr){
				$data = DB::table($attr['table'])->select($attr['columns'])->where($attr['compare'],$attr['compare_value'])->get();
				return $data;
			}else{
				return null;
			}
			
		}

	}