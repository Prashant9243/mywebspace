<?php
/**
 * @name CodeIgniter Message Library
 */
if (!defined('BASEPATH'))exit('No direct script access allowed');
    
class Messages
{
    private $ci;
    function __construct($params = array())
    {
        $this->ci = & get_instance();
        $this->ci->load->library('session');
    }
    
    function clear($type = null)
    {
        if (!empty($type))
        {
            $messages = $this->ci->session->userdata('messages');
            if (!is_array($messages))
            {
                $messages = array();
            }
            
            if (array_key_exists($type, $messages))
            {
                unset($messages[$type]);
            }
            
            $this->ci->session->set_userdata('messages', $messages);
        }
        else
        {
            $this->ci->session->set_userdata('messages', array());
        }
    }
    
    function add($message, $type = 'message')
    {
        $messages = $this->ci->session->userdata('messages');
        if (!is_array($messages))
        {
            $messages = array();
        }
        
        if (is_a($message, 'Exception'))
        {
            $message = $message->getMessage();
            $type = 'error';
        }
        
        if ((!isset($messages[$type]) || !in_array($message, $messages[$type])) && is_string($message) && $message)
        {
            $messages[$type][] = $message;
        }
        
        $this->ci->session->set_userdata('messages', $messages);
    }
    
    function count($type = null)
    {
        $messages = $this->ci->session->userdata('messages');
        if (!is_array($messages))
        {
            $messages = array();
        }
        
        if (!empty($type))
        {
            if (array_key_exists($type, $messages))
            {
                return count($messages[$type]);
            } 
            else
            {
                return 0;
            }
        }
        
        $i = 0;
        foreach ($messages as $type => $m)
        {
            $i += count($messages[$type]);
        }
        return $i;
    }
    
    function get($type = null)
    {
        $messages = $this->ci->session->userdata('messages');
        if (!is_array($messages))
        {
            $messages = array();
        }
        
        if (!empty($type)) 
        {
            if (array_key_exists($type, $messages))
            {
                $messages = $messages[$type];
                $this->clear($type);
                return $messages;
            }
            else
            {
                return array();
            }
        }
        
        $this->clear();
        return $messages;
    }
}  