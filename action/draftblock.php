<?php
/**
 * Approve Plus
 * 
 * Requires: Approve-Plugin, Sqlite-Plugin
 * 
 * @license: GPL2
 * @author: Gero Gothe <practical@medizin-lernen.de>
 * 
 */


// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();



/**
 * Class action_plugin_approveplus_draftblock
 */
class action_plugin_approveplus_draftblock extends DokuWiki_Action_Plugin {

    /**
     * Register callbacks
     */
    public function register(Doku_Event_Handler $controller) {

        $list = plugin_list();

        if (in_array('approve',$list) && $this->getConf('block_unapproved')==1) { # Hooks nur eintragen, wenn das Approve-Plugin ebenfalls installiert ist	
            $controller->register_hook('TPL_ACT_RENDER', 'BEFORE', $this, 'handle_viewer');
        }
    }


    /**
     * Based on the original function from the approve-plugin
     * 
     * Block pages, which have not been approved yet
     *
     * @param Doku_Event $event
     */
    public function handle_viewer(Doku_Event $event) {
        global $INFO;

        /** @var \helper_plugin_approve_db|null $db_helper */
        $db_helper = plugin_load('helper', 'approve_db');
        /** @var \helper_plugin_approve_acl|null $acl_helper */
        $acl_helper = plugin_load('helper', 'approve_acl');
        if (!$db_helper || !$acl_helper) return;

        if ($event->data != 'show') return;
        //apply only to current page
        if ($INFO['rev'] != 0) return;
        if (!$acl_helper->useApproveHere($INFO['id'])) return;
        if ($acl_helper->clientCanSeeDrafts($INFO['id'])) return;

        $last_approved_rev = $db_helper->getLastDbRev($INFO['id'], 'approved');
        //no page is approved
        if (!$last_approved_rev) {
            global $auth;
            $editor = $INFO['editor'] ?? '';
            $a = $auth->getUserData($editor);
            $name = $a['name'] ?? $editor;
            echo '<div class="plugin__approveblock_info">' . str_replace("%AUTHOR%",$name,$this->getLang("none_approved")) .'</div>';
            $event->preventDefault();
            return;
        }

        #$last_change_date = @filemtime(wikiFN($INFO['id']));
        //current page is approved
        #if ($last_approved_rev == $last_change_date) return;

        #header("Location: " . wl($INFO['id'], ['rev' => $last_approved_rev], false, '&'));
    }


}

//Setup VIM: ex: et ts=4 enc=utf-8 :
