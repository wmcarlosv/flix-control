<?php

	namespace App\Helpers;

	use DB;
	use App\Models\User;
	use App\Models\Setting;

	class Helper{

		public static function getDataSelect($attr){
			if($attr){
				$data = DB::table($attr['table'])->select($attr['columns'])->where($attr['compare'],$attr['compare_value'])->get();
				return $data;
			}else{
				return null;
			}
			
		}

		public static function addCredits(User $user, $credits){
			$currentCredits = $user->total_credits;
			$newCredits = floatval(($currentCredits + floatval($credits)));
			$user->total_credits = $newCredits;
			if($user->save()){
				return true;
			}else{
				return false;
			}
		}

		public static function removeCredits(User $user, $credits){
			$salida = -1;
			$currentCredits = $user->total_credits;

			if(floatval($currentCredits) < floatval($credits)){
				$salida = 2;
			}else{
				$newCredits = floatval(($currentCredits - floatval($credits)));
				$user->total_credits = $newCredits;
				if($user->save()){
					$salida = 1;
				}else{
					$salida = 0;
				}
			}
			
			return $salida;
		}

		public static function currentSymbol(){
			$data = Setting::first();
			$symbol = "$";
			if(!empty($data->currency)){
				$response = json_decode($data->currency, true);
				$symbol = $response['symbol'];
			}

			return $symbol;
		}

	}