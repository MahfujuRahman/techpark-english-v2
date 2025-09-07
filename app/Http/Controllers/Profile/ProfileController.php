<?php

namespace App\Http\Controllers\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Modules\Management\UserManagement\User\Models\Model as User;
use App\Modules\Management\UserManagement\User\Models\UserAddressModel as UserAddress;
use App\Modules\Management\UserManagement\User\Models\StudentDetailsModel as StudentDetails;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = User::with('address', 'studentDetails')->find(auth()->user()->id);
        return view('frontend.pages.profile.profile', compact('user'));
    }

    public function profileUpdate(Request $request)
    {

        $validatedData = $request->validate([
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'nullable|string|max:255',
            'email'             => 'required|email|max:255',
            'image'             => 'nullable|image|max:2048',
            'password'          => 'nullable|string|min:8|confirmed',

            'whatsapp_number'   => 'nullable|string|max:20',
            'phone_number'      => 'nullable|string|max:20',
            'country'           => 'nullable|string|max:255',
            'city'              => 'nullable|string|max:255',
            'state'             => 'nullable|string|max:255',
            'post'               => 'nullable|string|max:20',

            'father_name'       => 'nullable|string|max:255',
            'mother_name'      => 'nullable|string|max:255',
            'gender'            => 'nullable|string|in:Male,Female,Other',
            'guardian_number'   => 'nullable|string|max:20',
            'blood_group'       => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',

            'occupation'        => 'nullable|string|max:255',
            'designation'       => 'nullable|string|max:255',
            'organization'       => 'nullable|string|max:255',

            'institution'       => 'nullable|string|max:255',
            'class' => 'nullable|string|max:255',
            'last_certificate_name' => 'nullable|string|max:255',
            'reference_source'  => 'nullable|string|max:255',
        ]);



        // dd($request->all());

        $user = User::where('id', auth()->user()->id)->first();

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $validatedData['image'] = uploader($image, 'uploads/profile_photos');
        }

        if ($request->filled('password')) {
            $validatedData['password'] = Hash::make($request->password);
        } else {
            unset($validatedData['password']);
        }

        $user->update([
            'first_name'        => $validatedData['first_name'],
            'last_name'         => $validatedData['last_name'],
            'email'             => $validatedData['email'],
            'image'             => $validatedData['image'] ?? $user->image,
            'password'          => $validatedData['password'] ?? $user->password,
        ]);

        $phoneNumbers = [];
        if (!empty($validatedData['phone_number'])) {
            $phoneNumbers[] = $validatedData['phone_number'];
        }
        if (!empty($validatedData['whatsapp_number'])) {
            $phoneNumbers[] = $validatedData['whatsapp_number'];
        }
        $phoneNumbersJson = json_encode($phoneNumbers);

        $validatedData['address'] = implode(',', [
            $validatedData['state'] ?? $user->state,
            $validatedData['city'] ?? $user->city,
            $validatedData['post'] ?? $user->post,
            $validatedData['country'] ?? $user->country
        ]);

        UserAddress::updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone_number'      => $phoneNumbersJson,
                'country'           => $validatedData['country'] ?? $user->country,
                'city'              => $validatedData['city'] ?? $user->city,
                'state'             => $validatedData['state'] ?? $user->state,
                'post'              => $validatedData['post'] ?? $user->post,
                'address'           => $validatedData['address'] ?? $user->address
            ]
        );

        StudentDetails::updateOrCreate(
            ['user_id' => $user->id],
            [
                'father_name'           => $validatedData['father_name'] ?? $user->father_name,
                'mother_name'           => $validatedData['mother_name'] ?? $user->mother_name,
                'gender'                => $validatedData['gender'] ?? $user->gender,
                'guardian_number'       => $validatedData['guardian_number'] ?? $user->guardian_number,
                'blood_group'           => $validatedData['blood_group'] ?? $user->blood_group,

                'occupation'            => $validatedData['occupation'] ?? $user->occupation,
                'designation'           => $validatedData['designation'] ?? $user->designation,
                'organization'          => $validatedData['organization'] ?? $user->organization,

                'institution'           => $validatedData['institution'] ?? $user->institution,
                'class'                 => $validatedData['class'] ?? $user->class,
                'last_certificate_name' => $validatedData['last_certificate_name'] ?? $user->last_certificate_name,
                'reference_source'      => $validatedData['reference_source'] ?? $user->reference_source,
            ]
        );

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
