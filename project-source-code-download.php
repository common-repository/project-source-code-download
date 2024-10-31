<?php 
/**

 Plugin Name: Project source code download
 Plugin URI: http://wordpress.org/plugins/project-source-code-download/
 Description: Wordpress project source code download zip , zipe file download
 Version: 1.0.0
 Author: Satish
 Author URI:
 Text Domain: project-source-code-download
 License: GPL3
 
 You should have received a  copy of the GNU General Public License
 along with Remove revision history. If not, see http://wordpress.org/plugins/project-source-code-download/
 */
// file name
$zipName = 'backup_'.date('Y-m-d').'.zip';

// zip file create fun
function pscd_createFilesBackup()
   {
        $sitePath = realpath(ABSPATH);
        $zip = new ZipArchive();
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($sitePath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        $zipName = 'backup_'.date('Y-m-d').'.zip';

        $zipfileName = plugin_dir_path( __DIR__ ).$zipName;
       
        $zip->open($zipfileName, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        foreach ($files as $name => $file)
        {
            if (!$file->isDir())
            {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($sitePath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();
 
   }

// download zip file
if (isset($_POST['create'])) {
		pscd_createFilesBackup();
        $filename = plugin_dir_path( __DIR__ ).$zipName;
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="'.basename($filename).'"');
        header('Content-Length: ' . filesize($filename));

        flush();
        readfile($filename);
        // delete file
        unlink($filename);
}


add_action('admin_menu', 'pscd_register_submenu_page');
function pscd_register_submenu_page() {
add_options_page('Create download ZIP', 'Create download ZIP', 'manage_options', 'pscd-options', 'pscd_create_dowmload_zip_option');
}
// All option
function pscd_create_dowmload_zip_option() {
	
	?> 
<div id="pscd" class="wrap"> 
    <div id="icon-settings" class="icon32"></div>
        <h2><?php _e('Project source code download Zip file using PHP', 'project-source-code-download') ?></h2>
		<form method='post' action=''>
		    <?php wp_nonce_field('pscd_form_submit','pscd_form_nonce'); ?>
            <input type='submit' class="button button-primary button-large" name='create' value='Project source code download' />
			
        </form>
</div>
    
    <?php
}
?>
