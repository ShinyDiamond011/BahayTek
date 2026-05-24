<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\Staff;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ConsultationSeeder extends Seeder
{
    public function run(): void
    {
        $staff = Staff::first() ?? Staff::create([
            'first_name' => 'Admin',
            'last_name'  => 'BahayTek',
            'username'   => 'admin',
            'email'      => 'admin@bahaytek.com',
            'password'   => Hash::make('password'),
            'role'       => 'superadmin',
            'status'     => 'active',
        ]);

        // Time slots offered per day
        $timeSlots = [
            ['start' => '09:00', 'end' => '10:00'],
            ['start' => '10:00', 'end' => '11:00'],
            ['start' => '13:00', 'end' => '14:00'],
            ['start' => '14:00', 'end' => '15:00'],
            ['start' => '15:00', 'end' => '16:00'],
        ];

        // Consultation types rotated across days for variety
        $types = [
            'research_consultancy',
            'product_development',
            'general_consultancy',
            'any',
        ];

        // Generate slots for the next 5 weeks (weekdays only)
        $start = now()->addDay()->startOfDay();
        $end   = now()->addWeeks(5)->endOfDay();

        $current   = $start->copy();
        $typeIndex = 0;

        while ($current->lte($end)) {
            // Skip weekends
            if ($current->isWeekend()) {
                $current->addDay();
                continue;
            }

            foreach ($timeSlots as $slot) {
                Schedule::create([
                    'date'       => $current->toDateString(),
                    'start_time' => $slot['start'],
                    'end_time'   => $slot['end'],
                    'type'       => $types[$typeIndex % count($types)],
                    'status'     => 'available',
                    'created_by' => $staff->id,
                ]);

                $typeIndex++;
            }

            $current->addDay();
        }
    }
}
