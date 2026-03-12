<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Field;
use App\Models\Owner;
use App\Models\Reservation;
use App\Models\Tenant;
use App\Models\Employee;
use App\Services\AdminService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminServiceTest extends TestCase
{
    use RefreshDatabase;

    private AdminService $adminService;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Database\Eloquent\Model::unguard();
        $this->adminService = new AdminService();
    }

    public function test_can_get_all_users()
    {
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'phone' => '1234567890',
            'type' => 'employee'
        ]);

        $users = $this->adminService->getAllUsers();

        $this->assertCount(1, $users);
    }

    public function test_can_delete_user()
    {
        $user = User::create([
            'first_name' => 'To Delete',
            'last_name' => 'User',
            'email' => 'delete@test.com',
            'password' => bcrypt('password'),
            'phone' => '0987654321',
            'type' => 'tenant'
        ]);

        $result = $this->adminService->deleteUser($user->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_can_get_all_fields()
    {
        $user = User::create(['first_name' => 'O', 'last_name' => 'U', 'email' => 'o@t.com', 'password' => '1', 'phone' => '1', 'type' => 'owner']);
        $owner = Owner::create(['user_id' => $user->id, 'registration_date' => now(), 'siret' => 's1']);
        
        Field::create(['owner_id' => $owner->id, 'name' => 'F1', 'description' => 'D', 'address' => 'A', 'price_per_hour' => 100]);

        $fields = $this->adminService->getAllFields();
        $this->assertCount(1, $fields);
    }

    public function test_can_get_all_reservations()
    {
        $ownerUser = User::create(['first_name' => 'O', 'last_name' => 'U', 'email' => 'o@t.com', 'password' => '1', 'phone' => '1', 'type' => 'owner']);
        $owner = Owner::create(['user_id' => $ownerUser->id, 'registration_date' => now(), 'siret' => 's2']);
        $field = Field::create(['owner_id' => $owner->id, 'name' => 'F1', 'description' => 'D', 'address' => 'A', 'price_per_hour' => 100]);
        
        $tenantUser = User::create(['first_name' => 'T', 'last_name' => 'U', 'email' => 't@t.com', 'password' => '1', 'phone' => '2', 'type' => 'tenant']);
        $tenant = Tenant::create(['user_id' => $tenantUser->id, 'cin' => 'cin1', 'birth_date' => '2000-01-01']);

        Reservation::create([
            'tenant_id' => $tenant->id,
            'field_id' => $field->id,
            'first_name' => 'Jon',
            'last_name' => 'Doe',
            'email' => 'jon@doe.com',
            'phone' => '123',
            'request_date' => now(),
            'start_time' => '2024-05-20 10:00:00',
            'end_time' => '2024-05-20 11:00:00',
            'price' => 100,
            'status' => 'APPROVED'
        ]);

        $reservations = $this->adminService->getAllReservations();
        $this->assertCount(1, $reservations);
        $this->assertEquals('F1', $reservations->first()->field->name);
    }

    public function test_can_cancel_reservation()
    {
        $ownerUser = User::create(['first_name' => 'O', 'last_name' => 'U', 'email' => 'o@t.com', 'password' => '1', 'phone' => '1', 'type' => 'owner']);
        $owner = Owner::create(['user_id' => $ownerUser->id, 'registration_date' => now(), 'siret' => 's3']);
        $field = Field::create(['owner_id' => $owner->id, 'name' => 'F1', 'description' => 'D', 'address' => 'A', 'price_per_hour' => 100]);

        $tenantUser = User::create(['first_name' => 'T', 'last_name' => 'U', 'email' => 't_cancel@t.com', 'password' => '1', 'phone' => '1', 'type' => 'tenant']);
        $tenant = Tenant::create(['user_id' => $tenantUser->id, 'cin' => 'c1', 'birth_date' => '2000-01-01']);

        $reservation = Reservation::create([
            'tenant_id' => $tenant->id,
            'field_id' => $field->id,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'j@d.com',
            'phone' => '1',
            'request_date' => now(),
            'start_time' => '2024-05-20 12:00:00',
            'end_time' => '2024-05-20 13:00:00',
            'price' => 120,
            'status' => 'APPROVED'
        ]);

        $this->adminService->cancelReservation($reservation->id);

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'CANCELED'
        ]);
    }

    public function test_can_reject_reservation()
    {
        $ownerUser = User::create(['first_name' => 'O', 'last_name' => 'U', 'email' => 'o@t.com', 'password' => '1', 'phone' => '1', 'type' => 'owner']);
        $owner = Owner::create(['user_id' => $ownerUser->id, 'registration_date' => now(), 'siret' => 's4']);
        $field = Field::create(['owner_id' => $owner->id, 'name' => 'F1', 'description' => 'D', 'address' => 'A', 'price_per_hour' => 100]);

        $tenantUser = User::create(['first_name' => 'T', 'last_name' => 'U', 'email' => 't_reject@t.com', 'password' => '1', 'phone' => '1', 'type' => 'tenant']);
        $tenant = Tenant::create(['user_id' => $tenantUser->id, 'cin' => 'c2', 'birth_date' => '2000-01-01']);

        $reservation = Reservation::create([
            'tenant_id' => $tenant->id,
            'field_id' => $field->id,
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'j@d.com',
            'phone' => '1',
            'request_date' => now(),
            'start_time' => '2024-05-20 12:00:00',
            'end_time' => '2024-05-20 13:00:00',
            'price' => 120,
            'status' => 'PENDING'
        ]);

        $this->adminService->rejectReservation($reservation->id);

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'status' => 'REJECTED'
        ]);
    }

    public function test_can_get_dashboard_stats()
    {
        $ownerUser = User::create(['first_name' => 'O', 'last_name' => 'U', 'email' => 'o@t.com', 'password' => '1', 'phone' => '1', 'type' => 'owner']);
        $owner = Owner::create(['user_id' => $ownerUser->id, 'registration_date' => now(), 'siret' => 's5']);
        $field = Field::create(['owner_id' => $owner->id, 'name' => 'F1', 'description' => 'D', 'address' => 'A', 'price_per_hour' => 100]);

        $tenantUser1 = User::create(['first_name' => 'T1', 'last_name' => 'U1', 'email' => 't1@t.com', 'password' => '1', 'phone' => '1', 'type' => 'tenant']);
        Tenant::create(['user_id' => $tenantUser1->id, 'cin' => 'cin1', 'birth_date' => '2000-01-01', 'is_cni_valid' => true, 'cni_document_url' => 'url1']);
        
        $tenantUser2 = User::create(['first_name' => 'T2', 'last_name' => 'U2', 'email' => 't2@t.com', 'password' => '1', 'phone' => '2', 'type' => 'tenant']);
        Tenant::create(['user_id' => $tenantUser2->id, 'cin' => 'cin2', 'birth_date' => '2000-01-01', 'is_cni_valid' => false, 'cni_document_url' => 'url2']);
        
        Reservation::create([
            'tenant_id' => $tenantUser1->id,
            'field_id' => $field->id,
            'first_name' => 'T',
            'last_name' => '1',
            'email' => 't@1.com',
            'phone' => '1',
            'request_date' => now()->toDateString(),
            'start_time' => now()->subHour(),
            'end_time' => now(),
            'price' => 200,
            'status' => 'APPROVED'
        ]);

        $stats = $this->adminService->getDashboardStats();

        $this->assertEquals(2, $stats['total_clients']);
        $this->assertEquals(1, $stats['validated_cnis']);
        $this->assertEquals(1, $stats['pending_cnis']);
        $this->assertEquals(200, $stats['todays_income']);
    }

    public function test_can_verify_cni()
    {
        $tenantUser = User::create(['first_name' => 'T', 'last_name' => 'U', 'email' => 't_cni@t.com', 'password' => '1', 'phone' => '1', 'type' => 'tenant']);
        $tenant = Tenant::create(['user_id' => $tenantUser->id, 'cin' => 'c1', 'birth_date' => '2000-01-01', 'is_cni_valid' => false, 'cni_document_url' => 'fake_url']);

        $this->adminService->verifyCNI($tenant->id, true);
        $this->assertDatabaseHas('tenants', ['id' => $tenant->id, 'is_cni_valid' => 1]);

        $this->adminService->verifyCNI($tenant->id, false);
        $this->assertDatabaseHas('tenants', ['id' => $tenant->id, 'is_cni_valid' => 0, 'cni_document_url' => null]);
    }
}
