<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Department;
use App\Models\Coach;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UpdateCoachAttendance extends Command
{
	// php /home/u455530407/domains/staging.sportfolioapp.in/public_html/deccan-gymkhana/artisan UpdateCoachLastOutTime:run
	/**
		* The name and signature of the console command.
		*
		* @var string
		*/
	protected $signature = 'UpdateCoachLastOutTime:run';

	/**
		* The console command description.
		*
		* @var string
		*/
	protected $description = 'update Coach Last out time attendance and truncate the table.';

	/**
		* Execute the console command.
		*/
	public function handle()
	{
		// |--------------------------------------------------------------------------
		// | 1. get all department name 
		// |--------------------------------------------------------------------------

		$department = Department::select('dept_name', 'dept_id')->get();
		$today = Carbon::today()->toDateString();

		//Get all coach 

		foreach ($department as $dept) {

			$deptName = strtolower($dept->dept_name);

			$table = $deptName . '_coach_attendance';
			$temp_table = $deptName . '_temp_coach_attendance';

			// $table = 'kabaddi_coach_attendance'; 
			// $temp_table = 'kabaddi_temp_coach_attendance';

			DB::table($table . ' as main')
				->join(
					DB::raw("         
                        (
                            SELECT ca1.coach_id, ca1.attendance_date, ca1.out_time
                            FROM {$temp_table} ca1
                            INNER JOIN (                                     
                                SELECT coach_id, attendance_date, MAX(created_at) as max_created
                                FROM {$temp_table}
                                WHERE attendance_date = '{$today}'
                                AND deleted_at IS NULL
                                GROUP BY coach_id, attendance_date
                            )  ca2                      
                            ON ca1.coach_id = ca2.coach_id
                            AND ca1.attendance_date = ca2.attendance_date
                            AND ca1.created_at = ca2.max_created
                        ) as temp"), // end DB raw
					function ($join) {
						$join->on('main.coach_id', '=', 'temp.coach_id')
							->on('main.attendance_date', '=', 'temp.attendance_date');
					} // end sub function 
				) // end join
				->where('main.attendance_date', $today)
				->whereNull('main.deleted_at')
				->whereNull('main.out_time')
				->update([
					'main.out_time' => DB::raw('temp.out_time')
				]);


			$this->info('out time updated successfully!');

			// DB::table($temp_table)->truncate();
				$this->info('temp table deleted successfully!');

		}
	}
}
