<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\Dealer;
use App\Models\PropertyRequest;
use App\Models\RequestAttachment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $dialA = User::where('role', 'dial_a')->first();
        if (!$dialA) {
            $dialA = User::updateOrCreate(
                ['email' => 'dial.handyman@gateway.ph'],
                [
                    'name' => 'Dial-A',
                    'designation' => 'Dial-A / Property Management Support',
                    'role' => 'dial_a',
                    'is_active' => true,
                    'password' => bcrypt('Gateway@2026'),
                ]
            );
        }

        $manager = User::where('role', 'pm_manager')->first();

        // Prepare demo files in storage for attachments
        $demoIssuePath = 'request-attachments/demo-issue.png';
        $demoInspectionPath = 'request-attachments/inspection/demo-inspection.png';
        $demoWoPath = 'request-attachments/work_order/demo-wo.png';
        $demoSrPath = 'request-attachments/service_report/demo-sr.png';

        $sampleImgPath = public_path('images/gateway-logo.png');
        $imgContents = file_exists($sampleImgPath) ? file_get_contents($sampleImgPath) : 'SAMPLE_PNG_DATA';

        Storage::put($demoIssuePath, $imgContents);
        Storage::put($demoInspectionPath, $imgContents);
        Storage::put($demoWoPath, $imgContents);
        Storage::put($demoSrPath, $imgContents);

        $imgSize = file_exists($sampleImgPath) ? filesize($sampleImgPath) : 1024;

        // Clean up previous request records so total is exactly 30
        DB::transaction(function () {
            RequestAttachment::query()->delete();
            DatabaseNotification::whereNotNull('property_request_id')->delete();
            AuditLog::where('subject_type', 'PropertyRequest')->orWhere('action', 'like', 'request%')->delete();
            PropertyRequest::query()->delete();
        });

        // Exactly 30 requests covering all activities and progress stages
        $items = [
            // ==========================================
            // GROUP 1: FOR ACKNOWLEDGEMENT (4 entries)
            // ==========================================
            [
                'reference_no' => 'GPM-20260916-0001',
                'source_no' => 1,
                'category' => 'Electrical Works',
                'priority' => 'regular',
                'description' => 'Workshop main distribution panel breaker trips intermittently during peak operating hours. Needs thermal scanning and electrical inspection.',
                'request_date' => '2026-09-16',
                'due_date' => '2026-09-20',
                'status' => 'pending',
                'inspection_date' => null,
                'inspection_start_time' => null,
                'inspection_end_date' => null,
                'inspection_end_time' => null,
                'reps' => [],
                'inspection_completed_at' => null,
                'wo_start_date' => null,
                'wo_start_time' => null,
                'wo_end_date' => null,
                'wo_end_time' => null,
                'wo_reps' => null,
                'wo_completed_at' => null,
                'sr_date' => null,
                'sr_completed_at' => null,
                'completion_notified_at' => null,
                'completed_at' => null,
                'acknowledged' => false,
                'attach_issue' => true,
            ],
            [
                'reference_no' => 'GPM-20260916-0002',
                'source_no' => 5,
                'category' => 'Plumbing Works',
                'priority' => 'urgent',
                'description' => 'High-pressure underground pipe burst in wash bay causing localized flooding and water supply interruption to customer restrooms.',
                'request_date' => '2026-09-16',
                'due_date' => '2026-09-18',
                'status' => 'pending',
                'inspection_date' => null,
                'inspection_start_time' => null,
                'inspection_end_date' => null,
                'inspection_end_time' => null,
                'reps' => [],
                'inspection_completed_at' => null,
                'wo_start_date' => null,
                'wo_start_time' => null,
                'wo_end_date' => null,
                'wo_end_time' => null,
                'wo_reps' => null,
                'wo_completed_at' => null,
                'sr_date' => null,
                'sr_completed_at' => null,
                'completion_notified_at' => null,
                'completed_at' => null,
                'acknowledged' => false,
                'attach_issue' => true,
            ],
            [
                'reference_no' => 'GPM-20260917-0001',
                'source_no' => 13,
                'category' => 'Airconditioning',
                'priority' => 'urgent',
                'description' => 'Server room precision air-conditioning unit flashing error code E4, room ambient temperature rising to 32°C. Critical IT equipment at risk.',
                'request_date' => '2026-09-17',
                'due_date' => '2026-09-19',
                'status' => 'pending',
                'inspection_date' => null,
                'inspection_start_time' => null,
                'inspection_end_date' => null,
                'inspection_end_time' => null,
                'reps' => [],
                'inspection_completed_at' => null,
                'wo_start_date' => null,
                'wo_start_time' => null,
                'wo_end_date' => null,
                'wo_end_time' => null,
                'wo_reps' => null,
                'wo_completed_at' => null,
                'sr_date' => null,
                'sr_completed_at' => null,
                'completion_notified_at' => null,
                'completed_at' => null,
                'acknowledged' => false,
                'attach_issue' => false,
            ],
            [
                'reference_no' => 'GPM-20260917-0002',
                'source_no' => 69,
                'category' => 'General Repairs',
                'priority' => 'regular',
                'description' => 'Showroom main entrance automatic sliding glass door track jammed and sticking midway. Safety hazard for walk-in clients.',
                'request_date' => '2026-09-17',
                'due_date' => '2026-09-21',
                'status' => 'pending',
                'inspection_date' => null,
                'inspection_start_time' => null,
                'inspection_end_date' => null,
                'inspection_end_time' => null,
                'reps' => [],
                'inspection_completed_at' => null,
                'wo_start_date' => null,
                'wo_start_time' => null,
                'wo_end_date' => null,
                'wo_end_time' => null,
                'wo_reps' => null,
                'wo_completed_at' => null,
                'sr_date' => null,
                'sr_completed_at' => null,
                'completion_notified_at' => null,
                'completed_at' => null,
                'acknowledged' => false,
                'attach_issue' => false,
            ],

            // ==========================================
            // GROUP 2: INSPECTION PENDING (Acknowledged) (4 entries)
            // ==========================================
            [
                'reference_no' => 'GPM-20260915-0001',
                'source_no' => 1,
                'category' => 'Painting',
                'priority' => 'regular',
                'description' => 'Service reception wall requires repainting and surface smoothing due to previous water seep marks.',
                'request_date' => '2026-09-15',
                'due_date' => '2026-09-19',
                'status' => 'pending',
                'inspection_date' => null,
                'inspection_start_time' => null,
                'inspection_end_date' => null,
                'inspection_end_time' => null,
                'reps' => [],
                'inspection_completed_at' => null,
                'wo_start_date' => null,
                'wo_start_time' => null,
                'wo_end_date' => null,
                'wo_end_time' => null,
                'wo_reps' => null,
                'wo_completed_at' => null,
                'sr_date' => null,
                'sr_completed_at' => null,
                'completion_notified_at' => null,
                'completed_at' => null,
                'acknowledged' => true,
                'attach_issue' => true,
            ],
            [
                'reference_no' => 'GPM-20260915-0002',
                'source_no' => 42,
                'category' => 'Carpentry & Woodworks',
                'priority' => 'regular',
                'description' => 'Customer lounge credenza and beverage station cabinet hinges detached and melamine edging damaged.',
                'request_date' => '2026-09-15',
                'due_date' => '2026-09-19',
                'status' => 'pending',
                'inspection_date' => null,
                'inspection_start_time' => null,
                'inspection_end_date' => null,
                'inspection_end_time' => null,
                'reps' => [],
                'inspection_completed_at' => null,
                'wo_start_date' => null,
                'wo_start_time' => null,
                'wo_end_date' => null,
                'wo_end_time' => null,
                'wo_reps' => null,
                'wo_completed_at' => null,
                'sr_date' => null,
                'sr_completed_at' => null,
                'completion_notified_at' => null,
                'completed_at' => null,
                'acknowledged' => true,
                'attach_issue' => false,
            ],
            [
                'reference_no' => 'GPM-20260916-0003',
                'source_no' => 7,
                'category' => 'Electrical Works',
                'priority' => 'urgent',
                'description' => 'Service bay 3 diagnostic heavy power outlet sparking when vehicle scanner plugged in.',
                'request_date' => '2026-09-16',
                'due_date' => '2026-09-18',
                'status' => 'pending',
                'inspection_date' => null,
                'inspection_start_time' => null,
                'inspection_end_date' => null,
                'inspection_end_time' => null,
                'reps' => [],
                'inspection_completed_at' => null,
                'wo_start_date' => null,
                'wo_start_time' => null,
                'wo_end_date' => null,
                'wo_end_time' => null,
                'wo_reps' => null,
                'wo_completed_at' => null,
                'sr_date' => null,
                'sr_completed_at' => null,
                'completion_notified_at' => null,
                'completed_at' => null,
                'acknowledged' => true,
                'attach_issue' => true,
            ],
            [
                'reference_no' => 'GPM-20260916-0004',
                'source_no' => 45,
                'category' => 'General Repairs',
                'priority' => 'regular',
                'description' => 'Warehouse roof gutter seam disconnected, rainwater overflowing directly into parts receiving staging area.',
                'request_date' => '2026-09-16',
                'due_date' => '2026-09-20',
                'status' => 'pending',
                'inspection_date' => null,
                'inspection_start_time' => null,
                'inspection_end_date' => null,
                'inspection_end_time' => null,
                'reps' => [],
                'inspection_completed_at' => null,
                'wo_start_date' => null,
                'wo_start_time' => null,
                'wo_end_date' => null,
                'wo_end_time' => null,
                'wo_reps' => null,
                'wo_completed_at' => null,
                'sr_date' => null,
                'sr_completed_at' => null,
                'completion_notified_at' => null,
                'completed_at' => null,
                'acknowledged' => true,
                'attach_issue' => false,
            ],

            // ==========================================
            // GROUP 3: INSPECTION ON-GOING (4 entries: 2 recent, 2 aging)
            // ==========================================
            [
                'reference_no' => 'GPM-20260915-0003',
                'source_no' => 10,
                'category' => 'Airconditioning',
                'priority' => 'regular',
                'description' => 'Service manager office 2.5HP split-type aircon blowing ambient room temperature air. Scheduled for diagnostic inspection.',
                'request_date' => '2026-09-15',
                'due_date' => '2026-09-19',
                'status' => 'on_going',
                'inspection_date' => '2026-09-16',
                'inspection_start_time' => '09:30:00',
                'inspection_end_date' => '2026-09-16',
                'inspection_end_time' => null,
                'reps' => ['Engr. Carlos Mendoza', 'Dial-A'],
                'inspection_completed_at' => null,
                'wo_start_date' => null,
                'wo_start_time' => null,
                'wo_end_date' => null,
                'wo_end_time' => null,
                'wo_reps' => null,
                'wo_completed_at' => null,
                'sr_date' => null,
                'sr_completed_at' => null,
                'completion_notified_at' => null,
                'completed_at' => null,
                'acknowledged' => true,
                'attach_issue' => true,
            ],
            [
                'reference_no' => 'GPM-20260916-0005',
                'source_no' => 25,
                'category' => 'Plumbing Works',
                'priority' => 'regular',
                'description' => 'Ground floor customer restroom flush valve handles stuck open, continuous water draining into bowl.',
                'request_date' => '2026-09-16',
                'due_date' => '2026-09-20',
                'status' => 'on_going',
                'inspection_date' => '2026-09-17',
                'inspection_start_time' => '10:00:00',
                'inspection_end_date' => '2026-09-17',
                'inspection_end_time' => null,
                'reps' => ['Ronaldo Santos', 'Dial-A'],
                'inspection_completed_at' => null,
                'wo_start_date' => null,
                'wo_start_time' => null,
                'wo_end_date' => null,
                'wo_end_time' => null,
                'wo_reps' => null,
                'wo_completed_at' => null,
                'sr_date' => null,
                'sr_completed_at' => null,
                'completion_notified_at' => null,
                'completed_at' => null,
                'acknowledged' => true,
                'attach_issue' => false,
            ],
            // 2 Aging (<= 2026-09-12)
            [
                'reference_no' => 'GPM-20260908-0001',
                'source_no' => 2,
                'category' => 'General Repairs',
                'priority' => 'regular',
                'description' => 'Heavy duty roll-up shutter door on delivery bay jammed at 45 degree angle. Motor chain guide misaligned.',
                'request_date' => '2026-09-08',
                'due_date' => '2026-09-12',
                'status' => 'on_going',
                'inspection_date' => '2026-09-10',
                'inspection_start_time' => '11:00:00',
                'inspection_end_date' => '2026-09-10',
                'inspection_end_time' => null,
                'reps' => ['Dial-A', 'Arturo Reyes'],
                'inspection_completed_at' => null,
                'wo_start_date' => null,
                'wo_start_time' => null,
                'wo_end_date' => null,
                'wo_end_time' => null,
                'wo_reps' => null,
                'wo_completed_at' => null,
                'sr_date' => null,
                'sr_completed_at' => null,
                'completion_notified_at' => null,
                'completed_at' => null,
                'acknowledged' => true,
                'attach_issue' => true,
            ],
            [
                'reference_no' => 'GPM-20260909-0001',
                'source_no' => 58,
                'category' => 'Carpentry & Woodworks',
                'priority' => 'regular',
                'description' => 'Drywall partition between customer lounge and sales manager cubicle damaged by water and localized pest infestation.',
                'request_date' => '2026-09-09',
                'due_date' => '2026-09-13',
                'status' => 'on_going',
                'inspection_date' => '2026-09-11',
                'inspection_start_time' => '14:00:00',
                'inspection_end_date' => '2026-09-11',
                'inspection_end_time' => null,
                'reps' => ['Dial-A', 'Felipe Dizon'],
                'inspection_completed_at' => null,
                'wo_start_date' => null,
                'wo_start_time' => null,
                'wo_end_date' => null,
                'wo_end_time' => null,
                'wo_reps' => null,
                'wo_completed_at' => null,
                'sr_date' => null,
                'sr_completed_at' => null,
                'completion_notified_at' => null,
                'completed_at' => null,
                'acknowledged' => true,
                'attach_issue' => false,
            ],

            // ==========================================
            // GROUP 4: WORK ORDER ON-GOING (5 entries: 3 recent, 2 aging)
            // ==========================================
            [
                'reference_no' => 'GPM-20260914-0001',
                'source_no' => 1,
                'category' => 'Electrical Works',
                'priority' => 'urgent',
                'description' => 'Rewiring and circuit breaker installation for newly acquired detailing machine in vehicle preparation area.',
                'request_date' => '2026-09-14',
                'due_date' => '2026-09-18',
                'status' => 'on_going',
                'inspection_date' => '2026-09-15',
                'inspection_start_time' => '09:00:00',
                'inspection_end_date' => '2026-09-15',
                'inspection_end_time' => '11:30:00',
                'reps' => ['Engr. Carlos Mendoza', 'Dial-A'],
                'inspection_completed_at' => '2026-09-15 11:30:00',
                'wo_start_date' => '2026-09-16',
                'wo_start_time' => '08:30:00',
                'wo_end_date' => null,
                'wo_end_time' => null,
                'wo_reps' => ['Mark Bautista', 'Dial-A'],
                'wo_completed_at' => null,
                'sr_date' => null,
                'sr_completed_at' => null,
                'completion_notified_at' => null,
                'completed_at' => null,
                'acknowledged' => true,
                'attach_issue' => true,
                'attach_inspection' => true,
            ],
            [
                'reference_no' => 'GPM-20260914-0002',
                'source_no' => 14,
                'category' => 'Airconditioning',
                'priority' => 'regular',
                'description' => 'Replacement of AC condenser fan motor and support bracket on rooftop floor-mounted unit.',
                'request_date' => '2026-09-14',
                'due_date' => '2026-09-18',
                'status' => 'on_going',
                'inspection_date' => '2026-09-15',
                'inspection_start_time' => '11:00:00',
                'inspection_end_date' => '2026-09-15',
                'inspection_end_time' => '14:00:00',
                'reps' => ['Dial-A'],
                'inspection_completed_at' => '2026-09-15 14:00:00',
                'wo_start_date' => '2026-09-16',
                'wo_start_time' => '10:00:00',
                'wo_end_date' => null,
                'wo_end_time' => null,
                'wo_reps' => ['Vicente Ramos', 'Dial-A'],
                'wo_completed_at' => null,
                'sr_date' => null,
                'sr_completed_at' => null,
                'completion_notified_at' => null,
                'completed_at' => null,
                'acknowledged' => true,
                'attach_issue' => true,
                'attach_inspection' => true,
            ],
            [
                'reference_no' => 'GPM-20260915-0004',
                'source_no' => 63,
                'category' => 'Plumbing Works',
                'priority' => 'regular',
                'description' => 'Main sewer cleanout declogging, hydro-jetting, and grease trap baffle repair behind customer pantry.',
                'request_date' => '2026-09-15',
                'due_date' => '2026-09-19',
                'status' => 'on_going',
                'inspection_date' => '2026-09-16',
                'inspection_start_time' => '08:30:00',
                'inspection_end_date' => '2026-09-16',
                'inspection_end_time' => '09:15:00',
                'reps' => ['Dial-A', 'Gilbert Tan'],
                'inspection_completed_at' => '2026-09-16 09:15:00',
                'wo_start_date' => '2026-09-17',
                'wo_start_time' => '08:00:00',
                'wo_end_date' => null,
                'wo_end_time' => null,
                'wo_reps' => ['Dial-A', 'Gilbert Tan'],
                'wo_completed_at' => null,
                'sr_date' => null,
                'sr_completed_at' => null,
                'completion_notified_at' => null,
                'completed_at' => null,
                'acknowledged' => true,
                'attach_issue' => false,
                'attach_inspection' => true,
            ],
            // 2 Aging (<= 2026-09-12)
            [
                'reference_no' => 'GPM-20260905-0001',
                'source_no' => 67,
                'category' => 'General Repairs',
                'priority' => 'regular',
                'description' => 'Vehicle service hydraulic two-post lift cylinder seals leaking hydraulic fluid under load. Safety inspection and seal kit overhaul.',
                'request_date' => '2026-09-05',
                'due_date' => '2026-09-09',
                'status' => 'on_going',
                'inspection_date' => '2026-09-07',
                'inspection_start_time' => '09:00:00',
                'inspection_end_date' => '2026-09-07',
                'inspection_end_time' => '10:00:00',
                'reps' => ['Dial-A', 'Dante Gomez'],
                'inspection_completed_at' => '2026-09-07 10:00:00',
                'wo_start_date' => '2026-09-08',
                'wo_start_time' => '09:00:00',
                'wo_end_date' => null,
                'wo_end_time' => null,
                'wo_reps' => ['Dial-A', 'Dante Gomez'],
                'wo_completed_at' => null,
                'sr_date' => null,
                'sr_completed_at' => null,
                'completion_notified_at' => null,
                'completed_at' => null,
                'acknowledged' => true,
                'attach_issue' => true,
                'attach_inspection' => true,
            ],
            [
                'reference_no' => 'GPM-20260907-0001',
                'source_no' => 51,
                'category' => 'Carpentry & Woodworks',
                'priority' => 'regular',
                'description' => 'Replacement and reinforcement of customer transaction consultation booths with heavy duty laminate surfacing.',
                'request_date' => '2026-09-07',
                'due_date' => '2026-09-11',
                'status' => 'on_going',
                'inspection_date' => '2026-09-09',
                'inspection_start_time' => '10:00:00',
                'inspection_end_date' => '2026-09-09',
                'inspection_end_time' => '11:00:00',
                'reps' => ['Dial-A', 'Manuel Santos'],
                'inspection_completed_at' => '2026-09-09 11:00:00',
                'wo_start_date' => '2026-09-10',
                'wo_start_time' => '10:30:00',
                'wo_end_date' => null,
                'wo_end_time' => null,
                'wo_reps' => ['Dial-A', 'Manuel Santos'],
                'wo_completed_at' => null,
                'sr_date' => null,
                'sr_completed_at' => null,
                'completion_notified_at' => null,
                'completed_at' => null,
                'acknowledged' => true,
                'attach_issue' => false,
                'attach_inspection' => true,
            ],

            // ==========================================
            // GROUP 5: SERVICE REPORT ON-GOING (5 entries: 3 recent, 2 aging)
            // ==========================================
            [
                'reference_no' => 'GPM-20260914-0003',
                'source_no' => 3,
                'category' => 'Airconditioning',
                'priority' => 'urgent',
                'description' => 'Overhaul blower assembly and install replacement drive belts for 10-ton packaged aircon unit servicing showroom.',
                'request_date' => '2026-09-14',
                'due_date' => '2026-09-18',
                'status' => 'on_going',
                'inspection_date' => '2026-09-14',
                'inspection_start_time' => '13:00:00',
                'inspection_end_date' => '2026-09-14',
                'inspection_end_time' => '15:00:00',
                'reps' => ['Dial-A'],
                'inspection_completed_at' => '2026-09-14 15:00:00',
                'wo_start_date' => '2026-09-15',
                'wo_start_time' => '09:00:00',
                'wo_end_date' => '2026-09-16',
                'wo_end_time' => '16:30:00',
                'wo_reps' => ['Dial-A', 'Dennis Sison'],
                'wo_completed_at' => '2026-09-16 16:30:00',
                'sr_date' => '2026-09-17',
                'sr_completed_at' => null,
                'completion_notified_at' => null,
                'completed_at' => null,
                'acknowledged' => true,
                'attach_issue' => true,
                'attach_inspection' => true,
                'attach_wo' => true,
            ],
            [
                'reference_no' => 'GPM-20260915-0006',
                'source_no' => 59,
                'category' => 'Plumbing Works',
                'priority' => 'regular',
                'description' => 'Replace water line check valve and install pressure relief valve on booster pump manifold.',
                'request_date' => '2026-09-15',
                'due_date' => '2026-09-19',
                'status' => 'on_going',
                'inspection_date' => '2026-09-15',
                'inspection_start_time' => '15:00:00',
                'inspection_end_date' => '2026-09-15',
                'inspection_end_time' => '16:00:00',
                'reps' => ['Dial-A'],
                'inspection_completed_at' => '2026-09-15 16:00:00',
                'wo_start_date' => '2026-09-16',
                'wo_start_time' => '08:30:00',
                'wo_end_date' => '2026-09-16',
                'wo_end_time' => '17:00:00',
                'wo_reps' => ['Dial-A', 'Gilbert Tan'],
                'wo_completed_at' => '2026-09-16 17:00:00',
                'sr_date' => '2026-09-17',
                'sr_completed_at' => null,
                'completion_notified_at' => null,
                'completed_at' => null,
                'acknowledged' => true,
                'attach_issue' => false,
                'attach_inspection' => true,
                'attach_wo' => true,
            ],
            [
                'reference_no' => 'GPM-20260916-0006',
                'source_no' => 70,
                'category' => 'General Repairs',
                'priority' => 'regular',
                'description' => 'Repair perimeter security chainlink fence bottom anchor posts and install razor wire reinforcement.',
                'request_date' => '2026-09-16',
                'due_date' => '2026-09-20',
                'status' => 'on_going',
                'inspection_date' => '2026-09-16',
                'inspection_start_time' => '11:00:00',
                'inspection_end_date' => '2026-09-16',
                'inspection_end_time' => '14:00:00',
                'reps' => ['Dial-A', 'Noel Castro'],
                'inspection_completed_at' => '2026-09-16 14:00:00',
                'wo_start_date' => '2026-09-17',
                'wo_start_time' => '08:00:00',
                'wo_end_date' => '2026-09-17',
                'wo_end_time' => '11:00:00',
                'wo_reps' => ['Dial-A', 'Noel Castro'],
                'wo_completed_at' => '2026-09-17 11:00:00',
                'sr_date' => '2026-09-17',
                'sr_completed_at' => null,
                'completion_notified_at' => null,
                'completed_at' => null,
                'acknowledged' => true,
                'attach_issue' => false,
                'attach_inspection' => true,
                'attach_wo' => true,
            ],
            // 2 Aging (<= 2026-09-12)
            [
                'reference_no' => 'GPM-20260906-0001',
                'source_no' => 4,
                'category' => 'Electrical Works',
                'priority' => 'urgent',
                'description' => 'Generator automatic transfer switch (ATS) auxiliary relay contacts replacement and synchronization test.',
                'request_date' => '2026-09-06',
                'due_date' => '2026-09-10',
                'status' => 'on_going',
                'inspection_date' => '2026-09-07',
                'inspection_start_time' => '11:00:00',
                'inspection_end_date' => '2026-09-07',
                'inspection_end_time' => '14:00:00',
                'reps' => ['Dial-A', 'Ramon Morales'],
                'inspection_completed_at' => '2026-09-07 14:00:00',
                'wo_start_date' => '2026-09-08',
                'wo_start_time' => '09:00:00',
                'wo_end_date' => '2026-09-09',
                'wo_end_time' => '16:00:00',
                'wo_reps' => ['Dial-A', 'Ramon Morales'],
                'wo_completed_at' => '2026-09-09 16:00:00',
                'sr_date' => '2026-09-10',
                'sr_completed_at' => null,
                'completion_notified_at' => null,
                'completed_at' => null,
                'acknowledged' => true,
                'attach_issue' => true,
                'attach_inspection' => true,
                'attach_wo' => true,
            ],
            [
                'reference_no' => 'GPM-20260908-0002',
                'source_no' => 9,
                'category' => 'Painting',
                'priority' => 'regular',
                'description' => 'Apply self-leveling industrial epoxy floor coating and reflective yellow demarcation striping in delivery inspection bay.',
                'request_date' => '2026-09-08',
                'due_date' => '2026-09-12',
                'status' => 'on_going',
                'inspection_date' => '2026-09-09',
                'inspection_start_time' => '13:00:00',
                'inspection_end_date' => '2026-09-09',
                'inspection_end_time' => '15:30:00',
                'reps' => ['Dial-A'],
                'inspection_completed_at' => '2026-09-09 15:30:00',
                'wo_start_date' => '2026-09-10',
                'wo_start_time' => '08:00:00',
                'wo_end_date' => '2026-09-12',
                'wo_end_time' => '17:00:00',
                'wo_reps' => ['Dial-A', 'Joel Santos'],
                'wo_completed_at' => '2026-09-12 17:00:00',
                'sr_date' => '2026-09-13',
                'sr_completed_at' => null,
                'completion_notified_at' => null,
                'completed_at' => null,
                'acknowledged' => true,
                'attach_issue' => false,
                'attach_inspection' => true,
                'attach_wo' => true,
            ],

            // ==========================================
            // GROUP 6: AWAITING DIAL-A CONFIRMATION (3 entries)
            // ==========================================
            [
                'reference_no' => 'GPM-20260913-0001',
                'source_no' => 1,
                'category' => 'Airconditioning',
                'priority' => 'urgent',
                'description' => 'Showroom 15HP ceiling cassette VRF aircon system refrigerant recovery, coil nitrogen pressure test, and R410A recharge.',
                'request_date' => '2026-09-13',
                'due_date' => '2026-09-17',
                'status' => 'on_going',
                'inspection_date' => '2026-09-14',
                'inspection_start_time' => '08:30:00',
                'inspection_end_date' => '2026-09-14',
                'inspection_end_time' => '10:00:00',
                'reps' => ['Engr. Carlos Mendoza', 'Dial-A'],
                'inspection_completed_at' => '2026-09-14 10:00:00',
                'wo_start_date' => '2026-09-14',
                'wo_start_time' => '13:00:00',
                'wo_end_date' => '2026-09-15',
                'wo_end_time' => '15:00:00',
                'wo_reps' => ['Mark Bautista', 'Dial-A'],
                'wo_completed_at' => '2026-09-15 15:00:00',
                'sr_date' => '2026-09-16',
                'sr_completed_at' => '2026-09-16 16:00:00',
                'completion_notified_at' => '2026-09-16 16:05:00',
                'completed_at' => null,
                'acknowledged' => true,
                'attach_issue' => true,
                'attach_inspection' => true,
                'attach_wo' => true,
                'attach_sr' => true,
            ],
            [
                'reference_no' => 'GPM-20260914-0004',
                'source_no' => 60,
                'category' => 'Electrical Works',
                'priority' => 'regular',
                'description' => 'Install dedicated 60A 3-phase electrical submeter, conduit run, and disconnect switch for EV customer display charger.',
                'request_date' => '2026-09-14',
                'due_date' => '2026-09-18',
                'status' => 'on_going',
                'inspection_date' => '2026-09-15',
                'inspection_start_time' => '09:00:00',
                'inspection_end_date' => '2026-09-15',
                'inspection_end_time' => '11:00:00',
                'reps' => ['Dial-A', 'Nestor Ramos'],
                'inspection_completed_at' => '2026-09-15 11:00:00',
                'wo_start_date' => '2026-09-15',
                'wo_start_time' => '13:00:00',
                'wo_end_date' => '2026-09-16',
                'wo_end_time' => '14:30:00',
                'wo_reps' => ['Dial-A', 'Nestor Ramos'],
                'wo_completed_at' => '2026-09-16 14:30:00',
                'sr_date' => '2026-09-17',
                'sr_completed_at' => '2026-09-17 10:00:00',
                'completion_notified_at' => '2026-09-17 10:05:00',
                'completed_at' => null,
                'acknowledged' => true,
                'attach_issue' => false,
                'attach_inspection' => true,
                'attach_wo' => true,
                'attach_sr' => true,
            ],
            [
                'reference_no' => 'GPM-20260915-0007',
                'source_no' => 8,
                'category' => 'Plumbing Works',
                'priority' => 'urgent',
                'description' => 'Emergency repair and calibration of rooftop overhead water storage tank mechanical float valve and high-level alarm.',
                'request_date' => '2026-09-15',
                'due_date' => '2026-09-19',
                'status' => 'on_going',
                'inspection_date' => '2026-09-15',
                'inspection_start_time' => '14:00:00',
                'inspection_end_date' => '2026-09-15',
                'inspection_end_time' => '17:00:00',
                'reps' => ['Dial-A'],
                'inspection_completed_at' => '2026-09-15 17:00:00',
                'wo_start_date' => '2026-09-16',
                'wo_start_time' => '08:00:00',
                'wo_end_date' => '2026-09-16',
                'wo_end_time' => '16:00:00',
                'wo_reps' => ['Dial-A', 'Gilbert Tan'],
                'wo_completed_at' => '2026-09-16 16:00:00',
                'sr_date' => '2026-09-17',
                'sr_completed_at' => '2026-09-17 11:30:00',
                'completion_notified_at' => '2026-09-17 11:35:00',
                'completed_at' => null,
                'acknowledged' => true,
                'attach_issue' => true,
                'attach_inspection' => true,
                'attach_wo' => true,
                'attach_sr' => true,
            ],

            // ==========================================
            // GROUP 7: FULLY COMPLETED (5 entries)
            // ==========================================
            [
                'reference_no' => 'GPM-20260902-0001',
                'source_no' => 1,
                'category' => 'General Repairs',
                'priority' => 'regular',
                'description' => 'Repair leaky corrugated roof flashing, apply elastomeric sealant, and clear roof downspouts over technician staging area.',
                'request_date' => '2026-09-02',
                'due_date' => '2026-09-06',
                'status' => 'completed',
                'inspection_date' => '2026-09-03',
                'inspection_start_time' => '09:00:00',
                'inspection_end_date' => '2026-09-03',
                'inspection_end_time' => '11:00:00',
                'reps' => ['Engr. Carlos Mendoza', 'Dial-A'],
                'inspection_completed_at' => '2026-09-03 11:00:00',
                'wo_start_date' => '2026-09-04',
                'wo_start_time' => '08:30:00',
                'wo_end_date' => '2026-09-04',
                'wo_end_time' => '17:00:00',
                'wo_reps' => ['Mark Bautista', 'Dial-A'],
                'wo_completed_at' => '2026-09-04 17:00:00',
                'sr_date' => '2026-09-05',
                'sr_completed_at' => '2026-09-05 14:00:00',
                'completion_notified_at' => '2026-09-05 14:05:00',
                'completed_at' => '2026-09-05 15:45:00',
                'acknowledged' => true,
                'attach_issue' => true,
                'attach_inspection' => true,
                'attach_wo' => true,
                'attach_sr' => true,
            ],
            [
                'reference_no' => 'GPM-20260903-0001',
                'source_no' => 22,
                'category' => 'Electrical Works',
                'priority' => 'regular',
                'description' => 'Replaced burned magnetic starter and wiring harness for vehicle exhaust extraction fan in main service workshop.',
                'request_date' => '2026-09-03',
                'due_date' => '2026-09-07',
                'status' => 'completed',
                'inspection_date' => '2026-09-04',
                'inspection_start_time' => '10:00:00',
                'inspection_end_date' => '2026-09-04',
                'inspection_end_time' => '12:00:00',
                'reps' => ['Dial-A', 'Ferdinand Gomez'],
                'inspection_completed_at' => '2026-09-04 12:00:00',
                'wo_start_date' => '2026-09-05',
                'wo_start_time' => '09:00:00',
                'wo_end_date' => '2026-09-05',
                'wo_end_time' => '16:00:00',
                'wo_reps' => ['Dial-A', 'Ferdinand Gomez'],
                'wo_completed_at' => '2026-09-05 16:00:00',
                'sr_date' => '2026-09-06',
                'sr_completed_at' => '2026-09-06 11:00:00',
                'completion_notified_at' => '2026-09-06 11:05:00',
                'completed_at' => '2026-09-06 14:15:00',
                'acknowledged' => true,
                'attach_issue' => true,
                'attach_inspection' => true,
                'attach_wo' => true,
                'attach_sr' => true,
            ],
            [
                'reference_no' => 'GPM-20260904-0001',
                'source_no' => 11,
                'category' => 'Airconditioning',
                'priority' => 'regular',
                'description' => 'Chemical cleaning, fan motor lubrication, and filter replacement for 6 ceiling cassette units in customer showroom.',
                'request_date' => '2026-09-04',
                'due_date' => '2026-09-08',
                'status' => 'completed',
                'inspection_date' => '2026-09-05',
                'inspection_start_time' => '09:00:00',
                'inspection_end_date' => '2026-09-05',
                'inspection_end_time' => '11:30:00',
                'reps' => ['Dial-A'],
                'inspection_completed_at' => '2026-09-05 11:30:00',
                'wo_start_date' => '2026-09-06',
                'wo_start_time' => '08:00:00',
                'wo_end_date' => '2026-09-07',
                'wo_end_time' => '17:00:00',
                'wo_reps' => ['Dial-A', 'Reynaldo Silva'],
                'wo_completed_at' => '2026-09-07 17:00:00',
                'sr_date' => '2026-09-08',
                'sr_completed_at' => '2026-09-08 14:00:00',
                'completion_notified_at' => '2026-09-08 14:05:00',
                'completed_at' => '2026-09-08 17:00:00',
                'acknowledged' => true,
                'attach_issue' => true,
                'attach_inspection' => true,
                'attach_wo' => true,
                'attach_sr' => true,
            ],
            [
                'reference_no' => 'GPM-20260910-0001',
                'source_no' => 66,
                'category' => 'Carpentry & Woodworks',
                'priority' => 'regular',
                'description' => 'Built and installed custom heavy-duty plywood shelving unit with security wire mesh door for warranty parts return cage.',
                'request_date' => '2026-09-10',
                'due_date' => '2026-09-14',
                'status' => 'completed',
                'inspection_date' => '2026-09-11',
                'inspection_start_time' => '10:00:00',
                'inspection_end_date' => '2026-09-11',
                'inspection_end_time' => '12:00:00',
                'reps' => ['Dial-A', 'Dante Gomez'],
                'inspection_completed_at' => '2026-09-11 12:00:00',
                'wo_start_date' => '2026-09-12',
                'wo_start_time' => '08:30:00',
                'wo_end_date' => '2026-09-13',
                'wo_end_time' => '15:00:00',
                'wo_reps' => ['Dial-A', 'Dante Gomez'],
                'wo_completed_at' => '2026-09-13 15:00:00',
                'sr_date' => '2026-09-13',
                'sr_completed_at' => '2026-09-13 16:00:00',
                'completion_notified_at' => '2026-09-13 16:05:00',
                'completed_at' => '2026-09-13 16:30:00',
                'acknowledged' => true,
                'attach_issue' => true,
                'attach_inspection' => true,
                'attach_wo' => true,
                'attach_sr' => true,
            ],
            [
                'reference_no' => 'GPM-20260911-0001',
                'source_no' => 61,
                'category' => 'Painting',
                'priority' => 'regular',
                'description' => 'Pressure washed, applied anti-corrosion metal primer, and repainted stockyard perimeter gate and guardhouse exterior.',
                'request_date' => '2026-09-11',
                'due_date' => '2026-09-15',
                'status' => 'completed',
                'inspection_date' => '2026-09-12',
                'inspection_start_time' => '09:00:00',
                'inspection_end_date' => '2026-09-12',
                'inspection_end_time' => '11:00:00',
                'reps' => ['Dial-A'],
                'inspection_completed_at' => '2026-09-12 11:00:00',
                'wo_start_date' => '2026-09-13',
                'wo_start_time' => '08:00:00',
                'wo_end_date' => '2026-09-14',
                'wo_end_time' => '10:00:00',
                'wo_reps' => ['Dial-A', 'Arnel Pineda'],
                'wo_completed_at' => '2026-09-14 10:00:00',
                'sr_date' => '2026-09-14',
                'sr_completed_at' => '2026-09-14 11:00:00',
                'completion_notified_at' => '2026-09-14 11:05:00',
                'completed_at' => '2026-09-14 11:20:00',
                'acknowledged' => true,
                'attach_issue' => true,
                'attach_inspection' => true,
                'attach_wo' => true,
                'attach_sr' => true,
            ],
        ];

        DB::transaction(function () use ($items, $dialA, $manager, $demoIssuePath, $demoInspectionPath, $demoWoPath, $demoSrPath, $imgSize) {
            foreach ($items as $item) {
                $dealer = Dealer::where('source_no', $item['source_no'])->firstOrFail();
                $dealerUser = User::where('dealer_id', $dealer->id)->first();
                if (!$dealerUser) {
                    $dealerUser = User::create([
                        'dealer_id' => $dealer->id,
                        'name' => $dealer->name,
                        'designation' => 'Dealer Representative',
                        'email' => $item['source_no'] === 1 ? 'dealer@gateway.com' : "dealer{$item['source_no']}@gateway.com",
                        'role' => 'dealer',
                        'is_active' => true,
                        'password' => bcrypt('Gateway@2026'),
                    ]);
                }

                $createdAt = Carbon::parse($item['request_date'].' 08:30:00');
                $updatedAt = $item['completed_at'] ? Carbon::parse($item['completed_at']) : ($item['sr_completed_at'] ? Carbon::parse($item['sr_completed_at']) : ($item['wo_completed_at'] ? Carbon::parse($item['wo_completed_at']) : ($item['inspection_completed_at'] ? Carbon::parse($item['inspection_completed_at']) : $createdAt)));

                $requestData = [
                    'reference_no' => $item['reference_no'],
                    'dealer_id' => $dealer->id,
                    'dealer_name' => $dealer->brand ?: $dealer->name,
                    'submitted_by' => $dealerUser->id,
                    'assigned_support_id' => $dialA->id,
                    'assigned_manager_id' => $manager?->id,
                    'submitter_name' => $dealerUser->name,
                    'designation' => $dealerUser->designation,
                    'branch' => $dealer->branch,
                    'area' => $dealer->area,
                    'request_type' => $item['category'],
                    'priority' => $item['priority'],
                    'description' => $item['description'],
                    'request_date' => $item['request_date'],
                    'due_date' => $item['due_date'],
                    'approved_date' => $item['inspection_date'] ?? null,
                    'inspection_date' => $item['inspection_date'],
                    'inspection_start_time' => $item['inspection_start_time'],
                    'inspection_end_date' => $item['inspection_end_date'] ?? $item['inspection_date'],
                    'inspection_end_time' => $item['inspection_end_time'],
                    'representative_1' => $item['reps'][0] ?? null,
                    'representative_2' => $item['reps'][1] ?? null,
                    'representative_3' => $item['reps'][2] ?? null,
                    'inspection_completed_at' => $item['inspection_completed_at'] ? Carbon::parse($item['inspection_completed_at']) : null,
                    'work_order_start_date' => $item['wo_start_date'],
                    'work_order_start_time' => $item['wo_start_time'],
                    'work_order_end_date' => $item['wo_end_date'],
                    'work_order_end_time' => $item['wo_end_time'],
                    'work_order_representatives' => $item['wo_reps'],
                    'work_order_completed_at' => $item['wo_completed_at'] ? Carbon::parse($item['wo_completed_at']) : null,
                    'service_report_date' => $item['sr_date'],
                    'service_report_completed_at' => $item['sr_completed_at'] ? Carbon::parse($item['sr_completed_at']) : null,
                    'completion_notified_at' => $item['completion_notified_at'] ? Carbon::parse($item['completion_notified_at']) : null,
                    'status' => $item['status'],
                    'completed_at' => $item['completed_at'] ? Carbon::parse($item['completed_at']) : null,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ];

                $req = PropertyRequest::withoutEvents(function () use ($item, $requestData) {
                    return PropertyRequest::updateOrCreate(
                        ['reference_no' => $item['reference_no']],
                        $requestData
                    );
                });

                // 1. Attachments
                if (!empty($item['attach_issue'])) {
                    RequestAttachment::firstOrCreate(
                        ['property_request_id' => $req->id, 'category' => 'request', 'original_name' => 'issue-photo.png'],
                        [
                            'path' => $demoIssuePath,
                            'mime_type' => 'image/png',
                            'size' => $imgSize,
                        ]
                    );
                }

                if (!empty($item['attach_inspection'])) {
                    RequestAttachment::firstOrCreate(
                        ['property_request_id' => $req->id, 'category' => 'inspection', 'original_name' => 'inspection-report.png'],
                        [
                            'path' => $demoInspectionPath,
                            'mime_type' => 'image/png',
                            'size' => $imgSize,
                        ]
                    );
                }

                if (!empty($item['attach_wo'])) {
                    RequestAttachment::firstOrCreate(
                        ['property_request_id' => $req->id, 'category' => 'work_order', 'original_name' => 'work-order-signed.png'],
                        [
                            'path' => $demoWoPath,
                            'mime_type' => 'image/png',
                            'size' => $imgSize,
                        ]
                    );
                }

                if (!empty($item['attach_sr'])) {
                    RequestAttachment::firstOrCreate(
                        ['property_request_id' => $req->id, 'category' => 'service_report', 'original_name' => 'service-report-signed.png'],
                        [
                            'path' => $demoSrPath,
                            'mime_type' => 'image/png',
                            'size' => $imgSize,
                        ]
                    );
                }

                // 2. Notifications
                $readAt = $item['acknowledged'] ? $createdAt->copy()->addMinutes(15) : null;
                $notifId = Uuid::uuid5(Uuid::NAMESPACE_URL, "pms:{$req->id}:{$createdAt->toISOString()}:{$dialA->id}:created")->toString();

                DatabaseNotification::updateOrCreate(
                    ['id' => $notifId],
                    [
                        'type' => 'request_workflow',
                        'notifiable_type' => User::class,
                        'notifiable_id' => $dialA->id,
                        'property_request_id' => $req->id,
                        'data' => [
                            'kind' => 'new_request',
                            'title' => 'New request',
                            'message' => "{$req->reference_no}: {$req->request_type} requested by {$req->submitter_name} ({$req->branch}).",
                        ],
                        'read_at' => $readAt,
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]
                );

                if (!empty($item['completion_notified_at'])) {
                    $stageNotifId = Uuid::uuid5(Uuid::NAMESPACE_URL, "pms:{$req->id}:completion-notice")->toString();
                    DatabaseNotification::updateOrCreate(
                        ['id' => $stageNotifId],
                        [
                            'type' => 'request_workflow',
                            'notifiable_type' => User::class,
                            'notifiable_id' => $dialA->id,
                            'property_request_id' => $req->id,
                            'data' => [
                                'kind' => 'stage_completed',
                                'title' => 'Service Report completed',
                                'message' => "{$req->reference_no}: Service Report completed. Request is ready to be finished.",
                                'stage' => 'service_report',
                                'request_completed' => false,
                            ],
                            'read_at' => null,
                            'created_at' => Carbon::parse($item['completion_notified_at']),
                            'updated_at' => Carbon::parse($item['completion_notified_at']),
                        ]
                    );
                }

                // 3. Audit Logs
                AuditLog::firstOrCreate(
                    [
                        'subject_type' => 'PropertyRequest',
                        'subject_id' => $req->id,
                        'action' => 'request_submitted',
                    ],
                    [
                        'user_id' => $dealerUser->id,
                        'description' => "{$req->reference_no} was submitted by {$dealerUser->name} for {$req->dealer_name} ({$req->branch}).",
                        'ip_address' => '127.0.0.1',
                        'created_at' => $createdAt,
                        'updated_at' => $createdAt,
                    ]
                );

                if ($item['inspection_date']) {
                    AuditLog::firstOrCreate(
                        [
                            'subject_type' => 'PropertyRequest',
                            'subject_id' => $req->id,
                            'action' => 'inspection_date_set',
                        ],
                        [
                            'user_id' => $dialA->id,
                            'description' => "{$req->reference_no} inspection start date set to " . Carbon::parse($item['inspection_date'])->format('F d, Y') . " and status set to On-going by Dial-A {$dialA->name}.",
                            'ip_address' => '127.0.0.1',
                            'created_at' => Carbon::parse($item['inspection_date'] . ' ' . ($item['inspection_start_time'] ?? '09:00:00')),
                            'updated_at' => Carbon::parse($item['inspection_date'] . ' ' . ($item['inspection_start_time'] ?? '09:00:00')),
                        ]
                    );
                }

                if ($item['inspection_completed_at']) {
                    $repsStr = !empty($item['reps']) ? ' with representative(s): ' . implode(', ', $item['reps']) : '';
                    AuditLog::firstOrCreate(
                        [
                            'subject_type' => 'PropertyRequest',
                            'subject_id' => $req->id,
                            'action' => 'inspection_completed',
                        ],
                        [
                            'user_id' => $dialA->id,
                            'description' => "{$req->reference_no} inspection was conducted and completed by Dial-A {$dialA->name}{$repsStr}.",
                            'ip_address' => '127.0.0.1',
                            'created_at' => Carbon::parse($item['inspection_completed_at']),
                            'updated_at' => Carbon::parse($item['inspection_completed_at']),
                        ]
                    );
                }

                if ($item['wo_start_date']) {
                    AuditLog::firstOrCreate(
                        [
                            'subject_type' => 'PropertyRequest',
                            'subject_id' => $req->id,
                            'action' => 'work_order_date_set',
                        ],
                        [
                            'user_id' => $dialA->id,
                            'description' => "{$req->reference_no} Work Order start date set to " . Carbon::parse($item['wo_start_date'])->format('F d, Y') . " and status set to On-going by Dial-A {$dialA->name}.",
                            'ip_address' => '127.0.0.1',
                            'created_at' => Carbon::parse($item['wo_start_date'] . ' ' . ($item['wo_start_time'] ?? '09:00:00')),
                            'updated_at' => Carbon::parse($item['wo_start_date'] . ' ' . ($item['wo_start_time'] ?? '09:00:00')),
                        ]
                    );
                }

                if ($item['wo_completed_at']) {
                    AuditLog::firstOrCreate(
                        [
                            'subject_type' => 'PropertyRequest',
                            'subject_id' => $req->id,
                            'action' => 'work_order_completed',
                        ],
                        [
                            'user_id' => $dialA->id,
                            'description' => "{$req->reference_no} Work Order attachments were uploaded and completed by Dial-A {$dialA->name}.",
                            'ip_address' => '127.0.0.1',
                            'created_at' => Carbon::parse($item['wo_completed_at']),
                            'updated_at' => Carbon::parse($item['wo_completed_at']),
                        ]
                    );
                }

                if ($item['sr_date'] && !$item['sr_completed_at']) {
                    AuditLog::firstOrCreate(
                        [
                            'subject_type' => 'PropertyRequest',
                            'subject_id' => $req->id,
                            'action' => 'service_report_ongoing',
                        ],
                        [
                            'user_id' => $dialA->id,
                            'description' => "{$req->reference_no} Service Report file upload initiated and marked as On-going by Dial-A {$dialA->name}.",
                            'ip_address' => '127.0.0.1',
                            'created_at' => Carbon::parse($item['sr_date'] . ' 09:30:00'),
                            'updated_at' => Carbon::parse($item['sr_date'] . ' 09:30:00'),
                        ]
                    );
                }

                if ($item['sr_completed_at']) {
                    AuditLog::firstOrCreate(
                        [
                            'subject_type' => 'PropertyRequest',
                            'subject_id' => $req->id,
                            'action' => 'service_report_completed',
                        ],
                        [
                            'user_id' => $dialA->id,
                            'description' => "{$req->reference_no} Service Report was completed by Dial-A {$dialA->name}.",
                            'ip_address' => '127.0.0.1',
                            'created_at' => Carbon::parse($item['sr_completed_at']),
                            'updated_at' => Carbon::parse($item['sr_completed_at']),
                        ]
                    );
                }

                if ($item['completion_notified_at']) {
                    AuditLog::firstOrCreate(
                        [
                            'subject_type' => 'PropertyRequest',
                            'subject_id' => $req->id,
                            'action' => 'dial_lead_notified',
                        ],
                        [
                            'user_id' => $dialA->id,
                            'description' => "{$req->reference_no}: Dial-A {$dialA->name} confirmed ready for completion.",
                            'ip_address' => '127.0.0.1',
                            'created_at' => Carbon::parse($item['completion_notified_at']),
                            'updated_at' => Carbon::parse($item['completion_notified_at']),
                        ]
                    );
                }

                if ($item['completed_at']) {
                    AuditLog::firstOrCreate(
                        [
                            'subject_type' => 'PropertyRequest',
                            'subject_id' => $req->id,
                            'action' => 'request_completed',
                        ],
                        [
                            'user_id' => $dialA->id,
                            'description' => "{$req->reference_no} was reviewed, confirmed, and officially marked completed by Dial-A {$dialA->name}.",
                            'ip_address' => '127.0.0.1',
                            'created_at' => Carbon::parse($item['completed_at']),
                            'updated_at' => Carbon::parse($item['completed_at']),
                        ]
                    );
                }
            }
        });
    }
}
