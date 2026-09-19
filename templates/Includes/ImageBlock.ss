<% if $Image %>
<div class="content-element__column $ImageWidth">
    <% if $ImageLink %>
    <a href="$ImageLink.URL"<% if $ImageLink.OpenInNew %> target="_blank" rel="noopener noreferrer"<% end_if %>>
    <% end_if %>
    <img class="content-image"
         src="$Image.Convert('webp').ScaleWidth(760).URL"
         srcset="$Image.Convert('webp').ScaleWidth(320).URL 320w,
                 $Image.Convert('webp').ScaleWidth(400).URL 400w,
                 $Image.Convert('webp').ScaleWidth(550).URL 550w,
                 $Image.Convert('webp').ScaleWidth(760).URL 760w"
         sizes="(max-width: 760px) 100vw, 760px"
         width="$Image.Convert('webp').ScaleWidth(760).Width"
         height="$Image.Convert('webp').ScaleWidth(760).Height"
         loading="lazy"
         decoding="async"
         alt="$ImageAlt">
    <% if $ImageLink %>
    </a>
    <% end_if %>
</div>
<% end_if %>
