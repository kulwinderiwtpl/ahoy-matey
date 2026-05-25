<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Mail\Invitation as InivationMail;
use App\Models\Invitation;
use Illuminate\Support\Facades\Mail;
use App\Models\Space;
use App\Models\SpacesUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class InviteController extends Controller
{
    function index()
    {

        $url = URL::temporarySignedRoute('inviteUser', now()->addMinutes(30), ['user' => auth()->user()->id]);
        return view('ajax.invites', compact('url'))->render();
    }

    function sendInvitesEmail(Request $request)
    {
        foreach (explode(",", $request->emails) as $email) {
            $url = URL::temporarySignedRoute('inviteUser', now()->addMinutes(30), ['user' => auth()->user()->id, 'email' => $email]);
            Invitation::create(['email' => $email, 'user_id' => auth()->user()->id]);
            Mail::to($email)->send(new InivationMail(['link' => ($url), 'email' => $email]));
        }

        return response("", 204);
    }

    function showRegistrationForm(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(401);
        } else {
            if (auth()->check()) {
                if (auth()->user()->id != $request->user) {
                    // fetch all spaces related to invited user
                    $spaces = Space::where('user_id', $request->user)->pluck('id')->toArray();
                    if (!empty($spaces)) {
                        foreach ($spaces as $space) {
                            $space_user = SpacesUser::where('user_id', auth()->user()->id)->where('space_id', $space)->first();
                            if (empty($space_user)) {
                                SpacesUser::create(['space_id' => $space, 'user_id' => auth()->user()->id, 'role_id' => 2]);
                            }
                        }
                        return redirect('/user/profile')->with('success', 'Inviation has been accepted successfully');
                    } else {
                        return redirect('/user/profile')->with('success', 'Inviation has been already accepted successfully');
                    }
                } else {
                    return redirect('/user/profile');
                }
            } else {
                if (!empty($request->email)) {
                    $email = $request->email;
                    $name_sub = explode("@", $request->email);

                    $user = User::where('email', $email)->first();
                    if (empty($user)) {
                        $createUser = User::create(['name' => $name_sub[0], 'email' => $email, 'password' => Hash::make('12345678')]);
                        $userdata = array(
                            'email' => $email,
                            'password' => '12345678'
                        );
                        if (auth()->attempt($userdata)) {
                            $spaces = Space::where('user_id', $request->user)->pluck('id')->toArray();
                            if (!empty($spaces)) {
                                foreach ($spaces as $space) {
                                    $space_user = SpacesUser::where('user_id', auth()->user()->id)->where('space_id', $space)->first();
                                    if (empty($space_user)) {
                                        SpacesUser::create(['space_id' => $space, 'user_id' => auth()->user()->id, 'role_id' => 2]);
                                    }
                                }
                            }
                            return redirect('/user/profile')->with('success', 'Registration and invitation is successfully accepted!');;
                        }
                    }
                } else {
                    return redirect('/login');
                }
            }
        }
    }
}
