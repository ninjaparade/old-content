<script type="text/template" data-grid="post" data-template="results">

	<% _.each(results, function(r) { %>

		<tr>
			<td><input content="id" name="entries[]" type="checkbox" value="<%= r.id %>"></td>
			<td><a href="{{ URL::toAdmin('content/posts/<%= r.id %>/edit') }}"><%= r.id %></a></td>
			<td><%= r.title %></td>
			<td><%= r.author.name %></td>
			<td><%= r.post_type %></td>
			<td><%= r.categories[0].name %></td>
			<td>
				<% _.each(r.tags, function(t){ %>
					<span class="label label-primary"><%= t.name %></span>
				<% }); %>
			</td>
			
			<td><%= r.content %></td>
			<td>
				<% if(r.publish_status == 1){ %>
					<span class="label label-success">Published</span>
				<% }else{ %>
					<span class="label label-warning">Draft</span>
				<% } %>
			</td>
			<td>
				<% if(r.private == 1){ %>
					<span class="label label-danger">Private</span>
				<% }else{ %>
					<span class="label label-success">Public</span>
				<% } %>
			</td>
			<td><%= r.created_at %></td>
		</tr>

	<% }); %>

</script>
