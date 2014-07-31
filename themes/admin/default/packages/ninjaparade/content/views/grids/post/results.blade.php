<script type="text/template" data-grid="post" data-template="results">

	<% _.each(results, function(r) { %>

		<tr>
			<td><input content="id" name="entries[]" type="checkbox" value="<%= r.id %>"></td>
			<td><a href="{{ URL::toAdmin('content/posts/<%= r.id %>/edit') }}"><%= r.id %></a></td>
			<td><%= r.author_id %></td>
			<td><%= r.post_type %></td>
			<td><%= r.slug %></td>
			<td><%= r.pullquote %></td>
			<td><%= r.title %></td>
			<td><%= r.content %></td>
			<td><%= r.publish_status %></td>
			<td><%= r.private %></td>
			<td><%= r.created_at %></td>
		</tr>

	<% }); %>

</script>
