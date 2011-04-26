<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Websecurity_test extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->db->db_debug = true;
        $this->config->load('websecurity', true);
        $config =& $this->config->config['websecurity'];
        $config['usertablename'] = 'MyUsers';
        $config['useridcolumn'] = 'MyUserId';
        $config['usernamecolumn'] = 'MyUserName';
        $this->load->library("WebSecurityLib");
    }

    function index($data=array())
    {
        if (!$this->is_root_user(true))
        {
            return;
        }

        $data['main']         = 'WebSecurity/admin/test';
        $data['is_root_user'] = true;
        
        $this->load->helper('url');
        $this->load->view('/WebSecurity/_Layout', $data);
    }

    function assignrole()
    {
        if (!$this->is_root_user(true))
        {
            return;
        }

        $status = $this->websecuritylib->AssignRoleToUser('Guest', 'bob');
        $message = $status? "Bob is now 'Guest'": "Failed to assign role Guest to bob";
        $this->show_menu($message);
    }
    
    function createtables()
    {
        if (!$this->is_root_user(true))
        {
            return;
        }

        $profileKeys = array("FirstName", "LastName");
        $status = $this->websecuritylib->InitializeDatabaseConnection("MyUsers", "MyUserId", "MyUserName", true, $profileKeys);
        $message = $status?"Create tables succeeded." : "Create tables failed";
        $this->show_menu($message);
    }
    
    function createrole()
    {
        if (!$this->is_root_user(true))
        {
            return;
        }

        $success = $this->websecuritylib->CreateRole('Guest');
        $this->show_menu('Guest role created.');
    }
    
    function createuser()
    {
        if (!$this->is_root_user(true))
        {
            return;
        }

        $success = $this->websecuritylib->CreateUserAndAccount("bob", "bob1", array("FirstName"=>"Robert", "LastName"=>"Simpson"), false);
        $this->show_menu($success? "User created" : "User creation failed");
    }
    
    function droptables()
    {
        if (!$this->is_root_user(true))
        {
            return;
        }

        $this->load->dbforge();
        $this->load->database();
        $tablesDropped = array();
        foreach(array('webpages_Membership', 'webpages_Roles', 'webpages_UserInRoles', 'MyUsers') as $tableName)
        {
            if ($this->db->table_exists($tableName))
            {
                $this->dbforge->drop_table($tableName);
                $tablesDropped[] = $tableName;
            }            
        }        
        $this->show_menu('Tables ' . join(',', $tablesDropped) . ' dropped');
    }
    
    function login()
    {
        if (!$this->is_root_user(true))
        {
            return;
        }

        $message = $this->websecuritylib->Login("bob", "bob1", true)? "Login Succeeded": "Login Failed";
        $this->show_menu($message);
    }
    
    function login_openid()
    {
        $this->load->helper('url');
        $this->load->library('lightopenid');
        $this->lightopenid->identity = 'https://www.google.com/accounts/o8/id';
        $this->lightopenid->returnUrl = site_url('/websecurity_test/openid_return');
        $this->lightopenid->required = array('contact/email');
        header('Location: ' . $this->lightopenid->authUrl());
    }
    
    function logout()
    {
        $message = $this->websecuritylib->Logout()? "Log Out Succeeded": "Log Out Failed";
        $this->show_menu($message);
    }
    
    function requireroles()
    {
        if (!$this->is_root_user(true))
        {
            return;
        }

        $roleName = $this->input->get('roleName');
        if ($this->websecuritylib->RequireRoles($roleName))
        {
            $this->show_menu("You are viewing a page that requires $roleName rights");
        }
    }
    
    private function show_menu($message)
    {
        if (!$this->is_root_user(true))
        {
            return;
        }

        $this->load->helper("url");
        $data = array();
        if ($message !== null)
            $data['message'] = $message;
        $this->index($data);
    }
    
    private function is_root_user($redirect=false)
    {
        $this->load->library('session');
        $result = $this->session->userdata('is_root_user');
        
        if (!$result && $redirect)
        {
            $this->load->helper('url');
            redirect('/websecurity/quickstart');
        }
        
        return $result;
    }
}

?>
