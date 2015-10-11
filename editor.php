<?php
$file_path = $root_folder.$current_path;
$file_content = file_get_contents($file_path);
$file_info = pathinfo($file_path);
$ace_editor_mode =  array_key_exists($file_info['extension'], $ace_editor['ext']) ? "ace/mode/".$ace_editor['ext'][$file_info['extension']] : false;
?>
<script type="text/javascript">
	var current_file_path = "<?php echo $file_path ?>";
	var ace_editor_mode = <?php echo !$ace_editor_mode ?: '"'.$ace_editor_mode.'"' ?>;
</script>
<div class="main-container">
	<div class="editor-nav">
		<div class="form-inline pull-right">
			<button type="button" class="btn btn-sm btn-default soft_wrap">Soft Wrap</button>
			<div class="form-group">
				<select id="editor-theme" class="form-control input-sm">
					<optgroup label="Bright">
						<option value="ace/theme/chrome" selected="selected">Chrome</option><option value="ace/theme/clouds">Clouds</option><option value="ace/theme/crimson_editor">Crimson Editor</option><option value="ace/theme/dawn">Dawn</option><option value="ace/theme/dreamweaver">Dreamweaver</option><option value="ace/theme/eclipse">Eclipse</option><option value="ace/theme/github">GitHub</option><option value="ace/theme/iplastic">IPlastic</option><option value="ace/theme/solarized_light">Solarized Light</option><option value="ace/theme/textmate">TextMate</option><option value="ace/theme/tomorrow">Tomorrow</option><option value="ace/theme/xcode">XCode</option><option value="ace/theme/kuroir">Kuroir</option><option value="ace/theme/katzenmilch">KatzenMilch</option><option value="ace/theme/sqlserver">SQL Server</option>
					</optgroup>
					<optgroup label="Dark">
						<option value="ace/theme/ambiance">Ambiance</option><option value="ace/theme/chaos">Chaos</option><option value="ace/theme/clouds_midnight">Clouds Midnight</option><option value="ace/theme/cobalt">Cobalt</option><option value="ace/theme/idle_fingers">idle Fingers</option><option value="ace/theme/kr_theme">krTheme</option><option value="ace/theme/merbivore">Merbivore</option><option value="ace/theme/merbivore_soft">Merbivore Soft</option><option value="ace/theme/mono_industrial">Mono Industrial</option><option value="ace/theme/monokai">Monokai</option><option value="ace/theme/pastel_on_dark">Pastel on dark</option><option value="ace/theme/solarized_dark">Solarized Dark</option><option value="ace/theme/terminal">Terminal</option><option value="ace/theme/tomorrow_night">Tomorrow Night</option><option value="ace/theme/tomorrow_night_blue">Tomorrow Night Blue</option><option value="ace/theme/tomorrow_night_bright">Tomorrow Night Bright</option><option value="ace/theme/tomorrow_night_eighties">Tomorrow Night 80s</option><option value="ace/theme/twilight">Twilight</option><option value="ace/theme/vibrant_ink">Vibrant Ink</option>
					</optgroup>
				</select>
			</div>
		</div>
		<div class="btn-group" role="group" aria-label="Editor Tools">
			<button type="button" class="btn btn-sm btn-success file_save disabled"><i class="fa fa-lg fa-floppy-o"></i> Save</button>
		</div>
	</div>
	<div id="editor"><?php echo htmlentities($file_content); ?></div>
</div>
<script src="assets/js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
	var editor = ace.edit("editor");
	if ( ace_editor_mode  !== false ) editor.getSession().setMode(ace_editor_mode);
</script>
