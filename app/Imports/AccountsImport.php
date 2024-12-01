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
        }
    }

    public function validateColumns($row){
        $result = [];
        $result['success'] = true;



        if($row[0] != "Servicio"){
            $result['success'] = false;
        }

        $service = Service::where('name', $row[1])->first();
        if(empty($service->id)){
            $result['success'] = false;
        }else{
            $result['service_id'] = $service->id;
        }

        $user = User::where('email', $row[8])->first();

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
