 @forelse($coach_attend as $index => $coach)
 
 <tr>
	<td>{{ ++$index }}</td>
 	<td>{{ $coach->attendance_date }}</td>
 	<td>{{ $coach->first_name. ' '. $coach->middle_name. ' '. $coach->last_name ?? '' }}</td>
 	<td>{{ $coach->coach_id }}</td>
 	<td>{{ $coach->batch_name ?? '' }}</td>
 	<td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $coach->in_time)->format('g:i:s A') }}
 	</td>
 	<td>{{ $coach->out_time ? \Carbon\Carbon::parse($coach->out_time)->format('g:i:s A') : '-' }}
 	</td>
 	<td>
 		<a href="{{ url('coach-profile/'.$coach->coach_id.'?tab=attendance_tab') }}">
 			<i class="bi bi-eye-fill px-1"></i>
 		</a>
 	</td>
 </tr>
 @empty
 <td colspan="8" class="dt-empty">No records found</td>

 @endforelse