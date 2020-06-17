<?php

namespace App\Http\Controllers\API;

use App\Advertisement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use PHPUnit\Framework\Constraint\IsNull;


class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all advertisements
        $ads = Advertisement::with('user')->get();

        //return all advertisements
        return response()->json(['advertisements' => $ads, 'message' => 'Retrieved advertisements successfully'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // validating the incoming data
        $validatedData = $request->validate([
            'title' => 'required|min:3|max:100',
            'detail' => 'required|min:3|max:500'
        ]);

        //check if user is logged in
        if(!$loggedIn = auth()->user()) {
            return response()->json(['message' => 'user is not logged in'], 401);
        }

        //get user from user class
        $user = User::find($loggedIn->id);

        //create a new advertisement object with the validated data
        $ad = new Advertisement($validatedData);

        //create new advertisement and add to logged in user
        $user->advertisements()->save($ad);

        //return the created advertisement
        return response()->json(['advertisement' => $validatedData, 'message' => 'advertisement successfully created'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $advertisement = Advertisement::with('user')->find($id);

        if(is_null($advertisement)) {
            return response()->json(['message' => 'advertisement not found. nothing to show.'], 404);
        }

        //return advertisment with the help op implicit route model binding
        return response()->json(['advertisement' => $advertisement, 'message' => 'advertisement successfully retrieved'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        // get specific advertisement by id
        $advertisement = Advertisement::with('user')->find($id);

        if(is_null($advertisement)) {
            return response()->json(['message' => 'advertisement not found. Nothing to update.'], 404);
        }

        //validating data
        $validatedData = $request->validate([
            'title' => 'required|min:3|max:100',
            'detail' => 'required|min:3|max:500'
        ]);

        // check if the user owns this advertisement
        if(auth()->user()->id !== $advertisement['user']['id']) {
            return response()->json(['message' => 'not authorized'], 401);
        }

        $advertisement->update($validatedData);

        return response()->json(['advertisement' => $advertisement, 'message' => 'advertisement succesfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Advertisement $advertisement)
    {
        $advertisement->delete();

        return response()->json(['advertisement' => null, 'message' => 'advertisement deleted']);
    }
}
