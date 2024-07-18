<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Clinic_user;
use App\Models\CollectionLog;
use App\Models\Residue;
use App\Models\Clinic;
use App\Models\Waste_collection;
use App\Models\ResidueType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class CollectorController extends Controller
{

    // Vista de residuos no peligrosos

    public function index()
    {
        return view('Collector/index');
    }

    // Vista de residuos biologicos

    public function noHazardousView()
    {
        return view('Collector/nonhazardous');
    }


    //Vista de residuos: Quimicos - Respel

    public function residueChemical()
    {
        return view('Collector/residueChemical');
    }

    public function store(Request $request)
    {
        $clinics = $request->datos;
        $general_data = $request->data_general;
        $anioActual = getdate();
        $currentDate = date('Y-m-d');


        if ($this->dateValidations($general_data, $anioActual)) {
            foreach ($clinics as $key => $clinic) {
                if (!$this->collectionExists($general_data, $clinic)) {
                    if ($this->clinicValidate($clinic)) {
                        if ($general_data["schedule"] == 'Extra - 6:00 AM') {
                            $lastDate = date('Y-m-d', strtotime($currentDate . ' -1 day'));
                            $collection = new CollectionLog();
                            $collection->user_id = auth()->user()->id;
                            $collection->clinic_id = $clinic["clinic_id"];
                            $collection->year = $general_data["year"];
                            $collection->month = $general_data["month"];
                            $collection->schedule = $general_data["schedule"];
                            $collection->created_at = $lastDate;
                            $collection->save();
                        } else {
                            $collection = new CollectionLog();
                            $collection->user_id = auth()->user()->id;
                            $collection->clinic_id = $clinic["clinic_id"];
                            $collection->year = $general_data["year"];
                            $collection->month = $general_data["month"];
                            $collection->schedule = $general_data["schedule"];
                            $collection->save();
                        }
                        foreach ($clinic["data"] as $key => $residue) {
                            if ($residue["weight"] > 0) {
                                $residues = new Waste_collection();
                                $residues->collection_logs_id = $collection->id;
                                $residues->id_residue = $residue["residue_id"];
                                $residues->weight = $residue["weight"];
                                if ($general_data["schedule"] == 'Extra - 6:00 AM') {
                                    $residues->created_at = $lastDate;
                                }
                                $residues->save();
                            }
                        }
                    }
                }
            }

            $data = [
                'message' => 'Recolección registrada',
                'status' => true,
                'datos' => $clinics,
            ];

            return response()->json($data);
        } else {
            $data = [
                'message' => "Datos incorrectos en la fecha",
                'status' => false,
            ];
            return response()->json($data);
        }
    }

    public function storeAPI(Request $request)
    {
        $email = $request->user['user'];
        $password = $request->user['password'];

        // Validar las credenciales del usuario
        $user = User::where('email', $email)->first();
        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'message' => 'Usuario o contraseña incorrectos',
                'status' => false,
            ], 401);
        }

        $general_data = $request->data;
        $currentDate = date('Y-m-d');
        $type = $request->type;
        $dates = $request->general;

        foreach ($general_data as $key => $clinic) {
            if (!$this->collectionExists($dates, $clinic)) {
                if ($this->clinicValidate($clinic)) {
                    if ($type == 'extra') {
                        $lastDate = date('Y-m-d', strtotime($currentDate . ' -1 day'));
                        $collection = new CollectionLog();
                        $collection->user_id = $user->id; // Usar el ID del usuario autenticado
                        $collection->clinic_id = $clinic["clinic_id"];
                        $collection->year = $dates["year"];
                        $collection->month = $dates["month"];
                        $collection->schedule = $type;
                        $collection->created_at = $lastDate;
                        $collection->save();
                    } else {
                        $collection = new CollectionLog();
                        $collection->user_id = $user->id; // Usar el ID del usuario autenticado
                        $collection->clinic_id = $clinic["clinic_id"];
                        $collection->year = $dates["year"];
                        $collection->month = $dates["month"];
                        $collection->schedule = $type;
                        $collection->save();
                    }
                    foreach ($clinic["data"] as $key => $residue) {
                        if ($residue["weight"] > 0) {
                            $residues = new Waste_collection();
                            $residues->collection_logs_id = $collection->id;
                            $residues->id_residue = $residue["residue_id"];
                            $residues->weight = $residue["weight"];
                            if ($type == 'extra') {
                                $residues->created_at = $lastDate;
                            }
                            $residues->save();
                        }
                    }
                }
            }
        }

        $data = [
            'message' => 'Recolección registrada',
            'status' => true,
        ];
        return response()->json($data);
    }

    //Función para actualizar las recolecciones dependiendo del horario de recoleccion tomando la inserción más reciente

    public function updateCollection(Request $request)
    {


        $collections = $request->datos;
        $general_data = $request->data_general;
        $anioActual = getdate();
        $actualDate = date('Y-m-d');

        $items = '';

        if ($this->dateValidations($general_data, $anioActual)) {
            foreach ($collections as $key => $collection) {

                if ($general_data["schedule"] == 'Extra - 6:00 AM') {
                    $items = CollectionLog::where('clinic_id', $collection["clinic_id"])
                        ->where('schedule', $general_data["schedule"])
                        ->whereDate(DB::raw('DATE(created_at)'), Carbon::today()->subDay()->toDateString())
                        ->select('id', 'clinic_id')
                        ->first();
                } else {
                    $items = CollectionLog::where('clinic_id', $collection["clinic_id"])
                        ->where('schedule', $general_data["schedule"])
                        ->whereDate(DB::raw('DATE(created_at)'), Carbon::today()->toDateString())
                        ->select('id', 'clinic_id')
                        ->first();
                }



                foreach ($collection["data"] as $key2 => $residue) {
                    if ($residue["weight"] > 0) {
                        Waste_collection::where('collection_logs_id', $items["id"])
                            ->where('id_residue', $residue["residue_id"])
                            ->update(["weight" => $residue["weight"]]);
                    }
                }
            }

            $data = [
                'message' => 'Modificacion registrada',
                'status' => true,
                'collection' => $actualDate,

            ];

            return response()->json($data);
        } else {
            $data = [
                'message' => 'Informacion de modificacion incompleta',
                'status' => true,

            ];

            return response()->json($data);
        }
    }

    // Función para obtener recolecciones en base al horario tomando el dato más reciente.

    public function getCollections(Request $request)
    {
        $schedule = $request->data_general["schedule"];
        if ($request->type != 'METALES PESADOS') {
            $residueType = ResidueType::where('residue_type', $request->type)->value('id');
        }

        $actualDate = date('Y-m-d');


        $collectionData = [];
        $items = '';


        $collections = CollectionLog::where('schedule', $schedule)
            ->select('clinic_id')
            ->distinct()
            ->get();


        foreach ($collections as $collection) {

            if ($schedule == 'Extra - 6:00 AM') {
                $items = CollectionLog::where('clinic_id', $collection["clinic_id"])
                    ->where('schedule', $schedule)
                    ->whereDate(DB::raw('DATE(created_at)'), Carbon::today()->subDay()->toDateString())
                    ->select('id', 'clinic_id')
                    ->first();
            } else {
                $items = CollectionLog::where('clinic_id', $collection["clinic_id"])
                    ->where('schedule', $schedule)
                    ->whereDate(DB::raw('DATE(created_at)'), Carbon::today()->toDateString())
                    ->select('id', 'clinic_id')
                    ->first();
            }



            if ($request->type == 'METALES PESADOS') {
                $weight = Waste_collection::join('residues', 'residues.id', '=', 'waste_collections.id_residue')
                    ->where('waste_collections.collection_logs_id', $items["id"])
                    ->whereIn('residues.residue_type_id', [3, 4])
                    ->select('waste_collections.weight', 'waste_collections.id_residue', 'residues.residue_name')
                    ->get();
            } else {
                if ($items) {
                    $weight = Waste_collection::join('residues', 'residues.id', '=', 'waste_collections.id_residue')
                        ->where('waste_collections.collection_logs_id', $items["id"])
                        ->where('residues.residue_type_id', $residueType)
                        ->select('waste_collections.weight', 'waste_collections.id_residue', 'residues.residue_name')
                        ->get();
                }
            }


            if ($items) {
                $data2 = [

                    'message' => 'items',
                    'weight' => $weight,
                    'clinic' => $items["clinic_id"],


                ];

                array_push($collectionData, $data2);
            }
        }

        $data = [
            'message' => 'INFORMACION DE RECOLECCION',
            'datos' => $collectionData,
        ];

        return response()->json($data);
    }


    // Funcion para validar que las fechas sean correctas y se haya diligenciado un horario

    public function dateValidations($general_data, $anioActual)
    {


        if ($anioActual["year"] > $general_data["year"] || $anioActual["year"] < $general_data["year"] || $general_data["year"] == '') {
            return false;
        }

        if ($general_data['month'] > 12 || $general_data['month'] < 1) {
            return false;
        }

        if ($general_data['schedule'] == '' || $general_data["schedule"] == null || $general_data["schedule"] == "undefined") {
            return false;
        }

        return true;
    }


    // Funcion para validar que los campos se encuentren diligenciados

    public function clinicValidate($clinic)
    {
        foreach ($clinic["data"] as $key => $residue) {
            if ($residue["weight"] != 0) {
                return true;
            }
        }

        return false;
    }

    // Función para validar la existencia de una recolección

    public function collectionExists($date, $clinic)
    {


        $exists =  CollectionLog::where('year', $date["year"])
            ->where('month', $date["month"])
            ->where('schedule', $date["schedule"])
            ->whereDay('created_at', date('d'))
            ->where('clinic_id', $clinic["clinic_id"])
            ->exists();

        return $exists;
    }


    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    // Función para traer las consultorios registrados en el sistema y los residuos.

    public function getClinics(Request $request)
    {

        $clinicNumber = $request->clinicNumber;
        $towerNumber = $request->towerNumber;

        $id = auth()->user()->id;

        $clinics = Clinic::get();


        $nonHazardousWaste = Residue::where('residue_type_id', 1)->get();
        $hazardousWaste = Residue::where('residue_type_id', 2)->get();
        $residueChemical = Residue::whereIn('residue_type_id', [3, 4])->get();
        $currentDate = date('Y-m');
        $currentYear = date('Y');
        $currentMonth = date('m');

        $data = [
            'status' => true,
            'clinics' => $clinics,

            'residues' => $nonHazardousWaste,
            'hazardouswaste' => $hazardousWaste,
            'residueChemical' => $residueChemical,
            'year' => $currentYear,
            'month' => $currentMonth,
        ];

        return response()->json($data);
    }

    // Funcion para traer el rol del usuario

    public function getUserRole()
    {

        $user = Auth::user();
        $role = $user->getRoleNames();
        $data = [
            'status' => true,
            'role' => $role,
        ];

        return response()->json($data);
    }

    public function getAdmin(){
      return response()->json([
        'collectors' => CollectionLog::with('clinic', 'user', 'waste_collection.residues')->has('waste_collection')->get(),
        'residues' => Residue::get()
      ]);
    }

    public function updateWasteResidues(Request $request, CollectionLog $collector)
    {
        // Validar los datos recibidos
        $request->validate([
            'waste_collection' => 'required|array',
            'waste_collection.*.id_residue' => 'required|exists:residues,id',
            'waste_collection.*.weight' => 'required|numeric',
        ]);

        $wasteCollections = $request->input('waste_collection');

        // IDs de los residuos existentes
        $existingWasteIds = Waste_collection::where('collection_logs_id', $collector->id)->pluck('id')->toArray();
        $updatedWasteIds = [];

        // Actualizar o crear waste collections
        foreach ($wasteCollections as $wasteData) {
            if (isset($wasteData['id'])) {
                // Actualizar el residuo existente
                $wasteCollection = Waste_collection::find($wasteData['id']);
                if ($wasteCollection) {
                    $wasteCollection->update([
                        'id_residue' => $wasteData['id_residue'],
                        'weight' => $wasteData['weight']
                    ]);
                    $updatedWasteIds[] = $wasteData['id'];
                }
            } else {
                // Crear un nuevo residuo
                $newWaste = Waste_collection::create([
                    'collection_logs_id' => $collector->id,
                    'id_residue' => $wasteData['id_residue'],
                    'weight' => $wasteData['weight']
                ]);
                $updatedWasteIds[] = $newWaste->id;
            }
        }

        // Eliminar los residuos que no están en la lista de entrada
        Waste_collection::where('collection_logs_id', $collector->id)
            ->whereNotIn('id', $updatedWasteIds)
            ->delete();

        return response()->json(['message' => 'Waste collections updated successfully']);
    }
}
