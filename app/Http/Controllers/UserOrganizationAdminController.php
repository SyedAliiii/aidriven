<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserOrganizationAdminController extends Controller
{
    private function authorizeSuperAdmin(): void
    {
        $user = auth()->user();
        if (!$user || !$user->hasRole('super-admin')) {
            abort(403);
        }
    }

    public function index()
    {
        $this->authorizeSuperAdmin();

        $organizations = Organization::query()
            ->orderBy('name')
            ->get();

        $roles = Role::query()
            ->orderBy('name')
            ->get();

        $users = User::query()
            ->with(['organization', 'roles'])
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.users.index', compact('users', 'organizations', 'roles'));
    }

    public function store(Request $request)
    {
        $this->authorizeSuperAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'max:255'],
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'role' => ['nullable', 'string', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'organization_id' => (int) $data['organization_id'],
        ]);

        if (!empty($data['role'])) {
            $user->syncRoles([$data['role']]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function updateOrganization(Request $request, User $user)
    {
        $this->authorizeSuperAdmin();

        $data = $request->validate([
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
        ]);

        $user->organization_id = (int) $data['organization_id'];
        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User organization updated successfully.');
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeSuperAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:6', 'max:255'],
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
            'role' => ['nullable', 'string', 'exists:roles,name'],
        ]);

        $payload = [
            'name' => $data['name'],
            'email' => $data['email'],
            'organization_id' => (int) $data['organization_id'],
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make($data['password']);
        }

        $user->update($payload);

        if (array_key_exists('role', $data) && !empty($data['role'])) {
            $user->syncRoles([$data['role']]);
        } else {
            $user->syncRoles([]);
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $this->authorizeSuperAdmin();

        if (auth()->id() === $user->id) {
            return redirect()
                ->route('admin.users.index')
                ->with('success', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}

