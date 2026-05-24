<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\TrainingSession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WorkshopSeeder extends Seeder
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

        $sessions = [
            // ── Completed (past) ─────────────────────────────────────────────
            [
                'title'                 => 'Introduction to Renewable Energy Technologies',
                'description'           => "A foundational half-day seminar covering the basics of solar, biogas, and wind energy. Participants learned how these technologies can be applied in rural Bicol households and small farms. Hands-on demonstrations included a working solar panel setup and a small biogas digester model.\n\nTopics covered:\n• Solar panel types and efficiency comparison\n• Biogas production from farm and kitchen waste\n• Cost-benefit analysis for rural electrification\n• Q&A with BAHAYTEK engineers",
                'session_datetime'      => '2026-04-26 08:00:00',
                'registration_deadline' => '2026-04-23 23:59:00',
                'max_participants'      => 40,
                'venue'                 => 'BAHAYTEK Training Center, Legazpi City, Albay',
                'status'               => 'completed',
                'fee'                  => 0.00,
            ],
            [
                'title'                 => 'Solar-Powered Irrigation for Small Farms',
                'description'           => "A full-day practical workshop on designing and installing solar-powered water pumping systems for agricultural use. Participants brought their farm dimensions and water source data for a customized system sizing exercise.\n\nHighlights:\n• Pump selection based on head pressure and flow rate\n• Panel sizing calculation using the BAHAYTEK worksheet tool\n• Wiring and safety practices\n• Live installation demo on a model farm plot",
                'session_datetime'      => '2026-05-10 07:30:00',
                'registration_deadline' => '2026-05-07 23:59:00',
                'max_participants'      => 25,
                'venue'               => 'BAHAYTEK Demonstration Farm, Daraga, Albay',
                'status'              => 'completed',
                'fee'                 => 150.00,
            ],
            [
                'title'                 => 'Vermicomposting & Organic Fertilizer Production',
                'description'           => "This workshop trained participants in setting up and managing a vermicomposting bed using locally available organic waste. Attendees took home a starter kit of African Night Crawler earthworms and a 2 kg bag of finished vermicast.\n\nCovered topics:\n• Worm bed construction using recycled materials\n• Feeding ratios and moisture management\n• Harvesting and processing vermicast\n• Marketing options for excess vermicast",
                'session_datetime'      => '2026-05-17 08:00:00',
                'registration_deadline' => '2026-05-14 23:59:00',
                'max_participants'      => 30,
                'venue'                 => 'BAHAYTEK Training Center, Legazpi City, Albay',
                'status'               => 'completed',
                'fee'                  => 200.00,
            ],

            // ── Open (upcoming, registration active) ─────────────────────────
            [
                'title'                 => 'Solar Panel Installation & Maintenance Workshop',
                'description'           => "A two-day hands-on workshop designed for homeowners, barangay officials, and entrepreneurs who want to install and maintain their own solar power systems. No electrical background required — BAHAYTEK trainers will guide you step by step.\n\nDay 1 – Theory & System Design\n• Understanding solar radiation and panel orientation\n• Off-grid vs. grid-tied systems\n• Load calculation and battery sizing\n• Safe electrical practices and Philippine PESO standards\n\nDay 2 – Practical Installation\n• Mounting panel frames on rooftops and ground arrays\n• Charge controller and inverter wiring\n• Battery bank setup and safety\n• System testing and commissioning\n\nIncludes: certificate of participation, BAHAYTEK installation manual, and a complimentary 10-year maintenance checklist.",
                'session_datetime'      => '2026-06-14 07:30:00',
                'registration_deadline' => '2026-06-10 23:59:00',
                'max_participants'      => 20,
                'venue'                 => 'BAHAYTEK Training Center, Legazpi City, Albay',
                'status'               => 'open',
                'fee'                  => 500.00,
            ],
            [
                'title'                 => 'Biogas Technology for Household & Farm Use',
                'description'           => "Learn how to turn kitchen scraps, animal manure, and crop residues into clean cooking gas. This full-day workshop covers the science behind anaerobic digestion and walks you through building and operating a small-scale biogas digester.\n\nWhat you will learn:\n• Feedstock selection (pig, cow, chicken manure; kitchen waste)\n• Digester sizing for household vs. farm scale\n• Gas yield estimation and storage\n• Effluent (bio-slurry) management as liquid fertilizer\n• Troubleshooting common issues\n\nParticipants will assemble a working tabletop digester model during the session. Each household group may apply to BAHAYTEK's subsidized digester installation program after completing the workshop.",
                'session_datetime'      => '2026-06-21 08:00:00',
                'registration_deadline' => '2026-06-17 23:59:00',
                'max_participants'      => 30,
                'venue'                 => 'BAHAYTEK Demonstration Farm, Daraga, Albay',
                'status'               => 'open',
                'fee'                  => 300.00,
            ],
            [
                'title'                 => 'Rainwater Harvesting System Design & Setup',
                'description'           => "Water scarcity is a growing concern in many Bicol communities, especially during the dry season. This workshop equips participants with the knowledge to design, build, and maintain a rainwater harvesting system suited to their home or farm.\n\nTopics:\n• Estimating catchment area and runoff volume\n• Tank sizing for household and agricultural demand\n• First-flush diverters and sediment filters\n• Water quality testing and basic treatment\n• Integration with existing water supply systems\n• Low-cost alternative materials for rural settings\n\nAfternoon session includes a site visit to an operational 5,000-liter rooftop collection system installed by BAHAYTEK.",
                'session_datetime'      => '2026-07-05 08:00:00',
                'registration_deadline' => '2026-07-01 23:59:00',
                'max_participants'      => 25,
                'venue'                 => 'BAHAYTEK Training Center, Legazpi City, Albay',
                'status'               => 'open',
                'fee'                  => 250.00,
            ],

            // ── Coming Soon ───────────────────────────────────────────────────
            [
                'title'                 => 'Integrated Sustainable Farm Management',
                'description'           => "A two-day immersive workshop combining solar energy, water management, organic farming, and biogas technology into one integrated farm design. Ideal for farmers and agri-entrepreneurs who want to transition to a fully sustainable production model.\n\nDay 1 – Planning & Design\n• Permaculture principles and zone design\n• Energy audit and solar system sizing for the farm\n• Water harvesting and gravity-fed irrigation layout\n• Biogas integration with the composting cycle\n\nDay 2 – Implementation & Monitoring\n• Hands-on work at BAHAYTEK demo farm\n• Record-keeping and cost-tracking for smallholder farms\n• Linking to government agri-support programs (DA, DOST)\n• Participant farm planning with BAHAYTEK technicians\n\nRegistration opens June 15, 2026.",
                'session_datetime'      => '2026-07-19 07:00:00',
                'registration_deadline' => '2026-07-15 23:59:00',
                'max_participants'      => 20,
                'venue'                 => 'BAHAYTEK Demonstration Farm, Daraga, Albay',
                'status'               => 'coming_soon',
                'fee'                  => 750.00,
            ],
            [
                'title'                 => 'Community Solar Cooperative — Organizer Training',
                'description'           => "Designed for barangay leaders, cooperative officers, and community development workers, this workshop walks participants through the process of establishing a community-owned solar cooperative under the Philippine Cooperative Code and NERC regulations.\n\nKey sessions:\n• Legal framework: Cooperative Code, ERC regulations, net metering\n• Feasibility study preparation for a community solar project\n• Financing options: PCFC, OPIC, RE Fund, and concessional loans\n• Member education and buy-in strategies\n• Operation and maintenance governance\n\nParticipants will leave with a draft community solar cooperative proposal template ready for submission to their municipal LGU.\n\nRegistration opens July 1, 2026.",
                'session_datetime'      => '2026-08-08 08:00:00',
                'registration_deadline' => '2026-08-04 23:59:00',
                'max_participants'      => 35,
                'venue'                 => 'BAHAYTEK Training Center, Legazpi City, Albay',
                'status'               => 'coming_soon',
                'fee'                  => 400.00,
            ],
        ];

        foreach ($sessions as $data) {
            TrainingSession::create(array_merge($data, ['created_by' => $staff->id]));
        }
    }
}
