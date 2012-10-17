<?php

if (!function_exists('asset_url'))
{   
    function asset_url()
    {
        // the helper function doesn't have access to $this, so we need to get a reference to the 
        // CodeIgniter instance.  We'll store that reference as $CI and use it instead of $this
        $CI =& get_instance();

        // return the asset_url
        return base_url() . $CI->config->item('asset_path') . "/";
    }
}

if (!function_exists('css_url'))
{   
    function css_url()
    {
        // the helper function doesn't have access to $this, so we need to get a reference to the 
        // CodeIgniter instance.  We'll store that reference as $CI and use it instead of $this
        $CI =& get_instance();

        // return the css_url
        return asset_url() . $CI->config->item('css_path') . "/";
    }
}

if (!function_exists('js_url'))
{   
    function js_url()
    {
        // the helper function doesn't have access to $this, so we need to get a reference to the 
        // CodeIgniter instance.  We'll store that reference as $CI and use it instead of $this
        $CI =& get_instance();

        // return the css_url
        return asset_url() . $CI->config->item('js_path') . "/";
    }
}

/* End of file path_helper.php */
/* Location: ./application/helpers/path_helper.php */
