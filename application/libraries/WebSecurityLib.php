<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
DEFINE('SESSIONLESS', FALSE);

// ------------------------------------------------------------------------

/**
 * WebSecurity module
 *
 * @access	public
 * @param	array	array of data for the CAPTCHA
 * @param	string	path to create the image in
 * @param	string	URL to the CAPTCHA image folder
 * @param	string	server path to font
 * @return	string
 */
class WebSecurityLib
{
    private static $userTableName;
    private static $userIdColumn;
    private static $userNameColumn;
    private static $encryptionKey;
    private static $authenticationCookie = '_CIAUTH';
    
    public $CurrentUserId = null;
    public $CurrentUserName = null;
    public $HasUserId = false;
    public $IsAuthenticated = false;
    
    private $CurrentRoleNames = null;
    
    /**
	 * Constructor
	 *
	 * Loads the websecurity file
	 */
	public function __construct($config = array())
	{
		$this->CI =& get_instance();
        $this->CI->load->database();
        
        self::$encryptionKey = $this->CI->config->item('encryption_key');
        
        $this->CI->config->load('websecurity', true);
        self::$userTableName  = $this->CI->config->item('usertablename', 'websecurity');
        self::$userIdColumn   = $this->CI->config->item('useridcolumn', 'websecurity');
        self::$userNameColumn = $this->CI->config->item('usernamecolumn', 'websecurity');
        if (!empty(self::$userTableName) &&
            !empty(self::$userIdColumn) &&
            !empty(self::$userNameColumn))
        {
            $userprofiles = $this->CI->config->item('userprofiles', 'websecurity');
            $this->InitializeDatabaseConnection(self::$userTableName, self::$userIdColumn, self::$userNameColumn, false, $userprofiles);
        }
        
        log_message('debug', "WebSecurityLib Class Initialized");
	}

    public function AssignRoleToUser($roleName, $userName)
    {
        $this->CI->load->database();
        $query = $this->CI->db->select('COUNT(*) AS NumRows')
            ->From('webpages_UserInRoles')
            ->Join('webpages_Roles', 'webpages_Roles.RoleId=webpages_UserInRoles.RoleId')
            ->Join(self::$userTableName, self::$userTableName.'.'.self::$userIdColumn.'=webpages_UserInRoles.UserId')
            ->Where(array('RoleName' => $roleName,
                          self::$userTableName.'.'.self::$userNameColumn => $userName))
            ->get();
        if (get_one_row($query)->NumRows == 0)
        {
            $roleId = get_one_row($this->CI->db->get_where('webpages_Roles', array('RoleName'=>$roleName)))->RoleId;
            $user = get_one_row($this->CI->db->get_where(self::$userTableName, array(self::$userNameColumn => $userName)));
            $userIdColumn = self::$userIdColumn;
            $userId = $user->$userIdColumn;
            $this->CI->db->insert("webpages_UserInRoles", array("UserId"=> $userId, "RoleId"=>$roleId));
            $affected_rows = $this->CI->db->affected_rows();
            return ($affected_rows > 0);
        }
        else
        {   
            return false;
        }
    }
    
    public function ChangePassword($userName, $currentPassword, $newPassword)
    {
    }
    
    public function ConfirmAccount ($accountConfirmationToken)
    {
    }
    
    public function CreateAccount($userName, $password, $requireConfirmationToken)
    {
        $propertyValues = array();
        return $this->CreateUserAndAccount($userName, $password, $propertyValues, $requireConfirmationToken);
    }
    
    public function CreateRole($roleName)
    {
        $this->CI->load->database();
        $query = $this->CI->db->get_where('webpages_Roles', array('RoleName'=>$roleName));
        if ($query->num_rows() == 0)
        {
            $this->CI->db->insert('webpages_Roles', array('RoleName'=>$roleName));
        }    
    }
    
    public function CreateUserAndAccount($userName, $password, $propertyValues, $requireConfirmationToken)
    {
        if ($this->UserExists($userName))
        {
            return false;
        }
        
        $this->CI->load->helper('string');
        $this->CI->load->library('PasswordHash');
        
        $passwordSalt = random_string('alnum', 16);
        $propertyValues[self::$userNameColumn] = $userName;
        
        $this->CI->db->trans_start();
        $this->CI->db->insert(self::$userTableName, $propertyValues);
        $userId = $this->CI->db->insert_id();
        
        $confirmationToken = ($requireConfirmationToken)? random_string("alnum", 48) : "";
       
        $this->CI->db->insert("webpages_Membership", array(
            "userId" => $userId,
            "ConfirmationToken" => $confirmationToken,
            "IsConfirmed" => !$requireConfirmationToken,
            "Password" => $this->CI->passwordhash->HashPassword($password),
            "PasswordSalt" => $passwordSalt,
            "CreateDate" => date('Y-m-d h:m:s', time())));
            
        $this->CI->db->trans_complete();
        
        return true;
    }
    
    public static function GeneratePasswordResetToken(
        $userName, 
        $tokenExpirationInMinutesFromNow)
    {
    }
    
    public static function GetUserId($userName)
    {
    }
            
    public function InitializeDatabaseConnection(
        $userTableName, 
        $userIdColumn, 
        $userNameColumn, 
        $autoCreateTables,
        $profileKeys = array())
    {
        self::$userTableName = $userTableName;
        self::$userIdColumn = $userIdColumn;
        self::$userNameColumn = $userNameColumn;
        
        $result = true;            
        if ($autoCreateTables)
        {
            $result = $this->InitializeDatabaseTables($profileKeys);
        } 
        $this->DecodeRequest();
        return $result;
    }
    
    private function InitializeDatabaseTables($profileKeys)
    {
        $obj =& $this->CI;
        $obj->load->dbforge();               
        
        $success = true;
        
        if (!$obj->db->table_exists(self::$userTableName))
        {
            $fields = array(
            self::$userIdColumn => array(
                "type" => "INTEGER",
                "auto_increment" => true),
            self::$userNameColumn => array(
                "type" => "VARCHAR",
                "constraint" => 50)
            );
            
            if (is_array($profileKeys))
            {
                foreach($profileKeys as $profileKey)
                {
                    $fields[$profileKey] = array("type" => "VARCHAR", "constraint" => "50", "null" => true);
                }
            }
            
            $obj->dbforge->add_field($fields);
            $obj->dbforge->add_key(self::$userIdColumn, true);
            $success = $success && $obj->dbforge->create_table(self::$userTableName, TRUE);
        }
        
        if (!$obj->db->table_exists("webpages_Roles"))
        {
            $fields = array(
            "RoleId" => array(
                "type" => "INTEGER"),
            "RoleName" => array(
                "type" => "VARCHAR",
                "constraint" => 50)
            );
            $obj->dbforge->add_field($fields);
            $obj->dbforge->add_key('RoleId', true);
            $success = $success && $obj->dbforge->create_table('webpages_Roles', TRUE);
        }
        
        if (!$obj->db->table_exists("webpages_UserInRoles"))
        {
            $fields = array(
            "RoleId" => array(
                "type" => "INTEGER"),
            "userid" => array(
                "type" => "INTEGER")
            );
            $obj->dbforge->add_field($fields);
            $obj->dbforge->add_key('roleid', true);
            $obj->dbforge->add_key('userid', true);
            $success = $success && $obj->dbforge->create_table('webpages_UserInRoles', TRUE);
        }
        
        if (!$obj->db->table_exists("webpages_Membership"))
        {
            $fields = array(
                "userid" => array(
                    "type" => "INTEGER"),
                "CreateDate" => array(
                    "type" => "DATETIME"),
                "ConfirmationToken" => array(
                    "type" => "VARCHAR",
                    "constraint" => "128",
                    "null" => true),
                "IsConfirmed" => array(
                     "type" => "BIT"),
                "LastPasswordFailureDate" => array(
                     "type" => "DATETIME",
                     "null" => true),
                "PasswordFailuresSinceLastSuccess" => array(
                     "type" => "INTEGER",
                     "default" => 0),
                "Password" => array(
                     "type" => "VARCHAR",
                     "constraint" => "128"),
                "PasswordChangeDate" => array(
                    "type"  => "DATETIME",
                    "null"  => true),
                "PasswordSalt" => array(
                    "type" => "VARCHAR",
                    "constraint" => "128"),
                "PasswordVerificationToken" => array(
                    "type" => "VARCHAR",
                    "constraint" => "128",
                    "null" => true),
                "PasswordVerificationTokenExpirationDate" => array(
                    "type" => "DATETIME",
                    "null" => true)
            );
            $obj->dbforge->add_field($fields);
            $obj->dbforge->add_key('userid', true);
            $success = $success && $obj->dbforge->create_table('webpages_Membership', TRUE);
        }
        
        return $success;
    }
    
    public function Login($userName, $password, $persistCookie)
    {
        $obj =& get_instance();
        $obj->load->helper('string');
        $obj->load->database();
        
        $obj->db->select("Password As Password, PasswordSalt As PasswordSalt, webpages_Membership.UserId As UserId")->
            From('webpages_Membership')->
            Join(self::$userTableName, self::$userTableName .".". self::$userIdColumn ."=webpages_Membership.UserId")->
            Where(self::$userNameColumn, $userName)->
            Where('webpages_Membership.IsConfirmed', 1);
            
        $query = $obj->db->get();
        if ($query->num_rows() > 0)
        {
            $this->CI->load->library('PasswordHash');
            
            foreach ($query->result() as $row)
            {
                if ($this->CI->passwordhash->CheckPassword($password, $row->Password))
                {
                    $this->CurrentUserId = $row->UserId;
                    $this->CurrentUserName = $userName;
                    $this->HasUserId = true;
                    $this->IsAuthenticated = true;
                    
                    $this->OnUserLoggedIn();
                    return true;
                }
                else
                {
                    break;
                }
            }
        }
        
        $this->CurrentUserId = null;
        $this->CurrentUserName = null;
        $this->HasUserId = false;
        $this->IsAuthenticated = false;
        return false;
    }
    
    public function Logout()
    {
        $this->CurrentUserId = null;
        $this->CurrentUserName = null;
        $this->HasUserId = false;
        $this->IsAuthenticated = false;
        
        $this->OnUserLoggedOut();
        return true;
    }
    
    public static function RequireAuthenticatedUser()
    {
    }
    
    /**
     * @param $role1, $role2, ... varargs
     * @return boolean
     **/
    public function RequireRoles()
    {
        if ($this->HasUserId)
        {
            
            $rolesRequired = func_get_args();
            
            if ($this->CurrentRoleNames === null)
            {
                // load from database
                $query = $this->CI->db->Select("RoleName")->
                    From("webpages_Roles")->
                    Join("webpages_UserInRoles", "webpages_Roles.RoleId = webpages_UserInRoles.RoleId")->
                    Where("webpages_UserInRoles.UserId", $this->CurrentUserId)->get();
                              
                $this->CurrentRoleNames = array();    
                foreach ($query->result() as $row)
                {
                    $this->CurrentRoleNames[] = $row->RoleName;
                }
            }        
        
            // return true if all elements in rolesRequired are present in CurrentRoleNames
            $rolesMissing = array_diff($rolesRequired, $this->CurrentRoleNames);
            if  (sizeof($rolesMissing)==0)
            {
                return true;
            }
            else
            {
                $message = "You have to be " . join(", and ", $rolesMissing) . " to view this page.";
                $this->CI->load->view('WebSecurity/403', array('message'=>$message));
            }

        }
        else
        {
            $this->showLoginScreen();
            return false;
        }
        
    }
    
    public static function RequireUserId($userId)
    {
    }
    
    public static function RequireUser($userName)
    {
    }
    
    public static function ResetPassword(
        $passwordResetToken, 
        $newPassword)
    {
    }
    
    public function TablesInitialized()
    {
        $obj =& $this->CI;
        $obj->load->database();               
        return ($obj->db->table_exists(self::$userTableName) && 
                $obj->db->table_exists("webpages_Membership") &&
                $obj->db->table_exists("webpages_Roles") &&
                $obj->db->table_exists("webpages_UserInRoles"));
    }
    
    public function UserExists($userName)
    {
        $query = $this->CI->db->get_where(self::$userTableName, array(self::$userNameColumn => $userName));
        return ($query !== NULL && $query->num_rows() > 0);
    }
    
    // =====================================================================================
    // Sessionless authentication.
    // The user credentials are stored in a cookie and hashed with a secret token
    // =====================================================================================
    private function EncodeCookie($userId, $userName, $expiry, $roleNames)
    {
        $this->CI->load->helper("string");
        $salt = random_string("alnum", 16);
        $encryptionKey = self::$encryptionKey;
        
        // Cookie value contains 
        // userId, userName, expiry, roles...,salt, hash(+encryptionKey) 
        // e.g.
        // 15;bob.dylan;1303702131;Administrator;Moderator;bA8j77Qgc;8ab80aa16283fad3
        $value = join("-", array($userId, $userName, $expiry, join("-", $roleNames), $salt));
        return $value."-".sha1($value . "-" . self::$encryptionKey);
    }
    
    private function DecodeCookie($cookieValue)
    {
        if ($cookieValue === false) return false;
        
        $vars = explode('-', $cookieValue);
        $sha1 = array_pop($vars);
        $salt = array_pop($vars);
        
        list($userId, $userName, $expiry) = $vars;
        $roleNames = array_slice($vars, 3);

        $value = join("-", array($userId, $userName, $expiry, join("-", $roleNames), $salt));
        if ((time() > (int) $expiry) && sha1($value . "-" . self::$encryptionKey) == $sha1)
        {
            $this->CurrentUserId = $userId;
            $this->CurrentUserName = $userName;
            $this->HasUserId = true;
            $this->IsAuthenticated = true;
        }
        else
        {
            $this->CurrentUserId = null;
            $this->CurrentUserName = null;
            $this->HasUserId = false;
            $this->IsAuthenticated = false;
        }
    }
    
    private $decodedRequest=false;
    private function DecodeRequest()
    {
        if ($this->decodedRequest) return;
        
        $this->decodedRequest = true;
        
        if (SESSIONLESS)
        {
            $this->CI->load->helper('cookie');
            $encodedValue = get_cookie(self::$authenticationCookie);
            return $this->DecodeCookie($encodedValue);
        }
        else
        {        
            $this->CI->load->library('session');
            if ($this->CI->session->userdata('RoleNames') !== FALSE)
            {
                $this->CurrentUserId = $this->CI->session->userdata('UserId');
                $this->CurrentUserName = $this->CI->session->userdata('UserName');
                $this->CurrentRoleNames = $this->CI->session->userdata('RoleNames');
                $this->HasUserId = ((int) $this->CurrentUserId > 0);
                $this->IsAuthenticated = ((int) $this->CurrentUserId > 0);
            }
        }
    }
    
    // Overridable
    protected function ShowLoginScreen()
    {
        $this->CI->load->helper('url');
        $this->CI->load->view("websecurity/login");
    }
    
    protected function OnUserLoggedIn()
    {
        // trigger loading of rolenames
        $this->RequireRoles();

        if (SESSIONLESS)
        {
            $expiry = time() + 100;
            $encodedValue = $this->EncodeCookie($this->CurrentUserId, $this->CurrentUserName, $expiry, $this->CurrentRoleNames);
            
            $this->CI->load->helper('cookie');
            set_cookie(array('name'=> self::$authenticationCookie, 'value'=>$encodedValue, 'expire'=>600));
        }
        else
        {
            $this->CI->load->library('session');
            $this->CI->session->set_userdata(array(
                'UserId'    => $this->CurrentUserId,
                'UserName'  => $this->CurrentUserName,
                'RoleNames' => $this->CurrentRoleNames,
            ));
        }
    }
    
    protected function OnUserLoggedOut()
    {
        if (SESSIONLESS)
        {
            delete_cookie(self::$authenticationCookie);
        }
        else
        {
            $this->CI->load->library('session');
            $this->CI->session->unset_userdata(array(
                'UserId'    => '',
                'UserName'  => '',
                'RoleNames' => ''
            ));
        }
    }
}

function get_one_row($query)
{
    $result = $query->result();
    return $result[0];
}

?>