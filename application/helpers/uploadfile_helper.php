<?php
function single_upload($path_files,$elem_name,$new_filename='',$allowed=[])
{
    $ci = &get_instance(); 
    if (!is_dir($path_files)) { mkdir($path_files, 0777, TRUE); }
    $config['upload_path'] = $path_files;
    $config['allowed_types'] = implode("|",$ci->config->item('upload_file_types'));
	if(!empty($allowed)) $config['allowed_types']=$allowed;
    $config['max_size']  = ($ci->config->item('upload_max_size'))*1024; //2Mb 
    $config['overwrite'] = $ci->config->item('upload_overwrite');
    $config['file_name'] = ($new_filename!='') ? $new_filename : date("Y-m-d his");
    $data_files_upload=array();
    $_FILES['upload']=$_FILES[$elem_name];
    $ci->load->library('upload');
    $ci->upload->initialize($config);
    if (!$ci->upload->do_upload('upload')){
        $error=$ci->upload->display_errors();
        if(strpos($error, 'larger')){
            sendError(lang('msg_upload_error_maxsize'));
        }else
        if(strpos($error, "not allowed")){
            sendError(lang('msg_upload_error_filetype'));
        }else
        if(strpos($error, 'not select')){
            sendError(lang('msg_upload_error_no_file_selected'));
        }else{
            sendError($error);  
        }

    }else{
        //$filename= $_FILES["file"]["name"];
        //$file_ext = pathinfo($filename,PATHINFO_EXTENSION);
        $upd_file=$ci->upload->data();
        $data_files_upload['file_name']=$upd_file['file_name'];
        $ftype=explode("/",$upd_file['file_type']);
        if(sizeof($ftype)>1){
            $data_files_upload['file_type']=$ftype[1];
            $data_files_upload['file_ext']=str_replace(".","",$upd_file['file_ext']);
        }else{
            $data_files_upload['file_type']=$ftype[0];
            $data_files_upload['file_ext']=str_replace(".","",$upd_file['file_ext']);
        }
    }
    return $data_files_upload;
}
