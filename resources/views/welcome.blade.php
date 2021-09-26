@extends('layouts.master')
@section('content')


<!DOCTYPE html>
<html>
<head>
    <title>Laravel 8|7 Datatables Tutorial</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body>

<div class="container mt-5">
    <h2 class="mb-4">Employees</h2>
    <table class="table table-bordered yajra-datatable">
        <thead>
            <tr role="row" class="odd">
                <th> </th>
                <th>Anrede</th>
                <th>Vorname</th>
                <th>Nachname</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Handy</th>
                <th>Firma</th>
                <th>Eintrittsdatum</th>

            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

</body>



<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
  $(function () {
    var template = Handlebars.compile($("#details-template").html());
    console.log(template)
    var table = $('.yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url:"{{ route('employees.list') }}",
            method:'GET'
        },
        columns: [
            {
                "className":      'details-control',
                "orderable":      false,
                "searchable":      false,
                "data":           null,
                "defaultContent": '<i class="glyphicon glyphicon-plus-sign"></ i>'
            },
            {data: 'anrede', name: 'anrede'},
            {data: 'vorname', name: 'vorname'},
            {data: 'nachname', name: 'nachname'},
            {data: 'email', name: 'email'},
            {data: 'telefon', name: 'telefon'},
            {data: 'handy', name: 'handy'},
            {data: 'firma', name: 'firma'},
                {
            data: 'created_at',
            type: 'num',
            render: {
                _: 'display',
                sort: 'timestamp'
            }
        },

        ],
         order:[8, "ASC"]

    });




  $('.table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row(tr);
        var tableId = 'posts-' + row.data().id;
        console.log(row.data())
        if (row.child.isShown()) {
            console.log(row.data())
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            console.log(row.data())
            row.child(template(row.data())).show();
            initTable(tableId, row.data());
            tr.addClass('shown');
            tr.next().find('td').addClass('no-padding bg-gray');
        }
    });

    function initTable(tableId, data) {

        $('#' + tableId).DataTable({
            processing: true,
            serverSide: true,
            ajax: data.details_url,
            columns: [

                { data: 'name', name: 'name' },
                { data: 'ort', name: 'ort' },
                { data: 'strasse', name: 'strasse' },
                { data: 'telefon', name: 'telefon' },
            ]
        })
    }
})
</script>
</html>
@endsection

<script id="details-template" type="text/x-handlebars-template">
        <div class="label label-info">  @{{vorname}} 's Firma</div>
        <table class="table details-table" id="posts-@{{id}}">
            <thead>
            <tr>
                <th>Name</th>
                <th>Ort</th>
                <th>Strasse</th>
                <th>Telefon</th>

            </tr>
            </thead>
        </table>
    </script>
