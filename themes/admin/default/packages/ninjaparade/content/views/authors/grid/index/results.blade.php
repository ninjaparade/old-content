<script type="text/template" data-grid="author" data-template="results">

	<% _.each(results, function(r) { %>

		<tr data-grid-row>
			<td><input content="id" input data-grid-checkbox="" name="entries[]" type="checkbox" value="<%= r.id %>"></td>
			<td><a href="{{ URL::toAdmin('content/authors/<%= r.id %>') }}" href="{{ URL::toAdmin('content/authors/<%= r.id %>') }}"><%= r.id %></a></td>
			<td><%= r.name %></td>
			<td><%= r.postion %></td>
			<td><%= r.bio %></td>
			<td><%= r.avatar %></td>
			<td><%= r.created_at %></td>
		</tr>

	<% }); %>

</script>
