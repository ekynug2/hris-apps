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

        Log::info("ADMS: Device Registry SN: $sn");

        if ($sn) {
            $device = Device::firstOrCreate(
                ['sn' => $sn],
                [
                    'ip_address' => $request->ip(),
                    'last_activity' => now(),
                    'push_version' => $request->input('pushver'),
                    'dev_language' => $request->input('language'),
                ]
            );

            $device->update(['last_activity' => now()]);

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

        Log::info("ADMS: Data Push SN: $sn | Table: $table");

        if (!$sn)
            return "Error";

        $device = Device::where('sn', $sn)->first();
        if ($device) {
            $device->update([
                'ip_address' => $request->ip(),
                'last_activity' => now()
            ]);
        }

        if ($table == 'ATTLOG') {
            $body = $request->getContent();
            // Lines are standard: ID  timestamp  status  ...
            // Or tab separated depending on fw.

            $lines = explode("\n", $body);
            $count = 0;

            foreach ($lines as $line) {
                if (empty(trim($line)))
                    continue;

                // Format: PIN  Time  Status  Verify  WorkCode  Reserved  Reserved
                // Split by tab or space
                $data = preg_split('/\s+/', trim($line));

                if (count($data) >= 2) {
                    $pin = $data[0];
                    $time = $data[1];
                    if (isset($data[2]) && preg_match('/\d{2}:\d{2}:\d{2}/', $data[2])) {
                        // Sometimes time is split into date time
                        $time .= ' ' . $data[2];
                        $status = $data[3] ?? 0;
                    } else {
                        $status = $data[2] ?? 0;
                    }

                    // Find employee
                    $employee = Employee::where('nik', $pin)->first();
                    // Or search by ID if nik is numeric match
                    if (!$employee && is_numeric($pin)) {
                        $employee = Employee::find($pin);
                    }

                    if ($employee) {
                        // Check duplicate
                        $exists = Attendance::where('employee_id', $employee->id)
                            ->where('date', Carbon::parse($time)->toDateString())
                            ->where('clock_in', Carbon::parse($time)->toTimeString())
                            ->exists(); // Strict duplicate check logic might differ

                        if (!$exists) {
                            // Simple logic: insert as generic attendance
                            // In real system, logic to determine IN/OUT based on time or status
                            // Status: 0=CheckIn, 1=CheckOut, etc (varies by device)

                            // For this MVP, we just create a record.
                            // Better logic: Find attendance for that day
                            $date = Carbon::parse($time)->toDateString();
                            $att = Attendance::firstOrCreate(
                                ['employee_id' => $employee->id, 'date' => $date],
                                ['status' => 'present']
                            );

                            // 0/4/5 => Check IN usually
                            // 1 => Check OUT usually
                            // But let's assume raw log processing.
                            // We update clock_in if empty, else clock_out (simple logic)

                            if (in_array($status, ['0', '4', '5'])) {
                                if (!$att->clock_in)
                                    $att->clock_in = Carbon::parse($time)->toTimeString();
                            } else {
                                if (!$att->clock_out)
                                    $att->clock_out = Carbon::parse($time)->toTimeString();
                            }
                            $att->save();
                            $count++;
                        }
                    }
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
        if (!$device)
            return "OK";

        $device->update(['last_activity' => now()]);

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
        Log::info("ADMS: Device Cmd Feedback SN: $sn");

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
        }

        return "OK";
    }

    public function ping(Request $request)
    {
        return "OK";
    }
}
