<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User; 


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    /**
     * Show the user's Information.
     */
    public function dashboard()
    {
        // Get the authenticated user's ID
        $authId = Auth::id();

        // print_r($authId);exit;

       // Assuming $authId is the authenticated user's ID
        $userData = User::join('country', 'country.id', '=', 'users.country')
        ->join('state', 'state.id', '=', 'users.state')
        ->join('city', 'city.id', '=', 'users.city')
        ->where('users.id', $authId)
        ->select('users.*', 'country.country as country', 'state.state as state', 'city.city as city')
        ->first();


        return view('dashboard',['userData'=>$userData]);

    }
}
