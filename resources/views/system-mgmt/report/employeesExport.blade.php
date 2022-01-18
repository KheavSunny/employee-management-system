<table id="example2" class="table table-bordered table-hover dataTable" role="grid" aria-describedby="example2_info">
	<thead>
	  <tr role="row">
	    <th width = "20%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending">Employee Name</th>
	    <th width = "20%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthday: activate to sort column ascending">Birthday</th>
	    <th width = "40%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Address: activate to sort column ascending">Address</th>
	    <th width = "20%" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Birthday: activate to sort column ascending">Hired Day</th>
	  </tr>
	</thead>
	<tbody>
	@foreach ($employees as $employee)
	    <tr role="row" class="odd">
	      <td>{{ $employee['firstname'] }}</td>
	      <td>{{ $employee['birthdate'] }}</td>
	      <td>{{ $employee['address'] }}</td>
	      <td>{{ $employee['date_hired'] }}</td>
	  </tr>
	@endforeach
	</tbody>
	<tfoot>
	  <tr role="row">

	  </tr>
	</tfoot>
      </table>