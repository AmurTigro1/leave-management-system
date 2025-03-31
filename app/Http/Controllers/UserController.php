<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        'employee_code' => 'required|string|max:50|unique:users,employee_code',
        'name' => 'required|string',
        'first_name' => 'required|string|max:100',
        'middle_name' => 'nullable|string|max:100',
        'last_name' => 'required|string|max:100',
        'position' => 'required|string|max:100',
        'department' => 'required|string|max:100',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
        'birthday' => 'required|date',
        'role' => 'required|in:employee,admin,hr,supervisor',
    ]);

    // Handle file upload
    $profileImagePath = null;
    if ($request->hasFile('profile_image')) {
        $file = $request->file('profile_image');
        $filename = $file->getClientOriginalName(); // Keep original filename
        $profileImagePath = $filename;

        // Move the file to public/storage/profile_images/
        $file->storeAs('profile_pictures', $filename, 'public');
    }

    // Create user
    User::create([
        'profile_image' => $profileImagePath ?? null, // Save path in DB
        'employee_code' => $validatedData['employee_code'],
        'name' => $validatedData['name'],
        'first_name' => $validatedData['first_name'],
        'middle_name' => $validatedData['middle_name'] ?? null,
        'last_name' => $validatedData['last_name'],
        'position' => $validatedData['position'],
        'department' => $validatedData['department'],
        'email' => $validatedData['email'],
        'password' => Hash::make($validatedData['password']), // ✅ Hash Password
        'birthday' => $validatedData['birthday'],
        'role' => $validatedData['role'],
    ]);

    notify()->success('User created successfully!');
    return redirect()->back();
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validatedData = $request->validate([
        'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'employee_code' => 'required|string|max:50|unique:users,employee_code,' . $user->id,
        'name' => 'required|string',
        'first_name' => 'required|string|max:100',
        'middle_name' => 'nullable|string|max:100',
        'last_name' => 'required|string|max:100',
        'position' => 'required|string|max:100',
        'department' => 'required|string|max:100',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|string|min:8',
        'birthday' => 'required|date',
        'role' => 'required|in:employee,admin,hr,supervisor',
    ]);

    // Handle file upload
    if ($request->hasFile('profile_image')) {
        $file = $request->file('profile_image');
        $filename = $file->getClientOriginalName(); // Keep original filename

        // Delete the old profile image if it exists
        if ($user->profile_image) {
            Storage::delete('public/profile_pictures/' . $user->profile_image);
        }

        // Store new image in public/storage/profile_pictures
        $file->storeAs('profile_pictures', $filename, 'public');
        $user->profile_image = $filename; // Save new path in DB
    }

    // Update user details
    $user->employee_code = $validatedData['employee_code'];
    $user->name = $validatedData['name'];
    $user->first_name = $validatedData['first_name'];
    $user->middle_name = $validatedData['middle_name'] ?? null;
    $user->last_name = $validatedData['last_name'];
    $user->position = $validatedData['position'];
    $user->department = $validatedData['department'];
    $user->email = $validatedData['email'];
    $user->birthday = $validatedData['birthday'];
    $user->role = $validatedData['role'];

    // Update password only if provided
    if (!empty($validatedData['password'])) {
        $user->password = Hash::make($validatedData['password']);
    }

    $user->save(); // Save changes to database

    notify()->success('User updated successfully!');
    return redirect()->back();
}

public function destroy($id)
{
    $user = User::findOrFail($id);

    // Delete profile image if exists
    if ($user->profile_image) {
        Storage::delete('public/profile_pictures/' . $user->profile_image);
    }

    $user->delete();

    notify()->success('User deleted successfully!');
    return redirect()->back();
}


}
