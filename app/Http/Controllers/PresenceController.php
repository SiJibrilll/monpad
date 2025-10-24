<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePresenceRequest;
use App\Http\Resources\PresenceResource;
use App\Models\Group;
use App\Models\Presence;
use App\Models\WeekType;
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Group $group, WeekType $weekType)
    {
        
        $users = $group->members()->pluck('user_id'); // only get user IDs
        $existingUserIds = Presence::where('group_id', $group->id)
            ->where('week_type_id', $weekType->id)
            ->pluck('user_id');

        // Figure out which users don't have presence records yet
        $missingUserIds = $users->diff($existingUserIds);

        // If there are missing ones, bulk insert them
        if ($missingUserIds->isNotEmpty()) {
            $toInsert = $missingUserIds->map(fn ($userId) => [
                'user_id' => $userId,
                'group_id' => $group->id,
                'week_type_id' => $weekType->id,
                'present' => false,
                'date' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ])->toArray();

            Presence::insert($toInsert);
        }

        // Load all presence data (complete and consistent)
        $presences = Presence::where('group_id', $group->id)
            ->where('week_type_id', $weekType->id)
            ->whereHas('mahasiswa.mahasiswa_data')
            ->with('mahasiswa.mahasiswa_data')
            ->get();

        return PresenceResource::collection($presences);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePresenceRequest $request, Group $group, WeekType $weekType)
    {
        $validated = $request->validated();

        foreach ($validated['presences'] as $presence) {
            Presence::find($presence['presence_id'])->update(['present' => $presence['present']]);
        }

        $presences = Presence::where('group_id', $group->id)
            ->where('week_type_id', $weekType->id)
            ->whereHas('mahasiswa.mahasiswa_data')
            ->with('mahasiswa.mahasiswa_data')
            ->get();
        
        return PresenceResource::collection($presences);
    }
}
