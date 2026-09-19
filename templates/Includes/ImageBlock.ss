<% if $Image.First %>
<div class="content-element__column $ImageWidth">
    <% if $ImageLink %>
    <a href="$ImageLink.Link">
    <% end_if %>
    <img class="content-image"
         sizes="(max-width: 1140px) 100vw, 1140px"
         srcset="
$Image.First.ScaleWidth(320).URL 320w,
$Image.First.ScaleWidth(400).URL 400w,
$Image.First.ScaleWidth(550).URL 550w,
$Image.First.ScaleWidth(760).URL 760w"
         src="$Image.First.ScaleWidth(760).URL"
         alt="$ImageAlt">
    <% if $ImageLink %>
    </a>
    <% end_if %>
</div>
<% end_if %>
