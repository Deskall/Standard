
<div <% if ContentImage %>class="uk-flex" data-uk-grid <% if LightBox %>data-uk-lightbox="toggle: a.dk-lightbox;"<% end_if %><% end_if %>>
	<% if ContentImage %>
		<% if Layout == right || Layout == left %>
			<div class="$ImageWidth uk-flex uk-flex-center <% if Layout == right %>uk-flex-right@m<% else %>uk-flex-left@m<% end_if %>">
				<% if LightBox %><a href="$ContentImage.getSourceURL" class="dk-lightbox" data-caption="$ContentImage.Description" ><% end_if %>
					<figure>
						<img class="uk-preserve" src="<% if ContentImage.getExtension == "svg" %>$ContentImage.URL<% else %><% if Top.RoundedImage %>$ContentImage.FocusFill(350,350).URL<% else %>$ContentImage.ScaleWidth(350).URL<% end_if %><% end_if %>" alt="$AltTag($ContentImage.Description, $ContentImage.Name, $Title)" title="$TitleTag($ContentImage.Name,$Title)" <% if ContentImage.getExtension == "svg" %><% end_if %> <% if Top.RoundedImage %>class="uk-border-circle"<% end_if %>>
						<% if ContentImage.Description %><figcaption>$ContentImage.Description</figcaption><% end_if %>
					</figure>
				<% if LightBox %></a><% end_if %>
			</div>
			<% if HTML || LinkableLink.exists %>
			<div class="dk-text-content uk-width-expand@m <% if Layout == "right" %>uk-flex-first<% end_if %> $TextAlign  $TextColumns  <% if TextColumnsDivider %>uk-column-divider<% end_if %>">
				$HTML
				<% if LinkableLinkID > 0 %>
					<% include CallToActionLink c=w,b=primary,pos=$LinkPosition %>
				<% end_if %>
			</div>
			<% end_if %>
		<% else %>
			<div class="uk-width-1-1">
				<% if LightBox %><a href="$ContentImage.getSourceURL" class="dk-lightbox" data-caption="$ContentImage.Description"><% end_if %>
					<figure>
						<% if $FullWidth %>
							<% if ContentImage.getExtension == "svg" %>
								<img src="uk-preserve $ContentImage.URL" >
							<% else %>
								<% if Top.RoundedImage %>
								<img src="$ContentImage.FocusFillMax(750,750).URL" data-src="$ContentImage.FocusFillMax(500,500).URL"
								     data-srcset="$ContentImage.FocusFillMax(500,500).URL 500w,
								                  $ContentImage.FocusFillMax(1000,1000).URL 1000w,
								                  $ContentImage.FocusFillMax(1500,1500).URL 1500w,
								                  $ContentImage.FocusFillMax(2000,2000).URL 2500w"
								     sizes="(min-width: 1700px) 2500px,(min-width: 1000px) 1500px,(min-width: 650px) 1000px, 100vw"  alt="$AltTag($ContentImage.Description, $ContentImage.Name, $Title)" class="uk-border-circle" data-uk-img>
								     <% if ContentImage.Description %><figcaption>$ContentImage.Description</figcaption><% end_if %>
								<% else %>
								<%-- $ContentImage.Content($ContentImage.ID,2500,$Title) --%>
								<img src="$ContentImage.FitMax(1500,1500).URL" data-src="$ContentImage.FitMax(500,500).URL"
								     data-srcset="$ContentImage.FitMax(500,500).URL 500w,
								                  $ContentImage.FitMax(1000,1000).URL 1000w,
								                  $ContentImage.FitMax(1500,1500).URL 1500w,
								                  $ContentImage.FitMax(2500,2500).URL 2500w"
								     sizes="(min-width: 1700px) 2500px,(min-width: 1000px) 1500px,(min-width: 650px) 1000px, 100vw"  alt="$AltTag($ContentImage.Description, $ContentImage.Name, $Title)" data-uk-img>
								<% end_if %>
							<% end_if %>
						<% else %>
							<% if ContentImage.getExtension == "svg" %>
								<img class="uk-preserve" src="$ContentImage.URL" >
							<% else %>
								<% if Top.RoundedImage %>
								<img src="$ContentImage.FocusFillMax(750,750).URL" data-src="$ContentImage.FocusFillMax(500,500).URL"
								     data-srcset="$ContentImage.FocusFillMax(500,500).URL 500w,
								                  $ContentImage.FocusFillMax(1000,1000).URL 1000w,
								                  $ContentImage.FocusFillMax(1500,1500).URL 1500w,
								                  $ContentImage.FocusFillMax(2000,2000).URL 2500w"
								     sizes="(min-width: 1700px) 2500px,(min-width: 1000px) 1500px,(min-width: 650px) 1000px, 100vw"  alt="$AltTag($ContentImage.Description, $ContentImage.Name, $Title)" class="uk-border-circle" data-uk-img>
								<% else %>
								<%-- $ContentImage.Content($ContentImage.ID,2500,$Title) --%>
								<img src="$ContentImage.FitMax(1500,1500).URL" data-src="$ContentImage.FitMax(500,500).URL"
								     data-srcset="$ContentImage.FitMax(500,500).URL 500w,
								                  $ContentImage.FitMax(1000,1000).URL 1000w,
								                  $ContentImage.FitMax(1500,1500).URL 1500w,
								                  $ContentImage.FitMax(2500,2500).URL 2500w"
								     sizes="(min-width: 1700px) 2500px,(min-width: 1000px) 1500px,(min-width: 650px) 1000px, 100vw"  alt="$AltTag($ContentImage.Description, $ContentImage.Name, $Title)" data-uk-img>
								<% end_if %>
							<% end_if %>
						<% end_if %>
					<% if ContentImage.Description %><figcaption>$ContentImage.Description</figcaption><% end_if %>
				</figure>

				<% if LightBox %></a><% end_if %>
			</div>
			<div class="dk-text-content uk-width-1-1 <% if Layout == "above" %>uk-flex-first<% end_if %> $TextAlign  $TextColumns  <% if TextColumnsDivider %>uk-column-divider<% end_if %>">
				$HTML
				<% if LinkableLinkID > 0 %>
					<% include CallToActionLink c=w,b=primary,pos=$LinkPosition %>
				<% end_if %>
			</div>
		<% end_if %>
	<% else %>
	<% if HTML %>
	<div class="dk-text-content $TextAlign  $TextColumns  <% if TextColumnsDivider %>uk-column-divider<% end_if %>">
		$HTML
	</div>
	<% end_if %>
	<% if LinkableLinkID > 0 %>
		<% include CallToActionLink c=w,b=primary,pos=$LinkPosition %>
	<% end_if %>
	<% end_if %>
</div>

