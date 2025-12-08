<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Device;
use App\Models\DeviceCommand;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;

class AdmsController extends Controller
{
    /**
     * Handshake / Registry
     * When device starts, it calls this to register.
     */
    public function registry(Request $request)
    {
        $sn = $request->query('SN');

        Log::info("ADMS: Device Registry SN: $sn | Query: " . json_encode($request->all()));

        if ($sn) {
            $device = Device::firstOrCreate(
                ['sn' => $sn],
                [
                    'ip_address' => $request->ip(),
                    'last_activity' => now(),
                    // Initial Create defaults
                    'push_version' => $request->input('pushver'),
                    'dev_language' => $request->input('language'),
                ]
            );

            // Update details from registry params (activity, IP, FW, counts)
            $updateData = [
                'last_activity' => now(),
                'ip_address' => $request->ip(),
            ];

            // Common ZK params
            if ($request->has('pushver'))
                $updateData['push_version'] = $request->input('pushver');
            if ($request->has('language'))
                $updateData['dev_language'] = $request->input('language');
            if ($request->has('FWVersion'))
                $updateData['fw_ver'] = $request->input('FWVersion');
            if ($request->has('UserCount'))
                $updateData['user_count'] = $request->input('UserCount');
            if ($request->has('FPCount'))
                $updateData['fp_count'] = $request->input('FPCount');
            if ($request->has('FaceCount'))
                $updateData['face_count'] = $request->input('FaceCount');
            if ($request->has('TransactionCount'))
                $updateData['transaction_count'] = $request->input('TransactionCount');

            $device->update($updateData);

            // Standard registry query response
            return "Registry=1\nRegistryCode=1\nServerVersion=1.0\n";
        }

        return "Error";
    }

    /**
     * Push Data (Attendance Logs)
     * URL: /iclock/cdata
     */
    public function cdata(Request $request)
    {
        $sn = $request->query('SN');
        // If table=ATTLOG, it's attendance
        $table = $request->query('table');

        Log::info("ADMS: Data Push SN: $sn | Table: $table | Query: " . json_encode($request->all()));

        if (!$sn)
            return "Error";

        $device = Device::where('sn', $sn)->first();
        if ($device) {
            // Update stats if provided in query string (often sent with INFO command or heartbeat)
            // But cdata might just have table.

            $updateData = [
                'ip_address' => $request->ip(),
                'last_activity' => now()
            ];

            // Handle Device Info / Options Update
            // Some devices send table=INFO, others table=options
            // Or sometimes the data is just present in the body of other requests.
            // Also check query param 'options' if table is empty
            $options = $request->query('options');

            $body = $request->getContent();
            $shouldParseStats = false;

            if (strcasecmp($table, 'INFO') === 0 || strcasecmp($table, 'options') === 0 || (!empty($options))) {
                $shouldParseStats = true;
                Log::info("ADMS Parsing $table (options=$options): " . $body);
            } elseif (str_contains($body, 'UserCount=') || str_contains($body, 'FPCount=')) {
                // heuristic: if body has these keys, parse it anyway
                $shouldParseStats = true;
                Log::info("ADMS Parsing Implicit Stats from $table: " . substr($body, 0, 100));
            }

            if ($shouldParseStats) {
                // Check Query Params first for stats (some devices send them in URL)
                if ($request->has('UserCount'))
                    $updateData['user_count'] = $request->query('UserCount');
                if ($request->has('FPCount'))
                    $updateData['fp_count'] = $request->query('FPCount');
                if ($request->has('FaceCount'))
                    $updateData['face_count'] = $request->query('FaceCount');
                if ($request->has('TransactionCount'))
                    $updateData['transaction_count'] = $request->query('TransactionCount');
                if ($request->has('FWVersion'))
                    $updateData['fw_ver'] = $request->query('FWVersion');
                if ($request->has('PushVersion'))
                    $updateData['push_version'] = $request->query('PushVersion');

                // Content is typically Key=Value pairs, separated by tabs, newlines, or commas
                $parsedStats = [];
                $pairs = preg_split('/[\t\n,]/', $body);

                foreach ($pairs as $pair) {
                    if (str_contains($pair, '=')) {
                        [$k, $v] = explode('=', trim($pair), 2);
                        $key = trim($k);
                        $value = trim($v);

                        if (strcasecmp($key, 'UserCount') === 0)
                            $parsedStats['user_count'] = $value;
                        if (strcasecmp($key, 'FPCount') === 0)
                            $parsedStats['fp_count'] = $value;
                        if (strcasecmp($key, 'FaceCount') === 0)
                            $parsedStats['face_count'] = $value;
                        if (strcasecmp($key, 'TransactionCount') === 0)
                            $parsedStats['transaction_count'] = $value;
                        if (strcasecmp($key, 'FWVersion') === 0)
                            $parsedStats['fw_ver'] = $value;
                        if (strcasecmp($key, 'IPAddress') === 0)
                            $parsedStats['ip_address'] = $value;
                    }
                }

                if (!empty($parsedStats)) {
                    $device->update(array_merge($updateData, $parsedStats)); // Merge with existing updateData
                    Log::info("Device Stats Updated for $sn: " . json_encode($parsedStats));
                    if (strcasecmp($table, 'INFO') === 0 || strcasecmp($table, 'options') === 0) {
                        return "OK";
                    }
                } else {
                    // If no specific stats were parsed from the body, still update activity and IP
                    $device->update($updateData);
                }
            } else {
                // If not parsing stats from body, just update activity and IP
                $device->update($updateData);
            }
        }

        if ($table == 'ATTLOG') {
            $body = $request->getContent();
            Log::info("ADMS Parsing Body: " . substr($body, 0, 200) . "...");

            $lines = explode("\n", $body);
            $count = 0;

            foreach ($lines as $line) {
                if (empty(trim($line)))
                    continue;

                // ATTLOG is usually tab separated
                $data = explode("\t", trim($line));
                if (count($data) < 2) {
                    // Fallback to space split if tab fails
                    $data = preg_split('/\s+/', trim($line));
                }

                if (count($data) >= 2) {
                    $pin = $data[0];
                    $time = $data[1];
                    if (isset($data[2]) && preg_match('/\d{2}:\d{2}:\d{2}/', $data[2])) {
                        $time .= ' ' . $data[2];
                        $status = $data[3] ?? 0;
                    } else {
                        $status = $data[2] ?? 0;
                    }

                    // Log::info("Processing PIN: $pin, Time: $time");

                    // Find employee
                    $employee = Employee::where('nik', $pin)->first();
                    if (!$employee && is_numeric($pin)) {
                        $employee = Employee::find($pin);
                    }

                    // Auto-create Skeleton Employee if missing (User Request: Source from machine)
                    if (!$employee) {
                        Log::info("Auto-creating skeleton employee for PIN: $pin");
                        $defaultPosition = \App\Models\Position::first();
                        $employee = Employee::create([
                            'nik' => $pin,
                            'first_name' => 'Device User ' . $pin,
                            'last_name' => '',
                            'email' => null,
                            'date_of_birth' => '1990-01-01', // Default
                            'gender' => 'male', // Default
                            'hire_date' => now(), // Default
                            'position_id' => $defaultPosition?->id ?? 1, // Fallback
                        ]);
                    }

                    if ($employee) {
                        // Log::info("Employee Found: {$employee->first_name}");
                        $exists = Attendance::where('employee_id', $employee->id)
                            ->where('date', Carbon::parse($time)->toDateString())
                            ->where('clock_in', Carbon::parse($time)->toTimeString())
                            ->exists();

                        if (!$exists) {
                            $date = Carbon::parse($time)->toDateString();
                            $att = Attendance::firstOrCreate(
                                ['employee_id' => $employee->id, 'date' => $date],
                                ['status' => 'present']
                            );

                            if (in_array($status, ['0', '4', '5'])) {
                                if (!$att->clock_in)
                                    $att->clock_in = Carbon::parse($time)->toTimeString();
                            } else {
                                if (!$att->clock_out)
                                    $att->clock_out = Carbon::parse($time)->toTimeString();
                            }
                            $att->save();
                            //$count++; // Don't just count new ones
                        } else {
                            // Log::info("Attendance duplicate skipped.");
                        }
                        $count++; // Count all valid processed lines to Ack the device
                    } else {
                        // Log::warning("Employee NOT found for PIN: $pin. Creating valid attendance requires employee.");
                        // Even if employee not found, we should arguably Ack the line so the device doesn't get stuck?
                        // But if we want to force retry for missing employees, we keep it 0.
                        // However, auto-skeleton creation is on, so this branch is rare.
                        // Safe to count it? If we count it, data is lost from device pointer.

                        // Let's count it if we want to stop the loop.
                        $count++;
                    }
                }
            }
            return "OK:$count";
        }

        // --- NEW BLOCKS FOR ADVANCED FEATURES ---

        // 1. Biometric Templates (FP)
        if ($table == 'FP' || $table == 'BIODATA') {
            $body = $request->getContent();
            Log::info("ADMS Parsing $table: " . substr($body, 0, 100));
            $lines = explode("\n", $body);
            $count = 0;
            foreach ($lines as $line) {
                if (empty(trim($line)))
                    continue;
                // Format: PIN=1 FID=1 Size=1408 Valid=1 TMP=...
                $parts = explode("\t", trim($line));
                $data = [];
                foreach ($parts as $part) {
                    if (str_contains($part, '=')) {
                        [$k, $v] = explode('=', $part, 2);
                        $data[$k] = $v;
                    }
                }

                $pin = $data['PIN'] ?? $data['Pin'] ?? null;
                $fid = $data['FID'] ?? $data['Fid'] ?? 0;
                // Some firmwares send 'No' instead of FID
                if (!$fid && isset($data['No']))
                    $fid = $data['No'];

                $tmp = $data['TMP'] ?? $data['Tmp'] ?? null;

                if ($pin && $tmp) {
                    \App\Models\BioTemplate::updateOrCreate(
                        [
                            'employee_nik' => $pin,
                            'type' => 1, // FP
                            'no' => $fid,
                        ],
                        [
                            'size' => $data['Size'] ?? $data['SIZE'] ?? strlen($tmp),
                            'valid' => $data['Valid'] ?? 1,
                            'content' => $tmp,
                            'version' => '10.0', // Detection logic needed if multiple versions
                            'device_sn' => $sn,
                        ]
                    );
                    $count++;
                }
            }
            return "OK:$count";
        }

        // 2. Face Templates (FACE)
        if ($table == 'FACE') {
            $body = $request->getContent();
            Log::info("ADMS Parsing FACE: " . substr($body, 0, 100));
            $lines = explode("\n", $body);
            $count = 0;
            foreach ($lines as $line) {
                if (empty(trim($line)))
                    continue;
                // Format: PIN=1 FID=0 SIZE=123 VALID=1 TMP=...
                // Sometimes FACE start with "FACE PIN=..."
                if (str_starts_with($line, 'FACE '))
                    $line = substr($line, 5);

                $parts = explode("\t", trim($line));
                $data = [];
                foreach ($parts as $part) {
                    if (str_contains($part, '=')) {
                        [$k, $v] = explode('=', $part, 2);
                        $data[$k] = $v;
                    }
                }

                $pin = $data['PIN'] ?? null;
                $fid = $data['FID'] ?? 0;
                $tmp = $data['TMP'] ?? null;

                if ($pin && $tmp) {
                    \App\Models\BioTemplate::updateOrCreate(
                        [
                            'employee_nik' => $pin,
                            'type' => 9, // FACE
                            'no' => $fid,
                        ],
                        [
                            'size' => $data['SIZE'] ?? strlen($tmp),
                            'valid' => $data['VALID'] ?? 1,
                            'content' => $tmp,
                            'device_sn' => $sn,
                        ]
                    );
                    $count++;
                }
            }
            return "OK:$count";
        }

        // 3. User Photos (USERPIC)
        if ($table == 'USERPIC') {
            // USERPIC data is binary in body, usually with CMD=uploadphoto line
            // But in some ADMS push, it's just raw multipart or stream.
            // PushDemo suggests stream reading.
            // The format from PushDemo:
            // Line 1: CMD=uploadphoto
            // Line 2: PIN=1
            // Line 3: FileName=1.jpg
            // Line 4: Size=1234
            // Line 5: Content=... (Binary)
            // ...

            // PHP input stream
            $content = file_get_contents("php://input");

            // Allow larger memory for this
            ini_set('memory_limit', '256M');

            if (preg_match('/PIN=(\d+)/', $content, $pinMatch) && preg_match('/Content=(.*)/s', $content, $contentMatch)) {
                $pin = $pinMatch[1];
                $imageData = $contentMatch[1];

                // Save to storage
                $fileName = "photos/user_{$pin}.jpg";
                \Illuminate\Support\Facades\Storage::disk('public')->put($fileName, $imageData);

                // Update Employee
                $employee = Employee::where('nik', $pin)->first();
                if ($employee) {
                    $employee->update(['photo_path' => $fileName]);
                }

                return "OK";
            }

            return "OK"; // Acknowledge even if fail parsing to stop retry loop
        }

        // 4. Attendance Photos (ATTPHOTO)
        if ($table == 'ATTPHOTO') {
            // Similar to USERPIC but linked to attendance
            $content = file_get_contents("php://input");
            // Format often: CMD=uploadphoto\nPIN=1\nSymbol=...\nSize=...\nContent=...

            if (preg_match('/PIN=(\d+)/', $content, $pinMatch) && preg_match('/Content=(.*)/s', $content, $contentMatch)) {
                $pin = $pinMatch[1];
                $imageData = $contentMatch[1];

                // Try to find date/time in header if available?
                // Usually ZK sends the filename as '1_20231010120000.jpg' or similar in 'FileName='
                // Let's regex the FileName
                $fileNameStr = "";
                if (preg_match('/FileName=([^\s]+)/', $content, $fnMatch)) {
                    $fileNameStr = $fnMatch[1];
                }

                $storePath = "att_photos/" . ($fileNameStr ?: "att_{$pin}_" . time() . ".jpg");
                \Illuminate\Support\Facades\Storage::disk('public')->put($storePath, $imageData);

                // Ideally link to 'attendances' table. We need the timestamp.
                // If filename contains timestamp: PIN_YYYYMMDDHHMMSS.jpg
                // 1001_20251201080000.jpg
                if (preg_match('/_(\d{14})/', $fileNameStr, $timeMatch)) {
                    $ts = Carbon::createFromFormat('YmdHis', $timeMatch[1]);
                    $employee = Employee::where('nik', $pin)->first();
                    if ($employee) {
                        // Find nearest attendance? Or create?
                        // Usually ATTPHOTO comes AFTER ATTLOG.
                        $att = Attendance::where('employee_id', $employee->id)
                            ->whereBetween('created_at', [$ts->copy()->subMinutes(5), $ts->copy()->addMinutes(5)])
                            ->latest()
                            ->first();

                        if ($att) {
                            $att->update(['photo_path' => $storePath]);
                        }
                    }
                }
            }
            return "OK";
        }

        // 5. Operation Logs (OPERLOG) (Modified handling)
        // This block is a placeholder in the instruction, it then says to merge.
        // The final provided code block for `if ($table == 'USERINFO' || $table == 'OPERLOG')`
        // will contain the merged logic. So, I will *not* add a separate `if ($table == 'OPERLOG')` block here.
        // Instead, I will replace the *existing* `if ($table == 'OPERLOG' || $table == 'USERINFO')` block.

        if ($table == 'USERINFO' || $table == 'OPERLOG') {
            // ... (The Logic from previous step) ...
            // We need to re-include it because replace_file_content overwrites.
            $body = $request->getContent();
            Log::info("ADMS Parsing $table Body: " . substr($body, 0, 200));
            $lines = explode("\n", $body);
            $count = 0;
            $defaultPosition = \App\Models\Position::first();

            foreach ($lines as $line) {
                if (empty(trim($line)))
                    continue;

                // OPLOG Parsing (Added)
                if ($table == 'OPERLOG' && str_starts_with($line, 'OPLOG ')) {
                    $opLine = substr($line, 6);
                    $parts = preg_split('/\s+/', trim($opLine));
                    if (count($parts) >= 4) {
                        $opType = $parts[0];
                        $operator = $parts[1];
                        $time = $parts[2] . ' ' . $parts[3];

                        \App\Models\AuditLog::create([
                            'event_time' => $time,
                            'event_type' => $opType,
                            'module' => 'DEVICE_OPLOG',
                            'description' => "Operation $opType by Operator $operator",
                            'ip_address' => $request->ip(),
                            'user_agent' => 'Device ' . $sn,
                            'is_from_device' => true,
                            'device_sn' => $sn,
                            'properties' => [
                                'operator' => $operator,
                                'value1' => $parts[4] ?? null,
                                'value2' => $parts[5] ?? null,
                            ],
                        ]);
                    }
                    continue; // Done with this line
                }

                // USER Parsing
                if ($table == 'OPERLOG' && str_starts_with($line, 'USER ')) {
                    $line = substr($line, 5);
                }
                if ($table == 'OPERLOG' && !str_contains($line, 'PIN=') && !str_contains($line, 'Name=')) {
                    continue;
                }

                $parts = explode("\t", trim($line));
                $userData = [];
                foreach ($parts as $part) {
                    if (str_contains($part, '=')) {
                        [$k, $v] = explode('=', $part, 2);
                        $userData[$k] = $v;
                    }
                }

                if (empty($userData)) {
                    $parts = preg_split('/\s+/', trim($line));
                }

                $pin = $userData['PIN'] ?? $userData['Pin'] ?? null;
                $name = $userData['Name'] ?? null;
                $privilege = $userData['Pri'] ?? $userData['Privilege'] ?? 0;

                if ($pin) {
                    $employee = Employee::where('nik', $pin)->first();
                    if ($employee) {
                        $updates = [];
                        if ($name)
                            $updates['first_name'] = $name;
                        if (isset($userData['Pri']) || isset($userData['Privilege'])) {
                            $updates['device_privilege'] = $privilege;
                        }
                        if (!empty($updates)) {
                            $employee->update($updates);
                            Log::info("Updated Employee $pin: " . json_encode($updates));
                        }
                    } else {
                        Employee::create([
                            'nik' => $pin,
                            'first_name' => $name ?? 'Device User ' . $pin,
                            'last_name' => '',
                            'date_of_birth' => '1990-01-01',
                            'gender' => 'male',
                            'hire_date' => now(),
                            'position_id' => $defaultPosition?->id ?? 1,
                            'device_privilege' => $privilege,
                        ]);
                        Log::info("Created Employee for PIN $pin Name " . ($name ?? 'Unknown') . " Pri: $privilege");
                    }
                    $count++;
                }
            }
            return "OK:$count";
        }

        // Heartbeat or other data
        return "OK";
    }

    /**
     * Get Request (Device asking for commands)
     * URL: /iclock/getrequest
     */
    public function getrequest(Request $request)
    {
        $sn = $request->query('SN');
        Log::info("ADMS: Get Request SN: $sn");

        if (!$sn)
            return "Error";

        $device = Device::where('sn', $sn)->first();
        if (!$device) {
            // Auto register if not found
            $device = Device::create([
                'sn' => $sn,
                'alias' => 'Auto-Detected ' . $sn,
                'ip_address' => $request->ip(),
                'last_activity' => now(),
                'state' => 1,
            ]);
        } else {
            $device->update(['last_activity' => now(), 'ip_address' => $request->ip()]);
        }

        // Fetch pending commands
        // We look for commands where trans_time is NULL (not yet sent/processed)
        $commands = DeviceCommand::where('device_sn', $sn)
            ->whereNull('trans_time')
            ->orderBy('id', 'asc')
            ->limit(10)
            ->get();

        if ($commands->isEmpty()) {
            return "OK";
        }

        $cmdBuffer = "";
        foreach ($commands as $cmd) {
            // Format ZK: C:ID:COMMAND_STRING
            $cmdBuffer .= "C:" . $cmd->id . ":" . $cmd->content . "\n";
            // Mark as sent? Usually we mark when devicecmd confirms delivery
            // But ADMS might re-request if not confirmed.
            // For now, let's keep it until confirmed/executed in devicecmd.
            // But some implementations mark 'sent' here.
        }

        return $cmdBuffer;
    }

    /**
     * Device Command Feedback
     * URL: /iclock/devicecmd
     */
    public function devicecmd(Request $request)
    {
        $sn = $request->query('SN');
        Log::info("ADMS: Device Cmd Feedback SN: $sn | Body: " . $request->getContent() . " | Query: " . json_encode($request->all()));

        // Body typically: ID=1&Return=0&CMD=DATA
        // Process POST data
        $content = $request->getContent();

        // Parse lines
        // ID=123&Return=0

        // Or sometimes query string? Usually POST body.

        if ($content) {
            parse_str($content, $data);
            if (isset($data['ID']) && isset($data['Return'])) {
                $cmdId = $data['ID'];
                $ret = $data['Return'];

                $cmd = DeviceCommand::find($cmdId);
                if ($cmd) {
                    $cmd->update([
                        'trans_time' => now(),
                        'return_value' => $ret
                    ]);
                }
            }

            // Check if body contains stats (UserCount, etc.)
            // The logs show the body contains "~DeviceName=...", "UserCount=39", etc.
            // separated by newlines.
            if (str_contains($content, 'UserCount=') || str_contains($content, 'FPCount=')) {
                $updateData = [];
                // Split by newline
                $lines = explode("\n", $content);
                foreach ($lines as $line) {
                    $line = trim($line);
                    if (empty($line) || str_starts_with($line, '~'))
                        continue; // Skip empty or params starting with ~ if not needed (though some like ~SerialNumber might be useful, we use standard keys)

                    if (str_contains($line, '=')) {
                        [$key, $value] = explode('=', $line, 2);
                        $key = trim($key);
                        $value = trim($value);

                        if (strcasecmp($key, 'UserCount') === 0)
                            $updateData['user_count'] = $value;
                        if (strcasecmp($key, 'FPCount') === 0)
                            $updateData['fp_count'] = $value;
                        if (strcasecmp($key, 'FaceCount') === 0)
                            $updateData['face_count'] = $value;
                        if (strcasecmp($key, 'TransactionCount') === 0)
                            $updateData['transaction_count'] = $value;
                        if (strcasecmp($key, 'FWVersion') === 0)
                            $updateData['fw_ver'] = $value;
                        if (strcasecmp($key, 'PushVersion') === 0)
                            $updateData['push_version'] = $value;
                        if (strcasecmp($key, 'IPAddress') === 0)
                            $updateData['ip_address'] = $value;
                    }
                }

                if (!empty($updateData)) {
                    $device = Device::where('sn', $sn)->first();
                    if ($device) {
                        $device->update($updateData);
                        Log::info("Device Stats Updated from CMD Feedback for $sn: " . json_encode($updateData));
                    }
                }
            }
        }

        return "OK";
    }

    public function ping(Request $request)
    {
        return "OK";
    }
}
