<div class="item<% if $LinksToAnotherStageID || $Service.Link %> has-link<% end_if %><% if $LinksToAnotherStageID %> link<% else_if $Type %> $Type.Name.LowerCase<% end_if %><% if $ProcessCase %> $ProcessCase.Title.LowerCase<% else %> all<% end_if %>" data-type="$Type.Name.LowerCase" data-filter-class='[<% if $ProcessCase %>"case_{$ProcessCase.ID}"<% else %>"all"<% end_if %>]'>
	<% if $LinksToAnotherStageID %>
		<a href="#StageID{$LinksToAnotherStageID}" class="outer-link"></a>
	<% else_if $Service.Link %>
		<a href="$Service.Link" class="outer-link" target="_blank"></a>
	<% end_if %>
	<% if $Title %>
		<h4 <% if Content %>class="has-content"<% end_if %>>$Title</h4>
	<% else_if $Service %>
		<h4 <% if Content %>class="has-content"<% end_if %>>$Service.Name</h4>
	<% else %>
		<span class="has-icon"> </span>
	<% end_if %>

	<% if not $Title && not $Service %>
		<div class="indent">
			$Content
		</div>
	<% else %>
		$Content
	<% end_if %>
	
</div>

