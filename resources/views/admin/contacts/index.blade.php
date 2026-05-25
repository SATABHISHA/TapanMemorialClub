@extends('layouts.admin')

@section('content')
<div class="glass-card p-4">
    <h5 class="mb-3 text-warning">Contact Inbox</h5>
    <table class="table table-dark table-hover align-middle">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email / Phone</th>
                <th>Subject</th>
                <th>Preview</th>
                <th>Status</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contacts as $contact)
                <tr>
                    <td>{{ $contact->name }}</td>
                    <td>
                        <div>{{ $contact->email }}</div>
                        @if($contact->phone)
                            <small class="text-light-emphasis">{{ $contact->phone }}</small>
                        @endif
                    </td>
                    <td>{{ $contact->subject ?: '-' }}</td>
                    <td style="max-width: 360px;">
                        <span title="{{ $contact->message }}">{{ \Illuminate\Support\Str::limit($contact->message, 140) }}</span>
                    </td>
                    <td>
                        @if($contact->is_read)
                            <span class="badge text-bg-success">Read</span>
                        @else
                            <span class="badge text-bg-warning">Unread</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-2">
                            <form method="POST" action="{{ route('admin.contacts.update', $contact) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="is_read" value="{{ $contact->is_read ? 0 : 1 }}">
                                <button class="btn btn-sm btn-outline-info">{{ $contact->is_read ? 'Mark Unread' : 'Mark Read' }}</button>
                            </form>
                            <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $contacts->links() }}
</div>
@endsection
