<?php

namespace App\Policies;

use App\Models\Trip;
use App\Models\User;

class TripPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) {
            return true;
        }
        
        return null;
    }

    public function view(User $user, Trip $trip): bool
    {
        // Trip owner can view
        if ($user->id === $trip->user_id) {
            return true;
        }

        // Trip participants can view
        if ($trip->participants()->where('users.id', $user->id)->exists()) {
            return true;
        }

        // Public trips can be viewed by anyone
        return $trip->is_public;
    }

    public function create(User $user): bool
    {
        // Any authenticated user can create trips
        return true;
    }

    public function update(User $user, Trip $trip): bool
    {
        // Trip owner can update
        if ($user->id === $trip->user_id) {
            return true;
        }

        // Participants with edit permission can update
        return $trip->participants()
            ->where('users.id', $user->id)
            ->where('can_edit', true)
            ->exists();
    }

    public function delete(User $user, Trip $trip): bool
    {
        // Only trip owner can delete
        return $user->id === $trip->user_id;
    }

    public function addParticipant(User $user, Trip $trip): bool
    {
        // Only trip owner can add participants
        return $user->id === $trip->user_id;
    }

    public function removeParticipant(User $user, Trip $trip): bool
    {
        // Only trip owner can remove participants
        return $user->id === $trip->user_id;
    }
} 