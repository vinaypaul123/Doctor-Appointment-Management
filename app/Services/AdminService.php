<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Task;
use App\Models\User;
use App\Enums\TaskStatusEnum;
use App\Events\TaskCompleteEvent;
use App\Jobs\SendTaskAssignedNotification;
use App\Models\Doctor;
use App\Repositories\AdminRepository;

class AdminService extends AdminRepository
{
public function createAvailability(Doctor $doctor,array $data)
{
    return $doctor->availabilities()->create($data);
}

public function createSlots($availability,$data)
{
    return  $availability->timeSlots()->create($data);
}

public function updateAvailability(Doctor $doctor,$day,$data)
{
    $status = isset($data['status']) && $data['status'] === 'working' ? 'working' : 'leave';

    // Get availability for the day or create if not exists
    $availability = $doctor->availabilities()->firstOrNew(['day' => $day]);

    // Update the status
    $availability->status = $status;
    $availability->save();

    if ( isset($data['slots']) && is_array($data['slots'])) {
        $existingSlots = $availability->timeSlots->keyBy('id');

        foreach ($data['slots'] as $index => $slot) {
            if (isset($slot['slot_id'])) {
                // Update existing slot
                $timeSlot = $existingSlots->get($slot['slot_id']);
                if ($timeSlot) {
                    $timeSlot->update([
                        'start_time' => $slot['start'],
                        'end_time' => $slot['end'],
                    ]);
                    $existingSlots->forget($slot['slot_id']);
                }
            } else {
                // Create new slot
                if (!empty($slot['start']) && !empty($slot['end'])) {
                    $availability->timeSlots()->create([
                        'start_time' => $slot['start'],
                        'end_time' => $slot['end'],
                    ]);
                }
            }
        }

        // Delete removed slots
        foreach ($existingSlots as $slot) {
            $slot->delete();
        }
    } else {
        // Leave day — remove all existing slots
        $availability->timeSlots()->delete();
    }
    return true;
}

public function createAvailabilitytest(Doctor $doctor,$day,$data)
{

        // Determine status: default to 'leave' if not explicitly set to 'working'
        $status = isset($data['status']) && $data['status'] === 'working' ? 'working' : 'leave';
        // Create availability for the day
        $data1=[
            'day' => $day,
            'status' => $status,
        ];

        $availability=$this->createAvailability($doctor,$data1);

        // If working, process time slots
        if (isset($data['slots']) && is_array($data['slots'])) {
            foreach ($data['slots'] as $slot) {
                if (!empty($slot['start']) && !empty($slot['end'])) {
                    $data=[
                        'start_time' => $slot['start'],
                        'end_time' => $slot['end'],
                    ];
                    $this->createSlots($availability,$data);
                }
            }
        }
    }
}

