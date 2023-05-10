<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Device;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class InventoryController extends Controller
{
    /**
     * método que recoje todos los dispositivos y
     * los datos de los dispositivos filtrando por el company_id
     *
     * @return void
     */
    public function index(){
        if(auth()->user()->type == 'client'){
            $data = DB::table('devices')
            ->select('id', 'serial_number', 'state', 'model', 'brand', 'description', 'mac_ethernet', 'mac_wifi')
            ->where('company_id', '=', auth()->user()->company_id)
            ->get();
            
            return response()->json($data);
        }
        else{
            $data = DB::table('devices')
            ->select('id', 'serial_number', 'state', 'model', 'brand', 'description', 'mac_ethernet', 'mac_wifi')
            ->get();
            
            return response()->json($data);
        }
    }


    // public function listInventaryAPI($id){
    //     // $idUser = 1;//Aquí es possara la variable de sessió que contingui el id de la sessió
    //     return Device::findOrFail($id)->get();
    // }

    public function listInventary(Request $request){

        $idUser = Auth::id();
        $user = User::find($idUser);
        $idComapny = $user->company_id;

        $filtro = $request->buscar;

        $dispositivosInventario = Device::where('company_id', $idComapny)
                                    ->join('type_devices', 'devices.type_device_id', '=', 'type_devices.id')
                                    ->where(function ($query) use ($filtro) {
                                        $query->where('brand', 'LIKE', '%'.$filtro.'%')
                                            ->orWhere('model', 'LIKE', '%'.$filtro.'%')
                                            ->orWhere('mac_ethernet', 'LIKE', '%'.$filtro.'%')
                                            ->orWhere('mac_wifi', 'LIKE', '%'.$filtro.'%')
                                            ->orWhere('type_devices.name', 'LIKE', '%'.$filtro.'%')
                                            ->orWhere('description', 'LIKE', '%'.$filtro.'%')
                                            ->orWhere('state', 'LIKE', '%'.$filtro.'%')
                                            ->orWhere('serial_number', 'LIKE', '%'.$filtro.'%');
                                        })
                                        ->paginate(3);

        return response()->json($dispositivosInventario, 200);
    }

    public function uploadImage(Request $request)
    {
        // retrieve the uploaded file and get its original name
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();

        // store the file on the disk, for example 'public'
        Storage::disk('public')->put($filename, file_get_contents($file));

        // do something with the stored file
        return response('Image uploaded successfully.', 200);
    }

}
