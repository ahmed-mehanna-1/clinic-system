<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use App\Models\Illness;
use App\Models\Drug;
use App\Models\PatientHistory;
use App\Models\Rumour;
use App\Models\analysis;
use App\Models\Patientturn;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use mysql_xdevapi\Result;

class DoctorController extends Controller
{

    public function index()
    {
        $userTurn = Patientturn::all();
        $userTurn = $userTurn->first();
        if ($userTurn === null) {
            return redirect()->back()->with('messageError', 'There are no recently patient has been arrived');

        } else {

            return view('pages.doctor-dashboard', ["userTurn" => $userTurn]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "illness-name" => "required",
            "illness-diagnose" => "required",
            "select_type" => "required",
            "name" => "required",
            "description" => "required",
            "user_id" => "required",
//********************************************************************//
//        "summary"=> "required",
//        "analysis_name"=> "required",
//        "analysis_result"=> "required",
//        "rumour_name"=> "required",
//        "rumour_result"=> "required",
        ]);
        $illness = new Illness;
        $illness->illnessName = $request["illness-name"];
        $illness->illnessDiagnose = $request["illness-diagnose"];
        $illness->user_id = $request["user_id"];
        $illness->save();


        for ($i = 0; $i < count($request['select_type']); ++$i) {
            if ($request["select_type"][$i] == "Drugs") {
                $drug = new Drug();
                $drug->drugName = $request["name"][$i];
                $drug->drugDescription = $request["description"][$i];
                $drug->illness_id = $illness->id;
                $drug->save();
            } elseif ($request["select_type"][$i] == "Analysis") {
                $analyses = new analysis();
                $analyses->title = $request["name"][$i];
                $analyses->result = $request["description"][$i];
                $analyses->illness_id = $illness->id;
                $analyses->save();

            } elseif ($request["select_type"][$i] == "Rumours") {
                $rumour = new Rumour();
                $rumour->title = $request["name"][$i];
                $rumour->result = $request["description"][$i];
                $rumour->illness_id = $illness->id;
                $rumour->save();
            }
        }


//      for ($i=0; $i < count($request['name']); ++$i){
//        $drug = new Drug();
//        $drug->drugName = $request["name"][$i];
//        $drug->drugDescription = $request["description"][$i];
//        $drug->illness_id = $illness->id;
//        $drug->save();
//     }

        //if not have summary and analses and rumors
//      $patient_history = new PatientHistory();
//      $patient_history["Summary"] = $request["summary"];
//      $patient_history["user_id"] = $request["user_id"];
//        $user_update_summary = User::find($request->input("user_id"));
//        $user_update_summary->patientHistory->update(["Summary" => $request->input("summary")]);
//
//        $find_analyses = analysis::all();
//        $find_rumour = Rumour::all();


//
//        for ($i=count($find_analyses); $i < count($request['analysis_name']); ++$i){
//            $analyses = new analysis();
//            $analyses->title = $request["analysis_name"][$i];
//            $analyses->result = $request["analysis_result"][$i];
//            $analyses->user_id = $request["user_id"];
//            $analyses->save();
//
//        }
//        for ($i=count($find_rumour); $i < count($request['rumour_name']); ++$i){
//            $rumour = new Rumour();
//            $rumour->title = $request["rumour_name"][$i];
//            $rumour->result = $request["rumour_result"][$i];
//            $rumour->user_id =$request["user_id"];
//            $rumour->save();
//        }
//        $userSkipturn = User::find($request->input("user_id"));
//        $userSkipturn->patientTurn->delete();

        return redirect('/doctor');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }


    public function findPatient(Request $request)
    {
        $data = $request->input("data");
        if (filter_var($data, FILTER_VALIDATE_EMAIL)) {
            $user = User::firstWhere("email", $request->input("data"));
            if ($user === null || ($user->id === \auth()->user()->id) || $user->Role == 2) {
                return redirect('/')->with('searchError', 'We couldn\'t find your patient');
            } else {
                return view('pages.doctor-find-patient', ["user" => $user]);
            }
        } else {
            $user1 = User::firstWhere("id", $request->input("data"));
            $user2 = User::firstWhere("phoneNumber", $request->input("data"));

            if ($user1 && $user2 === null) {
                if ($user1->id === \auth()->user()->id || $user1->Role == 2) {
                    return redirect('/')->with('searchError', 'We couldn\'t find your patient');
                } else {

                    return view('pages.doctor-find-patient', ["user" => $user1]);
                }
            } elseif ($user2 && $user1 === null) {
                $doctor = User::find(auth()->user()->id);
                if ($doctor->phoneNumber === $user2->phoneNumber || $user2->Role == 2) {
                    return redirect('/')->with('searchError', 'We couldn\'t find your patient');
                } else {
                    return view('pages.doctor-find-patient', ["user" => $user2]);
                }
            } else {
                return redirect('/')->with('searchError', 'We couldn\'t find your patient');
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
