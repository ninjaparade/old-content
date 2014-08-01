<script type="text/template" data-grid="posttype" data-template="results">

	<% _.each(results, function(r) { %>

		<tr>
			<td><input content="id" name="entries[]" type="checkbox" value="<%= r.id %>"></td>
			<td><a href="{{ URL::toAdmin('content/posttypes/<%= r.id %>/edit') }}"><%= r.id %></a></td>
			<td><%= r.slug %></td>
			<td><%= r.title %></td>
			<td><%= r.created_at %></td>
		</tr>

	<% }); %>

</script>
