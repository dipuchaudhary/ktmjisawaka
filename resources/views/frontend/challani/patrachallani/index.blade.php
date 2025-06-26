@extends('layouts.master')
    @section('content')
    <div class="container mt-5">
        <h1> पत्र चलानी सूची </h1>
         <a href="{{ route('patra_challani.create') }}" class="btn btn-primary float-right mb-5">
          नयाँ पत्र चलानी गर्नुहोस्
        </a>
        <table id="muddaTable" class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>पत्र चलानी भएको कार्यालय</th>
                    <th>चलानी मिति</th>
                    <th>चलानी नं. </th>
                    <th>मुद्दा नं. </th>
                    <th>विषय</th>
                    <th>बोधार्थ</th>
                    <th>दस्तखत गर्ने अधिकारी</th>
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
            ajax: "",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'karyalaya_name', name: 'karyalaya_name' },
                { data: 'challani_date', name: 'challani_date' },
                { data: 'challani_number', name: 'challani_number' },
                { data: 'mudda_number', name: 'mudda_number' },
                { data: 'challani_subject', name: 'challani_subject' },
                { data: 'bodartha', name: 'bodartha' },
                { data: 'verified_by', name: 'verified_by' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
            });
        });
    </script>
    @endpush
