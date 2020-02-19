<div class="content-element__column $ImageWidth">
    <% if $ImageLink %>
    <a href="$ImageLink.Link">
    <% end_if %>
    <img class="content-image"
         sizes="(max-width: 1140px) 100vw, 1140px"
         srcset="
$Image.First.Scalewidth(320).URL 320w,
$Image.First.Scalewidth(400).URL 400w,
$Image.First.Scalewidth(550).URL 550w,
$Image.First.Scalewidth(760).URL 760w"
         src="$Image.First.Scalewidth(760).URL"
         alt="$ImageAlt">
    <% if $ImageLink %>
    </a>
    <% end_if %>
</div>