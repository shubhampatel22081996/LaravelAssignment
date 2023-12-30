<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use \App\Mail\SendMail;
use App\Models\Country;
use App\Models\State;
use App\Models\City;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $country = Country::all();
        $states = State::all();
        $cities = City::all();
        return view('auth.register',['countries' => $country,'states' => $states,'cities' => $cities]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'dob' => ['required', 'date'],
            'gender' => ['required'],
            'country' => ['required'],
            'state' => ['required'],
            'city' => ['required'],


        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'dob' => $request->dob,
            'gender' => $request->gender,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
        ]);

        event(new Registered($user));

        if($user) {

            $data = [];
            $data["name"] = $request->name;
            $data["email"] = $request->email;
            $data["password"] = $request->password;

            $data = array('data' => $data, 'subject' => 'Thank you for registration', 'pagename' => 'email.registration_mail');

            Mail::to($request->email)->send(new SendMail($data));
        }

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    // get state accordint to country dependensy
    public function getstates(Request $request)
    {
        $countryId = $request->input('country_id');

       //  print_r($countryId);exit;

        // Fetch states based on the provided country ID
        $states = State::where('country_id', $countryId)->get();

        return response()->json($states);

    }

    // get city accordint to country dependensy

    public function getCities(Request $request)
    {
        $stateId = $request->input('state_id');

        // Fetch cities based on the provided state ID
        $cities = City::where('state_id', $stateId)->get();

        return response()->json($cities);
    }
}
