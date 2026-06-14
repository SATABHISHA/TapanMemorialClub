@extends('layouts.admin')

@section('content')
@if(session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- ========== HOMEPAGE VISIBILITY CONTROLS ========== --}}
<div class="glass-card p-4 mb-4" style="border:1px solid rgba(212,175,55,.25);">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="mb-1 text-warning"><i class="bi bi-display"></i> Homepage Performance Visibility Controls</h5>
            <small class="text-light-emphasis">Control introduction cards, summary cards, Tournament Records, and Performance Recap independently on the public homepage.</small>
        </div>
        <a href="{{ url('/#live-performance') }}" target="_blank" class="btn btn-sm btn-outline-info">
            <i class="bi bi-eye"></i> Preview on Site
        </a>
    </div>
    <div class="row g-3">
        <div class="col-lg-3">
            <form method="POST" action="{{ route('admin.performances.visibility') }}" class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                @csrf
                <input type="hidden" name="club_introduction_feature_cards_visible" value="0">
                <div class="form-check form-switch m-0">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        role="switch"
                        name="club_introduction_feature_cards_visible"
                        value="1"
                        id="clubIntroductionFeatureCardsVisible"
                        onchange="this.form.submit()"
                        @checked($isIntroductionFeatureCardsVisible ?? true)
                    >
                    <label class="form-check-label" for="clubIntroductionFeatureCardsVisible">
                        {{ ($isIntroductionFeatureCardsVisible ?? true) ? 'Intro Feature Cards: Visible (Unhide)' : 'Intro Feature Cards: Hidden (Hide)' }}
                    </label>
                </div>
                <button class="btn btn-gold btn-sm">
                    <i class="bi bi-save"></i> Save
                </button>
            </form>
        </div>
        <div class="col-lg-3">
            <form method="POST" action="{{ route('admin.performances.visibility') }}" class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                @csrf
                <input type="hidden" name="club_performance_summary_visible" value="0">
                <div class="form-check form-switch m-0">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        role="switch"
                        name="club_performance_summary_visible"
                        value="1"
                        id="clubPerformanceSummaryVisible"
                        onchange="this.form.submit()"
                        @checked($isPerformanceSummaryVisible ?? true)
                    >
                    <label class="form-check-label" for="clubPerformanceSummaryVisible">
                        {{ ($isPerformanceSummaryVisible ?? true) ? 'Summary Cards: Visible (Unhide)' : 'Summary Cards: Hidden (Hide)' }}
                    </label>
                </div>
                <button class="btn btn-gold btn-sm">
                    <i class="bi bi-save"></i> Save
                </button>
            </form>
        </div>
        <div class="col-lg-3">
            <form method="POST" action="{{ route('admin.performances.visibility') }}" class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                @csrf
                <input type="hidden" name="club_tournament_records_visible" value="0">
                <div class="form-check form-switch m-0">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        role="switch"
                        name="club_tournament_records_visible"
                        value="1"
                        id="clubTournamentRecordsVisible"
                        onchange="this.form.submit()"
                        @checked($isTournamentRecordsVisible ?? true)
                    >
                    <label class="form-check-label" for="clubTournamentRecordsVisible">
                        {{ ($isTournamentRecordsVisible ?? true) ? 'Tournament Records: Visible (Unhide)' : 'Tournament Records: Hidden (Hide)' }}
                    </label>
                </div>
                <button class="btn btn-gold btn-sm">
                    <i class="bi bi-save"></i> Save
                </button>
            </form>
        </div>
        <div class="col-lg-3">
            <form method="POST" action="{{ route('admin.performances.visibility') }}" class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                @csrf
                <input type="hidden" name="club_performance_recap_visible" value="0">
                <div class="form-check form-switch m-0">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        role="switch"
                        name="club_performance_recap_visible"
                        value="1"
                        id="clubPerformanceRecapVisible"
                        onchange="this.form.submit()"
                        @checked($isPerformanceRecapVisible ?? true)
                    >
                    <label class="form-check-label" for="clubPerformanceRecapVisible">
                        {{ ($isPerformanceRecapVisible ?? true) ? 'Performance Recap: Visible (Unhide)' : 'Performance Recap: Hidden (Hide)' }}
                    </label>
                </div>
                <button class="btn btn-gold btn-sm">
                    <i class="bi bi-save"></i> Save
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ========== PERFORMANCE RECAP TEXT EDITOR ========== --}}
<div class="glass-card p-4 mb-4" style="border:1px solid rgba(212,175,55,.25);">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="mb-1 text-warning"><i class="bi bi-journal-richtext"></i> Performance Recap Paragraph</h5>
            <small class="text-light-emphasis">Long-form text shown on the homepage under the chart.</small>
        </div>
        <a href="{{ url('/#live-performance') }}" target="_blank" class="btn btn-sm btn-outline-info">
            <i class="bi bi-eye"></i> Preview on Site
        </a>
    </div>
    <form method="POST" action="{{ route('admin.performances.recap-text') }}">
        @csrf
        <div class="alert alert-dark border-warning small mb-3" style="background:rgba(212,175,55,.08);">
            <strong class="text-warning"><i class="bi bi-info-circle"></i> Format Guide</strong>
            <div class="mt-1">Start each season with <code class="text-warning">YYYY-YY :</code> (e.g. <code>2017-18 :</code>) and separate accomplishments with <code class="text-warning"> – </code> (en-dash with spaces). The homepage will auto-render each season as a trophy card.</div>
            <div class="mt-2 small text-light-emphasis"><strong>Example:</strong><br>
            <code>2017-18 : CAB 1st Division League – 2nd position in Group – Pre-Quarter Final CAB Senior Knockout – Semi Final J.C Mukherjee Trophy</code></div>
        </div>
        <textarea name="value" class="form-control" rows="8" style="font-family: 'Inter', monospace; font-size:.9rem; line-height:1.7;" placeholder="2013-14 : CAB 1st Division League – 1st position in Group – Pre-Quarter Final CAB Senior Knockout – Pre-Quarter final J.C Mukherjee Tournament Trophy – Knockout Stage&#10;2014-15 : CAB 1st Division League – 2nd position in Group – ...&#10;2015-16 : CAB 1st Division League – ...">{{ old('value', $performanceText ?? '') }}</textarea>
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <small class="text-light-emphasis"><i class="bi bi-lightbulb text-warning"></i> Tip: one season per line keeps the source readable; parser is whitespace-tolerant.</small>
            <button class="btn btn-gold"><i class="bi bi-save"></i> Save Recap Text</button>
        </div>
    </form>
</div>

{{-- ========== ADD PERFORMANCE FORM ========== --}}
<div class="glass-card p-4 mb-4">
    <h5 class="mb-3 text-warning"><i class="bi bi-plus-circle"></i> Add Tournament Record</h5>
    <form method="POST" action="{{ route('admin.performances.store') }}" class="row g-3">
        @csrf
        <div class="col-md-2"><label class="form-label small mb-1">Year</label><input name="year" type="number" class="form-control" placeholder="2024" required></div>
        <div class="col-md-3"><label class="form-label small mb-1">Tournament</label><input name="tournament" class="form-control" placeholder="CAB 1st Division League" required></div>
        <div class="col-md-2"><label class="form-label small mb-1">Position</label><input name="position" class="form-control" placeholder="Champion"></div>
        <div class="col-md-1"><label class="form-label small mb-1">M</label><input name="matches_played" type="number" class="form-control" placeholder="0"></div>
        <div class="col-md-1"><label class="form-label small mb-1">W</label><input name="wins" type="number" class="form-control" placeholder="0"></div>
        <div class="col-md-1"><label class="form-label small mb-1">L</label><input name="losses" type="number" class="form-control" placeholder="0"></div>
        <div class="col-md-1"><label class="form-label small mb-1">Pts</label><input name="points" type="number" class="form-control" placeholder="0"></div>
        <div class="col-md-1 d-grid align-items-end"><label class="form-label small mb-1">&nbsp;</label><button class="btn btn-gold">Add</button></div>
        <div class="col-md-3"><label class="form-label small mb-1">Highlight Color</label><input name="highlight_color" type="color" class="form-control form-control-color" value="#D4AF37"></div>
        <div class="col-md-7"><label class="form-label small mb-1">Description (optional)</label><input name="description" class="form-control" placeholder="Brief note about this campaign"></div>
        <div class="col-md-2 d-flex align-items-end"><div class="form-check"><input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featuredNew"><label class="form-check-label" for="featuredNew">Featured</label></div></div>
    </form>
</div>

{{-- ========== TOURNAMENTS TABLE ========== --}}
<div class="glass-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 text-warning"><i class="bi bi-list-stars"></i> Tournament Records</h5>
        <span class="text-light-emphasis small">{{ $performances->total() }} entries</span>
    </div>
    <div class="table-responsive">
        <table class="table table-dark table-hover align-middle">
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Tournament</th>
                    <th>Position</th>
                    <th>M</th>
                    <th>W</th>
                    <th>L</th>
                    <th>Pts</th>
                    <th>Featured</th>
                    <th style="min-width:230px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($performances as $item)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $item->year }}</span></td>
                        <td><strong>{{ $item->tournament }}</strong>@if($item->description)<div class="small text-light-emphasis">{{ \Illuminate\Support\Str::limit($item->description, 60) }}</div>@endif</td>
                        <td>{{ $item->position }}</td>
                        <td>{{ $item->matches_played }}</td>
                        <td class="text-info">{{ $item->wins }}</td>
                        <td class="text-danger">{{ $item->losses }}</td>
                        <td class="text-warning fw-bold">{{ $item->points }}</td>
                        <td>@if($item->is_featured)<i class="bi bi-star-fill text-warning"></i>@else<i class="bi bi-star text-light-emphasis"></i>@endif</td>
                        <td>
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editPerf{{ $item->id }}">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <a href="{{ url('/#live-performance') }}" target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye"></i> Preview
                                </a>
                                <form method="POST" action="{{ route('admin.performances.destroy', $item) }}" onsubmit="return confirm('Delete this record?');" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-light-emphasis py-4">No tournament records yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $performances->links() }}
</div>

{{-- ========== EDIT MODALS ========== --}}
@foreach($performances as $item)
    <div class="modal fade" id="editPerf{{ $item->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="background:#0e1932;border:1px solid rgba(212,175,55,.3);color:#fff;">
                <div class="modal-header" style="border-bottom:1px solid rgba(212,175,55,.2);">
                    <h5 class="modal-title text-warning"><i class="bi bi-pencil-square"></i> Edit Tournament Record</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.performances.update', $item) }}">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-3"><label class="form-label small">Year</label><input name="year" type="number" class="form-control" value="{{ $item->year }}" required></div>
                            <div class="col-md-6"><label class="form-label small">Tournament</label><input name="tournament" class="form-control" value="{{ $item->tournament }}" required></div>
                            <div class="col-md-3"><label class="form-label small">Position</label><input name="position" class="form-control" value="{{ $item->position }}"></div>
                            <div class="col-md-3"><label class="form-label small">Matches</label><input name="matches_played" type="number" class="form-control" value="{{ $item->matches_played }}"></div>
                            <div class="col-md-3"><label class="form-label small">Wins</label><input name="wins" type="number" class="form-control" value="{{ $item->wins }}"></div>
                            <div class="col-md-3"><label class="form-label small">Losses</label><input name="losses" type="number" class="form-control" value="{{ $item->losses }}"></div>
                            <div class="col-md-3"><label class="form-label small">Points</label><input name="points" type="number" class="form-control" value="{{ $item->points }}"></div>
                            <div class="col-md-3"><label class="form-label small">Highlight Color</label><input name="highlight_color" type="color" class="form-control form-control-color" value="{{ $item->highlight_color ?: '#D4AF37' }}"></div>
                            <div class="col-md-9"><label class="form-label small">Description</label><textarea name="description" class="form-control" rows="2">{{ $item->description }}</textarea></div>
                            <div class="col-md-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="featuredEdit{{ $item->id }}" @checked($item->is_featured)>
                                    <label class="form-check-label" for="featuredEdit{{ $item->id }}">Featured (highlight on homepage)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid rgba(212,175,55,.2);">
                        <a href="{{ url('/#live-performance') }}" target="_blank" class="btn btn-sm btn-outline-info me-auto"><i class="bi bi-eye"></i> Preview Live</a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-gold"><i class="bi bi-save"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection
