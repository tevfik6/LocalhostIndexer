<?php
return array(
    // ignore paths accept relative path
    // ie: 'relative/path/to/my/project'
    // or: 'relative/path/to/a/file.ext'
    'ignored_paths' => array(
        'index.php',
        'LocalhostIndexer',
    ),

    // ignore files in every directory 
    // ie: single_file_name_without_path.ext
    'ignored_files' => array(
        '.DS_Store',
        '.git',
    ),

    //file and folder list view date format
    'date_format' => "d-m-Y H:i:s",

    'ace' => array(
        //default file extension mapping to edit in ace editor
        'extensions' => array(
            // 'file extension' => 'ace library'
            'php'       => 'php',
            'js'        => 'javascript',
            'html'      => 'html',
            'css'       => 'css',
            'json'      => 'json',
            'sql'       => 'sql',
            'xml'       => 'xml',
            'py'        => 'python',
            'diff'      => 'diff',
            'htaccess'  => 'apache_conf',
            'gitignore' => 'gitignore',
            'hbs'       => 'handlebars',
            'md'        => 'markdown',
            'coffee'    => 'coffee',
            'sh'        => 'sh',
            'less'      => 'less',
            'ini'       => 'ini',
            'yml'       => 'yaml',
            'log'       => 'text',
            'txt'       => 'text',
            // ''    => 'smarty',
        ),
    ),
);
?>
