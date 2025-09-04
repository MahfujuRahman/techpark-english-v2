@php
    $meta = [
        'seo' => [
            'title' => 'Edit Profile',
            'image' => asset('seo.jpg'),
        ],
    ];
@endphp

@extends('frontend.layouts.layout', $meta)

@section('contents')
    <style>
        .divider:after,
        .divider:before {
            content: "";
            flex: 1;
            height: 1px;
            background: #eee;
        }

        .card-custom {
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card-header-custom {
            background: #f8f9fa;
            font-size: 1.25rem;
            font-weight: bold;
            border-bottom: 1px solid #dee2e6;
        }

        /* Visual split for form sections */
        .profile-section {
            background: #fff;
            /* keep white so it blends with card */
            border: 1px solid #f1f5f9;
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 18px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.03);
        }

        .profile-section h5 {
            margin-top: 0;
            margin-bottom: 12px;
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
        }

        @media (max-width: 767px) {
            .profile-section {
                padding: 12px;
            }
        }
    </style>
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <section class="py-5 my-5">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-12 col-md-10">
                    <div class="card card-custom">
                        <div class="card-header card-header-custom text-center">
                            Edit Profile
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('update_profile') }}" enctype="multipart/form-data">
                                @csrf

                                <!-- Personal information -->
                                <div class="profile-section">
                                    <h5 class="mb-3">Personal Information</h5>
                                    <div class="row gx-3">
                                        <div class="col-12 col-md-4">
                                            <div class="mb-3">
                                                <label for="first_name" class="form-label">First name <span
                                                        class="text-danger">*</span></label>
                                                <input id="first_name" name="first_name" type="text" class="form-control"
                                                    value="{{ old('first_name', auth()->user()->first_name) }}"
                                                    placeholder="John">
                                                @error('first_name')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="mb-3">
                                                <label for="last_name" class="form-label">Last name</label>
                                                <input id="last_name" name="last_name" type="text" class="form-control"
                                                    value="{{ old('last_name', auth()->user()->last_name) }}"
                                                    placeholder="Doe">
                                                @error('last_name')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                      
                                        <div class="col-12 col-md-4">
                                            <div class="mb-3">
                                                <label for="gender" class="form-label">Gender</label>
                                                <select id="gender" name="gender" class="form-select">
                                                    <option value="">Choose...</option>
                                                    <option value="Male"
                                                        {{ old('gender', $user->studentDetails->gender) == 'Male' ? 'selected' : '' }}>
                                                        Male</option>
                                                    <option value="Female"
                                                        {{ old('gender', $user->studentDetails->gender) == 'Female' ? 'selected' : '' }}>
                                                        Female</option>
                                                    <option value="Other"
                                                        {{ old('gender', $user->studentDetails->gender) == 'Other' ? 'selected' : '' }}>
                                                        Other</option>
                                                </select>
                                                @error('gender')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="profile-section">
                                    <div class="row align-items-center mb-3">
                                        <div class="col-12 col-md-3 text-center text-md-start">
                                            @if (auth()->user()->image)
                                                <img src="{{ asset(auth()->user()->image) }}" alt="avatar"
                                                    class="img-thumbnail"
                                                    style="width:110px; height:110px; object-fit:cover;">
                                            @else
                                                <div class="img-thumbnail d-inline-block"
                                                    style="width:110px; height:110px; background:#f1f5f9;border-radius:6px;">
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <label for="image" class="form-label">Profile picture</label>
                                            <input id="image" name="image" type="file" class="form-control">
                                            @error('image')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row gx-3">
                                        <div class="col-12 col-md-6">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input id="email" name="email" type="text" class="form-control"
                                                    value="{{ old('email', auth()->user()->email) }}"
                                                    placeholder="Doe">
                                                @error('email')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="mb-3">
                                                <label for="reference_source" class="form-label">Reference</label>
                                                <select id="reference_source" name="reference_source" class="form-select">
                                                    <option value="">Select</option>
                                                    @php $ref = old('reference_source', $user->studentDetails->reference_source); @endphp
                                                    @foreach (['Facebook', 'Youtube', 'Organization', 'Ex-Student', 'Employee', 'Telesales', 'Offline Marketing', 'Friend', 'Other'] as $r)
                                                        <option value="{{ $r }}"
                                                            {{ $ref == $r ? 'selected' : '' }}>{{ $r }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact information -->
                                <div class="profile-section">
                                    <h5 class="mb-3 mt-0">Contact</h5>
                                    <div class="row gx-3">
                                        <div class="col-12 col-md-4">
                                            <div class="mb-3">
                                                <label for="phone_number" class="form-label">Mobile number <span
                                                        class="text-danger">*</span></label>
                                                <input id="phone_number" name="phone_number" type="text"
                                                    class="form-control"
                                                    value="{{ old('phone_number', is_array(json_decode($user->address->phone_number)) ? json_decode($user->address->phone_number)[0] : '') }}"
                                                    placeholder="01XXXXXXXXX">
                                                @error('phone_number')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="mb-3">
                                                <label for="whatsapp_number" class="form-label">WhatsApp</label>
                                                <input id="whatsapp_number" name="whatsapp_number" type="text"
                                                    class="form-control"
                                                    value="{{ old('whatsapp_number', is_array(json_decode($user->address->phone_number)) ? json_decode($user->address->phone_number)[1] : '') }}"
                                                    placeholder="01XXXXXXXXX">
                                                @error('whatsapp_number')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="mb-3">
                                                <label for="guardian_number" class="form-label">Guardian's number</label>
                                                <input id="guardian_number" name="guardian_number" type="text"
                                                    class="form-control"
                                                    value="{{ old('guardian_number', $user->studentDetails->guardian_number) }}"
                                                    placeholder="Optional">
                                                @error('guardian_number')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Location -->
                                <div class="profile-section">
                                    <h5 class="mb-3 mt-0">Location</h5>
                                    <div class="row gx-3">
                                        <div class="col-6 col-md-3">
                                            <div class="mb-3">
                                                <label for="country" class="form-label">Country</label>
                                                <input id="country" name="country" type="text" class="form-control"
                                                    value="{{ old('country', $user->address->country) }}"
                                                    placeholder="Country">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="mb-3">
                                                <label for="state" class="form-label">State</label>
                                                <input id="state" name="state" type="text" class="form-control"
                                                    value="{{ old('state', $user->address->state) }}"
                                                    placeholder="State">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="mb-3">
                                                <label for="city" class="form-label">City</label>
                                                <input id="city" name="city" type="text" class="form-control"
                                                    value="{{ old('city', $user->address->city) }}" placeholder="City">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <div class="mb-3">
                                                <label for="post" class="form-label">Post/Zip</label>
                                                <input id="post" name="post" type="text" class="form-control"
                                                    value="{{ old('post', $user->address->post) }}"
                                                    placeholder="Post code">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Professional -->
                                <div class="profile-section">
                                    <h5 class="mb-3 mt-0">Professional</h5>
                                    <div class="row gx-3">
                                        <div class="col-12 col-md-4">
                                            <div class="mb-3">
                                                <label for="occupation" class="form-label">Occupation</label>
                                                <input id="occupation" name="occupation" type="text"
                                                    class="form-control"
                                                    value="{{ old('occupation', $user->studentDetails->occupation) }}"
                                                    placeholder="e.g. Student / Developer">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="mb-3">
                                                <label for="organization" class="form-label">Organization</label>
                                                <input id="organization" name="organization" type="text"
                                                    class="form-control"
                                                    value="{{ old('organization', $user->studentDetails->organization) }}"
                                                    placeholder="Organization">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="mb-3">
                                                <label for="designation" class="form-label">Designation</label>
                                                <input id="designation" name="designation" type="text"
                                                    class="form-control"
                                                    value="{{ old('designation', $user->studentDetails->designation) }}"
                                                    placeholder="Job title">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Institutional -->
                                <div class="profile-section">
                                    <h5 class="mb-3 mt-0">Institutional</h5>
                                    <div class="row gx-3">
                                        <div class="col-12 col-md-4">
                                            <div class="mb-3">
                                                <label for="institution" class="form-label">Institution</label>
                                                <input id="institution" name="institution" type="text"
                                                    class="form-control"
                                                    value="{{ old('institution', $user->studentDetails->institution) }}"
                                                    placeholder="Organization / School">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="mb-3">
                                                <label for="class" class="form-label">Class </label>
                                                <input id="class" name="class" type="text" class="form-control"
                                                    value="{{ old('class', $user->studentDetails->class) }}"
                                                    placeholder="Class">
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="mb-3">
                                                <label for="last_certificate_name" class="form-label">Last
                                                    certificate</label>
                                                <input id="last_certificate_name" name="last_certificate_name"
                                                    type="text" class="form-control"
                                                    value="{{ old('last_certificate_name', $user->studentDetails->last_certificate_name) }}"
                                                    placeholder="e.g. HSC / Diploma">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Security -->
                                <div class="profile-section">
                                    <h5 class="mb-3 mt-0">Security</h5>
                                    <div class="row gx-3">
                                        <div class="col-12 col-md-6">
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Password</label>
                                                <input id="password" name="password" type="password"
                                                    class="form-control" placeholder="Leave empty to keep current">
                                                @error('password')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <div class="mb-3">
                                                <label for="password_confirmation" class="form-label">Confirm
                                                    password</label>
                                                <input id="password_confirmation" name="password_confirmation"
                                                    type="password" class="form-control" placeholder="Repeat password">
                                                @error('password_confirmation')
                                                    <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary rounded-pill px-4">Update
                                        Profile</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
