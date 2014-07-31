<script type="text/template" data-grid="author" data-template="results">

	<% _.each(results, function(r) { %>

		<tr>
			<td><input content="id" name="entries[]" type="checkbox" value="<%= r.id %>"></td>
			<td><a href="{{ URL::toAdmin('content/authors/<%= r.id %>/edit') }}"><%= r.id %></a></td>
			<td><%= r.name %></td>
			<td><%= r.position %></td>
			<td><%= r.bio %></td>
			<td><%= r.avatar %></td>
			<td><%= r.created_at %></td>
		</tr>

	<% }); %>

</script>
