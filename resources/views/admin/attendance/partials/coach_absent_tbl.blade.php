@forelse($absent_coaches as  $index => $coach)
<tr>
    <td>{{ ++$index }}</td>
    <td>{{ $filterDate }}</td>
    <td>{{ $coach->first_name . ' '. $coach->middle_name . ' '. $coach->last_name}}</td>
    <td>{{ $coach->id }}</td>
    <td>{{ $coach->batch[0]->batch_name  ?? $coach->batch_name}}</td>
    <td>
        <a  href="{{ url('coach-profile/'.$coach->id.'?tab=attendance_tab') }}">
            <i class="bi bi-eye-fill px-1"></i>
        </a>
    </td>
</tr>
 @empty
 <td colspan="8" class="dt-empty">No records found</td>

 @endforelse