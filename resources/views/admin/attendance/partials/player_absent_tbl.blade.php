@if( isset($absent_players) && $absent_players->isNotEmpty())
@foreach($absent_players as $index => $absentPlayer)
<tr>
    <td>{{ ++$index }}</td>
    <td>{{ $filterDate }}</td>
    <td>{{ $absentPlayer->fullName ?? $absentPlayer->first_name.' '. $absentPlayer->middle_name.' '.$absentPlayer->last_name }}</td>
    <td>{{ $absentPlayer->player_id}}</td>
    <td>{{ $absentPlayer->batch->batch_name ?? $absentPlayer->batch_name }}</td>

    <td>{{ $absentPlayer->coach->fullName ?? $absentPlayer->coach_fname.' '.$absentPlayer->coach_mname.' '.$absentPlayer->coach_lname}}</td>
    <td>
        <a href="{{ url('players-profile/'.$absentPlayer->player_id ) }}">
            <i class="bi bi-eye-fill px-1"></i>
        </a>
    </td>
</tr>
@endforeach

@else

<td colspan="8" class="dt-empty text-center">No data available in table</td>

@endif