<?php

namespace App\Http\Controllers;

use App\Models\Luser;
use Illuminate\Http\Request;

class LuserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Luser::orderByDesc('points')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:lusers,name',
            'age' => 'required|integer',
            'address' => 'required|string'
        ]);
        
        $luser = Luser::create($request->all());
        if($luser) {
            $response = response($luser, 201);
        } else {
            $response = response([
                'message'=> 'User creation has failed',
            ], 403);
        }
        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $luser = Luser::find($id);
        if($luser) {
            $response = $luser;
        } else {
            $response = response([
                'message' => 'Bad Request',
                'Details' => 'Passed id has not valid'
            ], 400);
        }
        return $response;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Updating the points based on the request value increment/decrement
        $request->validate([
            'operation' => 'required|string'
        ]);
        $pointsOperation = strtolower($request->input('operation'));
        $operationArr = ['increment', 'decrement'];
        $luser = Luser::find($id);
        
        if($luser && in_array($pointsOperation, $operationArr)) {
            $points = $luser['points'];
            if(strcmp('increment', $pointsOperation) == 0) {
                $points += 1; 
            } else if(strcmp('decrement', $pointsOperation) == 0)
            {
                $points = ($points > 0 ) ? $points - 1 : 0; 
            }
            $luser->points = $points;
            $luser->save();

            $response = response($luser, 200);
           
        } else {
            $response = response([
                'message' => 'Bad Request',
                'Details' => (is_null($luser)) ? 'Passed id has not valid' : 'Passed operation has not valid'
            ], 400);
        }
        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {   
        $luser = Luser::destroy($id);
        if($luser) {
            $response = response([
                'message' => 'User has deleted successfully'
            ], 200);
        } else {
            $response = response([
                'message' => 'Bad Request',
                'Details' => 'Passed id has not valid'
            ], 400);
        }
       return $response;
    }
}
