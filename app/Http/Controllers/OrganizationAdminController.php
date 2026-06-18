<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Services\DatabaseConnectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class OrganizationAdminController extends Controller
{
    public function __construct(
        private readonly DatabaseConnectionService $databaseConnectionService,
    ) {
    }

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
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.organizations.index', compact('organizations'));
    }

    public function create()
    {
        $this->authorizeSuperAdmin();

        return view('admin.organizations.create');
    }

    public function store(Request $request)
    {
        $this->authorizeSuperAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'db_host' => ['required', 'string', 'max:255'],
            'db_database' => ['required', 'string', 'max:255'],
            'db_username' => ['required', 'string', 'max:255'],
            'db_password' => ['nullable', 'string', 'max:255'],
            'db_port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'status' => ['nullable', 'in:active,inactive'],
        ]);

        $encryptedPassword = !blank($data['db_password'] ?? null)
            ? Crypt::encryptString($data['db_password'])
            : null;

        Organization::create([
            'name' => $data['name'],
            'db_host' => $data['db_host'],
            'db_database' => $data['db_database'],
            'db_username' => $data['db_username'],
            'db_password' => $encryptedPassword,
            'db_port' => $data['db_port'] ?? 3306,
            'status' => $data['status'] ?? 'active',
        ]);

        return redirect()
            ->route('admin.organizations.index')
            ->with('success', 'Organization created successfully.');
    }

    public function edit(Organization $organization)
    {
        $this->authorizeSuperAdmin();

        return view('admin.organizations.edit', compact('organization'));
    }

    public function update(Request $request, Organization $organization)
    {
        $this->authorizeSuperAdmin();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'db_host' => ['required', 'string', 'max:255'],
            'db_database' => ['required', 'string', 'max:255'],
            'db_username' => ['required', 'string', 'max:255'],
            'db_password' => ['nullable', 'string', 'max:255'],
            'db_port' => ['nullable', 'integer', 'min:1', 'max:65535'],
            'status' => ['nullable', 'in:active,inactive'],
        ]);

        $payload = [
            'name' => $data['name'],
            'db_host' => $data['db_host'],
            'db_database' => $data['db_database'],
            'db_username' => $data['db_username'],
            'db_port' => $data['db_port'] ?? 3306,
            'status' => $data['status'] ?? 'active',
        ];

        // If password is left blank, keep existing encrypted value.
        if (array_key_exists('db_password', $data) && !blank($data['db_password'])) {
            $payload['db_password'] = Crypt::encryptString($data['db_password']);
        }

        $organization->update($payload);

        return redirect()
            ->route('admin.organizations.index')
            ->with('success', 'Organization updated successfully.');
    }

    public function destroy(Organization $organization)
    {
        $this->authorizeSuperAdmin();

        $organization->delete();

        return redirect()
            ->route('admin.organizations.index')
            ->with('success', 'Organization deleted successfully.');
    }

    public function testConnection(Organization $organization)
    {
        $this->authorizeSuperAdmin();

        $result = $this->databaseConnectionService->testConnectionForOrganization($organization);

        if ($result['ok']) {
            return redirect()
                ->route('admin.organizations.index')
                ->with('success', "[{$organization->name}] {$result['message']}");
        }

        return redirect()
            ->route('admin.organizations.index')
            ->with('error', "[{$organization->name}] Connection failed: {$result['message']}");
    }
}

