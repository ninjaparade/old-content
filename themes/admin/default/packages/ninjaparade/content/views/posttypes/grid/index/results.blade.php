<script type="text/template" data-grid="posttype" data-template="results">

	<% _.each(results, function(r) { %>

		<tr data-grid-row>
			<td><input content="id" input data-grid-checkbox="" name="entries[]" type="checkbox" value="<%= r.id %>"></td>
			<td><a href="{{ URL::toAdmin('content/posttypes/<%= r.id %>') }}" href="{{ URL::toAdmin('content/posttypes/<%= r.id %>') }}"><%= r.id %></a></td>
			<td><%= r.slug %></td>
			<td><%= r.name %></td>
			<td><%= r.created_at %></td>
		</tr>

	<% }); %>

</script>
