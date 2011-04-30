<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WebSecurity extends CI_Controller
{
    function __construct() {#<-PHP5 - __construct(); PHP4 - class name()
		parent::__construct();
		#enable query strings for this class
        if ($_SERVER['PATH_INFO'] === 'websecurity/openid_return')
        {
		    parse_str($_SERVER['QUERY_STRING'],$_GET);
        }
	}
    
    function admin()
    {
        $this->load->helper('url');
        redirect('/websecurity/quickstart');
    }
    
    function createrootpassword()
    {
        $password = $this->input->post('password');
        $password2 = $this->input->post('password2');
        
        if (trim($password) === trim($password2) && strlen(trim($password))>0)
        {
            $this->load->library('PasswordHash');
            $pwhash = $this->passwordhash;
            $pwhash->iteration_count_log2 = 15;
            $hash = $pwhash->HashPassword(trim($password));
            
            $this->quickstart(array("hash"=>$hash));
        }
        else if (trim($password) === trim($password2) && strlen(trim($password)) == 0)
        {
            $this->quickstart(array( "password_error"=>"Enter a password."));
        }
        else if (trim($password) != trim($password2))
        {
            $this->quickstart(array( "password_error"=>"Passwords do not match"));
        }

    }
    
    //================ public facing controllers ================================================
    
    function login()
    {
        $this->load->library('websecuritylib');
        if ($this->websecuritylib->IsAuthenticated)
        {
            $this->load->helper('url');
            redirect('');
            return;
        }
        
        $message = null;
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $persistCookie = $this->input->post('persistCookie');
        if (!empty($username) && !empty($password))
        {
            if ($this->websecuritylib->login($username, $password, $persistCookie))
            {
                $this->load->helper('url');
                redirect('');
                return;
            }
            else
            {
                $message = 'Incorrect password. Please try again';
            }
        }
        
        $this->load->helper('url');
        $this->load->view('/WebSecurity/login', array(
            'message' => $message));
            
    }
    
    function logout()
    {
        $this->load->library('websecuritylib');
        $this->websecuritylib->logout();
        $this->load->helper('url');
        redirect($_SERVER['HTTP_REFERER']);
    }

    function openid()
    {
        $openid_identifier = $this->input->get('openid_identifier');
        if ($openid_identifier !== false)
        {
            $this->load->library('lightopenid');
            $this->lightopenid->identity = $openid_identifier;
            $this->load->helper('url');
            $this->lightopenid->returnUrl = site_url('websecurity/openid_return');
            $this->lightopenid->required = array('contact/email', 'namePerson/friendly');
            header('Location: ' . $this->lightopenid->authUrl());
            return;
        }
    }
    
    function openid_return()
    {        
        $openid_mode = $this->input->get('openid_mode');
        $email = $this->input->get('openid_ax_value_email');
        
        if ($openid_mode !== false && $openid_mode !== 'cancel')
        {
            $this->load->library('lightopenid');
            $result = $this->lightopenid->validate();
            if ($result !== false)
            {
                $this->load->library('WebSecurityLib');
                if ($this->websecuritylib->IsAuthenticated)
                {
                    // TODO prompt user to associate account
                }
                else
                {
                    // TODO offer user a chance to login and associate account
                    // or 
                    
                }
                $this->load->view('WebSecurity/openid');
            }
            else
            {
                var_dump('not valid');
            }
        }
    }
    
    function register()
    {
        $this->load->helper('captcha');
        $vals = array(
            'img_path'	 => './captcha/',
            'img_url'	 => 'http://' . $_SERVER['HTTP_HOST'] . '/captcha/'
        );
        
        $cap = create_captcha($vals);

        $this->load->helper('form');
        $this->load->view('WebSecurity/register',
            array('cap' => $cap));
    }
    
    private function database_tables_exist()
    {
        $this->load->library('websecuritylib');
        $success = $this->websecuritylib->TablesInitialized();
        return $success;
    }
    
    //====================== root user access only ========================================

    function create_database_tables()
    {
        if (!$this->is_root_user(true))
        {
            return;
        }

        $this->config->load('websecurity', true);
        $this->load->library('websecuritylib');
        $success = $this->websecuritylib->InitializeDatabaseConnection(
            $this->config->item('usertablename', 'websecurity'),
            $this->config->item('useridcolumn', 'websecurity'),
            $this->config->item('usernamecolumn', 'websecurity'),
            true,
            $this->config->item('userprofiles', 'websecurity'));
        $this->quickstart();
    }
    
    function create_openid_tables()
    {
        if (!$this->is_root_user(true))
        {
            return;
        }

        $this->load->database();
        if (!$this->db->table_exists('webpages_OpenIdUsers'))
        {
            $this->load->dbforge();
            $fields = array(
                "OpenIdUserId" => array(
                    "type" => "INTEGER"),
                "UserId" => array(
                    "type" => "INTEGER"),
                "OpenIdUrl" => array(
                    "type" => "VARCHAR",
                    "constraint" => 200)
            );

            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('OpenIdUserId', true);
            $this->dbforge->create_table('webpages_OpenIdUsers', true);
        }
        
        $this->load->helper('url');
        redirect('/websecurity/openid');        
    }
    
    function create_role()
    {
        if (!$this->is_root_user(true))
        {
            return;
        }

        $roleName = trim($this->input->get('roleName'));
        if (!empty($roleName))
        {
            $this->load->library('WebSecurityLib');
            $this->websecuritylib->CreateRole($roleName);
        }
        
        $this->quickstart();
    }
    
    private function openid_tables_exist()
    {
        $this->load->database();
        return $this->db->table_exists('webpages_OpenIdUsers');
    }
    
    function quickstart($data=array())
    {
        $this->config->load('websecurity', true);
        $root_password_saved = (strlen($this->config->item('root_password', 'websecurity')) > 0);
        
        $this->load->helper('url');
        $data['main'] = '/WebSecurity/admin/quickstart';
        $data['is_root_user'] = $this->is_root_user();
        $data['root_password_saved'] = $root_password_saved;
        $data['database_tables_exist'] = $this->database_tables_exist(); 
        $this->load->view('/WebSecurity/_Layout', $data);
    }
    
    function roles()
    {
        if (!$this->is_root_user(true))
        {
            return;
        }

        $this->load->helper('url');
        $this->load->view('/WebSecurity/_Layout', 
            array("main"         => "/WebSecurity/admin/roles",
                  "is_root_user" => $this->is_root_user()
                  ));
    }
    
    function search_user()
    {
        if (!$this->is_root_user(true))
        {
            return;
        }
        
        $search = $this->input->get('user');
        $users = array(
            (object) (array("UserId"=>1, "UserName"=>"ObiWan")),
            (object) (array("UserId"=>2, "UserName"=>"LukeS"))
        );
        $this->load->helper('url');
        $this->load->view('/WebSecurity/_Layout', 
            array(
                "main" => "/WebSecurity/admin/users", 
                "is_root_user" => $this->is_root_user(),
                "users"=>$users, 
                "search"=>$search));
    }
    
    function setup_openid()
    {
        if (!$this->is_root_user(true))
        {
            return;
        }
    
        $this->load->helper('url');
        $this->load->view('/WebSecurity/_Layout', 
            array("main"                  => "/WebSecurity/admin/setup_openid", 
                  "database_tables_exist" => $this->openid_tables_exist(),
                  "is_root_user"          => $this->is_root_user()
                  ));
    }
    
    function users()
    {
        if (!$this->is_root_user(true))
        {
            return;
        }
        
        $users = array(
            (object) (array("UserId"=>1, "UserName"=>"ObiWan")),
            (object) (array("UserId"=>2, "UserName"=>"LukeS"))
        );
        
        $this->load->helper('url');
        $this->load->view('/WebSecurity/_Layout', array(
            "main" => "/WebSecurity/admin/users", 
            "is_root_user" => $this->is_root_user(),
            "users"=>$users));
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
    
    public function login_root_user()
    {
        $this->config->load('websecurity', TRUE);
        $root_password = $this->config->item('root_password', 'websecurity');
        
        $this->load->library('session');
        $this->load->helper('url');
        
        $this->load->library('PasswordHash');
        $pwhash = $this->passwordhash;
        $pwhash->iteration_count_log2 = 15;
        $success = $pwhash->CheckPassword($this->input->post('password'), $root_password);
        if ($success)
        {
            $this->session->set_userdata('is_root_user', true);
        }
        else
        {
            $this->session->set_userdata('is_root_user', false);           
        }
        
        redirect('/websecurity/quickstart');
    }
    
    public function logout_root_user()
    {
        $this->load->library('session');
        $this->session->set_userdata('is_root_user', false);
        $this->load->helper('url');
        redirect('/websecurity/quickstart');
    }
}

?>