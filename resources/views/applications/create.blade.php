@extends('website-layouts.app')

@section('title', 'Submit Application')

@section('content')
<div class="container mt-5">
    <h4 class="text-light bg-danger">IMPORTANT: Please submit your application only once. You will be contacted once your application has been considered.</h4>
    <p><a href="{{ route('applications.track') }}">If you have already applied, kindly track your application here.</a></p>

    <h2>Online Application</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="justify-content-center col-md-6 ">
        <small class="text-danger">Inputs marked with an asterisk (<span class="text-danger"> * </span>) are mandatory</small>
        <form action="{{ route('applications.store') }}" method="POST" enctype="multipart/form-data" class="mb-10">
            @csrf

            <div class="mb-3">
                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                @error('first_name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                @error('last_name') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                @error('email') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="nrc_number" class="form-label">NRC Number or Passport Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nrc_number" name="nrc_number" value="{{ old('nrc_number') }}" required>
                @error('nrc_number') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="position_applied_for" class="form-label">Position <span class="text-danger">*</span></label>
                <select class="form-control" id="position_applied_for" name="position_applied_for" required>
                    <option value="">Select Position</option>
                    <option value="Crusher Operator" {{ old('position_applied_for') == 'Crusher Operator' ? 'selected' : '' }}>Crusher Operator</option>
                    <option value="Milling and Flotation Operator" {{ old('position_applied_for') == 'Milling and Flotation Operator' ? 'selected' : '' }}>Milling and Flotation Operator</option>
                    <option value="Metallurgist" {{ old('position_applied_for') == 'Metallurgist' ? 'selected' : '' }}>Metallurgist</option>
                    <option value="Leaching, Solvent Extraction and Electrowinning Operator/Attendee" {{ old('position_applied_for') == 'Leaching, Solvent Extraction and Electrowinning Operator/Attendee' ? 'selected' : '' }}>Leaching, Solvent Extraction and Electrowinning Operator/Attendee</option>
                    <option value="Leaching, Solvent Extraction and Electrowinning Incharge" {{ old('position_applied_for') == 'Leaching, Solvent Extraction and Electrowinning Incharge' ? 'selected' : '' }}>Leaching, Solvent Extraction and Electrowinning Incharge</option>
                    <option value="Plastician/Plastic Welder" {{ old('position_applied_for') == 'Plastician/Plastic Welder' ? 'selected' : '' }}>Plasticians/Plastic Welder</option>
                    <option value="Mechanical Fitter" {{ old('position_applied_for') == 'Mechanical Fitter' ? 'selected' : '' }}>Mechanical Fitter</option>
                    <option value="Coded Welder" {{ old('position_applied_for') == 'Coded Welder' ? 'selected' : '' }}>Coded Welder</option>
                    <option value="Electrician" {{ old('position_applied_for') == 'Electrician' ? 'selected' : '' }}>Electrician</option>
                    <option value="HR Assistant Officer" {{ old('position_applied_for') == 'HR Assistant Officer' ? 'selected' : '' }}>HR Assistant Officer</option>
                    <option value="Safety Officer" {{ old('position_applied_for') == 'Safety Officer' ? 'selected' : '' }}>Safety Officer</option>
                    <option value="Chemist" {{ old('position_applied_for') == 'Chemist' ? 'selected' : '' }}>Chemist</option>
                    <option value="Accounts Assistant" {{ old('position_applied_for') == 'Accounts Assistant' ? 'selected' : '' }}>Accounts Assistant</option>
                    <option value="Senior Accountant" {{ old('position_applied_for') == 'Senior Accountant' ? 'selected' : '' }}>Senior Accountant</option>
                    <option value="Stores Assistant" {{ old('position_applied_for') == 'Stores Assistant' ? 'selected' : '' }}>Stores Assistant</option>
                </select>
                @error('position_applied_for') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="years_of_experience" class="form-label">Years of Experience <span class="text-danger">*</span></label>
                <select class="form-control" id="years_of_experience" name="years_of_experience" required>
                    <option value="">Select Years of Experience</option>
                    <option value="1" {{ old('years_of_experience') == '1' ? 'selected' : '' }}>1 Year</option>
                    <option value="2" {{ old('years_of_experience') == '2' ? 'selected' : '' }}>2 Years</option>
                    <option value="3" {{ old('years_of_experience') == '3' ? 'selected' : '' }}>3 Years</option>
                    <option value="4" {{ old('years_of_experience') == '4' ? 'selected' : '' }}>4 Years</option>
                    <option value="5" {{ old('years_of_experience') == '5' ? 'selected' : '' }}>5 Years</option>
                    <option value="6" {{ old('years_of_experience') == '6' ? 'selected' : '' }}>6 Years</option>
                    <option value="7" {{ old('years_of_experience') == '7' ? 'selected' : '' }}>7 Years</option>
                    <option value="8" {{ old('years_of_experience') == '8' ? 'selected' : '' }}>8 Years</option>
                    <option value="9" {{ old('years_of_experience') == '9' ? 'selected' : '' }}>9 Years</option>
                    <option value="10" {{ old('years_of_experience') == '10' ? 'selected' : '' }}>10+ Years</option>
                </select>
                @error('years_of_experience') <div class="text-danger">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="resume" class="form-label">CV <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="resume" name="resume" required>
                <small class="form-text text-muted">
                    Allowed file types: PDF, DOC, DOCX. Maximum file size: 10MB.
                </small>
                @error('resume')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="cover_letter" class="form-label">Cover Letter <span class="text-danger">*</span></label>
                <input type="file" class="form-control" id="cover_letter" name="cover_letter" required>
                <small class="form-text text-muted">
                    Allowed file types: PDF, DOC, DOCX. Maximum file size: 10MB.
                </small>
                @error('cover_letter')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="certificate_1" class="form-label">Upload Certified Copies of Academic and Professional Certificates <span class="text-danger">*</span> <span class="text-danger">(In one PDF Document)</span></label>
                <input type="file" class="form-control" id="certificate_1" name="certificates[]" required>
                <small class="form-text text-muted">
                    Allowed file types: PDF, DOC, DOCX. Maximum file size: 10MB.
                </small>
                @error('certificates')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Submit Application</button>
        </form>
    </div>
</div>
@endsection
