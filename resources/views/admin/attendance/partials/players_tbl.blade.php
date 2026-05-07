@php
    $player_attend = $player_attend ?? collect();
@endphp

@if($player_attend->isNotEmpty())

    @foreach($player_attend as $index => $players)

        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $players->attendance_date ?? '-' }}</td>
            <td>{{ $players->first_name ?? '-' }} {{ $players->middle_name ?? '-' }}  {{ $players->last_name ?? '-' }}</td>
            <td>{{ $players->player_id ?? '-' }}</td>
            <td>{{ $players->batch_name ?? '-' }}</td>
           <td>{{ $players->coach_first_name.' '.$players->coach_middle_name.' '.$players->coach_last_name ?? '-' }}</td>
            <td>
                {{ $players->in_time 
                    ? \Carbon\Carbon::createFromFormat('H:i:s', $players->in_time)->format('g:i A') 
                    : '-' }}
            </td>
            <td>
                {{ $players->out_time 
                    ? \Carbon\Carbon::parse($players->out_time)->format('g:i A') 
                    : '-' }}
            </td>
            <td>
                <a href="{{ url('players-profile/'.$players->player_id ) }}">
                    <i class="bi bi-eye-fill px-1"></i>
                </a>
            </td>
        </tr>
    @endforeach

@else
    
        <td colspan="8" class="dt-empty text-center">
            No data available in table
        </td>
   
@endif