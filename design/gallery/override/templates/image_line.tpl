{* Image template *}
{default href=false()}

<div class="image_line">
<h1><a href={$node.url_alias|ezurl}>{$node.name}</a></h1>

{attribute_view_gui attribute=$node.object.data_map.image href=$href image_class=small}

{attribute_view_gui attribute=$node.object.data_map.caption}
</div>
{/default}
