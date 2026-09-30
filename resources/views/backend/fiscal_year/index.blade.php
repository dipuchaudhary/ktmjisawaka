@extends('layouts.master')

@section('content')
<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>वित्तीय वर्ष व्यवस्थापन</h2>
            <p class="text-muted mb-0">पुराना डाटा archive मा सुरक्षित हुन्छ। archive वर्ष read-only हुन्छ।</p>
        </div>
        <a href="{{ route('fiscal-year.index') }}" class="btn btn-outline-primary">Refresh</a>
    </div>

    <div class="alert {{ $selected->is_current ? 'alert-success' : 'alert-warning' }}">
        हाल चयन गरिएको वित्तीय वर्ष:
        <strong>{{ $selected->display_name }}</strong>
        @if($selected->is_current)
            <span class="badge badge-success ml-2">चालु</span>
        @else
            <span class="badge badge-warning ml-2">Archive / Read Only</span>
        @endif
    </div>

    <div class="card mb-4">
        <div class="card-header"><strong>वित्तीय वर्षहरू</strong></div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>वित्तीय वर्ष</th>
                            <th>स्थिति</th>
                            <th>कार्य</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($fiscalYears as $year)
                        <tr>
                            <td>{{ $year->display_name }}</td>
                            <td>
                                @if($year->is_current)
                                    <span class="badge badge-success">चालु</span>
                                @elseif($year->is_closed)
                                    <span class="badge badge-secondary">बन्द / Archive</span>
                                @else
                                    <span class="badge badge-warning">Archive</span>
                                @endif
                            </td>
                            <td>
                                @if($selected->id !== $year->id)
                                    <form method="POST" action="{{ route('fiscal-year.switch', $year) }}" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-primary">यो वर्ष हेर्नुहोस्</button>
                                    </form>
                                @else
                                    <span class="text-muted">हाल चयन गरिएको</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($selected->is_current)
    <div class="card border-primary">
        <div class="card-header">नयाँ वित्तीय वर्ष सुरु गर्ने</div>
        <div class="card-body">
            <p class="mb-3">
                अर्को वित्तीय वर्ष <code>{{ $nextFiscalYear }}</code> स्वतः तयार हुन्छ। नयाँ वित्तीय वर्ष खाली transactional data सहित सुरु हुनेछ।
                पुरानो वर्षको data archive मा यथावत् रहनेछ।
            </p>
            <form method="POST" action="{{ route('fiscal-year.start-next') }}" class="form-inline">
                @csrf
                <input type="text" name="name" class="form-control mr-2" value="{{ $nextFiscalYear }}" readonly required>
                <button class="btn btn-success" onclick="return confirm('नयाँ वित्तीय वर्ष सुरु गर्ने?')">
                    नयाँ वित्तीय वर्ष सुरु गर्नुहोस्
                </button>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection