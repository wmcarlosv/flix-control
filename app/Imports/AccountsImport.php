<?php

namespace App\Imports;

use App\Models\Account;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Service;
use App\Models\User;

class AccountsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $result = $this->validateColumns($row);
        if($result['success']){
            $account = new Account();
            $account->service_id = $result['service_id'];
            $account->user_id = $result['user_id'];
            $account->email = $row[1];
            $account->password = $row[3];
            $account->passwordemail = $row[2];
            $account->dateto = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[6])->format('Y-m-d');

            if(!empty($row[9])){
                if(strtolower($row[9]) == "no"){
                    $account->is_store = 0;
                }else if (strtolower($row[9]) == "si"){
                    $account->is_store = 1;
                }else{
                    $account->is_store = 0;
                }
            }else{
                $account->is_store = 0;
            }

            if(!empty($row[4])){
                $account->sale_price = $row[4];
            }else{
                $account->sale_price = 0;
            }

            if(!empty($row[5])){
                $account->profile_price = $row[5];
            }else{
                $account->profile_price = 0;
            }
            $account->reseller_due_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[8])->format('Y-m-d');
            $account->save();
        }
    }

    public function validateColumns($row){
        $result = [];
        $result['success'] = true;



        if(strtolower($row[0]) == "servicio"){
            $result['success'] = false;
        }

        $service = Service::where('name', $row[0])->first();
        if(empty($service->id)){
            $result['success'] = false;
        }else{
            $result['service_id'] = $service->id;
        }

        $user = User::where('email', $row[7])->first();

        if(empty($user->id)){
            $result['success'] = false;
        }else{
            $result['user_id'] = $user->id;
        }

        if($result['success']){
            $account = Account::where('service_id',$result['service_id'])->where('email', $row[1])->get();
            if($account->count() > 0 ){
                $result['success'] = false;
            }
        }

        return $result;
    }
}
