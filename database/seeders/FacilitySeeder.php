<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookIssue;
use App\Models\Hostel;
use App\Models\HostelAllocation;
use App\Models\HostelBed;
use App\Models\HostelMaintenanceTicket;
use App\Models\HostelRoom;
use App\Models\RouteStop;
use App\Models\TransportAssignment;
use App\Models\TransportRoute;
use App\Models\Vehicle;
use App\Models\VehicleMaintenanceLog;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $this->seedLibrary();
        $this->seedTransport();
        $this->seedHostel();
    }

    private function seedLibrary(): void
    {
        $books = [
            ['title' => 'Introduction to Algorithms', 'author' => 'Cormen, Leiserson, Rivest', 'isbn' => '9780262033848', 'category' => 'Computer Science', 'subtitle' => 'Third Edition', 'total_copies' => 6, 'available_copies' => 4, 'borrow_count' => 12, 'campus_id' => 1],
            ['title' => 'Clean Code', 'author' => 'Robert C. Martin', 'isbn' => '9780132350884', 'category' => 'Software Engineering', 'subtitle' => 'A Handbook of Agile Software Craftsmanship', 'total_copies' => 5, 'available_copies' => 3, 'borrow_count' => 9, 'campus_id' => 1],
            ['title' => 'Calculus: Early Transcendentals', 'author' => 'James Stewart', 'isbn' => '9781285741550', 'category' => 'Mathematics', 'subtitle' => '8th Edition', 'total_copies' => 8, 'available_copies' => 6, 'borrow_count' => 20, 'campus_id' => 2],
            ['title' => 'Principles of Economics', 'author' => 'N. Gregory Mankiw', 'isbn' => '9781305585126', 'category' => 'Economics', 'subtitle' => '7th Edition', 'total_copies' => 4, 'available_copies' => 2, 'borrow_count' => 7, 'campus_id' => 2],
            ['title' => 'Campbell Biology', 'author' => 'Lisa A. Urry', 'isbn' => '9780134093413', 'category' => 'Biology', 'subtitle' => '11th Edition', 'total_copies' => 5, 'available_copies' => 5, 'borrow_count' => 4, 'campus_id' => 3],
        ];

        $bookModels = [];
        foreach ($books as $data) {
            $data['availability_status'] = $data['available_copies'] > 0 ? 'available' : 'unavailable';
            $bookModels[] = Book::create($data);
        }

        $issues = [
            ['book' => 0, 'student_id' => 1, 'issue_date' => '2026-05-20', 'due_date' => '2026-06-03', 'return_date' => null, 'status' => 'issued', 'fine_amount' => 0, 'fine_paid' => false, 'renewal_count' => 0],
            ['book' => 0, 'student_id' => 2, 'issue_date' => '2026-05-15', 'due_date' => '2026-05-29', 'return_date' => '2026-05-28', 'status' => 'returned', 'fine_amount' => 0, 'fine_paid' => false, 'renewal_count' => 1],
            ['book' => 1, 'student_id' => 3, 'issue_date' => '2026-05-01', 'due_date' => '2026-05-15', 'return_date' => null, 'status' => 'overdue', 'fine_amount' => 32.00, 'fine_paid' => false, 'renewal_count' => 0],
            ['book' => 2, 'student_id' => 4, 'issue_date' => '2026-06-01', 'due_date' => '2026-06-15', 'return_date' => null, 'status' => 'issued', 'fine_amount' => 0, 'fine_paid' => false, 'renewal_count' => 0],
            ['book' => 3, 'student_id' => 5, 'issue_date' => '2026-04-10', 'due_date' => '2026-04-24', 'return_date' => '2026-04-22', 'status' => 'returned', 'fine_amount' => 0, 'fine_paid' => false, 'renewal_count' => 0],
        ];

        foreach ($issues as $i) {
            BookIssue::create([
                'book_id' => $bookModels[$i['book']]->id,
                'borrower_type' => 'student',
                'student_id' => $i['student_id'],
                'issued_by' => 1,
                'issue_date' => $i['issue_date'],
                'due_date' => $i['due_date'],
                'return_date' => $i['return_date'],
                'status' => $i['status'],
                'fine_amount' => $i['fine_amount'],
                'fine_paid' => $i['fine_paid'],
                'renewal_count' => $i['renewal_count'],
            ]);
        }
    }

    private function seedTransport(): void
    {
        // Routes first (vehicles reference route_id; routes reference vehicle_id — wire after).
        $routesData = [
            ['name' => 'North City Loop', 'code' => 'RT-NORTH-01', 'start_point' => 'Main Campus Gate', 'end_point' => 'North Town Plaza', 'duration_minutes' => 45, 'monthly_fee' => 60.00, 'campus_id' => 1],
            ['name' => 'South Suburbs Express', 'code' => 'RT-SOUTH-02', 'start_point' => 'Main Campus Gate', 'end_point' => 'Greenfield Colony', 'duration_minutes' => 55, 'monthly_fee' => 75.00, 'campus_id' => 2],
            ['name' => 'East Ring Shuttle', 'code' => 'RT-EAST-03', 'start_point' => 'Annex Block', 'end_point' => 'Riverside Heights', 'duration_minutes' => 35, 'monthly_fee' => 50.00, 'campus_id' => 3],
        ];

        $routes = [];
        foreach ($routesData as $r) {
            $routes[] = TransportRoute::create($r + ['stops_count' => 0, 'students_count' => 0, 'status' => 'active']);
        }

        $vehiclesData = [
            ['vehicle_number' => 'BUS-1101', 'type' => 'Bus', 'capacity' => 52, 'occupied_seats' => 40, 'route' => 0, 'driver_id' => 1, 'campus_id' => 1, 'last_service_km' => 48200, 'status' => 'operational'],
            ['vehicle_number' => 'BUS-1102', 'type' => 'Bus', 'capacity' => 52, 'occupied_seats' => 33, 'route' => 1, 'driver_id' => 2, 'campus_id' => 2, 'last_service_km' => 51100, 'status' => 'operational'],
            ['vehicle_number' => 'VAN-2201', 'type' => 'Van', 'capacity' => 18, 'occupied_seats' => 12, 'route' => 2, 'driver_id' => 3, 'campus_id' => 3, 'last_service_km' => 23400, 'status' => 'operational'],
            ['vehicle_number' => 'VAN-2202', 'type' => 'Van', 'capacity' => 18, 'occupied_seats' => 0, 'route' => null, 'driver_id' => 4, 'campus_id' => 1, 'last_service_km' => 19800, 'status' => 'maintenance'],
        ];

        $vehicles = [];
        foreach ($vehiclesData as $v) {
            $routeRef = $v['route'];
            unset($v['route']);
            $v['route_id'] = $routeRef !== null ? $routes[$routeRef]->id : null;
            $vehicles[] = Vehicle::create($v);
        }

        // Wire route -> vehicle back-references.
        $routes[0]->update(['vehicle_id' => $vehicles[0]->id]);
        $routes[1]->update(['vehicle_id' => $vehicles[1]->id]);
        $routes[2]->update(['vehicle_id' => $vehicles[2]->id]);

        $maintLogs = [
            ['vehicle' => 0, 'type' => 'service', 'title' => 'Routine 50k km service', 'description' => 'Oil change, brake inspection and tyre rotation.', 'reported_by' => 1, 'due_in_days' => 14, 'status' => 'pending', 'logged_at' => '2026-06-10 09:30:00'],
            ['vehicle' => 1, 'type' => 'repair', 'title' => 'AC compressor replacement', 'description' => 'Cabin air conditioning not cooling.', 'reported_by' => 2, 'due_in_days' => 3, 'status' => 'in_progress', 'logged_at' => '2026-06-12 14:15:00'],
            ['vehicle' => 3, 'type' => 'inspection', 'title' => 'Annual fitness inspection', 'description' => 'Vehicle held for regulatory fitness certificate.', 'reported_by' => 3, 'due_in_days' => 7, 'status' => 'pending', 'logged_at' => '2026-06-14 08:00:00'],
        ];

        foreach ($maintLogs as $m) {
            $vehicleRef = $m['vehicle'];
            unset($m['vehicle']);
            $m['vehicle_id'] = $vehicles[$vehicleRef]->id;
            VehicleMaintenanceLog::create($m);
        }

        // Route stops.
        $stopsByRoute = [
            0 => [
                ['name' => 'Main Campus Gate', 'sequence' => 1, 'arrival_time' => '07:30:00', 'stop_duration_minutes' => 5],
                ['name' => 'Civic Center', 'sequence' => 2, 'arrival_time' => '07:50:00', 'stop_duration_minutes' => 3],
                ['name' => 'North Town Plaza', 'sequence' => 3, 'arrival_time' => '08:15:00', 'stop_duration_minutes' => 5],
            ],
            1 => [
                ['name' => 'Main Campus Gate', 'sequence' => 1, 'arrival_time' => '07:20:00', 'stop_duration_minutes' => 5],
                ['name' => 'Market Square', 'sequence' => 2, 'arrival_time' => '07:45:00', 'stop_duration_minutes' => 3],
                ['name' => 'Greenfield Colony', 'sequence' => 3, 'arrival_time' => '08:15:00', 'stop_duration_minutes' => 5],
            ],
            2 => [
                ['name' => 'Annex Block', 'sequence' => 1, 'arrival_time' => '07:40:00', 'stop_duration_minutes' => 4],
                ['name' => 'Riverside Heights', 'sequence' => 2, 'arrival_time' => '08:15:00', 'stop_duration_minutes' => 4],
            ],
        ];

        $stops = [];
        foreach ($stopsByRoute as $routeIdx => $routeStops) {
            $stops[$routeIdx] = [];
            foreach ($routeStops as $s) {
                $s['route_id'] = $routes[$routeIdx]->id;
                $stops[$routeIdx][] = RouteStop::create($s);
            }
            $routes[$routeIdx]->update(['stops_count' => count($routeStops)]);
        }

        // Transport assignments (students 1..5).
        $assignments = [
            ['student_id' => 1, 'route' => 0, 'pickup' => 1, 'dropoff' => 2, 'monthly_fee' => 60.00],
            ['student_id' => 2, 'route' => 0, 'pickup' => 0, 'dropoff' => 2, 'monthly_fee' => 60.00],
            ['student_id' => 3, 'route' => 1, 'pickup' => 1, 'dropoff' => 2, 'monthly_fee' => 75.00],
            ['student_id' => 4, 'route' => 2, 'pickup' => 0, 'dropoff' => 1, 'monthly_fee' => 50.00],
        ];

        $routeCounts = [];
        foreach ($assignments as $a) {
            TransportAssignment::create([
                'student_id' => $a['student_id'],
                'route_id' => $routes[$a['route']]->id,
                'pickup_stop_id' => $stops[$a['route']][$a['pickup']]->id,
                'dropoff_stop_id' => $stops[$a['route']][$a['dropoff']]->id,
                'monthly_fee' => $a['monthly_fee'],
                'status' => 'assigned',
            ]);
            $routeCounts[$a['route']] = ($routeCounts[$a['route']] ?? 0) + 1;
        }
        foreach ($routeCounts as $routeIdx => $count) {
            $routes[$routeIdx]->update(['students_count' => $count]);
        }
    }

    private function seedHostel(): void
    {
        $hostelsData = [
            ['name' => 'Iqbal Boys Hostel', 'block' => 'A', 'type' => 'boys', 'warden_id' => 4, 'campus_id' => 1],
            ['name' => 'Fatima Girls Hostel', 'block' => 'B', 'type' => 'girls', 'warden_id' => 5, 'campus_id' => 2],
            ['name' => 'Faculty Residences', 'block' => 'C', 'type' => 'faculty_staff', 'warden_id' => 1, 'campus_id' => 3],
        ];

        $roomPlans = [
            // hostelIdx => list of rooms [room_number, floor, type, capacity, room_rate]
            0 => [
                ['101', 'Ground', 'double', 2, 120.00],
                ['102', 'Ground', 'double', 2, 120.00],
                ['201', 'First', 'quad', 4, 90.00],
            ],
            1 => [
                ['B-101', 'Ground', 'single', 1, 180.00],
                ['B-102', 'Ground', 'twin', 2, 130.00],
            ],
            2 => [
                ['F-01', 'Ground', 'single', 1, 250.00],
            ],
        ];

        $hostels = [];
        $allRooms = [];
        $allBeds = [];

        foreach ($hostelsData as $idx => $hData) {
            $hostel = Hostel::create($hData + ['total_rooms' => 0, 'occupied_rooms' => 0, 'occupancy_status' => 'available']);
            $hostels[$idx] = $hostel;
            $allRooms[$idx] = [];

            foreach ($roomPlans[$idx] as $rp) {
                [$roomNumber, $floor, $type, $capacity, $rate] = $rp;
                $room = HostelRoom::create([
                    'hostel_id' => $hostel->id,
                    'room_number' => $roomNumber,
                    'floor' => $floor,
                    'type' => $type,
                    'capacity' => $capacity,
                    'available_beds' => $capacity,
                    'status' => 'available',
                    'room_rate' => $rate,
                    'rate_period' => 'monthly',
                ]);
                $allRooms[$idx][] = $room;

                $beds = [];
                for ($b = 1; $b <= $capacity; $b++) {
                    $beds[] = HostelBed::create([
                        'room_id' => $room->id,
                        'bed_label' => $roomNumber . '-' . chr(64 + $b),
                        'status' => 'vacant',
                    ]);
                }
                $allBeds[$room->id] = $beds;
            }

            $hostel->update(['total_rooms' => count($roomPlans[$idx])]);
        }

        // Allocations (students 1..5). Mark bed/room occupancy.
        $allocations = [
            ['student_id' => 1, 'hostel' => 0, 'room' => 0, 'bed' => 0, 'check_in_date' => '2026-02-01'],
            ['student_id' => 2, 'hostel' => 0, 'room' => 0, 'bed' => 1, 'check_in_date' => '2026-02-01'],
            ['student_id' => 3, 'hostel' => 1, 'room' => 0, 'bed' => 0, 'check_in_date' => '2026-02-05'],
        ];

        $occupiedRoomsPerHostel = [];
        foreach ($allocations as $a) {
            $room = $allRooms[$a['hostel']][$a['room']];
            $bed = $allBeds[$room->id][$a['bed']];

            HostelAllocation::create([
                'student_id' => $a['student_id'],
                'hostel_id' => $hostels[$a['hostel']]->id,
                'room_id' => $room->id,
                'bed_id' => $bed->id,
                'check_in_date' => $a['check_in_date'],
                'check_out_date' => null,
                'room_rate' => $room->room_rate,
                'rate_period' => $room->rate_period,
                'status' => 'active',
            ]);

            $bed->update(['status' => 'occupied']);
            $room->decrement('available_beds');
            $room->refresh();
            if ($room->available_beds <= 0) {
                $room->update(['status' => 'occupied']);
            }
            $occupiedRoomsPerHostel[$a['hostel']][$room->id] = true;
        }

        foreach ($occupiedRoomsPerHostel as $hostelIdx => $rooms) {
            $hostels[$hostelIdx]->update([
                'occupied_rooms' => count($rooms),
                'occupancy_status' => 'partially_occupied',
            ]);
        }

        // Maintenance tickets.
        HostelMaintenanceTicket::create([
            'ticket_number' => 'HMT-0001',
            'room_id' => $allRooms[0][2]->id,
            'hostel_id' => $hostels[0]->id,
            'category' => 'maintenance',
            'issue_type' => 'plumbing',
            'description' => 'Leaking tap in the attached washroom of room 201.',
            'priority' => 'high',
            'reported_by' => 1,
            'status' => 'pending',
        ]);

        HostelMaintenanceTicket::create([
            'ticket_number' => 'HMT-0002',
            'room_id' => $allRooms[1][1]->id,
            'hostel_id' => $hostels[1]->id,
            'category' => 'incident',
            'issue_type' => 'electrical',
            'description' => 'Power outlet sparking near study desk; cordoned off.',
            'priority' => 'critical',
            'reported_by' => 1,
            'status' => 'in_progress',
        ]);
    }
}
