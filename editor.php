<?php
$file_path = $root_folder.$current_path;
$file_content = file_get_contents($file_path);
$file_info = pathinfo($file_path);
$ace_editor_theme = isset($ace_editor['theme']) && $ace_editor['theme'] != '' ? $ace_editor['theme'] : false;
$ace_editor_mode =  array_key_exists($file_info['extension'], $ace_editor['ext']) ? $ace_editor['ext'][$file_info['extension']] : false;
?>
<div class="main-container">
	<div id="editor"><?php echo htmlentities($file_content); ?></div>
</div>
<script src="assets/js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
	var editor = ace.edit("editor");
<?php if ( $ace_editor_theme !== false ) { ?>	editor.setTheme("<?php echo $ace_editor_theme ?>");<?php } ?>

<?php if ( $ace_editor_mode  !== false ) { ?>	editor.getSession().setMode("ace/mode/<?php echo $ace_editor_mode ?>");<?php } ?>

</script>
