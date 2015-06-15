   @if ($packages->isEmpty())
		There are no packages! :(
    @else
<table class="table table-striped">
    <thead>
        <tr>
            <th>Package Name</th>
            <th>Posted</th>
            <th>Ended</th>
            <th>Actions</th>
        </tr>
    </thead>
<tbody>
                @foreach($packages as $package)
					<tr>
                        <td>{{ $package->title }}</td>
                        <td>{{ $package->posted }}</td>
                        <td>{{ $package->ended }}</td>
                        <td>
                        <a href="{{ action('PackagesController@edit', $package->id) }}" class="btn btn-default">Edit</a>
 
                                          	</td>
					</tr>
                @endforeach
            </tbody>
</table>
    @endif