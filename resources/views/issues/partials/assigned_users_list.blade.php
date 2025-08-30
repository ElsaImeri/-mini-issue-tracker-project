@if($assignedUsers->count() > 0)
    <div class="assigned-users mt-3">
        <h6>Assigned Users:</h6>
        <div class="d-flex flex-wrap gap-2">
            @foreach($assignedUsers as $user)
                <span class="badge bg-primary">
                    {{ $user->name }}
                    @if(isset($showDetach) && $showDetach)
                        <button type="button" class="btn-close btn-close-white ms-1" 
                                style="font-size: 0.6rem;"
                                onclick="detachUser({{ $issue->id }}, {{ $user->id }})"></button>
                    @endif
                </span>
            @endforeach
        </div>
    </div>
@else
    <p class="text-muted">No users assigned to this issue.</p>
@endif