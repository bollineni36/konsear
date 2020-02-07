<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head>
<title>Sorting Example</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($){
 $("#namelist").sortable({
 connectWith: '#deleteArea',
 update: function(event, ui){
 //Run this code whenever an item is dragged and dropped out of this list
 var order = $(this).sortable('serialize');
 $.ajax({
 url: 'processImage.php',
 type: 'POST',
 data: order
 });
 }
 });
 $("#deleteArea").droppable({
 accept: '#namelist > li',
 hoverClass: 'dropAreaHover',
 drop: function(event, ui) {
 deleteImage(ui.draggable,ui.helper);
 },
 activeClass: 'dropAreaHover'
 });
 function deleteImage($draggable,$helper){
 params = 'PID=' + $draggable.attr('id');
 $.ajax({
 url: 'deleteImage.php',
 type: 'POST',
 data: params
 });
 $helper.effect('transfer', { to: '#deleteArea', className: 'ui-effects-transfer' },500);
 $draggable.remove();
 }
});
</script>
<style type="text/css">
li { cursor: move; }
 
</style>
</head>
<body>
<p>Drag and drop list items to sort them out</p>
<ul id="namelist">
 <li id='i_1'>Image 1</li>
 <li id='i_2'>Image 2</li>
 <li id='i_3'>Image 3</li>
 <li id='i_4'>Image 4</li>
</ul>
 
<div id="deleteArea">
Drag here to delete.
</div>
</body>
</html>