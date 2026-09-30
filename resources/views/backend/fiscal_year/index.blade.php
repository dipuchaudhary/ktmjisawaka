@extends('adminlte::page')

@section('title', 'वित्तीय वर्ष व्यवस्थापन')

@section('content_header')
    <h1 class="m-0 text-dark">वित्तीय वर्ष व्यवस्थापन</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="text-muted mb-0">वित्तीय वर्ष थप्ने, संशोधन गर्ने, हेर्ने र व्यवस्थापन गर्ने। transactional data भएको archive मेटाउन मिल्दैन।</p>
        </div>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createFiscalYearModal">
            <i class="fas fa-plus mr-1"></i> नयाँ वित्तीय वर्ष
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
                <table class="table table-bordered table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>वित्तीय वर्ष</th>
                            <th>सुरु वर्ष</th>
                            <th>समाप्ति वर्ष</th>
                            <th>स्थिति</th>
                            <th>कार्य</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($fiscalYears as $index => $year)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $year->display_name }}</strong></td>
                            <td>{{ $year->start_year }}</td>
                            <td>{{ $year->end_year }}</td>
                            <td>
                                @if($year->is_current)
                                    <span class="badge badge-success">चालु</span>
                                @elseif($year->is_closed)
                                    <span class="badge badge-secondary">बन्द / Archive</span>
                                @else
                                    <span class="badge badge-warning">Archive</span>
                                @endif
                            </td>
                            <td class="text-nowrap">
                                @if($selected->id !== $year->id)
                                    <form method="POST" action="{{ route('fiscal-year.switch', $year) }}" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-primary" title="यो वर्ष हेर्नुहोस्">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </form>
                                @endif

                                <button type="button" class="btn btn-sm btn-info"
                                        data-toggle="modal" data-target="#editFiscalYear{{ $year->id }}"
                                        title="सम्पादन">
                                    <i class="fas fa-edit"></i>
                                </button>

                                @if(!$year->is_current)
                                    <form method="POST" action="{{ route('fiscal-year.destroy', $year) }}" class="d-inline"
                                          onsubmit="return confirm('यो वित्तीय वर्ष मेटाउने? transactional data भए मेटाउन दिइने छैन।');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" title="मेटाउनुहोस्">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-4">कुनै वित्तीय वर्ष भेटिएन।</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($selected->is_current)
    <div class="card border-primary">
        <div class="card-header">अर्को वित्तीय वर्ष सुरु गर्ने</div>
        <div class="card-body">
            <p class="mb-3">
                अर्को वित्तीय वर्ष <code>{{ $nextFiscalYear }}</code> स्वतः तयार हुन्छ। पुरानो वर्ष archive हुन्छ र नयाँ वर्ष चालु हुन्छ।
            </p>
            <form method="POST" action="{{ route('fiscal-year.start-next') }}" class="form-inline">
                @csrf
                <input type="text" name="name" class="form-control mr-2" value="{{ $nextFiscalYear }}" readonly required>
                <button class="btn btn-success" onclick="return confirm('नयाँ वित्तीय वर्ष सुरु गर्ने?')">
                    <i class="fas fa-play mr-1"></i> नयाँ वित्तीय वर्ष सुरु गर्नुहोस्
                </button>
            </form>
        </div>
    </div>
    @endif
</div>

@foreach($fiscalYears as $year)
<div class="modal fade" id="editFiscalYear{{ $year->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('fiscal-year.update', $year) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">वित्तीय वर्ष संशोधन — {{ $year->display_name }}</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>वित्तीय वर्ष</label>
                        <input type="text" name="name" class="form-control" value="{{ $year->name }}" required>
                        <small class="text-muted">उदाहरण: 2083/084</small>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>सुरु वर्ष</label>
                            <input type="number" name="start_year" class="form-control" value="{{ $year->start_year }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>समाप्ति वर्ष</label>
                            <input type="number" name="end_year" class="form-control" value="{{ $year->end_year }}" required>
                        </div>
                    </div>
                    @if($year->is_current)
                        <div class="alert alert-info mb-0">चालु वित्तीय वर्षको स्थिति यहाँबाट परिवर्तन हुँदैन।</div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">बन्द</button>
                    <button type="submit" class="btn btn-primary">सुरक्षित गर्नुहोस्</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<div class="modal fade" id="createFiscalYearModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('fiscal-year.store') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">नयाँ वित्तीय वर्ष थप्नुहोस्</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>वित्तीय वर्ष</label>
                        <input type="text" name="name" class="form-control" placeholder="2084/085" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>सुरु वर्ष</label>
                            <input type="number" name="start_year" class="form-control" placeholder="2084" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>समाप्ति वर्ष</label>
                            <input type="number" name="end_year" class="form-control" placeholder="2085" required>
                        </div>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="makeCurrent" name="is_current" value="1">
                        <label class="custom-control-label" for="makeCurrent">यसलाई चालु वित्तीय वर्ष बनाउने</label>
                    </div>
                    <small class="text-muted d-block mt-2">
                        सामान्य archive/future FY थप्दा यो विकल्प चयन नगर्नुहोस्। चालु FY बनाउनाले हालको चालु FY बन्द हुन्छ।
                    </small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">बन्द</button>
                    <button type="submit" class="btn btn-success">थप्नुहोस्</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
