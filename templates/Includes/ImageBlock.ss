<% if $Image %>
<div class="content-element__column $ImageWidth">
    <% if $ImageLink %>
    <a href="$ImageLink.URL"<% if $ImageLink.OpenInNew %> target="_blank" rel="noopener noreferrer"<% end_if %>>
    <% end_if %>
    <% with $Image.Convert('webp') %>
    <img class="content-image"
         src="$ScaleWidth(760).URL"
         srcset="$ScaleWidth(320).URL 320w,
                 $ScaleWidth(400).URL 400w,
                 $ScaleWidth(550).URL 550w,
                 $ScaleWidth(760).URL 760w"
         sizes="(max-width: 760px) 100vw, 760px"
         width="$ScaleWidth(760).Width"
         height="$ScaleWidth(760).Height"
         loading="lazy"
         decoding="async"
         alt="$Up.ImageAlt">
    <% end_with %>
    <% if $ImageLink %>
    </a>
    <% end_if %>
</div>
<% end_if %>
