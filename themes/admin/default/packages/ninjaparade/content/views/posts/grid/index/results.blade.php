<script type="text/template" data-grid="post" data-template="results">

	<% _.each(results, function(r) { %>

		<tr data-grid-row>
			<td><input content="id" input data-grid-checkbox="" name="entries[]" type="checkbox" value="<%= r.id %>"></td>
			<td><a href="{{ URL::toAdmin('content/posts/<%= r.id %>') }}" href="{{ URL::toAdmin('content/posts/<%= r.id %>') }}"><%= r.id %></a></td>
			<td><%= r.author_id %></td>
			<td><%= r.post_type %></td>
			<td><%= r.slug %></td>
			<td><%= r.excerpt %></td>
			<td><%= r.title %></td>
			<td><%= r.body %></td>
			<td><%= r.cover_image %></td>
			<td><%= r.images %></td>
			<td><%= r.publish_status %></td>
			<td><%= r.private %></td>
			<td><%= r.created_at %></td>
		</tr>

	<% }); %>

</script>
