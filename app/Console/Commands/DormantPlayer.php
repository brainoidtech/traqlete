<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Department;
use App\Models\Player;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

// php /home/u455530407/domains/staging.sportfolioapp.in/public_html/deccan-gymkhana/artisan DormantPlayer:run

class DormantPlayer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'DormantPlayer:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Update player status based on attendance and their fee valid_to date ";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {

            // |--------------------------------------------------------------------------
            // | 1. get all department name 
            // |--------------------------------------------------------------------------

            $department = Department::select('dept_name', 'dept_id')->get();
            $today = Carbon::today()->toDateString();
            $lastFifteenDay = Carbon::today()->subDays(14)->toDateString();



            foreach ($department as $dept) {
                $deptName = strtolower($dept->dept_name);


                //    $this->info($deptName); 
                $feeTable = $deptName . '_fee_validity';
                $playerAttenTable = $deptName . '_player_attendance';


                // |--------------------------------------------------------------------------
                // | 2.get fee expired player id 
                // |--------------------------------------------------------------------------


                $expiredPlayerFee = DB::table($feeTable . '  as fee')
                    ->whereDate('fee.valid_to', '<', $today)
                    ->whereRaw('fee.created_at = (
                             SELECT MAX(fee2.created_at)
                             FROM ' . $feeTable . ' as fee2
                             WHERE fee2.dept_player_id = fee.dept_player_id
                         )')
                    ->join('dept_players as dp', 'dp.id', '=', 'fee.dept_player_id')
                    ->join('players as pl', 'pl.player_id', '=', 'dp.player_id')
                    ->pluck('dp.player_id')
                    ->toArray();




                // |--------------------------------------------------------------------------
                // | 3.get 15 day absent player attendance 
                // |--------------------------------------------------------------------------

                $absentPlayerAtten = DB::table('dept_players as dp')
                    ->join('players as p', 'p.player_id', '=', 'dp.player_id')
                    ->where('dp.dept_id', $dept->dept_id)
                    ->whereDate('p.created_at', '<=',  $lastFifteenDay)
                    ->whereNotExists(function ($query) use ($playerAttenTable, $today, $lastFifteenDay) {
                        $query->select(DB::raw(1))
                            ->from($playerAttenTable . ' as pa')

                            ->whereColumn('pa.player_id', 'p.player_id')
                            ->whereBetween('pa.attendance_date', [$lastFifteenDay, $today]);
                    })
                    ->pluck('p.player_id')
                    ->toArray();


                // |--------------------------------------------------------------------------
                // | 4. Update player status 
                // |--------------------------------------------------------------------------

                $LeftPlayerIds = array_intersect($absentPlayerAtten, $expiredPlayerFee);
                $DormantPlayerIds = array_diff($absentPlayerAtten, $expiredPlayerFee);


                if (!empty($LeftPlayerIds)) {
                    Player::whereIn('player_id', $LeftPlayerIds)
                        ->update(['status' => 0]);
                }

                if (!empty($DormantPlayerIds)) {
                    Player::whereIn('player_id', $DormantPlayerIds)
                        ->update(['status' => 2]);
                }
            }
            $this->info('player status updated!');
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
