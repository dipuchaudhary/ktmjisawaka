@extends('layouts.master')
    @section('content')
    <div class="container mt-5">
        <h1> Mudda list </h1>
         <button type="button" class="btn btn-primary float-right mb-5" data-bs-toggle="modal" data-bs-target=".mudda_Modal">
          नयाँ मुल मुद्दा दर्ता सिर्जना गर्नुहोस्
        </button>
        @include('frontend.mudda_darta.create')
        @include('frontend.mudda_darta.edit')
        <table id="muddaTable" class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>अनुसन्धान गर्ने निकाय</th>
                    <th>मुद्दा नं.</th>
                    <th>मुद्दाको किसिम </th>
                    <th>जाहेरवालाको नाम</th>
                    <th>प्रतिवादीको नाम</th>
                    <th>मुद्दाको स्थिति</th>
                    <th>मुद्दा दर्ता मिति</th>
                    <th>सरकारी वकील</th>
                    <th>फाँट</th>
                    <th width="105px">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    @endsection
    @push('datatable_scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#muddaTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('mudda_darta.index') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'anusandhan_garne_nikaye', name: 'anusandhan_garne_nikaye' },
                { data: 'mudda_number', name: 'mudda_number' },
                { data: 'mudda_name', name: 'mudda_name' },
                { data: 'jaherwala_name', name: 'jaherwala_name' },
                { data: 'pratiwadi_name', name: 'pratiwadi_name' },
                { data: 'mudda_stithi', name: 'mudda_stithi' },
                { data: 'mudda_date', name: 'mudda_date' },
                { data: 'sarkariwakil_name', name: 'sarkariwakil_name' },
                { data: 'faat_name', name: 'faat_name' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
            });
        });
    </script>
    @endpush
