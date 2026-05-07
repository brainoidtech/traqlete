<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FeesValidity extends Model
{
    use HasFactory;
    protected $table = 'fee_validity';

    protected $fillable = [
        'id',
        'dept_player_id',
        'approved_by',
        'valid_from',
        'valid_to',
        'receipt_no',
        'date',
        'amount',
    ];
    public function deptPlayer()
    {
        return $this->belongsTo(DepartmentPlayers::class, 'dept_player_id');
    }



    // -------------------------------------------------------
    // DYNAMIC TABLE RESOLVER
    // -------------------------------------------------------

    /**
     * Returns model instance scoped to dept table.
     * e.g. "Badminton" => "badminton_fee_validity"
     */
    public static function forDept(?string $deptName = null): static
    {
        $instance = new static();
        $dept     = $deptName ?? Auth::user()->resolveDeptName();
        if (!$dept) {
            throw new \Exception('Please select a department first.');
        }
        $instance->table = strtolower(str_replace(' ', '_', $dept)) . '_fee_validity';
        return $instance;
    }

    /**
     * Returns query builder scoped to dept table.
     * Usage: FeesValidity::onDept('badminton')->where(...)->get()
     */
    public static function onDept(?string $deptName = null)
    {
        return static::forDept($deptName)->newQuery();
    }


    // ************************************************ Dashboard fee section **************************************************

    public function insertFee($data, $user_id, $dept_player_id)
    {
        $fee = new FeesValidity;

        $fee->valid_from = $data['valid_from'];
        $fee->valid_to = $data['valid_to'];
        $fee->receipt_no = $data['receipt_no'];
        $fee->amount = $data['amount'];
        $fee->date = $data['date'];
        $fee->approved_by = $user_id;
        $fee->dept_player_id = $dept_player_id;

        $fee->save();
    }

    public function getAllPlayerFee(?string $deptName = null): \Illuminate\Support\Collection
    {
        return static::onDept($deptName)
            ->with('deptPlayer.player', 'deptPlayer.feeValiditys')
            ->get();
    }

    public function getFeesValidity($deptPlayerId)
    {
        $deptName = auth()->user()->resolveDeptName(); // ✅ important

        if (!$deptName) {
            throw new \Exception('Please select a department first.');
        }

        $table = $deptName . '_fee_validity';

        return \DB::table($table)
            ->where('dept_player_id', $deptPlayerId)
            ->orderBy('valid_from', 'desc')
            ->get();
    }

    // public static function forDept(?string $deptName = null): static
    // {
    //     $instance = new static();
    //     $dept     = $deptName ?? Auth::user()->resolveDeptName();

    //     if (!$dept) {
    //         throw new \Exception('Please select a department first.');
    //     }

    //     $instance->table = strtolower(str_replace(' ', '_', $dept)) . '_fee_validity';
    //     return $instance;
    // }

    public function insertfeesdetailsByDepa($deptPlayer, $user, array $data): static
    {
        $deptName = $user->resolveDeptName();

        if (!$deptName) {
            throw new \Exception('Please select a department first.');
        }

        $instance = static::forDept($deptName);
        $instance->fill([
            'dept_player_id' => $deptPlayer->id,
            'approved_by'    => $user->id,
            'valid_from'     => $data['valid_from'],
            'valid_to'       => $data['valid_to'],
            'receipt_no'     => $data['receipt_no'],
            'amount'         => $data['amount'],
            'date'           => $data['date'],
        ]);
        $instance->save();

        return $instance;
    }
}
