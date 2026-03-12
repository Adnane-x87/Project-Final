<?php

namespace App\Services;

use App\Models\User;
use App\Models\Field;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Collection;

class AdminService
{
    /**
     * Get all users.
     *
     * @return Collection
     */
    public function getAllUsers(): Collection
    {
        return User::all();
    }

    /**
     * Delete a user by ID.
     *
     * @param int $userId
     * @return bool
     */
    public function deleteUser(int $userId): bool
    {
        $user = User::findOrFail($userId);
        return $user->delete();
    }

    /**
     * Get all fields with their owners.
     *
     * @return Collection
     */
    public function getAllFields(): Collection
    {
        return Field::with('owner.user')->get();
    }

    /**
     * Delete a field by ID.
     *
     * @param int $fieldId
     * @return bool
     */
    public function deleteField(int $fieldId): bool
    {
        $field = Field::findOrFail($fieldId);
        return $field->delete();
    }

    /**
     * Get all reservations with related details.
     *
     * @return Collection
     */
    public function getAllReservations(): Collection
    {
        return Reservation::with(['tenant.user', 'employee.user', 'field'])->get();
    }

    /**
     * Cancel a specific reservation.
     *
     * @param int $reservationId
     * @return bool
     */
    public function cancelReservation(int $reservationId): bool
    {
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->update(['status' => 'CANCELED']);
        return true;
    }

    /**
     * Reject a specific reservation.
     *
     * @param int $reservationId
     * @return bool
     */
    public function rejectReservation(int $reservationId): bool
    {
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->update(['status' => 'REJECTED']);
        return true;
    }

    /**
     * Get dashboard statistics.
     *
     * @return array
     */
    public function getDashboardStats(): array
    {
        return [
            'total_clients' => \App\Models\Tenant::count(),
            'active_users' => \App\Models\Reservation::whereDate('request_date', '>=', now()->subDays(30))->distinct('tenant_id')->count('tenant_id'),
            'validated_cnis' => \App\Models\Tenant::where('is_cni_valid', true)->count(),
            'pending_cnis' => \App\Models\Tenant::whereNotNull('cni_document_url')->where('is_cni_valid', false)->count(),
            'todays_income' => \App\Models\Reservation::whereDate('start_time', now()->toDateString())
                ->where('status', 'APPROVED')
                ->sum('price')
        ];
    }

    /**
     * Verify a tenant's CNI.
     *
     * @param int $tenantId
     * @param bool $isApproved
     * @return bool
     */
    public function verifyCNI(int $tenantId, bool $isApproved): bool
    {
        $tenant = \App\Models\Tenant::findOrFail($tenantId);
        $tenant->update(['is_cni_valid' => $isApproved]);
        
        // Optionally, if rejected, clean up the URL
        if (!$isApproved) {
            $tenant->update(['cni_document_url' => null]);
        }
        
        return true;
    }

    /**
     * Explicitly validate a reservation.
     *
     * @param int $reservationId
     * @return bool
     */
    public function validateReservation(int $reservationId): bool
    {
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->update(['status' => 'APPROVED']);
        return true;
    }

    /**
     * Get all tenants pending CNI validation.
     *
     * @return Collection
     */
    public function getPendingCNIs(): Collection
    {
        return \App\Models\Tenant::with('user')
            ->whereNotNull('cni_document_url')
            ->where('is_cni_valid', false)
            ->get();
    }
}
